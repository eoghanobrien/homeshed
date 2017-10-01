<?php

namespace Homeshed\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetCpus extends BaseCommand
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('set:cpus')
             ->setDescription('Set the VM cpus.');

        $this->addArgument('cpus', InputArgument::REQUIRED, 'The number of CPUs');
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
        $cpus = $input->getArgument('cpus');
        if (! filter_var($cpus, FILTER_VALIDATE_INT)) {
            $output->writeln($this->failure('<fg=red>%s</> is not a number', $cpus));
        }

        $this->settings()->updateCpus((int) $cpus)->commit();

        $output->writeln($this->success('Set the number of cpus to <fg=yellow>%s</>', $cpus));
    }
}
