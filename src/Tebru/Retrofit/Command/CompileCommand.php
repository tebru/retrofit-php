<?php
/**
 * File CompileCommand.php 
 */

namespace Tebru\Retrofit\Command;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tebru\Retrofit\Retrofit;

/**
 * Class CompileCommand
 *
 * @author Nate Brunette <n@tebru.net>
 */
class CompileCommand extends Command
{
    protected function configure()
    {
        $this->setName('retrofit:compile');
        $this->setDescription('Compiles and caches all services found in the project');
        $this->addArgument('sourceDirectory', InputArgument::REQUIRED, 'Enter the source directory');
        $this->addArgument('cacheDirectory', InputArgument::REQUIRED, 'Enter the cache directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $srcDir = $input->getArgument('sourceDirectory');
        $cacheDir = $input->getArgument('cacheDirectory');

        $retrofit = new Retrofit($cacheDir);

        $directory = new RecursiveDirectoryIterator($srcDir);
        $iterator = new RecursiveIteratorIterator($directory);
        $files = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        $matches = 0;
        foreach ($files as $file) {
            $regex = '/Tebru\\\\Retrofit\\\\Annotation/';
            $fileString = file_get_contents($file[0]);
            $matchesFound = preg_match($regex, $fileString);

            if (!$matchesFound) {
                continue;
            }

            $namespaceRegex = '/^namespace\s+([\w\\\\]+)/m';
            $interfaceRegex = '/^interface\s+([\w\\\\]+)[\s{\n]?/m';
            $namespaceMatchesFound = preg_match($namespaceRegex, $fileString, $namespaceMatches);
            $interfaceMatchesFound = preg_match($interfaceRegex, $fileString, $interfaceMatches);

            if (!$interfaceMatchesFound) {
                continue;
            }

            $matches++;

            $className = '';

            if ($namespaceMatchesFound) {
                $className .= '\\' . $namespaceMatches[1];
            }

            $className .= '\\' . $interfaceMatches[1];

            $retrofit->registerService($className);
        }

        $retrofit->createCache();

        $output->writeln(sprintf('<info>Compiled %s %s successfully</info>', $matches, ($matches === 1) ? 'class' : 'classes'));
    }
}
