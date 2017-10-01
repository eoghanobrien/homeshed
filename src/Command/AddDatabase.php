<?php

namespace Homeshed\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddDatabase extends BaseCommand
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('add:database')
             ->setDescription('Add a new database your Homestead configuration.');

        $this->addArgument('database', InputArgument::REQUIRED, 'The database name');
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $database = $input->getArgument('database');
        $database = filter_var($database, FILTER_SANITIZE_STRING);

        $this->settings()->updateDatabase($database);
        $this->settings()->commit();

        $output->writeln($this->success('Added database <fg=yellow>%s</>', $database));
    }
}
