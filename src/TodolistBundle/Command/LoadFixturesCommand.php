<?php

namespace TodolistBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class LoadFixturesCommand extends ContainerAwareCommand {

    /**
     * Register command.
     */
    protected function configure() {
        $this->setName('app:load-fixtures')->setDescription('')
                ->addArgument('entityName');
    }

    /**
     * Execute command.
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $startOfExecution = microtime(true); //track how long this script runs

        $entityName = $input->getArgument('entityName');

        $finder = new Finder();
        $finder->files()->in(__DIR__)->name($entityName . '.sql');

        $file = null;
        foreach ($finder as $file) {
            $file = $file;
        }


        $em = $this->getContainer()->get('doctrine')->getManager();
        if (($handle = fopen($file->getRealPath(), "r")) !== false) {
            $output->writeln("Started loading $entityName entities.");

            $sql = file_get_contents($file->getRealPath());

            try {
                $em->getConnection()->executeUpdate($sql);
            } catch (\Exception $e) {
                $message = $e->getMessage();
                $output->writeln("Caught an Exception while persisting: $message");
            }
        }

        fclose($handle);

        $durationOfExecution = round((microtime(true) - $startOfExecution), 1);
        $output->writeln("Loading complete. Execution time was $durationOfExecution seconds.");
    }

}
