<?php

namespace Drupal\animal_form\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class AnimalForm extends FormBase {

  public function getFormId()
  {
    return 'animal_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $node = Drupal::routeMatch()->getParameter('node');

    if(!(is_null($node))){
      $node_title = $node->label();
    } else {
      $node_title = null;
    }

    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email Address'),
      '#size' => 25,
      '#description' => $this->t("We will send updates to the email address you provide"),
      '#required' => TRUE,
    ];
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#size' => 50,
      '#description' => $this->t("Please add your name"),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit Interest Form'),
    ];
    $form['node_title'] = [
      '#type' => 'hidden',
      '#value' => $node_title,
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
      
      // Obtain values written in form
      $node_title = $form_state->getValue('node_title');
      $email = $form_state->getValue('email');
      $name = $form_state->getValue('name');

      $current_time = \Drupal::time()->getCurrentTime();

      // Phase 2: Begin dynamic database query
      $query = Drupal::database()->insert('animal_form');

      $query->fields([
        'node_title',
        'name',
        'mail',
        'created',
      ]);

      $query->values([
        $node_title,
        $name,
        $email,
        $current_time,
      ]);

      $query->execute();
      // End Phase 2

      // Phase 3

      Drupal::messenger()->addMessage($this->t('Thank you for your interest in @pet, we will contact you shortly to set up a playdate!', array('@pet' => $node_title)));

    } catch (\Exception $e) {
      // Add message to handle error
      Drupal::messenger()->addError($this->t('Unable to add message to the database currently. Try again later!'));
    }
  }


}