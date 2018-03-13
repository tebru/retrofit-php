<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal;

use InvalidArgumentException;
use LogicException;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\PrettyPrinterAbstract;
use ReflectionClass;
use RuntimeException;
use Tebru\PhpType\TypeToken;
use Tebru\Retrofit\HttpClient;
use Tebru\Retrofit\Internal\ServiceMethod\ServiceMethodFactory;
use Tebru\Retrofit\Proxy;
use Tebru\Retrofit\Proxy\AbstractProxy;
use Tebru\Retrofit\ProxyFactory;

/**
 * Class DefaultProxyFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class DefaultProxyFactory implements ProxyFactory
{
    public const PROXY_PREFIX = 'Tebru\Retrofit\Proxy\\';

    /**
     * @var BuilderFactory
     */
    private $builderFactory;

    /**
     * @var PrettyPrinterAbstract
     */
    private $printer;

    /**
     * @var ServiceMethodFactory
     */
    private $serviceMethodFactory;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var bool
     */
    private $enableCache;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * Constructor
     *
     * @param BuilderFactory $builderFactory
     * @param PrettyPrinterAbstract $printer
     * @param ServiceMethodFactory $serviceMethodFactory
     * @param HttpClient $httpClient
     * @param Filesystem $filesystem
     * @param bool $enableCache
     * @param string $cacheDir
     */
    public function __construct(
        BuilderFactory $builderFactory,
        PrettyPrinterAbstract $printer,
        ServiceMethodFactory $serviceMethodFactory,
        HttpClient $httpClient,
        Filesystem $filesystem,
        bool $enableCache,
        string $cacheDir
    ) {
        $this->builderFactory = $builderFactory;
        $this->printer = $printer;
        $this->serviceMethodFactory = $serviceMethodFactory;
        $this->httpClient = $httpClient;
        $this->filesystem = $filesystem;
        $this->enableCache = $enableCache;
        $this->cacheDir = $cacheDir;
    }

    /**
     * Create a new proxy class given an interface name. This returns a class
     * in a string to be cached.
     *
     * @param string $service
     * @return Proxy
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \Tebru\PhpType\Exception\MalformedTypeException
     * @throws \InvalidArgumentException
     */
    public function create(string $service): ?Proxy
    {
        $className = self::PROXY_PREFIX.$service;
        if ($this->enableCache && class_exists($className)) {
            return new $className($this->serviceMethodFactory, $this->httpClient);
        }

        if (!$this->enableCache && class_exists($className, false)) {
            return new $className($this->serviceMethodFactory, $this->httpClient);
        }

        if (!interface_exists($service)) {
            throw new InvalidArgumentException(sprintf('Retrofit: %s is expected to be an interface', $service));
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $reflectionClass = new ReflectionClass($service);
        $builder = $this->builderFactory
            ->class($reflectionClass->getShortName())
            ->extend('\\'.AbstractProxy::class)
            ->implement('\\'.$reflectionClass->name);

        foreach ($reflectionClass->getMethods() as $reflectionMethod) {
            $methodBuilder = $this->builderFactory
                ->method($reflectionMethod->name)
                ->makePublic();

            if ($reflectionMethod->isStatic()) {
                $methodBuilder->makeStatic();
            }

            $defaultValues = [];

            foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
                $paramBuilder = $this->builderFactory->param($reflectionParameter->name);

                if ($reflectionParameter->isDefaultValueAvailable()) {
                    $paramBuilder->setDefault($reflectionParameter->getDefaultValue());
                }

                if ($reflectionParameter->getType() === null) {
                    throw new LogicException(sprintf(
                        'Retrofit: Parameter types are required. None found for parameter %s in %s::%s()',
                        $reflectionParameter->name,
                        $reflectionClass->name,
                        $reflectionMethod->name
                    ));
                }

                $reflectionTypeName = (string)$reflectionParameter->getType();
                if ((new TypeToken($reflectionTypeName))->isObject()) {
                    $reflectionTypeName = '\\'.$reflectionTypeName;
                }

                $type = $reflectionParameter->getType()->allowsNull() ? new NullableType($reflectionTypeName): $reflectionTypeName;
                $paramBuilder->setTypeHint($type);

                if ($reflectionParameter->isPassedByReference()) {
                    $paramBuilder->makeByRef();
                }

                if ($reflectionParameter->isVariadic()) {
                    $paramBuilder->makeVariadic();
                }

                $methodBuilder->addParam($paramBuilder->getNode());

                // set all default values
                // if a method is called with two few arguments, a native exception will be thrown
                // so we can safely use null as a placeholder here.
                $defaultValues[] = $reflectionParameter->isDefaultValueAvailable()
                    ? $reflectionParameter->getDefaultValue()
                    : null;
            }

            if (!$reflectionMethod->hasReturnType()) {
                throw new LogicException(sprintf(
                    'Retrofit: Method return types are required. None found for %s::%s()',
                    $reflectionClass->name,
                    $reflectionMethod->name
                ));
            }

            /** @noinspection NullPointerExceptionInspection */
            $methodBuilder->setReturnType('\\'.(string)$reflectionMethod->getReturnType());

            $defaultNodes = $this->mapArray($defaultValues);

            $methodBuilder->addStmt(
                new Return_(
                    new MethodCall(
                        new Variable('this'),
                        '__handleRetrofitRequest',
                        [
                            new String_($reflectionClass->name),
                            new ConstFetch(new Name('__FUNCTION__')),
                            new FuncCall(new Name('func_get_args')),
                            new Array_($defaultNodes)
                        ]
                    )
                )
            );

            $builder->addStmt($methodBuilder->getNode());
        }

        $namespaceBuilder = $this->builderFactory
            ->namespace(self::PROXY_PREFIX.$reflectionClass->getNamespaceName())
            ->addStmt($builder);

        $source = $this->printer->prettyPrint([$namespaceBuilder->getNode()]);

        eval($source);

        if (!$this->enableCache) {
            return new $className($this->serviceMethodFactory, $this->httpClient);
        }

        $directory = $this->cacheDir.DIRECTORY_SEPARATOR.$reflectionClass->getNamespaceName();
        $directory = str_replace('\\', DIRECTORY_SEPARATOR, $directory);
        $filename = $directory.DIRECTORY_SEPARATOR.$reflectionClass->getShortName().'.php';

        $class = '<?php'.PHP_EOL.PHP_EOL.$source;
        if (!$this->filesystem->makeDirectory($directory)) {
            throw new RuntimeException(sprintf(
                'Retrofit: There was an issue creating the cache directory: %s',
                $directory)
            );
        }

        if (!$this->filesystem->put($filename, $class)) {
            throw new RuntimeException(sprintf('Retrofit: There was an issue writing proxy class to: %s', $filename));
        }

        return new $className($this->serviceMethodFactory, $this->httpClient);
    }

    /**
     * Convert array to an array of [@see Expr] to add to builder
     *
     * @param array $array
     * @return Expr[]
     */
    private function mapArray(array $array): array
    {
        // for each element in the array, create an Expr object
        $values = array_values(array_map(function ($value) {
            $type = TypeToken::createFromVariable($value);
            switch ($type) {
                case TypeToken::STRING:
                    return new String_($value);
                case TypeToken::INTEGER:
                    return new LNumber($value);
                case TypeToken::FLOAT:
                    return new DNumber($value);
                case TypeToken::BOOLEAN:
                    return $value === true ? new ConstFetch(new Name('true')) : new ConstFetch(new Name('false'));
                case TypeToken::HASH:
                    // recurse if array contains an array
                    return new Array_($this->mapArray($value));
                case TypeToken::NULL:
                    return new ConstFetch(new Name('null'));
            }
        }, $array));

        $keys = \array_keys($array);
        $isNumericKeys = \count(\array_filter($keys, '\is_string')) === 0;

        // a 0-indexed array can be returned as-is
        if ($isNumericKeys) {
            return $values;
        }

        // if we're dealing with an associative array, run the keys through the mapper
        $keys = $this->mapArray($keys);

        // create an array of ArrayItem objects for an associative array
        $items = [];
        foreach ($values as $index => $value) {
            $items[] = new Expr\ArrayItem($value, $keys[$index]);
        }

        return $items;
    }
}
