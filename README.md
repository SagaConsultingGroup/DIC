# DIC - Dependency Injection Container
Using this Drupal module, you can use the Symfony Dependency Injection Container in your Drupal 7 modules. This will help you writing custom Drupal modules in a more object-oriented, future-proof and maintainable way.

[Composer](https://getcomposer.org/ "Composer") is required in your project and this module expects a composer.json file in the root of your Drupal installation. If you install only this module, you will have to maintain your composer.json file yourself and make sure the classes you create in your custom Drupal modules are autoloaded correctly. If you want the [Composer Manager](https://www.drupal.org/project/composer_manager "Composer Manager") module to handle this, you can install the DIC - Composer Manager module.

## Installation and Configuration
Coming soon!

## Submodules
### DIC - Composer Manager
By default, the DIC module expects you to handle composer integration yourself. If you want to rely on the [Composer Manager](https://www.drupal.org/project/composer_manager "Composer Manager") module for this task, you can enable this module to help you with this. You can for example configure extra requirements, repositories... that will be added to the generated composer.json.

### DIC - Example Module
This module provides an easy to understand example of how the DIC module can be used in your own custom modules.

## Sponsors
This module is sponsored by [Saga Consulting Group](http://www.saga.be "Saga Consulting Group").

## License
This module is licensed under GPLv2. See LICENCE file for more information.