<?php

namespace Drupal\linkit_media_creation\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;

/**
 * LinkitMediaCreationDialogueForm.
 */
class LinkitMediaCreationDialogueForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'linkit_media_creation_dialogue_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $current_uri = \Drupal::requestStack()->getCurrentRequest()->getRequestUri();
    $allowedBundles = explode(',', UrlHelper::parse($current_uri)['query']['bundles']);
    $allowedBundles = array_map('trim', $allowedBundles);
    $allowedBundles = array_flip($allowedBundles);
    $allBundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo('media');
    $options = [];
    foreach (array_intersect_key($allBundles, $allowedBundles) as $bundle => $info) {
      $options[$bundle] = $info['label'];
    }
    $form['bundles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Select media type to create.'),
      '#options' => $options,
      '#id' => 'linkit-media-creation-dialogue-options',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Submit',
      '#id' => 'linkit-media-creation-dialogue-submit',
    ];
    $form['#attached']['library'][] = 'linkit_media_creation/dialogueForm';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $bundle = array_filter($form_state->getValue('bundles'));
    $bundle = array_keys($bundle)['0'];
    $form_state->setRedirect('linkit_media_creation.dialogue', [], ['query' => ['bundles' => $bundle]]);
  }

}
