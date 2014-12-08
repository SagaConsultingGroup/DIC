# DIC - Dependency Injection Container

[![Build Status](https://travis-ci.org/SagaConsultingGroup/DIC.svg)](https://travis-ci.org/SagaConsultingGroup/DIC) [![Latest Stable Version](https://poser.pugx.org/saga/dic-module/v/stable.svg)](https://packagist.org/packages/saga/dic-module) [![Total Downloads](https://poser.pugx.org/saga/dic-module/downloads.svg)](https://packagist.org/packages/saga/dic-module) [![Latest Unstable Version](https://poser.pugx.org/saga/dic-module/v/unstable.svg)](https://packagist.org/packages/saga/dic-module)

Using this Drupal module, you can use the Symfony Dependency Injection Container in your Drupal 7 modules. This will help you writing custom Drupal modules in a more object-oriented, future-proof and maintainable way.

[Composer](https://getcomposer.org/ "Composer") is required in your project and this module expects a composer.json file in the root of your Drupal installation.

## Installation and Configuration
- Make sure Composer is installed.
- Add a composer.json file to the root of your Drupal 7 installation with the following content:
```
{
  "require": {
    "php": ">=5.3.0",
    "composer/installers": "~1.0",
    "saga/dic-module": "~7.1"
  },
  "extra": {
    "installer-paths": {
      "sites/all/modules/vendor/{$name}": ["type:drupal-module"]
    }
  }
}
```
- In terminal, go to the root of your Drupal 7 sites (where the composer.json file lives) and execute "composer install".
- The DIC module will now be available in "sites/all/modules/vendor/dic" and is ready to be installed using the regular module installation procedure.
- Make sure that the "/vendor" and "sites/*/modules/vendor" folders are added to the .gitignore, you don't want these folders in your VCS.

## Creating custom modules
If you want to create custom modules that use the DIC module, make sure that the classes in your module are autoloaded correctly. You can use several methods to do this:

- Using the [Composer autoload functionality](https://getcomposer.org/doc/01-basic-usage.md#autoloading "Composer autoload functionality")
- Using the [xautoload](https://www.drupal.org/project/xautoload "xautoload") Drupal module
- Using the [registry_autoload](https://www.drupal.org/project/registry_autoload "registry_autoload") Drupal module

## Sponsors
This module is sponsored by [Saga Consulting Group](http://www.saga.be "Saga Consulting Group").

## License
This module is licensed under GENERAL PUBLIC LICENSE Version 2, June 1991. See the bundled LICENSE file for details.