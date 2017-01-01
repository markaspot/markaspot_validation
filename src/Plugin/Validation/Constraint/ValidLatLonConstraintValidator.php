<?php
/**
 * @file
 * Contains \Drupal\entity_validation\Plugin\Validation\Constraint\EvenNumberConstraintValidator.
 */
namespace Drupal\markaspot_validation\Plugin\Validation\Constraint;

use Polygon\Polygon;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use geoPHP;

/**
 * Validates the LatLon constraint.
 *
 * todo: Make this possible for polygons
 * with something like geoPHP ot this: http://assemblysys.com/php-point-in-polygon-algorithm/
 * 1. Get Place in Nomintim, check details, get relation id
 * 2. via https://www.openstreetmap.org/relation/175905
 * 3. http://polygons.openstreetmap.fr/index.py?id=175905
 *
 */
class ValidLatLonConstraintValidator extends ConstraintValidator {
  /**
   * {@inheritdoc}
   */
  public function validate($field, Constraint $constraint) {

    // Validate latlng
    if ( self::polygonCheck(floatval($field->lng),floatval($field->lat))){
      $validLatLng = true;
    }
    If (!isset($validLatLng)){
      $this->context->addViolation($constraint->noValidViewboxMessage);
    };


    // Leaf this here until release.
    //oad config from geolocation field settings.
    // $config = \Drupal::config('core.entity_form_display.node.service_request.default');
    // Get the limit_viewbox setting.
    // $configlimitViewbox = $config->get('content.field_geolocation.settings.limit_viewbox');
    // viewbox=<left>,<top>,<right>,<bottom>
    // $limitViewbox = explode(',', $configlimitViewbox);


    // var_dump($validLatLng);

    /*
    if (self::latCheckRange(floatval($field->lat), floatval($limitViewbox[1]), floatval($limitViewbox[3]))) {
      $validLat = true;
    }

    if (self::lngCheckRange(floatval($field->lng), floatval($limitViewbox[0]), floatval($limitViewbox[2]))) {
      $validLng= true;
    }

    If (!isset($validLat) || !(isset($validLng))){
      $this->context->addViolation($constraint->noValidViewboxMessage);
    };
    */
  }

  static function polygonCheck($lng, $lat){
    // Looking for a valid WKT polygon:
    $config = \Drupal::configFactory()
      ->getEditable('markaspot_validation.settings');

    $wkt = $config->get('wkt');

    // Transform wkt to json
    $geom = geoPHP::load($wkt,'wkt');
    $json =  $geom->out('json');
    $data = json_decode($json);
    //
    $polygon = new Polygon($data->coordinates[0]);
    return $polygon->contain($lng, $lat); // return bool(true)

  }

  /*
  static function latCheckRange($lat, $viewboxNorth, $viewboxSouth) {
    return ($viewboxNorth > $lat && $lat > $viewboxSouth);
  }
  static function lngCheckRange($lng, $viewboxWest, $viewboxEast) {
    return ($viewboxWest < $lng && $lng < $viewboxEast);
  }

  */
}