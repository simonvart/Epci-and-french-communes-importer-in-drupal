<?php

/**
 * @file
 * Contains commune.page.inc.
 *
 * Page callback for Commune entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Commune templates.
 *
 * Default template: commune.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_commune(array &$variables) {
  // Fetch commune Entity Object.
  $commune = $variables['elements']['#commune'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
