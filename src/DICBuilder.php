<?php
/**
 * @file
 * Contains Drupal\dic\DICBuilder.
 */

namespace Drupal\dic;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Defines the DIC Service Container wrapper.
 */
class DICBuilder {

  /**
   * @var string The path where container will be located.
   */
  private $path;

  public function __construct() {
    $this->path = drupal_realpath('public://') . '/.container/container.php';
  }

  /**
   * Builds the container.
   *
   * @return ContainerInterface
   */
  public function build() {
    $container = $this->loadCache($this->path);

    if ($container) {
      return $container;
    }

    $container = $this->createContainer();

    $this->compileContainer($container);
    $this->cacheContainer($container, $this->path);

    return $container;
  }

  /**
   * Try to load the container from cache.
   *
   * @param string $container_path
   *
   * @return null|\ProjectServiceContainer
   */
  private function loadCache($container_path) {
    if (!file_exists($container_path)) {
      return NULL;
    }

    require_once($container_path);

    return new \ProjectServiceContainer();
  }

  /**
   * Create a new container from all service definition files.
   *
   * @return ContainerBuilder
   */
  private function createContainer() {
    $container = new ContainerBuilder();

    $loader = new YamlFileLoader($container, new FileLocator());
    foreach ($this->getConfigPaths() as $path) {
      $loader->load($path);
    }

    return $container;
  }

  /**
   * Get the paths for all service definition files.
   *
   * @return array
   */
  private function getConfigPaths() {
    // Build service paths.
    // TODO: support even more levels.
    $patterns = array(
      DRUPAL_ROOT . '/sites/*/modules/*/*/*.services.yml',
      DRUPAL_ROOT . '/sites/*/modules/*/*/modules/*/*.services.yml',
    );

    // TODO: only use enabled modules.
    $paths = array();
    foreach ($patterns as $pattern) {
      if ($module_paths = glob($pattern)) {
        foreach ($module_paths as $module_path) {
          $paths[] = $module_path;
        }
      }
    }

    return $paths;
  }

  /**
   * Register all compiler passes and compile the container.
   *
   * @param $container
   */
  private function compileContainer(ContainerBuilder $container) {
    $tagged_services = $container->findTaggedServiceIds('compiler_pass');
    foreach ($tagged_services as $id => $tag_attributes) {
      $container->addCompilerPass($container->get($id));
    }

    $container->compile();
  }

  /**
   * Cache the container to the given path.
   *
   * @param ContainerBuilder $container
   * @param string $container_path
   */
  private function cacheContainer(ContainerBuilder $container, $container_path) {
    $folder = dirname($container_path);
    if (!is_dir($folder)) {
      $old = umask(0);
      mkdir($folder);
      umask($old);
    }

    $dumper = new PhpDumper($container);
    file_put_contents($container_path, $dumper->dump());
    chmod($container_path, 0755);
  }
}
