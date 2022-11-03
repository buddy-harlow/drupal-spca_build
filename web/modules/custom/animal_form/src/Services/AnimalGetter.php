<?php

namespace Drupal\animal_form\Services;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

class AnimalGetter
{
  use StringTranslationTrait;
  
  /**
   * Gets and returns all Animal form submissions for all nodes.
   * There are returned as an associative array, with each row
   * containing the username, the node title, and email of RSVP.
   *
   * @return array|null
   */
  public function getAnimalEntries()
  {
      try {
        $database = \Drupal::database();
        $select_query = $database->select('animal_form');
  
        $select_query->addField('animal_form', 'name');
        $select_query->addField('animal_form', 'mail');
        $select_query->addField('animal_form', 'node_title');
      
        $entries = $select_query->execute()->fetchAll(\PDO::FETCH_ASSOC);
  
        return $entries;
  
      } catch (\Exception $e){
        \Drupal::messenger()->addError($this->t(
          'Things are not looking good :('
        ));
        return null;
      }
  }
}
