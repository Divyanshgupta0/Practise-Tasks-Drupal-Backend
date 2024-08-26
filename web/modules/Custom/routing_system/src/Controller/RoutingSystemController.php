<?php

declare(strict_types=1);

namespace Drupal\routing_system\Controller;

use Drupal\Core\Controller\ControllerBase;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Returns responses for Routing system routes.
 */
final class RoutingSystemController extends ControllerBase {

  public function accessExample(AccountInterface $account) {
    if ($account->hasPermission('access routing system')) {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }

  public function campaignValue($id) {
    $message = $this->t('The value from the route url is: @id', ['@id' => $id]);
    $build = [
      '#type' => 'item',
      '#markup' => $message,
    ];
    return $build;
  }

  /**
   * Builds the response.
   */
  public function __invoke(): array {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('Welcome to landing page of Routing System'),
    ];

    return $build;
  }

}
