<?php

namespace Drupal\content_entity_example;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for the contact entity.
 */
class ContactAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   *
   * Link the activities to the permissions. checkAccess() is called with the
   * $operation as defined in the routing.yml file.
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    // Check the admin_permission as defined in your #[ContentEntityType]
    // attribute.
    $admin_permission = $this->entityType->getAdminPermission();
    if ($account->hasPermission($admin_permission)) {
      // Authozize all operations if the user has the admin permission.
      return AccessResult::allowed();
    }
    switch ($operation) {
      // Authorize 'view' if the user has the 'view contact entity' permission.
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view contact entity');
      // Authorize 'update' if the user has the 'edit contact entity' permission.
      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit contact entity');
      // Authorize 'delete' if the user has the 'delete contact entity' permission.
      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete contact entity');
    }
     
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   *
   * Separate from the checkAccess because the entity does not yet exist. It
   * will be created during the 'add' process.
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    // Check the admin_permission as defined in your #[ContentEntityType]
    // attribute.
    $admin_permission = $this->entityType->getAdminPermission();
    if ($account->hasPermission($admin_permission)) {
      return AccessResult::allowed();
    }
    return AccessResult::allowedIfHasPermission($account, 'add contact entity');
  }

}
