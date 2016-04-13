<?php

namespace TodolistBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Command line tool to build up a test database with doctrine.
 */
class BuildTestDatabaseCommand extends ContainerAwareCommand {

    /**
     * Register command.
     */
    protected function configure() {
        $this->setName('app:build-test-database')
                ->setDescription("Creates new database with test fixtures");
    }

    /**
     * Execute command.
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $startOfExecution = microtime(true);
        
        $this->runCommand(array('command' => 'doctrine:database:create'), $output);
        $this->runCommand(array('command' => 'doctrine:schema:update', '--force' => true, '--dump-sql' => true), $output);
        $this->runCommand(array('command' => 'app:load-fixtures', 'entityName' => 'TodoItem'), $output);

        $durationOfExecution = round((microtime(true) - $startOfExecution), 1);
        $output->writeln("<info>Execution time was $durationOfExecution seconds.</>");
    }

    /**
     * @param array $commandArgs
     * @param $output
     * @throws \Exception
     * @return void
     */
    protected function runCommand(array $commandArgs, $output) {
        $command = $this->getApplication()->find($commandArgs['command']);
        $input = new ArrayInput($commandArgs);

        $returnCode = $command->run($input, $output);
        $commandName = $command->getName();
        if ($returnCode == 0) {
            $output->writeln("<comment>Successfully executed $commandName.</>");
        }
    }

}
