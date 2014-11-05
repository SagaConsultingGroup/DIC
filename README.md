# DIC - Dependency Injection Container
Using this Drupal module, you can use the Symfony Dependency Injection Container in your Drupal 7 modules. This will help you writing custom Drupal modules in a more object-oriented, future-proof and maintainable way.

[Composer](https://getcomposer.org/ "Composer") is required in your project and this module expects a composer.json file in the root of your Drupal installation. If you install only this module, you will have to maintain your composer.json file yourself and make sure the classes you create in your custom Drupal modules are autoloaded correctly. If you want the [Composer Manager](https://www.drupal.org/project/composer_manager "Composer Manager") module to handle this, you can install the DIC - Composer Manager module.

## Installation and Configuration
- Make sure Composer is installed.
- Add a composer.json file to the root of your Drupal 7 installation with the following content:
```
{
  "require": {
    "php": ">=5.3.0",
    "composer/installers": "1.0.*",
    "saga/dic-module": "7.1.*"
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

## Submodules
### DIC - Composer Manager (experimental!)
By default, the DIC module expects you to handle composer integration yourself. If you want to rely on the [Composer Manager](https://www.drupal.org/project/composer_manager "Composer Manager") module for this task, you can enable this module to help you with this. You can for example configure extra requirements, repositories... that will be added to the generated composer.json.

### DIC - Example Module
This module provides an easy to understand example of how the DIC module can be used in your own custom modules.

## Sponsors
This module is sponsored by [Saga Consulting Group](http://www.saga.be "Saga Consulting Group").

## License
This module is licensed under GENERAL PUBLIC LICENSE Version 2, June 1991. See the bundled LICENSE file for details.
