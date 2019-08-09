<?php

namespace Drupal\linkit_media_creation\Ajax;

use Drupal\Core\Ajax\CommandInterface;

/**
 * Returns value to original linkit form.
 */
class ReturnToLinkitCommand implements CommandInterface {

  /**
   * Value to return to linkit form.
   *
   * @var string
   */
  protected $returnValue;

  /**
   * Linkit substitution handler.
   *
   * @var string
   */
  protected $entitySubstitution;

  /**
   * Entity type.
   *
   * @var string
   */
  protected $entityType;

  /**
   * Entity UUID.
   *
   * @var string
   */
  protected $entityUUID;

  /**
   * {@inheritdoc}
   */
  public function __construct($returnValue, $entitySubstitution, $entityType, $entityUUID) {
    $this->returnValue = $returnValue;
    $this->entitySubstitution = $entitySubstitution;
    $this->entityType = $entityType;
    $this->entityUUID = $entityUUID;
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    return [
      'command' => 'returnToLinkit',
      'returnValue' => $this->returnValue,
      'entitySubstitution' => $this->entitySubstitution,
      'entityType' => $this->entityType,
      'entityUUID' => $this->entityUUID,
    ];
  }

}
