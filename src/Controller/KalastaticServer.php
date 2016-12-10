<?php
/**
 * @file
 * Contains \Drupal\kalastatic\Controller\KalastaticAdmin.
 */

namespace Drupal\kalastatic\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\HtmlResponse;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class KalastaticServer extends ControllerBase {
  public function content($type) {
    // Get the path and split it up into an array.
    $path = rtrim(\Drupal::request()->getpathInfo(), '/');
    $args = explode('/', $path);
    array_shift($args);

    // Work out if we're trying to hit a file or a url.
    $last_arg = end($args);
    preg_match('/\.[^\.]+$/i', $last_arg, $ext);
    if (!isset($ext[0])) {
      // This isn't a file so let's assume we need to load the index.html inside
      // the folder we've been pointed to.
      $new_path = Url::fromUri('base:' . $path . '/index.html');
      $file_path = ltrim($new_path->toString(), '/');
      if (file_exists($file_path)) {
        return new RedirectResponse($new_path->toString());
      }
      else {
        drupal_set_message(t('The requested page could not be found inside Kalastatic'), 'error');
        throw new NotFoundHttpException();
      }
    }
  }

  /**
   * Return the path to the Kalastatic project relative to the site root.
   */
  public function getFilePath() {
    // If the path variable is set then use that, otherwise assume a default
    // inside the current theme.
    $path = \Drupal::config('kalastatic.settings')->get('kalastatic_file_path');
    return $path ? $path : kalastatic_path_to_kalastatic_default();
  }

  /**
   * Return the path to the built prototype relative to the site root.
   */
  public function getBuildPath() {
    return $this->getFilePath() . '/build';
  }
}
