<?php

namespace Homeshed;

use Exception;
use Laravel\Homestead\Settings\YamlSettings;
use Symfony\Component\Yaml\Yaml;

class Settings extends YamlSettings
{
    /**
     * The list of allowed site types.
     * @var array
     */
    protected $siteTypes = [
        'apache', 'laravel', 'proxy', 'silverstripe',
        'statamic', 'symfony2', 'symfony4',
    ];

    /**
     * The list of allowed providers.
     * @var array
     */
    protected $providers = [
        'virtualbox', 'vmware_fusion', 'vmware_workstation', 'parallels'
    ];

    /**
     * The Homestead.yaml file.
     * @var string
     */
    protected $filename;

    /**
     * Set the Homestead.yaml file.
     *
     * @param  string $filename
     * @return $this
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Update the virtual machine's memory allowance.
     *
     * @param  int  $memory
     * @return static
     */
    public function updateMemory($memory)
    {
        $this->update(['memory' => $memory]);

        return $this;
    }

    /**
     * Update the virtual machine's cpus.
     *
     * @param  int  $cpus
     * @return static
     */
    public function updateCpus($cpus)
    {
        $this->update(['cpus' => $cpus]);

        return $this;
    }

    /**
     * Update the virtual machine's provider.
     *
     * @param  int  $provider
     * @return static
     * @throws Exception
     */
    public function updateProvider($provider)
    {
        if (! in_array($provider, $this->providers)) {
            throw new Exception(sprintf('Unknown provider: %s', $provider));
        }

        $this->update(['provider' => $provider]);

        return $this;
    }

    /**
     * Add a database.
     *
     * @param  string $database
     * @return string
     */
    public function updateDatabase($database)
    {
        $this->updateArray('databases', $database);

        return $this;
    }

    /**
     * Add a site.
     *
     * @param  string $map
     * @param  string $to
     * @param  array  $options
     * @return array
     * @throws Exception
     */
    public function updateSite($map, $to, $options = [])
    {
        $to = "/home/vagrant/{$to}/public";

        if (isset($options['type'])) {
            if (! in_array($options['type'], $this->siteTypes)) {
                throw new Exception(sprintf('Unknown site type %s', $options['type']));
            }
            if ($options['type'] === 'laravel') {
                unset($options['type']);
            }
        }

        if (isset($options['schedule'])) {
            if ($options['schedule'] === false) {
                unset($options['schedule']);
            }
        }

        if (isset($options['params'])) {
            if (empty($options['params'])) {
                unset($options['params']);
            }
        }

        return $this->updateMap('sites', $map, $to, $options);
    }

    /**
     * Update a folder.
     *
     * @param  string $map
     * @param  string $to
     * @param  array  $options
     * @return array
     */
    public function updateFolder($map, $to, $options = [])
    {
        return $this->updateMap('folders', $map, $to, $options);
    }

    /**
     * Update an array with map and to.
     *
     * @param  string $key
     * @param  string $map
     * @param  string $to
     * @param  array  $options
     * @return array
     */
    protected function updateMap($key, $map, $to, $options = [])
    {
        $add = array_merge(['map' => $map, 'to'  => $to], $options);
        $index = array_search($map, array_column($this->attributes[$key], 'map'));

        if (false !== $index) {
            $this->attributes[$key][$index] = $add;
        } else {
            $this->attributes[$key][] = $add;
        }

        return $add;
    }

    /**
     * Update a simple array.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return $this
     */
    protected function updateArray($key, $value)
    {
        $attributes = array_filter($this->attributes[$key], function ($attribute) use ($value) {
            return $attribute === $value;
        });

        if (in_array($value, $attributes)) {
            return $this;
        }

        $this->attributes[$key][] = $value;

        return $this;
    }

    /**
     * Commit the changes to the Homestead.yaml file.
     *
     * @return $this
     */
    public function commit()
    {
        $this->save($this->filename);

        return $this;
    }

    /**
     * Save the homestead settings.
     *
     * @param  string  $filename
     * @return void
     */
    public function save($filename)
    {
        file_put_contents($filename,  Yaml::dump($this->attributes, 5));
    }
}
