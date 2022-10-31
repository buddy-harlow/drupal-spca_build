<?php

namespace Drupal\animal_form\Controller;

use Drupal\animal_form\Services\GreetingGenerator;
use Drupal\Core\Controller\ControllerBase;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class AnimalFormController extends ControllerBase {
  
  public function form($count){

    $greetingGenerator = new GreetingGenerator();
    $greeting = $greetingGenerator->GetGreeting($count);

    return new Response($greeting);
  }

  public static function create(ContainerInterface $container) {
    $formGenerator = $container->get('form_builder');
    
  }

}