<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use LogicException;
use Tebru\Retrofit\Generation\Handler;
use Tebru\Retrofit\Generation\HandlerContext;
use Tebru\Retrofit\Generation\Manipulator\QueryManipulator;

/**
 * Class BodyHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BodyHandler implements Handler
{
    /**
     * Create body
     *
     * @param HandlerContext $context
     * @return null
     * @throws LogicException
     */
    public function __invoke(HandlerContext $context)
    {
        if (!$context->annotations()->hasBody()) {
            $context->body()->add('$body = null;');

            return null;
        }

        // if body is optional and 'empty' it should be null
        if ($context->annotations()->isBodyOptional()) {
            $context->body()->add('if (empty(%s)) { $body = null; } else {', $context->annotations()->getBody());
        }

        $this->doCreateBody($context);

        if ($context->annotations()->isBodyOptional()) {
            $context->body()->add('}');
        }
    }

    /**
     * Create the body
     *
     * @param HandlerContext $context
     * @return null
     * @throws LogicException
     */
    private function doCreateBody(HandlerContext $context)
    {
        $body = $context->annotations()->getBody();

        // check if body is a string, set variable if it doesn't already equal '$body'
        if (!$context->annotations()->isBodyArray() && !$context->annotations()->isBodyObject() && null === $context->annotations()->getBodyParts()) {
            if ('$body' !== $body) {
                $context->body()->add('$body = %s;', $body);
            }

            return null;
        }

        // if we're json encoding, we don't need the body as an array first
        if ($context->annotations()->isJsonEncoded()) {
            $this->createBodyJson($context);

            return null;
        }

        // otherwise, we do need to convert the body to an array
        $this->createBodyArray($context);

        if ($context->annotations()->isFormUrlEncoded()) {
            $context->body()->add('$bodyArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString($bodyArray);');
            $context->body()->add('$body = http_build_query($bodyArray);');

            return null;
        }

        // body is multipart
        $context->body()->add('$bodyParts = [];');
        $context->body()->add('foreach ($bodyArray as $key => $value) {');
        $context->body()->add('$file = null;');
        $context->body()->add('if (is_resource($value)) { $file = $value; }');
        $context->body()->add('if (is_string($value)) { $file = fopen($value, "r"); }');
        $context->body()->add('if (!is_resource($file)) { throw new \LogicException("Expected resource or file path"); }');
        $context->body()->add('$bodyParts[] = ["name" => $key, "contents" => $file];');
        $context->body()->add('}');

        $context->body()->add('$body = new \GuzzleHttp\Psr7\MultipartStream($bodyParts, "%s");', $context->annotations()->getMultipartBoundary());
    }

    /**
     * Create json body logic
     *
     * @param HandlerContext $context
     * @return null
     * @throws LogicException
     */
    private function createBodyJson(HandlerContext $context)
    {
        $body = $context->annotations()->getBody();

        // json encode arrays
        if ($context->annotations()->isBodyArray()) {
            $context->body()->add('$body = json_encode(%s);', $body);

            return null;
        }

        // if parts exist, json encode the parts
        if (null !== $context->annotations()->getBodyParts()) {
            $context->body()->add('$body = json_encode(%s);', $context->printer()->printArray($context->annotations()->getBodyParts()));

            return null;
        }

        // if it's an object, serialize it unless it implements \JsonSerializable
        if ($context->annotations()->isBodyJsonSerializable()) {
            $context->body()->add('$body = json_encode(%s);', $body);

            return null;
        }

        $this->createContext($context, '$bodySerializationContext');
        $context->body()->add('$body = $this->serializer->serialize(%s, "json", $bodySerializationContext);', $body);
    }

    /**
     * Create body as array first
     *
     * @param HandlerContext $context
     * @return null
     * @throws LogicException
     */
    private function createBodyArray(HandlerContext $context)
    {
        $body = $context->annotations()->getBody();

        // if it's already an array, set to variable
        if ($context->annotations()->isBodyArray()) {
            $context->body()->add('$bodyArray = %s;', $body);

            return null;
        }

        // if parts exist, set to variable
        if (null !== $context->annotations()->getBodyParts()) {
            $context->body()->add('$bodyArray = %s;', $context->printer()->printArray($context->annotations()->getBodyParts()));

            return null;
        }

        // if it implements \JsonSerializable, call jsonSerialize() to get array
        if ($context->annotations()->isBodyJsonSerializable()) {
            $context->body()->add('$bodyArray = %s->jsonSerialize();', $body);

            return;
        }

        // otherwise, convert to array
        $this->createContext($context, '$bodySerializationContext');
        $context->body()->add('$bodyArray = $this->serializer->toArray(%s, $bodySerializationContext);', $body);
    }

    /**
     * Create serialization context
     *
     * @param HandlerContext $context
     * @param string $contextVar
     * @return null
     */
    private function createContext(HandlerContext $context, $contextVar)
    {
        $serializationContext = $context->annotations()->getSerializationContext();

        $context->body()->add('%s = \JMS\Serializer\SerializationContext::create();', $contextVar);

        if (0 === count($serializationContext)) {
            return null;
        }

        if (!empty($serializationContext['groups'])) {
            $context->body()->add('%s->setGroups(%s);', $contextVar, $context->printer()->printArray($serializationContext['groups']));
        }

        if (!empty($serializationContext['version'])) {
            $context->body()->add('%s->setVersion(%d);', $contextVar, (int) $serializationContext['version']);
        }

        if (!empty($serializationContext['serializeNull'])) {
            $serializeNull = QueryManipulator::boolToString([(bool) $serializationContext['serializeNull']])[0];
            $context->body()->add('%s->setSerializeNull(%s);', $contextVar, $serializeNull);
        }

        if (!empty($serializationContext['enableMaxDepthChecks'])) {
            $context->body()->add('%s->enableMaxDepthChecks();', $contextVar);
        }

        if (!empty($serializationContext['attributes'])) {
            foreach ($serializationContext['attributes'] as $key => $value) {
                $context->body()->add('%s->setAttribute("%s", "%s");', $contextVar, $key, $value);
            }
        }
    }
}
