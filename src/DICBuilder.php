<?php
/**
 * @file
 * Contains Drupal\dic\DICBuilder.
 */

namespace Drupal\dic;

use Symfony\Component\Config\ConfigCache;
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
  private $servicePaths;

  /**
   * @var string
   *   The path where container will be located.
   */
  private $containerPath;

  /**
   * @var bool
   */
  private $debug = FALSE;

  /**
   * Initialize the DICBuilder and its dependencies.
   *
   * @param string $servicePaths
   *   Paths where service.yml files can be found.
   * @param string $containerPath
   *   The location where the container file will be cached.
   */
  public function __construct($servicePaths, $containerPath) {
    $this->servicePaths = $servicePaths;
    $this->containerPath = $containerPath;
  }

  /**
   * Builds the container.
   *
   * @return ContainerInterface
   *   The fully built and compiled container.
   */
  public function build() {
    $cache = new ConfigCache($this->containerPath, $this->debug);
    $class = 'ProjectServiceContainer';

    if (!$cache->isFresh()) {
      $container = $this->createContainer();
      $this->compileContainer($container);
      $this->cacheContainer($cache, $container, $class);

      return $container;
    }

    require_once $cache;

    $container = new $class();

    return $container;
  }

  /**
   * Create a new container from all service definition files.
   *
   * @return ContainerBuilder
   *   ContainerBuilder containing all rules from the yml files.
   */
  private function createContainer() {
    $container = new ContainerBuilder();

    $loader = new YamlFileLoader($container, new FileLocator());
    foreach ($this->servicePaths as $path) {
      $loader->load($path);
    }

    return $container;
  }

  /**
   * Register all compiler passes and compile the container.
   *
   * @param ContainerBuilder $container
   *   The container we want to compile.
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
   * @param ConfigCache $cache
   *   Symfony ConfigCache instance to cache the container to file.
   * @param ContainerBuilder $container
   *   The container that needs to be cached.
   * @param string $class
   *   The class name for the cached container instance.
   */
  private function cacheContainer(ConfigCache $cache, ContainerBuilder $container, $class) {
    $dumper = new PhpDumper($container);

    $content = $dumper->dump(array('class' => $class));
    if (!$this->debug) {
      $content = static::stripComments($content);
    }
    $cache->write($content, $container->getResources());
  }

  /**
   * Removes comments from a PHP source string.
   *
   * We don't use the PHP php_strip_whitespace() function
   * as we want the content to be readable and well-formatted.
   *
   * @param string $source
   *   A PHP string.
   *
   * @return string
   *   The PHP string with the comments removed.
   */
  public static function stripComments($source) {
    if (!function_exists('token_get_all')) {
      return $source;
    }

    $raw_chunk = '';
    $output = '';
    $tokens = token_get_all($source);
    $ignore_space = FALSE;
    for (reset($tokens); FALSE !== $token = current($tokens); next($tokens)) {
      if (is_string($token)) {
        $raw_chunk .= $token;
      }
      elseif (T_START_HEREDOC === $token[0]) {
        $output .= $raw_chunk . $token[1];
        do {
          $token = next($tokens);
          $output .= $token[1];
        } while ($token[0] !== T_END_HEREDOC);
        $raw_chunk = '';
      }
      elseif (T_WHITESPACE === $token[0]) {
        if ($ignore_space) {
          $ignore_space = FALSE;

          continue;
        }

        // Replace multiple new lines with a single newline.
        $raw_chunk .= preg_replace(array('/\n{2,}/S'), "\n", $token[1]);
      }
      elseif (in_array($token[0], array(T_COMMENT, T_DOC_COMMENT))) {
        $ignore_space = TRUE;
      }
      else {
        $raw_chunk .= $token[1];

        // The PHP-open tag already has a new-line.
        if (T_OPEN_TAG === $token[0]) {
          $ignore_space = TRUE;
        }
      }
    }

    $output .= $raw_chunk;

    return $output;
  }
}
