<?php

/**
 * Summary of namespace Drupal\routing_system\Routing
 * Page-Level DocBlock
 *
 * @category Module
 *
 * @package MyPackage
 */
namespace Drupal\routing_system\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * This is class
 */
class RouteSubscriber extends RouteSubscriberBase {

    /**
     * {@inheritdoc}
     */
    protected function alterRoutes(RouteCollection $collection)
    {
        if ($route = $collection->get('routing_system.example')) {
            $route->setPath('/routing-system/changed');
            $requirements = $route->getRequirements();

            if (isset($requirements['_role'])) {
                $roles = explode(';', $requirements['_role']);
                $roles = array_filter(
                    $roles, function ($role) {
                        return $role !== 'content_editor';
                    }
                );
                if (empty($roles)) {
                    unset($requirements['_role']);
                } else {
                    $requirements['_role'] = implode(';', $roles);
                }

                $route->setRequirements($requirements);
            }
        }
    }
}
