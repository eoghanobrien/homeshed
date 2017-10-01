<?php

namespace Homeshed\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetProvider extends BaseCommand
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('set:provider')
             ->setDescription('Set the VM provider.');

        $this->addArgument('provider', InputArgument::REQUIRED, 'The VM provider');
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
        $provider = $input->getArgument('provider');

        try {
            $this->settings()->updateProvider($provider)->commit();
            $output->writeln($this->success('Set provider to <fg=yellow>%s</>', $provider));
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }
    }
}
