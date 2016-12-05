<?php
/**
 * @file
 * Contains \Drupal\kalastatic\Controller\KalastaticAdmin.
 */

namespace Drupal\kalastatic\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\HtmlResponse;
use Symfony\Component\HttpFoundation\Request;

class KalastaticServer extends ControllerBase {
  public function content($type) {
    // TODO: work out how we can load the html files. We need to acheive what
    // kalastatic_serve_ks_files() is doing from the D7 version.

    // Get the path and split it up into an array.
    $path = \Drupal::request()->getpathInfo();
    $args = explode('/', $path);
    array_shift($args);
    kint($path);
    kint($args);

    $path = 'index.html';
    $needs_headers = FALSE;
    // Check if we're visiting something deeper than the home page.
    if (isset($args[2])) {
      $suffix = '';

      // Regex on the last arg to see if we're trying to load a page or a file.
      $last_arg = end($args);
      preg_match('/\.[^\.]+$/i', $last_arg, $ext);

      if (isset($ext[0])) {
        // This is a file so we need to add headers for it's mime type before we
        // return the file.
        $needs_headers = TRUE;
      }
      else {
        // Assume we're loading the html page.
        $suffix = '/' . $path;
      }

      unset($args[0]);
      unset($args[1]);
      $path = implode('/', $args) . $suffix;
    }
    else {
      // There isn't a requested page, so redirect the user to index.html homepage.
      drupal_goto('kalastatic/prototype/index.html');
    }

    $file = $this->getBuildPath() . '/' . $path;

    // Add headers if needed.
    if ($needs_headers) {
      $mime_type = file_get_mimetype($file);
      drupal_add_http_header('Content-Type', $mime_type);
    }
    if (file_exists($file)) {
      $file_contents = file_get_contents($file);
      // We have a file to serve so let's do it!
      return new HtmlResponse('<h1>It works, boo</h1>');
    }
    elseif ((isset($args[1]) && $args[1] == 'prototype') || (isset($args[2]) && $args[2] == 'styleguide')) {
      // Kalastatic isn't were it's supposed to be so output a nice message.
      $replacements = array(
        '!path' => '<code>' . $this->getFilePath() . '</code>',
        '!link' => l(t('About page'), 'admin/config/kalastatic'),
      );
      $page = array(
        '#markup' => '<h2>' . t('Kalastatic could not be found!') . '</h2><p>' . t("We were looking in !path but it wasn't there. If Kalastatic is living somewhere else you can set the location on the !link.", $replacements) . '</p>',
      );

      return $page;
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
