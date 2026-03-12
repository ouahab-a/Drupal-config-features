<?php
// src/Plugin/Validation/Constraint/PasswordPolicyConstraint.php

namespace Drupal\content_entity_example\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Constraint(
 *   id = "PasswordPolicyConstraint",
 *   label = @Translation("Password Policy Constraint"),
 *   type = "string"
 * )
 */
class PasswordPolicyConstraint extends Constraint {
  public string $message = 'The password does not meet the policy requirements.';
}
