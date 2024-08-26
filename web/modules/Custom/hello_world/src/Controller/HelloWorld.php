<?php

/**
 * @file
 * Generates markups to be displayed on the page
 */

namespace Drupal\hello_world\Controller;
 
use Drupal\Core\Controller\ControllerBase;

class HelloWorld extends ControllerBase {

  public function DisplayName() {
    $user= $this->currentUser();
    $username= $user->getAccountName();
    return [
      "#type"=> "markup",
      "#markup"=> $this->t("Hello @username",['@username' => $username]),
    ];
  }
}