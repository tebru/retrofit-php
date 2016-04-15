<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Printer;

use PhpParser\Lexer;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;
use PhpParser\PrettyPrinterAbstract;
use Tebru\Retrofit\Exception\RetrofitException;

/**
 * Class ArrayPrinter
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ArrayPrinter
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var PrettyPrinterAbstract
     */
    private $printer;

    /**
     * Constructor
     *
     * @param Parser $parser
     * @param PrettyPrinterAbstract $printer
     */
    public function __construct(Parser $parser = null, PrettyPrinterAbstract $printer = null)
    {
        if (null === $parser) {
            $parser = new Parser(new Lexer());
        }

        if (null === $printer) {
            $printer = new Standard();
        }

        $this->parser = $parser;
        $this->printer = $printer;
    }

    /**
     * Take a PHP array and convert it to a string
     *
     * @param array $array
     * @return string
     * @throws RetrofitException
     */
    public function printArray(array $array)
    {
        $string = var_export($array, true);
        $string = preg_replace('/\'\$(.+)\'/', '$' . '\\1', $string);

        $statements = $this->parser->parse($string);

        if (null === $statements) {
            throw new RetrofitException('There was an error parsing the array');
        }

        return $this->printer->prettyPrintFile($statements);
    }
}
