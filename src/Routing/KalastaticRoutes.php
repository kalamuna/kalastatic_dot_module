<?php
/**
 * @file
 * Contains \Drupal\kalastatic\Routing\KalastaticRoutes.
 */

namespace Drupal\kalastatic\Routing;

use Symfony\Component\Routing\Route;

/**
 * Defines dynamic routes.
 */
class KalastaticRoutes {

  /**
   * {@inheritdoc}
   */
  public function routes() {
    xdebug_break();
    $routes = array();
    $path = '/kalastatic';

    // Drupal allows up to 9 levels so let's use them.
    for ($i=0; $i < 8 ; $i++) {
      // Append a wildcard to the path each time except the first.
      if ($i > 0) {
        $path .= '/{path_' . $i . '}';
      }

      // Create a new route.
      $routes['kalastatic.content_' . $i] = new Route(
        $path,
        array(
          '_controller' => '\Drupal\kalastatic\Controller\KalastaticServer::content',
          '_title' => 'Hello'
        ),
        array(
          '_permission'  => 'access content',
        )
      );
    }

    return $routes;
  }
}
