<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Internal;

use Doctrine\Common\Annotations\AnnotationReader;
use InvalidArgumentException;
use LogicException;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter\Standard;
use RuntimeException;
use Symfony\Component\Cache\Simple\NullCache;
use Tebru\AnnotationReader\AnnotationReaderAdapter;
use Tebru\Retrofit\Internal\AnnotationProcessor;
use Tebru\Retrofit\Internal\CallAdapter\CallAdapterProvider;
use Tebru\Retrofit\Internal\CallAdapter\DefaultCallAdapterFactory;
use Tebru\Retrofit\Internal\Converter\ConverterProvider;
use Tebru\Retrofit\Internal\Converter\DefaultConverterFactory;
use Tebru\Retrofit\Internal\DefaultProxyFactory;
use PHPUnit\Framework\TestCase;
use Tebru\Retrofit\Internal\ServiceMethod\ServiceMethodFactory;
use Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest\PFTCTestCreate;
use Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest\PFTCTestCreateCacheDirectoryFail;
use Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest\PFTCTestCreateClientFail;
use Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest\PFTCTestCreateTwice;
use Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest\PFTCTestCreateWithoutCache;
use Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest\ProxyFactoryTestClientNoReturnType;
use Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest\ProxyFactoryTestClientNoTypehint;
use Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest\ProxyFactoryTestFilesystem;
use Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest\ProxyFactoryTestHttpClient;

class DefaultProxyFactoryTest extends TestCase
{
    /**
     * @var ProxyFactoryTestFilesystem
     */
    private $filesystem;

    public function setUp()
    {
        $this->filesystem = new ProxyFactoryTestFilesystem();
    }

    public function testCreate()
    {
        $client = $this->createFactory()->create(PFTCTestCreate::class);

        self::assertInstanceOf(PFTCTestCreate::class, $client);
        self::assertSame($this->getExpectedClass(), $this->filesystem->contents);
        self::assertSame(
            '/tmp/cache/retrofit/Tebru/Retrofit/Proxy/Tebru/Retrofit/Test/Mock/Unit/Internal/ProxyFactoryTest',
            $this->filesystem->directory
        );
        self::assertSame(
            '/tmp/cache/retrofit/Tebru/Retrofit/Proxy/Tebru/Retrofit/Test/Mock/Unit/Internal/ProxyFactoryTest/PFTCTestCreate.php',
            $this->filesystem->filename
        );
    }

    public function testCreateTwice()
    {
        $this->createFactory()->create(PFTCTestCreateTwice::class);

        $this->filesystem->directory = null;
        $this->filesystem->filename = null;
        $this->filesystem->contents = null;

        $client = $this->createFactory()->create(PFTCTestCreateTwice::class);

        self::assertInstanceOf(PFTCTestCreateTwice::class, $client);
        self::assertNull($this->filesystem->contents);
        self::assertNull($this->filesystem->directory);
        self::assertNull($this->filesystem->filename);
    }

    public function testCreateWithoutCache()
    {
        $this->createFactory(false)->create(PFTCTestCreateWithoutCache::class);
        $client = $this->createFactory(false)->create(PFTCTestCreateWithoutCache::class);

        self::assertInstanceOf(PFTCTestCreateWithoutCache::class, $client);
        self::assertNull($this->filesystem->contents);
        self::assertNull($this->filesystem->directory);
        self::assertNull($this->filesystem->filename);
    }

    public function testCreateInvalidInterface()
    {
        try {
            $this->createFactory()->create('Foo');
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Foo is expected to be an interface', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testCreateNoTypehint()
    {
        try {
            $this->createFactory()->create(ProxyFactoryTestClientNoTypehint::class);
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Parameter types are required. None found for parameter foo in ' .
                'Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest\ProxyFactoryTestClientNoTypehint::foo()',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testCreateNoReturnType()
    {
        try {
            $this->createFactory()->create(ProxyFactoryTestClientNoReturnType::class);
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Method return types are required. None found for ' .
                'Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest\ProxyFactoryTestClientNoReturnType::foo()',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testCreateCacheDirectoryFail()
    {
        $this->filesystem->makeDirectory = false;
        try {
            $this->createFactory()->create(PFTCTestCreateCacheDirectoryFail::class);
        } catch (RuntimeException $exception) {
            self::assertSame(
                'Retrofit: There was an issue creating the cache directory: ' .
                '/tmp/cache/retrofit/Tebru/Retrofit/Proxy/Tebru/Retrofit/Test/Mock/Unit/Internal/ProxyFactoryTest',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testCreateClientFail()
    {
        $this->filesystem->put = false;
        try {
            $this->createFactory()->create(PFTCTestCreateClientFail::class);
        } catch (RuntimeException $exception) {
            self::assertSame(
                'Retrofit: There was an issue writing proxy class to: ' .
                '/tmp/cache/retrofit/Tebru/Retrofit/Proxy/Tebru/Retrofit/Test/Mock/Unit/Internal/ProxyFactoryTest/PFTCTestCreateClientFail.php',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    private function createFactory(bool $enableCache = true): DefaultProxyFactory
    {
        return new DefaultProxyFactory(
            new BuilderFactory(),
            new Standard(),
            new ServiceMethodFactory(
                new AnnotationProcessor([

                ]),
                new CallAdapterProvider([new DefaultCallAdapterFactory()]),
                new ConverterProvider([new DefaultConverterFactory()]),
                new AnnotationReaderAdapter(new AnnotationReader(), new NullCache()),
                'http://example.com'
            ),
            new ProxyFactoryTestHttpClient(),
            $this->filesystem,
            $enableCache,
            '/tmp/cache/retrofit'
        );
    }

    private function getExpectedClass(): string
    {
        return <<< 'EOT'
<?php

namespace Tebru\Retrofit\Proxy\Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest;

class PFTCTestCreate extends \Tebru\Retrofit\Proxy\AbstractProxy implements \Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest\PFTCTestCreate
{
    public function simple(string $path, \stdClass $body, string &$query = 'foo', string ...$fields) : \Tebru\Retrofit\Call
    {
        return $this->__handleRetrofitRequest('Tebru\\Retrofit\\Test\\Mock\\Unit\\Internal\\ProxyFactoryTest\\PFTCTestCreate', __FUNCTION__, func_get_args());
    }
    public static function static() : \Tebru\Retrofit\Call
    {
        return $this->__handleRetrofitRequest('Tebru\\Retrofit\\Test\\Mock\\Unit\\Internal\\ProxyFactoryTest\\PFTCTestCreate', __FUNCTION__, func_get_args());
    }
}
EOT;
    }
}
