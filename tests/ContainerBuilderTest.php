<?php
/**
 * @file
 * Contains Drupal\dic\Tests\ContainerBuilderTest.
 */

namespace Drupal\dic\Tests;

use Drupal\dic\DICBuilder;

class ContainerBuilderTest extends \PHPUnit_Framework_TestCase {

  private function clearContainerCache() {
    if (is_file($this->getContainerPath())) {
      unlink($this->getContainerPath());
    }
  }

  private function getContainerPath() {
    return __DIR__ . '/Resources/.container/container.php';
  }

  /**
   * @covers Drupal\dic\DICBuilder::build
   */
  public function testContainerBuild() {
    $this->clearContainerCache();

    $builder = new DICBuilder(array(), $this->getContainerPath());
    $container = $builder->build();
    $this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerInterface', $container);
  }

  /**
   * @covers Drupal\dic\DICBuilder::cacheContainer
   */
  public function testContainerBuildCache() {
    $this->clearContainerCache();

    $builder = new DICBuilder(array(), $this->getContainerPath());
    $container = $builder->build();

    $this->assertFileExists($this->getContainerPath());

    $reflection = new \ReflectionClass($container);
    $this->assertEquals($this->getContainerPath(), $reflection->getFileName());
  }
}
