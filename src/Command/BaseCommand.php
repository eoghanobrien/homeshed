<?php

namespace Homeshed\Command;

use Homeshed\Settings;
use Laravel\Homestead\Settings\YamlSettings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command
{
    /**
     * The unicode icon for a tick.
     * @var string
     */
    protected $pass = "\u{2713}";

    /**
     * The unicode icon for a cross.
     * @var string
     */
    protected $fail = "\u{2717}";

    /**
     * The Homestead yaml settings.
     * @var YamlSettings
     */
    protected $settings;

    /**
     * Initializes the command just after the input has been validated.
     *
     * This is mainly useful when a lot of commands extends one main command
     * where some things need to be initialized based on the input arguments and options.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->loadSettings();
    }

    /**
     * Format a success message.
     *
     * @return string
     */
    protected function success()
    {
        return sprintf("<fg=green>%s</> ", $this->pass) . $this->message(func_get_args());
    }

    /**
     * Format a failure message.
     *
     * @return string
     */
    protected function failure()
    {
        return sprintf("<fg=red>%s</> ", $this->fail) . $this->message(func_get_args());
    }

    /**
     * Format an array as a message using vsprintf.
     *
     * @param  array $parts
     * @return string
     */
    protected function message($parts)
    {
        if (empty($parts)) {
            return '';
        }

        $message = array_shift($parts);

        return vsprintf($message, $parts);
    }

    /**
     * Get the Homestead.yaml settings.
     *
     * @return Settings
     */
    protected function settings()
    {
        return $this->settings;
    }

    /**
     * Load the settings from the Homestead.yaml
     *
     * @return $this
     */
    protected function loadSettings()
    {
        $file = $this->homesteadFile();
        $this->settings = Settings::fromFile($file);
        $this->settings->setFilename($file);

        return $this;
    }

    /**
     * Get the correct path to the Homestead.yaml file.
     *
     * @return bool|mixed|string
     */
    protected function homesteadFile()
    {
        return $this->homesteadPath() . DIRECTORY_SEPARATOR . 'Homestead.yaml';
    }

    /**
     * Get the path to the directory containing the Homestead.yaml file.
     *
     * @return string
     */
    protected function homesteadPath()
    {
        return $this->getApplication()->getConfig()->get(
            'homestead.path', $this->guessHomesteadPath()
        );
    }

    /**
     * Guess the location of the directory containing the Homestead.yaml file.
     *
     * @return string
     */
    protected function guessHomesteadPath()
    {
        return getenv("HOME") . DIRECTORY_SEPARATOR . 'Homestead';
    }
}
