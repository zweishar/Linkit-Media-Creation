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
    $summery = parent::getSummary();

    $summery[] = $this->t('Add media creation link: @media_creation_link', [
      '@media_creation_link' => $this->configuration['media_creation_link'] ? $this->t('Yes') : $this->t('No'),
    ]);

    return $summery;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'media_creation_link' => FALSE,
      'media_creation_link_bundles' => [],
    ] + parent::defaultConfiguration();
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
      '#type' => 'details',
      '#title' => $this->t('Media creation'),
      '#open' => TRUE,
    ];

    $form['media_creation_link']['media_creation_link'] = [
      '#title' => $this->t('Add link to launch media creation form.'),
      '#type' => 'checkbox',
      '#default_value' => $this->configuration['media_creation_link'],
      '#description' => $this->t('Allow referencing of newly created media via a modal.'),
    ];

    $bundle_options = [];
    foreach ($this->entityTypeBundleInfo->getBundleInfo($this->targetType) as $bundle_name => $bundle_info) {
      $bundle_options[$bundle_name] = $bundle_info['label'];
    }

    $form['media_creation_link']['media_creation_link_bundles'] = [
      '#title' => $this->t('Restrict media types that can be created via the modal to the selected bundles.'),
      '#type' => 'checkboxes',
      '#options' => $bundle_options,
      '#default_value' => $this->configuration['media_creation_link_bundles'],
      '#description' => $this->t('If none of the checkboxes is checked, all bundles are allowed.'),
      '#states' => [
        'visible' => [
          ':input[name="media_creation_link"]' => ['checked' => TRUE],
        ],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);

    $this->configuration['media_creation_link'] = $form_state->getValue('media_creation_link');
    $this->configuration['media_creation_link_bundles'] = $form_state->getValue('media_creation_link_bundles');
  }

}
