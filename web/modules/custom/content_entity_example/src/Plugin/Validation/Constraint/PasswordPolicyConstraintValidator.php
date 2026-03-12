<?php

namespace Drupal\content_entity_example\Plugin\Validation\Constraint;

use Drupal\password_policy\PasswordPolicyValidatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;

class PasswordPolicyConstraintValidator extends ConstraintValidator
  implements ContainerInjectionInterface {

  public function __construct(
    private readonly PasswordPolicyValidatorInterface $passwordPolicyValidator
  ) {}

  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('password_policy.validator')
    );
  }

  public function validate(mixed $value, Constraint $constraint): void {
    // $value est un FieldItemList — extraire le string
    $password = $value->value;

    // Champ vide = pas de validation (mot de passe non modifié)
    if (empty($password)) {
      return;
    }

    // Récupérer l'entité User depuis le champ
    $user = $value->getEntity();

    $violations = $this->passwordPolicyValidator
      ->validatePassword($password, $user);

    if (!empty($violations)) {
      foreach ($violations as $violation) {
        $this->context->addViolation($violation);
      }
    }
  }

}