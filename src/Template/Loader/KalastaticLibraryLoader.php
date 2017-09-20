<?php

namespace Drupal\components\Template\Loader;

/**
 * Loads templates from the filesystem.
 *
 * This loader adds Kalastatic specific paths as namespaces to the Twig
 * filesystem loader so that templates can be referenced by namespace.
 */
class ComponentLibraryLoader extends \Twig_Loader_Filesystem {
  /**
   * Constructs a new ComponentsLoader object.
   */
  public function __construct() {
    // Register the namespace paths.
    foreach (kalastatic_namespaces() as $namespace => $path) {
      $this->addPath($path, $namespace);
    }
  }

}
