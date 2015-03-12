<?php
/**
 * @file
 * This file contains the Drush integration functionality for the DIC module.
 */

/**
 * Adds a cache clear option for DIC.
 */
function dic_drush_cache_clear(&$types) {
  $types['dic'] = 'dic_invalidate_container_cache';
}

/**
 * Remove the DIC cache file.
 */
function dic_invalidate_container_cache() {
  $path = drupal_realpath('public://') . '/.container/container.php';
  if (is_file($path)) {
    unlink($path);
  }
}
