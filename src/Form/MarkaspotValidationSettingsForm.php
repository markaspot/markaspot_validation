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
      '#default_value' => implode("\n", $locality),
      '#description' => t('Other localities wont be accespted. Put each locality on a separate line'),
    );


    return parent::buildForm($form, $form_state);
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $form_state->set('locality', []);
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

    // $values = $form_state->getValues();

    $this->config('markaspot_validation.settings')
      ->set('wkt', $form_state->get('wkt'))
      ->set('locality', $form_state->get('locality'))
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
