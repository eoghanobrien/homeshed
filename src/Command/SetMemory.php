<?php

namespace Homeshed\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetMemory extends BaseCommand
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('set:memory')
             ->setDescription('Set the VM memory allowance.');

        $this->addArgument('memory', InputArgument::REQUIRED, 'The amount memory in bytes e.g. 2048');
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
        $memory = $input->getArgument('memory');
        if (! filter_var($memory, FILTER_VALIDATE_INT)) {
            $output->writeln($this->failure('<fg=red>%s</> is not a byte representation', $memory));
        }

        $this->settings()->updateMemory((int) $memory)->commit();

        $output->writeln($this->success('Set memory to <fg=yellow>%s</>', $memory));
    }
}
