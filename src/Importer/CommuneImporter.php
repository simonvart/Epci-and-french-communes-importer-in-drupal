<?php

namespace Drupal\epci\Importer;

use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Import EPCI and communes.
 *
 * This importer uses two different files:
 *   One that contains all communes, without EPCI informations,
 *   The other contains a list of EPCI related communes.
 *
 * We import the first file, generating all comunes, then update
 * them with the second files.
 */
class CommuneImporter extends ImporterBase implements ImporterInterface {

  /**
   * The entity name this importer is for.
   */
  const ENTITY_NAME = 'commune';

  const EPCI_ENTITY_NAME = 'epci';

  /**
   * All communes.
   */
  const CSV_FILE = '/files/laposte_hexasmal.csv';

  /**
   * Map entity fields to EPCI importer commune index columns.
   */
  const CSV_MAPPING_COMMUNE_FROM_EPCI_FILE = [
    'department' => 0,
    'insee' => 9,
    'name' => 11,
    'siren' => 10,
  ];

  /**
   * Map entity fields to CSV_CP_FILE index columns.
   */
  const CSV_MAPPING_COMMUNE_FROM_ALL_CP = [
    'insee' => 0,
    'code_postal' => 2,
    'name' => 1,
  ];

  /**
   * The Entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  private EntityStorageInterface $storage;

  /**
   * The Entity EPCI storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  private EntityStorageInterface $epciStorage;

  /**
   * The EPCI importer.
   *
   * @var \Drupal\epci\Importer\EpciImporter
   */
  private EpciImporter $epciImporter;

  /**
   * Class constructor.
   */
  public function __construct() {
    parent::__construct();
    $this->storage = \Drupal::entityTypeManager()->getStorage(self::ENTITY_NAME);
    $this->epciStorage = \Drupal::entityTypeManager()->getStorage(self::EPCI_ENTITY_NAME);
    $this->epciImporter = new EpciImporter();
  }

  /**
   * Depending on the file $data origin, create or update Commune.
   *
   * @param array $data
   *   The CSV line data.
   * @param array $context
   *   The batch context.
   */
  public function processLine(array $data, array &$context): void {
    switch ($data['file']) {
      case 'import':
        $this->importCommune($data, $context);
        return;

      case 'update':
        $this->updateCommune($data, $context);
        return;

      default:
        BatchImporter::addToResultsByKey('error', $context);
    }
  }

  /**
   * Create a new commune from the commune list, without EPCI.
   *
   * @param array $data
   *   The CSV line data.
   * @param array $context
   *   The batch context.
   */
  private function importCommune(array $data, array &$context): void {
    try {
      foreach (self::CSV_MAPPING_COMMUNE_FROM_ALL_CP as $key => $value) {
        $dataEntity[$key] = $data[$value];
      }

      $dataEntity['department'] = self::DEPARTEMENT_LIMITER;

      // New entry.
      if (empty($entity = $this->storage->loadByProperties(['insee' => $dataEntity['insee']]))) {
        $context['message'] = $this->translator->translate('Creating commune: @commune', ['@commune' => $dataEntity['name']]);
        /** @var \Drupal\epci\Entity\Commune $newEntity */
        $newEntity = $this->storage->create($dataEntity);

        if ($newEntity->save()) {
          BatchImporter::addToResultsByKey('created', $context);
          $this->created[$newEntity->id()] = $newEntity->getName();

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
   * Process CSV_EPCI_FILE line.
   *
   * Will update each commune with the EPCI.
   *
   * @param array $data
   *   The CSV line data.
   * @param array $context
   *   The batch context.
   */
  private function updateCommune(array $data, array &$context): void {
    try {
      foreach (epciImporter::CSV_EPCI_MAPPING as $key => $value) {
        $dataEcpi[$key] = $data[$value];
      }

      foreach (self::CSV_MAPPING_COMMUNE_FROM_EPCI_FILE as $key => $value) {
        $dataCommune[$key] = $data[$value];
      }

      /* New Commune entry. Shouldn't happen as all communes
       * should have been created after importing the first CSV.
       */
      if (empty($entity = $this->storage->loadByProperties(['insee' => $dataCommune['insee']]))) {
        $context['message'] = $this->translator->translate('Creating missing commune: @commune', ['@commune' => $dataCommune['name']]);
        BatchImporter::addToResultsByKey('error', $context);
        /** @var \Drupal\epci\Entity\Commune $entity */
        $entity = $this->storage->create($dataCommune);
        $this->getLogger('epci')->notice(sprintf('Commune insee: %s was created when it should have been updated', $dataCommune['insee']));
      }
      else {
        $entity = array_shift($entity);
      }

      // Set EPCI if empty.
      /** @var \Drupal\epci\Entity\Commune $entity */
      if (empty($entity->getEpciId())) {
        if (empty($epci = $this->epciStorage->loadByProperties(['siren' => $dataEcpi['siren']]))) {
          // Non-existing ECPI. Shouldn't happen.
          $context['message'] = $this->translator->translate('Creating missing epci: @epci', ['@epci' => $dataEcpi['name']]);
          BatchImporter::addToResultsByKey('error', $context);
          $epci = $this->epciStorage->create($dataEcpi);
          $epci->save();
          $this->getLogger('epci')->notice(sprintf('EPCI Siren: %s was created while it should have existed', $dataEcpi['siren']));
        }
        else {
          $epci = array_shift($epci);
        }

        /** @var \Drupal\epci\Entity\Epci $epci */
        $entity->setEpciId($epci->id())->save();
        $context['message'] = $this->translator->translate(
          'Updated commune: @commune with epci @epci',
          [
            '@epci' => $dataEcpi['name'],
            '@commune' => $dataCommune['name'],
          ]
        );
        BatchImporter::addToResultsByKey('updated', $context);
        return;
      }
    }
    catch (\Exception $exception) {
      $this->getLogger('epci')->error($exception->getMessage());
    }
  }

  /**
   * Get all lines of both importer CSV files.
   *
   * @return Generator
   *   Yields each CSV line.
   */
  public function getCsvContent(): \Generator {
    foreach (parent::getCsvContent() as $line) {
      if (!$this->isLineInDepartement($line)) {
        continue;
      }

      $line['file'] = 'import';
      yield $line;
    }
    foreach ($this->epciImporter->getCsvContent() as $line) {
      $line['file'] = 'update';
      yield $line;
    }
  }

  /**
   * {@inheritDoc}
   */
  public function isLineInDepartement(array $line): bool {
    if (str_starts_with($line[self::CSV_MAPPING_COMMUNE_FROM_ALL_CP['code_postal']], self::DEPARTEMENT_LIMITER)) {
      return TRUE;
    }
    return FALSE;
  }

}
