<?php

namespace Homeshed\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetIpAddress extends BaseCommand
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('set:ip')
             ->setDescription('Set the VM IP address.');

        $this->addArgument('ip', InputArgument::REQUIRED, 'The IP address');
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
        $ip = $input->getArgument('ip');
        if (! filter_var($ip, FILTER_VALIDATE_IP)) {
            $output->writeln($this->failure('<fg=red>%s</> is not a valid IP address', $ip));
        }

        $this->settings()->updateIpAddress($ip)->commit();

        $output->writeln($this->success('Set IP address to <fg=yellow>%s</>', $ip));
    }
}
