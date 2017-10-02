<?php

namespace Homeshed\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AddSite extends BaseCommand
{
    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this->setName('add:site')
             ->setDescription('Add a new site to your Homestead configuration.');

        $this->addArgument('site', InputArgument::REQUIRED, 'The site name url. (e.g. example.app');
        $this->addArgument('path', InputArgument::REQUIRED, 'The site path folder. (e.g. /home/vagrant/Code/example/public)');

        $this->addOption('type', 't', InputOption::VALUE_OPTIONAL, 'The type of site (e.g. laravel, symfony4, etc)', 'laravel');
        $this->addOption('cron', 'c', InputOption::VALUE_NONE, 'Enable cron schedule for this site.');
        $this->addOption('params', 'p', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Add site parameters.', []);
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = filter_var($input->getArgument('site'), FILTER_SANITIZE_URL);
        $path = str_replace('\\', '/', $path = $input->getArgument('path'));

        $type = strtolower($input->getOption('type'));
        $cron = (bool) $input->getOption('cron');
        $vars = array_map(function ($var) {
            return array_combine(['key', 'value'], explode(':', $var));
        }, (array) $input->getOption('params'));

        try {
            $site = $this->settings()->updateSite($name, $path, [
                'type' => $type,
                'schedule' => $cron,
                'params' => $vars
            ]);
            $this->settings()->commit();

            $message = ($site['map'] === $name)
                ? 'Updated site <fg=yellow>%s</> path to <fg=yellow>%s</> '
                : 'Added site <fg=yellow>%s</> at <fg=yellow>%s</> ';

            return $output->writeln($this->success(
                $message,
                $site['map'],
                $site['to']
            ));
        } catch (\Exception $e) {
            return $output->writeln(
                sprintf('<error>Error: %s</error>', $e->getMessage())
            );
        }
    }
}
