<?php

namespace Drupal\epci\Importer;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

/**
 * Static class responsible for entity importation (epci, commune).
 */
class BatchImporter {

  const CHUNK_SIZE = 80;

  /**
   * Create a batch process for the provided entity.
   *
   * Divide all CSV line into array of CHUNK_SIZE items,
   * batch it and start process.
   *
   * if wanted to use drush, we'll need to use drush_backend_batch_process().
   *
   * @param string $entityTypeName
   *   The entitytype name to import.
   */
  public static function createBatch(string $entityTypeName) {
    $importerFactory = new ImporterFactory();
    $importer = $importerFactory->getImporter($entityTypeName);
    $translator = \Drupal::translation();

    $batch = [
      'title' => $translator->translate('Import @entityName', ['@entityName' => $importer::ENTITY_NAME]),
      'operations' => [],
      'finished' => [self::class, 'displayResult'],
      'progress_message' => $translator->translate('Processed @current out of @total.'),
      'init_message' => $translator->translate('Importing entity: @entityName', ['@entityName' => $importer::ENTITY_NAME]),
    ];

    foreach ($importer->getCsvContent() as $csvLine) {
      $csvLines[] = $csvLine;
    }

    foreach (array_chunk($csvLines, self::CHUNK_SIZE) as $chunk) {
      $batch['operations'][] = [
        [self::class, 'importBatchChunk'],
        [$chunk, get_class($importer), count($csvLines)],
      ];
    }

    batch_set($batch);
    return batch_process();
  }

  /**
   * Receive a chunck from batch process and import each CSV line from the cunk.
   *
   * @param array $chunk
   *   A chunk of CHUNK_SIZE CSV lines.
   * @param string $importerClassName
   *   The importer class name.
   * @param int $totalCsvLines
   *   The total lines to import.
   * @param array $context
   *   The batch context.
   */
  public static function importBatchChunk(array $chunk, string $importerClassName, int $totalCsvLines, array &$context) {
    $importer = new $importerClassName();

    if (!isset($context['results']['total'])) {
      $context['results']['total'] = $totalCsvLines;
    }

    if (!isset($context['results']['entityName'])) {
      $context['results']['entityName'] = $importer::ENTITY_NAME;
    }

    foreach ($chunk as $line) {
      $importer->processLine($line, $context);
    }
  }

  /**
   * Redirects to the entity collection page with a result batch summary.
   *
   * Batch ending callback.
   *
   * @param bool $success
   *   The batch status.
   * @param array $results
   *   The batch results.
   * @param array $operations
   *   The remaining operations.
   */
  public static function displayResult(bool $success, array $results, array $operations) {
    $messager = \Drupal::messenger();
    $redirectRoute = 'entity.' . $results['entityName'] . '.collection';
    $translator = \Drupal::translation();

    if (!$success || !empty($results['error'])) {
      $messager->addWarning($translator->translate('An error occured. Please check the logs'));
    }

    $messager->addMessage($translator->translate(
      'Total file lines for @entityTypeName: @total',
      [
        '@entityTypeName' => $results['entityName'],
        '@total' => $results['total'] ?? 0,
      ]
    ));
    $messager->addMessage($translator->translate(
      'Total @entityTypeName created: @created',
      [
        '@entityTypeName' => $results['entityName'],
        '@created' => $results['created'] ?? 0,
      ]
    ));
    $messager->addMessage($translator->translate(
      'Total @entityTypeName conflicted (already exists): @conflict',
      [
        '@entityTypeName' => $results['entityName'],
        '@conflict' => $results['conflict'] ?? 0,
      ]
    ));
    if (!empty($results['updated'])) {
      $messager->addMessage($translator->translate(
        'Total @entityTypeName updated with EPCI: @updated',
        [
          '@entityTypeName' => $results['entityName'],
          '@updated' => $results['updated'] ?? 0,
        ]
      ));
    }

    return new RedirectResponse(Url::fromRoute($redirectRoute)->toString());
  }

  /**
   * Add count to a context specific key.
   *
   * @param string $key
   *   The key to add to.
   * @param array $context
   *   The batch context.
   */
  public static function addToResultsByKey(string $key, array &$context): void {
    isset($context['results'][$key]) ? $context['results'][$key]++ : $context['results'][$key] = 1;
  }

}
