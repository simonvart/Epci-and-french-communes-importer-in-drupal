<?php

namespace Drupal\epci\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Drupal\epci\Importer\BatchImporter;

/**
 * Import controller.
 */
class ImportController extends ControllerBase {

  /**
   * Start the entity importation process.
   *
   * @param string $entityTypeName
   *   The entity type name to import.
   */
  public function importBatch(string $entityTypeName): Response {
    return BatchImporter::createBatch($entityTypeName);
  }

}
