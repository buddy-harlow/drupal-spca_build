<?php

/**
 * @file
 * A form to collect an email address for RSVP details
 */

namespace Drupal\rsvplist\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class RSVPForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'rsvplist_email_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $node = Drupal::routeMatch()->getParameter('node');

    if(!(is_null($node))){
      $nid = $node->id();
    } else {
      $nid = 0;
    }

    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email Address'),
      '#size' => 25,
      '#description' => $this->t("We will send updates to the email address you provide"),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Rsvp to the event'),
    ];
    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $value = $form_state->getValue('email');
    if ( !(\Drupal::service('email.validator')->isValid($value))){
      $form_state->setErrorByName('email',
      $this->t('It appears that %mail is not a valid email address. Please try again', ['%mail' => $value]));
    };
  }
  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $submitted_email = $form_state->getValue('email');
    // $this->messenger()->addMessage(t("This form is working! You entered @entry", ['@entry' => $submitted_email]));
    try{
      // Phase 1 set data to variables
      // Get current user ID.
      $uid = \Drupal::currentUser()->id();

      // Obtain values written in form
      $nid = $form_state->getValue('nid');
      $email = $form_state->getValue('email');

      $current_time = \Drupal::time()->getCurrentTime();

      // Phase 2: Begin dynamic database query
      $query = Drupal::database()->insert('rsvplist');

      $query->fields([
        'uid',
        'nid',
        'mail',
        'created',
      ]);

      $query->values([
        $uid,
        $nid,
        $email,
        $current_time,
      ]);

      $query->execute();
      // End Phase 2

      // Phase 3

      Drupal::messenger()->addMessage($this->t('Thank you for your RSVP, you are on the list for the event'));

    } catch (\Exception $e) {
      // Add message to handle error
      Drupal::messenger()->addError($this->t('Unable to add message to the database currently. Try again later!'));
    }
  }
}
