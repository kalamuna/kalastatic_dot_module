<?php

namespace Drupal\kalastatic\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Asset\AssetResolver;
use Drupal\Core\Asset\AttachedAssetsInterface;
use Drupal\Core\Asset\AttachmentsInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;


class KalastaticAssets extends ControllerBase {
  public function content() {
    $css = $this->get_drupal_asset('css');
    $js = $this->get_drupal_asset('js');

    // $attached = $response->getAttachments();
    // $assets = AttachedAssets::createFromRenderArray(['#attached' => $attached]);
    // $css = AssetResolver::getCssAssets($assets);
    // kint($assets);

    $form['description'] = [
      '#markup' => 'Copy and paste the following markup into your html.html template to add the Drupal CSS and Javascript to Kalastatic.',
    ];

    $form['css'] = [
      '#title' => 'Drupal CSS',
      '#type' => 'textarea',
      '#value' => $css,
    ];
    $form['js'] = [
      '#title' => 'Drupal Javascript',
      '#type' => 'textarea',
      '#value' => $js,
    ];

    return $form;
  }

  public function library_loader(ModuleHandlerInterface $module_handler, ThemeHandlerInterface $theme_handler){
    $extension_types = array(
      'module' => array(
        'handler' => $module_handler,
        'method' => 'getModuleList',
      ),
      'theme' => array(
        'handler' => $theme_handler,
        'method' => 'listInfo',
      ),
    );
    foreach ($extension_types as $type => $extension_type) {
      foreach ($extension_type['handler']->{$extension_type['method']}() as $name => $extension) {
        $existing_namespaces[] = $name;

        // If type is 'module' we need to get the info.
        if ($type == 'module') {
          $info = system_get_info($type, $name);
        }
        else {
          $info = $extension->info;
        }

        // TODO: we need to implement a service like the components module.
      }
    }
  }
  /**
   * Return either css or js markup that is being added to any given Drupal page.
   */
  public function get_drupal_asset($type) {
    $libs = $this->library_loader();
    global $conf;
    switch ($type) {
      case 'css':
        $conf['preprocess_css'] = FALSE;
        $output = ''; //drupal_get_css();
        break;

      case 'js':
        $conf['preprocess_js'] = FALSE;
        $output = ''; //drupal_get_js();
        break;
    }

    return $output;
  }
  //
  // protected function getAllLibraries() {
  //   $modules = \Drupal::moduleHandler()->getModuleList();
  //   $extensions = $modules;
  //   $module_list = array_keys($modules);
  //   sort($module_list);
  //   $this->assertEqual($this->allModules, $module_list, 'All core modules are installed.');
  //
  //   $themes = $this->themeHandler->listInfo();
  //   $extensions += $themes;
  //   $theme_list = array_keys($themes);
  //   sort($theme_list);
  //   $this->assertEqual($this->allThemes, $theme_list, 'All core themes are installed.');
  //
  //   $libraries['core'] = $this->libraryDiscovery->getLibrariesByExtension('core');
  //
  //   $root = \Drupal::root();
  //   foreach ($extensions as $extension_name => $extension) {
  //     $library_file = $extension->getPath() . '/' . $extension_name . '.libraries.yml';
  //     if (is_file($root . '/' . $library_file)) {
  //       $libraries[$extension_name] = $this->libraryDiscovery->getLibrariesByExtension($extension_name);
  //     }
  //   }
  //   return $libraries;
  // }

}
