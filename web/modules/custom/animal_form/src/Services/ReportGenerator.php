<?php

namespace Drupal\animal_form\Services;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

class ReportGenerator {
  use StringTranslationTrait;
  
  public function buildReport($table_rows){
    
  $content = [];

    $content['message'] = [
      '#markup' => $this->t('Below is a list of all Animal Submissions that have been made'),

    ];

    $headers = [
      $this->t('Name'),
      $this->t('Email'),
      $this->t('Animal'),
      
    ];

    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $table_rows,
      '#empty' => $this->t('No entries available'),
    ];

    $content['#cache']['max-age'] = 0;

    return $content;
  }
}