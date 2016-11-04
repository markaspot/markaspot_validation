<?php
/**
 * @file
 * Contains \Drupal\entity_validation\Plugin\Validation\Constraint\EvenNumberConstraint.
 */
namespace Drupal\markaspot_validation\Plugin\Validation\Constraint;
use Symfony\Component\Validator\Constraint;

/**
 * Checks that the Locality is valid.
 *
 * @Constraint(
 *   id = "ValidLocality",
 *   label = @Translation("Valid Locality", context = "Validation"),
 * )
 */
class ValidLocalityConstraint extends Constraint {

  public $noEvenNumberMessage = 'The jurisdiction is not quite what we expect from you.';
}