# homeshed

A small CLI application for updating your [Laravel Homestead](https://laravel.com/docs/5.5/homestead) configuration file, allows you to quickly update your Homestead.yaml file using simple commands. The tool is especially useful for creating development shortcuts and workflows.

> **Note:** The tool has not yet been fully tested on Windows!

# Requirements

On your local (non homestead) machine, you need the following:

- Git
- PHP
- Composer

# Installation

As of now, you must install the tool as a global composer package:

```sh
composer global require "eoghanobrien/homeshed" dev-master
```

In your home directory, create a `.homeshed.json` file with the following structure:

```json
{
  "homestead": {
    "path": "/path/to/my/Homestead"
  }
}
```

If you installed `Homestead` into a non-standard location, make sure to add your custom path here without the `Homestead.yaml` file suffixed.

# Commands

> **Pro-Tip** You can `quiet` all homeshed commands using the `-q` or `--quiet` option.

### List

Lists all the available commands:

```sh
homeshed list
```

### Add Site

Adds a new site to your Homestead.yaml file, or updates an existing site if the `map` value is the same.

The path will be set for you, so `Code/example` becomes `/home/vagrant/Code/example/public`

```sh
homeshed add:site example.app Code/example
```

#### Add Site Type

Homestead supports several [types of sites](https://laravel.com/docs/5.5/homestead#site-types) which allow you to easily run projects that are not based on Laravel. The available site types are: `apache`, `laravel` (the default), `proxy`, `silverstripe`, `statamic`, `symfony2`, and `symfony4`.

You can add the type using the `-t|--type` option.

```sh
homeshed add:site example.app Code/example -t symfony4
```

#### Add Cron Schedule

You can instruct Homestead to enable [cron schedules](https://laravel.com/docs/5.5/homestead#configuring-cron-schedules) using the `-c|--cron` option.

```sh
homeshed add:site example.app Code/example -c
```

#### Add Environment Parameters

You can also add additional Nginx fastcgi_param values to your site via the [params site directive](https://laravel.com/docs/5.5/homestead#site-parameters) by passing one or more `-p|--params` options.

```sh
homeshed add:site example.app Code/example -p foo:bar -p bar:baz -p baz:foo
```

### Add Database

Adds a new database to your Homestead.yaml file.

```sh
homeshed add:database my_db
```

> Feature Coming Soon: Prefix and Suffix database names using the `~/.homeshed.json` file.

### Add Folder

Adds a new folder to your Homestead.yaml file.

```sh
homeshed add:folder ~/my/local/folder /home/vagrant/my/shared/folder
```

### Set CPUs

Set the number of cpus your Homestead Virtual Machine should use.

```sh
homeshed set:cpus 2
```


### Set Hostname

Set the hostname of the Homestead Virtual Machine.

```sh
homeshed set:hostname symfony-sites
```

### Set IP Address
  
Set the local IP address of the Homestead Virtual Machine.

```sh
homeshed set:ip 192.168.200.200
```

### Set Memory

Set the memory allowance of the Homestead Virtual Machine.

```sh
homeshed set:memory 512
```

### Set Name

Set the VM name.

```sh
homeshed set:name laravel
```

### Set Provider

Set the provider of the Homestead Virtual Machine. The provider key in your Homestead.yaml file indicates which Vagrant provider should be used: `virtualbox`, `vmware_fusion`, `vmware_workstation`, or `parallels`.
  
```sh
homeshed set:provider vmware
```
