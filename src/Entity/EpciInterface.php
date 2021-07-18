<?php

namespace Drupal\epci\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining epci entities.
 *
 * @ingroup epci
 */
interface epciInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the epci name.
   *
   * @return string
   *   Name of the epci.
   */
  public function getName();

  /**
   * Sets the epci name.
   *
   * @param string $name
   *   The epci name.
   *
   * @return \Drupal\epci\Entity\epciInterface
   *   The called epci entity.
   */
  public function setName($name);

  /**
   * Gets the epci creation timestamp.
   *
   * @return int
   *   Creation timestamp of the epci.
   */
  public function getCreatedTime();

  /**
   * Sets the epci creation timestamp.
   *
   * @param int $timestamp
   *   The epci creation timestamp.
   *
   * @return \Drupal\epci\Entity\epciInterface
   *   The called epci entity.
   */
  public function setCreatedTime($timestamp);

}
