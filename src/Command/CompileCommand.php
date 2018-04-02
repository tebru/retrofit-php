<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Command;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tebru\Retrofit\HttpClient;
use Tebru\Retrofit\Retrofit;

/**
 * Class CompileCommand
 *
 * @author Nate Brunette <n@tebru.net>
 * @codeCoverageIgnore
 */
class CompileCommand extends Command
{
    /**
     * Configure command
     *
     * @throws InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('compile');
        $this->setDescription('Compiles and caches all services found in the project');
        $this->addArgument('sourceDirectory', InputArgument::REQUIRED, 'Enter the source directory');
        $this->addArgument('cacheDirectory', InputArgument::REQUIRED, 'Enter the cache directory');
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $srcDir = $input->getArgument('sourceDirectory');
        $cacheDir = $input->getArgument('cacheDirectory');

        $clientStub = new class implements HttpClient {

            /**
             * Send a request synchronously and return a PSR-7 [@see ResponseInterface]
             *
             * @param RequestInterface $request
             * @return ResponseInterface
             */
            public function send(RequestInterface $request): ResponseInterface
            {
                return new Response();
            }

            /**
             * Send a request asynchronously
             *
             * The response callback must be called if any response is returned from the request, and the failure
             * callback should only be executed if a request was not completed.
             *
             * The response callback should pass a PSR-7 [@see ResponseInterface] as the one and only argument. The
             * failure callback should pass a [@see Throwable] as the one and only argument.
             *
             * @param RequestInterface $request
             * @param callable $onResponse
             * @param callable $onFailure
             * @return void
             */
            public function sendAsync(RequestInterface $request, callable $onResponse, callable $onFailure): void
            {
            }

            /**
             * Calling this method should execute any enqueued requests asynchronously
             *
             * @return void
             */
            public function wait(): void
            {
            }
        };

        $retrofit = Retrofit::builder()
            ->setBaseUrl('')
            ->setHttpClient($clientStub)
            ->setCacheDir($cacheDir)
            ->enableCache()
            ->build();
        $count = $retrofit->createAll($srcDir);

        $output->writeln(sprintf('<info>Compiled %s %s successfully</info>', $count, ($count === 1) ? 'class' : 'classes'));
    }
}
