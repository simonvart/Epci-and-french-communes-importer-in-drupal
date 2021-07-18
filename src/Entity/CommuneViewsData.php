<?php

namespace Drupal\epci\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Commune entities.
 */
class CommuneViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
