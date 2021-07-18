<?php

namespace Drupal\epci\Importer;

/**
 * Importer Interface.
 */
interface ImporterInterface {

  /**
   * Read importer CSV file and yield each line.
   *
   * @return \Generator
   *   A iterable generator.
   */
  public function getCsvContent(): \Generator;

  /**
   * Process each CSV line.
   *
   * @param array $data
   *   The CSV line as data to process.
   * @param array $context
   *   Batch API context.
   */
  public function processLine(array $data, array &$context): void;

  /**
   * Check if the current CSV line matches the desired departement.
   *
   * @param array $line
   *   The CSV line.
   *
   * @return bool
   *   Whether the lCSV ine is in the department.
   */
  public function isLineInDepartement(array $line): bool;

}
