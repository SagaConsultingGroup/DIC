<?php
/**
 * @file
 * Contains DIC.
 */

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the DIC Service Container wrapper.
 */
class DIC {

  /**
   * @var bool
   */
  private static $booted;

  /**
   * The currently active container object.
   *
   * @var ContainerInterface
   */
  protected static $container;

  /**
   * Create service container if needed.
   */
  private static function boot() {
    if (!self::$booted) {
      $builder = new \Drupal\dic\DICBuilder(self::getServicePaths(), drupal_realpath('public://') . '/.container/container.php');
      static::$container = $builder->build();
      self::$booted = TRUE;
    }
  }

  /**
   * Force the container to be built again.
   */
  public static function reboot() {
    dic_clear_container_cache();
    self::$booted = FALSE;
    self::boot();
  }

  /**
   * Retrieves a service from the container.
   *
   * Use this method if the desired service is not one of those with a dedicated
   * accessor method below. If it is listed below, those methods are preferred
   * as they can return useful type hints.
   *
   * @param string $id
   *   The ID of the service to retrieve.
   * @return mixed
   *   The specified service.
   */
  public static function service($id) {
    self::boot();
    return static::$container->get($id);
  }

  /**
   * Indicates if a service is defined in the container.
   *
   * @param string $id
   *   The ID of the service to check.
   *
   * @return bool
   *   TRUE if the specified service exists, FALSE otherwise.
   */
  public static function hasService($id) {
    self::boot();
    return static::$container && static::$container->has($id);
  }

  /**
   * Get the paths for all service definition files.
   *
   * @return array
   */
  private static function getServicePaths() {
    $cached = cache_get('dic_service_paths', 'cache_bootstrap');
    if ($cached) {
      return $cached->data;
    }

    $paths = array();
    $extensions = system_list('module_enabled');
    foreach ($extensions as $extension_name => $extension) {
      // Check if the extension is not part of core.
      if (strpos($extension->filename, 'sites') === 0) {
        $module_path = substr($extension->filename, 0, strrpos($extension->filename, '/'));
        $service_path = DRUPAL_ROOT . "/$module_path/$extension_name.services.yml";
        if (file_exists($service_path)) {
          $paths[] = $service_path;
        }
      }
    }

    drupal_alter('dic_service_paths', $paths);
    cache_set('dic_service_paths', $paths, 'cache_bootstrap');

    return $paths;
  }
}
