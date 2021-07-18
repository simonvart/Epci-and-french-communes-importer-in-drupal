<?php

/**
 * @file
 * Contains epci.page.inc.
 *
 * Page callback for epci entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for epci templates.
 *
 * Default template: epci.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_epci(array &$variables) {
  // Fetch epci Entity Object.
  $epci = $variables['elements']['#epci'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
