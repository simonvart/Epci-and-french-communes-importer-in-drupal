<?php

namespace Drupal\epci\Importer;

/**
 * Importer Factory.
 */
class ImporterFactory {

  /**
   * Get Importer for a specific entity.
   */
  public function getImporter(string $entityName) {
    switch (TRUE) {
      case ($entityName === 'epci'):
        return new EpciImporter();

      case ($entityName === 'commune'):
        return new CommuneImporter();
    }

    throw new \Exception('Factory cannot get ' . $entityName);
  }

}
