<?php

namespace Drupal\markaspot_validation\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use GeoPHP\Geo;
use Polygon\Polygon;


/**
 * Validates the LatLon constraint.
 *
 */
class ValidLatLonConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($field, Constraint $constraint) {
    if ($this->polygonCheck(floatval($field->lng), floatval($field->lat))) {
      $validLatLng = TRUE;
    }
    if (!isset($validLatLng)) {
      $this->context->addViolation($constraint->noValidViewboxMessage);
    };
  }

  /**
   * Check if coordinates are within polygon.
   *
   * @param float $lng
   *   The longitude coordinate.
   * @param float $lat
   *   The latitude coordinate.
   *
   * @return bool
   *   Validates or not.
   */
  static public function polygonCheck($lng, $lat) {
    // Looking for a valid WKT polygon:
    $config = \Drupal::configFactory()->getEditable('markaspot_validation.settings');

    $wkt = $config->get('wkt');
    if ($wkt !== '') {
      // Transform wkt to json.
      $geom = Geo::load($wkt, 'wkt');
      $json = $geom->out('json');
      $data = json_decode($json);
      $validatePolygon = new \Polygon\Polygon($data->coordinates[0]);
      return $validatePolygon->contain($lng, $lat);
    }
    else {
      return TRUE;
    }
  }

}
