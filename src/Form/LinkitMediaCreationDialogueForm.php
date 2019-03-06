<?php

namespace Drupal\linkit_media_creation\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * LinkitMediaCreationDialogueForm.
 */
class LinkitMediaCreationDialogueForm extends FormBase {

  /**
   * Id of form to return to.
   *
   * @var string
   */
  protected $inputId;

  /**
   * Bundles to render as form options.
   *
   * @var array
   */
  protected $bundles;

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    $this->inputId = '123';
    $this->bundles = ['image'];
  }

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
    $form['bundles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Select media type to create.'),
      '#options' => $this->bundles,
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
    $form_state->setRedirect('linkit_media_creation.dialogue', [], ['query' => ['inputId' => $this->inputId, 'bundles' => $bundle]]);
  }

}
