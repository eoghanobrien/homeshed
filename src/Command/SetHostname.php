<?php

namespace Homeshed\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetHostname extends BaseCommand
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('set:hostname')
             ->setDescription('Set the VM hostname.');

        $this->addArgument('host', InputArgument::REQUIRED, 'The hostname');
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
        $host = $input->getArgument('host');

        $this->settings()->updateHostname($host)->commit();

        $output->writeln($this->success('Set hostname to <fg=yellow>%s</>', $host));
    }
}
