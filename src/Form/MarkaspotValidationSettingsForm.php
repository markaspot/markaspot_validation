<?php

namespace Drupal\markaspot_validation\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure georeport settings for this site.
 */
class MarkaspotValidationSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'markaspot_validation_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('markaspot_validation.settings');
    $form['markaspot_validation'] = array(
      '#type' => 'fieldset',
      '#title' => t('Validation Types'),
      '#collapsible' => TRUE,
      '#description' => t('This setting allow you too choose a map tile operator of your choose. Be aware that you have to apply the same for the Geolocation Field settings</a>, too.'),
      '#group' => 'settings',
    );



    $form['markaspot_validation']['wkt'] = array(
      '#type' => 'textarea',
      '#title' => t('Polygon in WKT Format'),
      '#default_value' => $config->get('wkt'),
      '#description' => t('Place your polygon wkt here.'),
    );

    $locality = $config->get('locality');
    $form['markaspot_validation']['locality'] = array(
      '#type' => 'textarea',
      '#title' => t('Localities that adresses will be validated with.'),
      '#rows' => 4,
      '#default_value' => $locality,
      '#description' => t('Other localities wont be accepted. Put each locality on a separate line'),
    );


    $form['markaspot_validation']['radius'] = array(
      '#type' => 'textfield',
      '#title' => t('Duplicate Request check Radius'),
      '#default_value' => $config->get('radius'),
      '#description' => t('Validate if new requests are possible duplicates within this radius.'),
    );

    $form['markaspot_validation']['unit'] = array(
      '#type' => 'radios',
      '#title' => t('Duplicate Radius Unit'),
      '#default_value' => $config->get('unit'),
      '#options' => array(
        'meters' => t('Meters'),
        'yards' => t('Yards'),
      ),
      '#description' => t('Validate if new requests are possible duplicates within this radius.'),
    );

    $form['markaspot_validation']['days'] = array(
      '#type' => 'number',
      '#min' => 1,
      '#max' => 1000,
      '#step' => 1,
      '#title' => t('Duplicate reach back in days'),
      '#default_value' => $config->get('days'),
      '#description' => t('How many days to reach back for similar requests'),
    );


    return parent::buildForm($form, $form_state);
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    #$form_state->set('locality', []);
    if (!$form_state->isValueEmpty('locality')) {
      $valid = array();
      foreach (explode("\n", trim($form_state->getValue('locality'))) as $locality) {
        $locality = trim($locality);
        if (!empty($locality)) {
          $valid[] = $locality;
        }
      }


    }
    $wkt = trim($form_state->getValue('wkt'));

    if (!empty($valid)) {
      $form_state->set('locality', $valid);
      $form_state->set('wkt', $wkt);

    }

    parent::validateForm($form, $form_state);
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('markaspot_validation.settings')
      ->set('wkt', $values['wkt'])
      ->set('locality', $values['locality'])
      ->set('radius', $values['radius'])
      ->set('unit', $values['unit'])
      ->set('days', $values['days'])
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'markaspot_validation.settings',
    ];
  }

}
