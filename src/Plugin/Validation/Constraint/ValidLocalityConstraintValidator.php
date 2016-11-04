<?php
/**
 * @file
 * Contains \Drupal\entity_validation\Plugin\Validation\Constraint\EvenNumberConstraintValidator.
 */
namespace Drupal\markaspot_validation\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


/**
 * Validates the Locality constraint.
 */
class ValidLocalityConstraintValidator extends ConstraintValidator {
  /**
   * {@inheritdoc}
   */
  public function validate($field, Constraint $constraint) {
    $value = $field->locality;

    if (!isset($value)) {
      return NULL;
    }
    // Verify that given value is even.
    if ($value != 'New York') {
      $this->context->addViolation($constraint->noEvenNumberMessage);
    }
  }
}