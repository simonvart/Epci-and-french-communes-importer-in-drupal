<?php

namespace Drupal\epci;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the epci entity.
 *
 * @see \Drupal\epci\Entity\epci.
 */
class EpciAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\epci\Entity\epciInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished epci entities');
        }

        return AccessResult::allowedIfHasPermission($account, 'view published epci entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit epci entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete epci entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add epci entities');
  }

}
