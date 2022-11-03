<?php

namespace Drupal\animal_form\Controller;

use Drupal\animal_form\Services\AnimalGetter;
use Drupal\animal_form\Services\ReportGenerator;
use Drupal\Core\Controller\ControllerBase;

class AnimalFormSubmissionController extends ControllerBase {
  
  /**
   * Creates the RSVPList report page.
   * 
   * @return array
   * Render array for the RSVPList report output
   */
  public function report() {
    $animalGetter = new AnimalGetter();
    $reportGenerator = new ReportGenerator();
    $table_rows = $animalGetter->getAnimalEntries();


    return $reportGenerator->buildReport($table_rows);
  }

}