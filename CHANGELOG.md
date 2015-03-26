# Changelog
## 7.1.4 (2015-03-26)
 - Added Drush integration for clearing the container cache (drush cc dic).
 - Added a rebuild function to force a container rebuild. This can be very useful in installation hooks.

## 7.1.3 (2015-03-08)
 - Composer autoload file is now included on boot, not on init.

## 7.1.2 (2014-11-24)
 - Only services of enabled (non-core) modules are now loaded.
 - drupal_is_cli is used instead of php_sapi_name.
 - A testing platform is introduced.

## 7.1.1 (2014-11-05)
 - Removed Demo module.
 - Removed Composer Manager integration module.

## 7.1.0 (2014-10-30)
 - Initial commit.
