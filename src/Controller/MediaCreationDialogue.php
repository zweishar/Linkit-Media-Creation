<?php

namespace Drupal\linkit_media_creation\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Form\FormBuilder;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\linkit_media_creation\Form\LinkitMediaCreationDialogueForm;
use Symfony\Component\HttpFoundation\Response;

/**
 * Create media dialogue.
 */
class MediaCreationDialogue extends ControllerBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilder
   */
  protected $formBuilder;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * The entity type bundle info.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('form_builder'),
      $container->get('request_stack'),
      $container->get('entity_type.bundle.info')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManager $entityTypeManager, FormBuilder $formBuilder, RequestStack $requestStack, EntityTypeBundleInfoInterface $entityTypeBundleInfo) {
    $this->entityTypeManager = $entityTypeManager;
    $this->formBuilder = $formBuilder;
    $this->requestStack = $requestStack;
    $this->entityTypeBundleInfo = $entityTypeBundleInfo;
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $request = $this->requestStack->getCurrentRequest();
    $inputId = $request->query->get('inputId');
    $bundles = $this->getAllowedBundles($request->query->get('bundles'));

    if (count($bundles) === 1) {
      $form = $this->buildMediaForm(array_keys($bundles)['0']);
    }
    else {
      $form = new LinkitMediaCreationDialogueForm($inputId, $bundles);
      $form = $this->formBuilder->getForm($form);
    }

    // $form['#theme'] = ['linkit_media_creation'];
    // $form['#form'] = $form;
    return $form;
  }

  /**
   * Gets all bundle info.
   *
   * @param mixed $allowedBundles
   *   Allowed bundles.
   *
   * @return array
   *   Bundle info.
   */
  protected function getAllowedBundles($allowedBundles = []) {
    $bundles = $this->getAllBundleInfo();

    if (!empty($allowedBundles)) {
      // Filter list to allowed bundles.
      $allowedBundles = explode(', ', $allowedBundles);
      $allowedBundles = array_flip($allowedBundles);
      $bundles = array_intersect_key($bundles, $allowedBundles);
    }

    return $bundles;
  }

  /**
   * Gets bundle info for all defined media types.
   *
   * @return array
   *   Bundle info.
   */
  protected function getAllBundleInfo() {
    $allBundles = [];
    foreach ($this->entityTypeBundleInfo->getBundleInfo('media') as $bundleName => $bundleInfo) {
      $allBundles[$bundleName] = $bundleInfo['label'];
    }

    return $allBundles;
  }

  /**
   * Builds media form.
   *
   * @param string $bundle
   *   The media bundle to create.
   *
   * @return mixed
   *   The built form.
   */
  protected function buildMediaForm($bundle) {
    $media = $this->entityTypeManager->getStorage('media')->create([
      'targetEntityType' => "media",
      'bundle' => $bundle,
      'status' => TRUE,
    ]);

    $form = $this->entityTypeManager
      ->getFormObject('media', 'default')
      ->setEntity($media);

    $form = $this->formBuilder->getForm($form);
    $form['#attached']['library'][] = 'linkit_media_creation/commands';
    return $form;
  }

}
