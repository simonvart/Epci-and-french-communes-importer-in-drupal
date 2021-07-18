<?php

namespace Drupal\epci\Importer;

use Drupal\Core\Logger\LoggerChannelTrait;

/**
 * Import EPCI and communes.
 */
class EpciImporter extends ImporterBase implements ImporterInterface {

  use LoggerChannelTrait;

  /**
   * The entity name this importer is for.
   */
  const ENTITY_NAME = 'epci';

  /**
   * The CSV file to import.
   */
  const CSV_FILE = '/files/epcicom2020.csv';

  /**
   * Map entity fields to CSV index columns.
   */
  const CSV_EPCI_MAPPING = [
    'department' => 0,
    'siren' => 1,
    'name' => 2,
  ];

  /**
   * The Entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  private $storage;

  /**
   * Class constructor.
   */
  public function __construct() {
    parent::__construct();
    $this->storage = \Drupal::entityTypeManager()->getStorage(self::ENTITY_NAME);
  }

  /**
   * {@inheritdoc}
   */
  public function processLine(array $data, array &$context): void {
    try {
      if (!$this->isLineInDepartement($data)) {
        return;
      }

      foreach (self::CSV_EPCI_MAPPING as $key => $value) {
        $dataEntity[$key] = $data[$value];
      }

      // New entry.
      if (empty($entity = $this->storage->loadByProperties(['siren' => $dataEntity['siren']]))) {
        $context['message'] = $this->translator->translate('Creating epci: @epci', ['@epci' => $dataEntity['name']]);
        /** @var \Drupal\epci\Entity\Epci $newEntity */
        $entity = $this->storage->create($dataEntity);

        if ($entity->save()) {
          BatchImporter::addToResultsByKey('created', $context);
          return;
        }
      }

      // Already imported. Just report.
      BatchImporter::addToResultsByKey('conflict', $context);
    }
    catch (\Exception $exception) {
      BatchImporter::addToResultsByKey('error', $context);
      $this->getLogger('epci')->error($exception->getMessage());
    }
  }

  /**
   * {@inheritDoc}
   */
  public function isLineInDepartement(array $line): bool {
    if ($line[self::CSV_EPCI_MAPPING['department']] === self::DEPARTEMENT_LIMITER) {
      return TRUE;
    }

    return FALSE;
  }

}
