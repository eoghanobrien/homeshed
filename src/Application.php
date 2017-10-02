<?php

namespace Homeshed;

use Adbar\Dot;
use Symfony\Component\Console\Application as ConsoleApplication;

class Application extends ConsoleApplication
{
    /**
     * The path to the installer configuration file.
     * @var string
     */
    protected $configPath;

    /**
     * Construct a new Ignite Installer console application.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('Homeshed', '1.0.0');

        $this->addDefaultCommands();
    }

    /**
     * Get the path to the user configuration file.
     *
     * @return string
     * @throws \Exception
     */
    protected function getUserConfig()
    {
        $userHome = getenv("HOME");
        $userPath = '.homeshed.json';
        $userFile = $userHome . DIRECTORY_SEPARATOR . $userPath;

        if (! file_exists($userFile)) {
            return [];
        }

        $data = json_decode(file_get_contents($userFile), true);

        if (0 !== json_last_error()) {
            throw new \Exception(
                sprintf(
                    'Error in your %s file: %s',
                    $userFile,
                    json_last_error_msg()
                )
            );
        }

        return $data;
    }

    /**
     * Get the configuration object.
     *
     * @return Config
     */
    public function getConfig()
    {
        return new Dot(array_merge([], $this->getUserConfig()));
    }

    /**
     * Register all of the default commands.
     *
     * @return $this
     */
    protected function addDefaultCommands()
    {
        $this->addCommands([
            new \Homeshed\Command\AddSite(),
            new \Homeshed\Command\AddDatabase(),
            new \Homeshed\Command\AddFolder(),
            new \Homeshed\Command\SetIpAddress(),
            new \Homeshed\Command\SetMemory(),
            new \Homeshed\Command\SetCpus(),
            new \Homeshed\Command\SetProvider(),
            new \Homeshed\Command\SetName(),
            new \Homeshed\Command\SetHostname(),
        ]);

        return $this;
    }
}
