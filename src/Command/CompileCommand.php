<?php
/**
 * File CompileCommand.php 
 */

namespace Tebru\Retrofit\Command;

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
        $this->setName('compile');
        $this->setDescription('Compiles and caches all services found in the project');
        $this->addArgument('sourceDirectory', InputArgument::REQUIRED, 'Enter the source directory');
        $this->addArgument('cacheDirectory', InputArgument::REQUIRED, 'Enter the cache directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $srcDir = $input->getArgument('sourceDirectory');
        $cacheDir = $input->getArgument('cacheDirectory');

        $retrofit = new Retrofit($cacheDir);
        $count = $retrofit->cacheAll($srcDir);

        $output->writeln(sprintf('<info>Compiled %s %s successfully</info>', $count, ($count === 1) ? 'class' : 'classes'));
    }
}
