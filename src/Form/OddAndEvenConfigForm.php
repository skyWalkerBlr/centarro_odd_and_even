<?php

namespace Drupal\centarro_odd_and_even\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class OddAndEvenConfigForm.
 */
class OddAndEvenConfigForm extends ConfigFormBase {

  /**
   * Config settings name.
   *
   * @var string
   */
  const SETTINGS = 'centarro_odd_and_even.adminsettings';

  /**
   * The current user.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidator
   */
  protected $cacheTagsInvalidator;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->cacheTagsInvalidator = $container->get('cache_tags.invalidator');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'centarro_odd_and_even_admin_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['node_for_odd'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Node ID "Odd"'),
      '#description' => $this->t('Node ID which is used for "Odd" minute value'),
      '#maxlength' => 32,
      '#size' => 32,
      '#default_value' => $config->get('node_for_odd'),
    ];

    $form['node_for_even'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Node ID "Even"'),
      '#description' => $this->t('Node ID which is used for "Even" minute value'),
      '#maxlength' => 32,
      '#size' => 32,
      '#default_value' => $config->get('node_for_even'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config(static::SETTINGS)
      ->set('node_for_even', $form_state->getValue('node_for_even'))
      ->set('node_for_odd', $form_state->getValue('node_for_odd'))
      ->save();

    // Also do not forget to flush custom cache tag
    $this->cacheTagsInvalidator->invalidateTags(['odd_and_even_cache_tag']);
  }

}
