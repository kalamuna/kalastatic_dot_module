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
    kint($type);
    // TODO: work out how we can load the html files. We need to acheive what 
    // kalastatic_serve_ks_files() is doing from the D7 version.
    return new HtmlResponse('<h1>It works, bitches</h1>');
  }
}
