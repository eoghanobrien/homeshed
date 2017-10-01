<?php

namespace Homeshed\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetName extends BaseCommand
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('set:name')
             ->setDescription('Set the VM name.');

        $this->addArgument('vm', InputArgument::REQUIRED, 'The VM name');
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
        $host = $input->getArgument('vm');

        $this->settings()->updateName($host)->commit();

        $output->writeln($this->success('Set VM name to <fg=yellow>%s</>', $host));
    }
}
