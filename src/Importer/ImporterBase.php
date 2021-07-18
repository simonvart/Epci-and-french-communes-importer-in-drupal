<?php

namespace Drupal\epci\Importer;

use Drupal\Core\Logger\LoggerChannelTrait;
use \Drupal\Core\StringTranslation\TranslationManager;

/**
 * The importer.
 */
abstract class ImporterBase {

  use LoggerChannelTrait;

  /**
   * Hard-coded limit to one departement.
   */
  const DEPARTEMENT_LIMITER = '01';

  /**
   * The CSV file to import. To be created in concrete implementations.
   */
  const CSV_FILE = '';

  /**
   * CSV file filesystem path.
   *
   * @var string
   */
  protected string $modulePath;

  /**
   * Translation manager.
   */
  protected TranslationManager $translator;

  /**
   * ImporterBase constructor.
   */
  public function __construct() {
    $this->modulePath = \Drupal::service('file_system')->realpath(\Drupal::service('module_handler')->getModule('epci')->getPath());
    $this->translator = \Drupal::translation();
  }

  /**
   * Parse EPCI File.
   *
   * @param string $fileName
   *   The file to open.
   * @param int $limit
   *   The CSV line length.
   *
   * @return array
   *   The CSV line.
   */
  protected function parseFile(string $fileName, int $limit = 1200): \Generator {
    try {
      if (empty($handle = fopen($this->modulePath . $fileName, "r"))) {
        throw new \Exception("Cannot open file.");
      }

      while ($line = fgetcsv($handle, $limit, ",")) {
        yield $line;
      }

      fclose($handle);
    }
    catch (\Exception $exception) {
      $this->getLogger('epci')->error($exception->getMessage());
    }
  }

  /**
   * Get all the CSV file lines one by one.
   *
   * Filters line outside of department scope.
   *
   * @return Generator
   *   Yields each CSV line.
   */
  public function getCsvContent(): \Generator {
    foreach ($this->parseFile(static::CSV_FILE) as $line) {
      if (!$this->isLineInDepartement($line)) {
        continue;
      }

      yield $line;
    }
  }

  /**
   * {@inheritDoc}
   */
  abstract public function isLineInDepartement(array $line): bool;
}
