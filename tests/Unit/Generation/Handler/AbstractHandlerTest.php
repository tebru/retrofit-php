<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Handler;

use Mockery;
use Mockery\MockInterface;
use PhpParser\Lexer;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;
use Tebru\Dynamo\Model\Body;
use Tebru\Retrofit\Generation\HandlerContext;
use Tebru\Retrofit\Generation\Printer\ArrayPrinter;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class AbstractHandlerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
abstract class AbstractHandlerTest extends MockeryTestCase
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Standard
     */
    private $printer;

    /**
     * @var Body
     */
    private $body;

    public function setUp()
    {
        parent::setUp();

        $this->parser = new Parser(new Lexer());
        $this->printer = new Standard();
    }

    protected function assertResponse($file)
    {
        $parsed = $this->parser->parse('<?php' . PHP_EOL . (string) $this->body);
        $printed = $this->printer->prettyPrintFile($parsed) . PHP_EOL;
        $filename = sprintf('%s/resources/generation/%s.php', TEST_DIR, $file);

        if (!file_exists($filename)) {
            touch($filename);
        }

        $expected = file_get_contents($filename);
        try {
            $this->assertSame($expected, $printed);
        } catch (\Exception $e) {
            file_put_contents($filename, $printed);
            throw $e;
        }
    }

    /**
     * @param MockInterface $annotationProvider
     * @return HandlerContext
     */
    protected function getHandlerContext(MockInterface $annotationProvider)
    {
        $this->body = new Body();
        $printer = new ArrayPrinter();

        return new HandlerContext($annotationProvider, $this->body, $printer);
    }
}
