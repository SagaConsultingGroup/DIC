<?php
/**
 * @file
 * Contains Drupal\dic\Tests\ContainerBuilderTest.
 */

namespace Drupal\dic\Tests;

use Drupal\dic\DICBuilder;

class ContainerBuilderTest extends \PHPUnit_Framework_TestCase {

  /**
   * @covers Drupal\dic\DICBuilder::build
   */
  public function testContainerBuild() {
    $builder = new DICBuilder('.', './tests/Resources/.container/container.php');
    $container = $builder->build();
    $this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerInterface', $container);
  }
}
