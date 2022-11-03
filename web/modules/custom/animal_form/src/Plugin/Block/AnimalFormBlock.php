<?php

/**
 * @file
 * Creates a block which displays the AnimalForm contained in AnimalForm.php
 */

namespace Drupal\animal_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * @Block(
 *  id = "animal_form_block",
 *  admin_label = @Translation("The Animal Form Block")
 * )
 */
class AnimalFormBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  function build() {
    return \Drupal::formBuilder()->getForm('Drupal\animal_form\Form\AnimalForm');
  }

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account) {
    $node = \Drupal::routeMatch()->getParameter('node');

    if( !(is_null($node))){
      return AccessResult::allowedIfHasPermission($account, 'view rsvplist');
    }

    return AccessResult::forbidden();
  }
}