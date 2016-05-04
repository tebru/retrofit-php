<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Provider;

use LogicException;
use OutOfBoundsException;
use ReflectionClass;
use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Dynamo\Model\ParameterModel;
use Tebru\Retrofit\Annotation\BaseUrl;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\FormUrlEncoded;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\HttpRequest;
use Tebru\Retrofit\Annotation\JsonBody;
use Tebru\Retrofit\Annotation\Multipart;
use Tebru\Retrofit\Annotation\Part;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\Annotation\ResponseType;
use Tebru\Retrofit\Annotation\Returns;
use Tebru\Retrofit\Annotation\Serializer\DeserializationContext;
use Tebru\Retrofit\Annotation\Serializer\SerializationContext;
use Tebru\Retrofit\Exception\RetrofitException;

/**
 * Class AnnotationProvider
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AnnotationProvider
{
    /**
     * @var AnnotationCollection
     */
    private $annotations;

    /**
     * @var MethodModel
     */
    private $methodModel;

    /**
     * @var string
     */
    private $multipartBoundary;

    /**
     * Constructor
     *
     * @param AnnotationCollection $annotations
     * @param MethodModel $methodModel
     */
    public function __construct(AnnotationCollection $annotations, MethodModel $methodModel)
    {
        $this->annotations = $annotations;
        $this->methodModel = $methodModel;
    }

    /**
     * Get base url
     *
     * @return null|string
     */
    public function getBaseUrl()
    {
        if (!$this->annotations->exists(BaseUrl::NAME)) {
            return null;
        }

        /** @var BaseUrl $baseUrlAnnotation */
        $baseUrlAnnotation = $this->annotations->get(BaseUrl::NAME);

        return $baseUrlAnnotation->getVariable();
    }

    /**
     * Get request method
     *
     * @return string
     * @throws LogicException
     */
    public function getRequestMethod()
    {
        return $this->getRequestAnnotation()->getType();
    }

    /**
     * Get request uri
     *
     * @return string
     * @throws LogicException
     */
    public function getRequestUri()
    {
        return $this->getRequestAnnotation()->getPath();
    }

    /**
     * Get request queries
     *
     * @return null|array
     * @throws LogicException
     */
    public function getQueries()
    {
        $queries = $this->getRequestAnnotation()->getQueries();

        if ($this->annotations->exists(Query::NAME)) {
            /** @var Query[] $queryAnnotations */
            $queryAnnotations = $this->annotations->get(Query::NAME);

            /** @var Query $queryAnnotation */
            foreach ($queryAnnotations as $queryAnnotation) {
                $queries[$queryAnnotation->getRequestKey()] = $queryAnnotation->getVariable();
            }

        }

        return 0 === count($queries) ? null : $queries;
    }

    /**
     * Get query map
     *
     * @return null|string
     */
    public function getQueryMap()
    {
        if (!$this->annotations->exists(QueryMap::NAME)) {
            return null;
        }

        /** @var QueryMap $queryMapAnnotation */
        $queryMapAnnotation = $this->annotations->get(QueryMap::NAME);

        return $queryMapAnnotation->getVariable();
    }

    /**
     * Get header variables
     *
     * @return array|null
     */
    public function getHeaders()
    {
        if (!$this->annotations->exists(Header::NAME)) {
            return null;
        }

        /** @var Header[] $headerAnnotations */
        $headerAnnotations = $this->annotations->get(Header::NAME);

        $headers = [];
        foreach ($headerAnnotations as $headerAnnotation) {
            $headers[$headerAnnotation->getRequestKey()] = $headerAnnotation->getVariable();
        }

        return $headers;
    }

    /**
     * Get headers defined by "@Headers"
     *
     * @return null|array
     */
    public function getStaticHeaders()
    {
        if (!$this->annotations->exists(Headers::NAME)) {
            return null;
        }

        /** @var Headers $headersAnnotation */
        $headersAnnotation = $this->annotations->get(Headers::NAME);

        $headers = [];
        foreach ($headersAnnotation->getHeaders() as $key => $value) {
            $headers[$key] = $value;
        }

        return $headers;
    }

    /**
     * If the request is json encoded
     *
     * @return bool
     */
    public function isJsonEncoded()
    {
        if ($this->annotations->exists(JsonBody::NAME)) {
            return true;
        }

        return false;
    }

    /**
     * If the request is form encoded
     *
     * @return bool
     */
    public function isFormUrlEncoded()
    {
        if ($this->annotations->exists(FormUrlEncoded::NAME)) {
            return true;
        }

        return !$this->isMultipart() && !$this->isJsonEncoded();
    }

    /**
     * If the request is multipart encoded
     *
     * @return bool
     */
    public function isMultipart()
    {
        if ($this->annotations->exists(Multipart::NAME)) {
            return true;
        }

        return false;
    }

    /**
     * Get the multipart boundary
     *
     * @return string
     */
    public function getMultipartBoundary()
    {
        /** @var Multipart $multipartAnnotation */
        $multipartAnnotation = $this->annotations->get(Multipart::NAME);
        $this->multipartBoundary = $multipartAnnotation->getBoundary();

        if (null === $this->multipartBoundary) {
            $this->multipartBoundary = uniqid('', false);
        }

        return $this->multipartBoundary;
    }

    /**
     * If there is a request body
     *
     * @return bool
     */
    public function hasBody()
    {
        return $this->annotations->exists(Body::NAME) || $this->annotations->exists(Part::NAME);
    }

    /**
     * If there is a body annotation
     *
     * @return bool
     */
    public function hasBodyAnnotation()
    {
        return $this->annotations->exists(Body::NAME);
    }

    /**
     * Get body variable
     *
     * @return null|string
     * @throws LogicException
     */
    public function getBody()
    {
        if (!$this->annotations->exists(Body::NAME)) {
            return null;
        }

        return $this->getBodyAnnotation()->getVariable();
    }

    /**
     * Get body parts
     *
     * @return array|null
     */
    public function getBodyParts()
    {
        if (!$this->annotations->exists(Part::NAME)) {
            return null;
        }

        /** @var Part[] $partAnnotations */
        $partAnnotations = $this->annotations->get(Part::NAME);

        $parts = [];
        foreach ($partAnnotations as $partAnnotation) {
            $parts[$partAnnotation->getRequestKey()] = $partAnnotation->getVariable();
        }

        return $parts;
    }

    /**
     * If the body parameter is an object
     *
     * @return bool
     * @throws LogicException
     */
    public function isBodyObject()
    {
        if (!$this->annotations->exists(Body::NAME)) {
            return false;
        }

        return $this->methodModel->getParameter($this->getBodyAnnotation()->getVariableName())->isObject();
    }

    /**
     * If the body parameter is an array
     *
     * @return bool
     * @throws LogicException
     */
    public function isBodyArray()
    {
        if (!$this->annotations->exists(Body::NAME)) {
            return false;
        }

        return $this->methodModel->getParameter($this->getBodyAnnotation()->getVariableName())->isArray();
    }

    /**
     * If the body parameter is optional
     *
     * @return bool
     * @throws LogicException
     */
    public function isBodyOptional()
    {
        if (!$this->annotations->exists(Body::NAME)) {
            return false;
        }

        return $this->methodModel->getParameter($this->getBodyAnnotation()->getVariableName())->isOptional();
    }

    /**
     * If the body parameter implements \JsonSerializable
     *
     * @return bool
     * @throws LogicException
     */
    public function isBodyJsonSerializable()
    {
        if (!$this->isBodyObject()) {
            return false;
        }

        $typehint = $this->methodModel->getParameter($this->getBodyAnnotation()->getVariableName())->getTypeHint();

        $reflectionClass = new ReflectionClass($typehint);
        $interfaces = $reflectionClass->getInterfaceNames();

        return in_array('JsonSerializable', $interfaces, true);
    }

    /**
     * Get JMS Serialization context
     *
     * @return array|null
     */
    public function getSerializationContext()
    {
        if (!$this->annotations->exists(SerializationContext::NAME)) {
            return null;
        }

        /** @var SerializationContext $contextAnnotation */
        $contextAnnotation = $this->annotations->get(SerializationContext::NAME);

        return [
            'groups' => $contextAnnotation->getGroups(),
            'version' => $contextAnnotation->getVersion(),
            'serializeNull' => $contextAnnotation->getSerializeNull(),
            'enableMaxDepthChecks' => $contextAnnotation->getEnableMaxDepthChecks(),
            'attributes' => $contextAnnotation->getAttributes(),
        ];
    }

    /**
     * Get JMS Deserialization context
     *
     * @return array|null
     */
    public function getDeserializationContext()
    {
        if (!$this->annotations->exists(DeserializationContext::NAME)) {
            return null;
        }

        /** @var DeserializationContext $contextAnnotation */
        $contextAnnotation = $this->annotations->get(DeserializationContext::NAME);

        return [
            'groups' => $contextAnnotation->getGroups(),
            'version' => $contextAnnotation->getVersion(),
            'serializeNull' => $contextAnnotation->getSerializeNull(),
            'enableMaxDepthChecks' => $contextAnnotation->getEnableMaxDepthChecks(),
            'attributes' => $contextAnnotation->getAttributes(),
            'depth' => $contextAnnotation->getDepth(),
        ];
    }

    /**
     * Get the expected return
     *
     * @return null|string
     */
    public function getReturnType()
    {
        if (!$this->annotations->exists(Returns::NAME)) {
            return null;
        }

        /** @var Returns $returnAnnotation */
        $returnAnnotation = $this->annotations->get(Returns::NAME);

        return $returnAnnotation->getReturn();
    }

    /**
     * Get the return type for a response return
     *
     * @return null|string
     */
    public function getResponseType()
    {
        if (!$this->annotations->exists(ResponseType::NAME)) {
            return null;
        }

        /** @var ResponseType $returnAnnotation */
        $returnAnnotation = $this->annotations->get(ResponseType::NAME);

        return $returnAnnotation->getType();
    }

    /**
     * Get the callback parameter variable
     *
     * @return null|string
     * @throws RetrofitException
     */
    public function getCallback()
    {
        $callback = $this->getCallbackParameter();

        if (null === $callback) {
            return null;
        }

        return '$' . $callback->getName();
    }

    /**
     * Returns if the callback is optional
     *
     * @return bool
     * @throws LogicException
     * @throws RetrofitException
     */
    public function isCallbackOptional()
    {
        $callback = $this->getCallbackParameter();

        if (null === $callback) {
            throw new LogicException('Callback does not exist');
        }

        return $callback->isOptional();
    }

    /**
     * Get the request annotation
     *
     * @return HttpRequest
     * @throws LogicException
     */
    private function getRequestAnnotation()
    {
        try {
            $requestAnnotation = $this->annotations->get(HttpRequest::NAME);
        } catch (OutOfBoundsException $exception) {
            throw new LogicException('Request annotation not found (e.g. @GET, @POST)');
        }

        return $requestAnnotation;
    }

    /***
     * Get the body annotation
     *
     * @return Body
     */
    private function getBodyAnnotation()
    {
        return $this->annotations->get(Body::NAME);
    }

    /**
     * Get the callback method parameter
     *
     * @return null|ParameterModel
     * @throws RetrofitException
     */
    private function getCallbackParameter()
    {
        $parameters = array_reverse($this->methodModel->getParameters());
        $callback = null;

        /** @var ParameterModel $parameter */
        foreach ($parameters as $parameter) {
            if ('\Tebru\Retrofit\Http\Callback' === $parameter->getTypeHint()) {
                $callback = $parameter;
            }
        }

        if (null === $callback) {
            return null;
        }

        $reflectionClass = new ReflectionClass($this->methodModel->getClassModel()->getInterface());

        if (!in_array('Tebru\Retrofit\Http\AsyncAware', $reflectionClass->getInterfaceNames(), true)) {
            throw new RetrofitException('Interfaces using async methods must implement the "AsyncAware" class');
        }

        return $callback;
    }
}
