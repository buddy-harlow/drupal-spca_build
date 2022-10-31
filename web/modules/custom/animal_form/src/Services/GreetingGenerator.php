<?php

namespace Drupal\animal_form\Services;

class GreetingGenerator
{
  public function GetGreeting($length)
  {
    return 'Hell' . str_repeat('o', $length) . ' my friend';
  }
}
