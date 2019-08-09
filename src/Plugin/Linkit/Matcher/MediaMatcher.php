<?php

namespace Drupal\linkit_media_creation\Plugin\Linkit\Matcher;

use Drupal\Core\Form\FormStateInterface;
use Drupal\linkit\Plugin\Linkit\Matcher\EntityMatcher;

/**
 * Provides specific linkit matchers for the node entity type.
 *
 * @Matcher(
 *   id = "entity:media",
 *   label = @Translation("Media"),
 *   target_entity = "media",
 *   provider = "media"
 * )
 */
class MediaMatcher extends EntityMatcher {

  /**
   * {@inheritdoc}
   */
  public function getSummary() {
    $summary = parent::getSummary();

    $summary[] = $this->t('Add media creation link: @media_creation_link', [
      '@media_creation_link' => $this->configuration['media_creation_link'] ? $this->t('Yes') : $this->t('No'),
    ]);

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'media_creation_link' => FALSE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    return parent::calculateDependencies() + [
      'module' => ['media'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['media_creation_link'] = [
      '#title' => $this->t('Add link to launch media creation form.'),
      '#type' => 'checkbox',
      '#default_value' => $this->configuration['media_creation_link'],
      '#description' => t('Allow referencing of newly created media via a modal.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    $this->configuration['media_creation_link'] = $form_state->getValue('media_creation_link');
  }

}
