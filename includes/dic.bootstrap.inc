<?php
/**
 * @file
 * Contains the Dependency Injection Container bootstrap functionality.
 */

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Create Dependency Injection Container bootstrap functionality.
 */
function dic_create_container() {
  // TODO: make this path configurable.
  $container_folder = drupal_realpath('public://') . '/.container';
  $container_path = $container_folder . '/container.php';

  // Check for existing container.
  if (file_exists($container_path)) {
    require_once($container_path);
    DIC::setContainer(new \ProjectServiceContainer());

    return;
  }

  // Create cache folder if it does not exist yet.
  if (!file_exists($container_folder)) {
    $old = umask(0);
    mkdir($container_folder);
    umask($old);
  }

  // If there is no autoloading going on, don't do anything.
  if (class_exists('\\Symfony\\Component\\DependencyInjection\\ContainerBuilder')) {
    $container = new ContainerBuilder();
    DIC::setContainer($container);

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

    // Load YAML files.
    $loader = new YamlFileLoader($container, new FileLocator());
    array_walk($paths,
      function ($path) use ($loader) {
        $loader->load($path);
      }
    );

    // Add compiler passes.
    $tagged_services = $container->findTaggedServiceIds('compiler_pass');
    foreach ($tagged_services as $id => $tag_attributes) {
      $container->addCompilerPass($container->get($id));
    }

    // Cache container.
    $container->compile();
    $dumper = new PhpDumper($container);
    file_put_contents($container_path, $dumper->dump());
    chmod($container_path, 0755);
  }
}
