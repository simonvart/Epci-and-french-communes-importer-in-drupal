<?php

namespace Drupal\epci\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Commune entities.
 *
 * @ingroup epci
 */
interface CommuneInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Commune name.
   *
   * @return string
   *   Name of the Commune.
   */
  public function getName();

  /**
   * Sets the Commune name.
   *
   * @param string $name
   *   The Commune name.
   *
   * @return \Drupal\epci\Entity\communeInterface
   *   The called Commune entity.
   */
  public function setName($name);

  /**
   * Gets the Commune creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Commune.
   */
  public function getCreatedTime();

  /**
   * Get the code postal.
   *
   * @return string
   *   Code postal of the commune.
   */
  public function getCodePostal();

  /**
   * Get the EPCI Id.
   *
   * @return int
   *   EPCI Id.
   */
  public function getEpciId();

  /**
   * Set the EPCI Id.
   * 
   * @return Commune
   *   The entity.
   */
  public function setEpciId(int $epciId);

  /**
   * Sets the Commune creation timestamp.
   *
   * @param int $timestamp
   *   The Commune creation timestamp.
   *
   * @return \Drupal\epci\Entity\communeInterface
   *   The called Commune entity.
   */
  public function setCreatedTime($timestamp);

}
