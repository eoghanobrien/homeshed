<?php

namespace Homeshed\Command;

use Homeshed\Settings;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AddFolder extends BaseCommand
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('add:folder')
             ->setDescription('Add a new folder your Homestead configuration.');

        $this->addArgument('source', InputArgument::REQUIRED, 'The folder to source.');
        $this->addArgument('target', InputArgument::REQUIRED, 'The folder to target.');
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
        $source = $input->getArgument('source');
        $target = $input->getArgument('target');

        $folder = $this->settings()->updateFolder($source, $target);
        $this->settings()->commit();

        if ($folder['map'] === $source) {
            $output->writeln($this->success(
                'Updated folder <fg=yellow>%s</> target to <fg=yellow>%s</> ',
                $folder['map'],
                $folder['to']
            ));
        } else {
            $output->writeln($this->success(
                'Added folder <fg=yellow>%s</> at <fg=yellow>%s</> ',
                $folder['map'],
                $folder['to']
            ));
        }
    }
}
