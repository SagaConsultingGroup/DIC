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
      $builder = new \Drupal\dic\DICBuilder(DRUPAL_ROOT, drupal_realpath('public://') . '/.container/container.php');
      static::$container = $builder->build();
      self::$booted = TRUE;
    }
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
}
