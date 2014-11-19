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
   * @var array
   *   The paths to the services.yml configs.
   */
  private $service_paths;

  /**
   * @var string
   *   The path where container will be located.
   */
  private $container_path;

  public function __construct($service_paths, $container_path) {
    $this->service_paths = $service_paths;
    $this->container_path = $container_path;
  }

  /**
   * Builds the container.
   *
   * @return ContainerInterface
   */
  public function build() {
    $container = $this->loadCache($this->container_path);

    if ($container) {
      return $container;
    }

    $container = $this->createContainer();

    $this->compileContainer($container);
    $this->cacheContainer($container, $this->container_path);

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
    foreach ($this->service_paths as $path) {
      $loader->load($path);
    }

    return $container;
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
