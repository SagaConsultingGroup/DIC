<?php
/**
 * @file
 * This file contains the Drush integration functionality for the DIC module.
 */

/**
 * Implements hook_drush_command().
 */
function dic_drush_command() {
  $items = array();

  $items['dic-container-build'] = array(
    'description' => dt('Build the container.'),
    'aliases' => array('dic-cb'),
  );

  return $items;
}

/**
 * Adds a cache clear option for DIC.
 */
function dic_drush_cache_clear(&$types) {
  $types['dic'] = 'dic_clear_container_cache';
}

/**
 * Rebuild the container.
 */
function drush_dic_container_build() {
  DIC::reboot();
  drush_log(dt('DIC container has been build!'), 'success');
}
