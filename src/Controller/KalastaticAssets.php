<?php
/**
 * @file
 * Contains \Drupal\kalastatic\Controller\KalastaticAssets.
 */

namespace Drupal\kalastatic\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

class KalastaticAssets extends ControllerBase {
  /**
   * Build a page that shows the js and css that is added to the page by Drupal.
   */
  public function assets() {
    $css = $this->get_drupal_asset('css');
    $js = $this->get_drupal_asset('js');

    $form['description'] = array(
      '#markup' => 'Copy and paste the following markup into your html.html template to add the Drupal CSS and Javascript to Kalastatic.',
    );

    $form['css'] = array(
      '#title' => 'Drupal CSS',
      '#type' => 'textarea',
      '#default_value' => $css,
    );
    $form['js'] = array(
      '#title' => 'Drupal Javascript',
      '#type' => 'textarea',
      '#default_value' => $js,
    );

    return $form;
  }

  /**
   * Return either css or js markup that is being added to any given Drupal
   * page.
   */
  public function get_drupal_asset($type) {
    global $conf;
    switch ($type) {
      case 'css':
        $conf['preprocess_css'] = FALSE;
        // TODO: Find out how to get our grubby little hands on the css.
        $output = '';
        break;

      case 'js':
        $conf['preprocess_js'] = FALSE;
        // TODO: Find out how to get our grubby little hands on the js.
        $output = '';
        break;
    }

    return $output;
  }

}
