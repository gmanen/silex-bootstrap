<?php

/*
 * This file is part of the c2is/silex-bootstrap.
 *
 * (c) Morgan Brunot <brunot.morgan@gmail.com>
 */

namespace Core\Command\Migration;

use Core\Command\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('migration:down')
            ->setDescription('Runs propel migration script')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $migrationStatusFile = sprintf("%s/migrations", $this->getProjectDirectory());

        $migrationToRun = (int) trim(file_get_contents($migrationStatusFile));
        $output->writeln(sprintf("%s <info>%s migration(s)</info>", $this->getName(), $migrationToRun));

        $trace = '';
        for ($i = 0; $i < $migrationToRun; $i++) {
            $trace .= $this->runPropelGen('down');
        }

        if (OutputInterface::VERBOSITY_VERBOSE === $output->getVerbosity()) {
            $output->write($trace);
        }

        $output->writeln(sprintf("%s <info>success</info>", $this->getName()));
    }
}
