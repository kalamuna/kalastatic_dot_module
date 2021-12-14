<?php

namespace Drupal\kalastatic\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Configure example settings for this site.
 */
class KalastaticSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'kalastic_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'kalastatic.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $settings = kalastatic_get_settings();
    $config = $this->config('kalastatic.settings');
    $github_url = 'https://github.com/kalamuna/kalastatic';
    $github_link = Link::fromTextAndUrl($github_url, Url::fromUri($github_url))->toString();

    // Check if we have a valid source and build path.
    $source_error = t('Source path not set. Make sure kalastatic.yml exists and contains a \'source\' path that exists.');
    $build_error = t('Build path not set. Make sure kalastatic.yml exists and contains a \'destination\' path that exists.');
    $source_path = empty($settings['yaml']['source']) ? $source_error : $settings['yaml']['source'];
    $build_path = empty($settings['yaml']['destination']) ? $build_error : $settings['yaml']['destination'];

    // Get a list of all enabled themes.
    $themes = \Drupal::service('theme_handler')->listInfo();
    $theme_list = [];
    foreach ($themes as $mn => $theme) {
      $theme_list[$mn] = $theme->info['name'];
    }
    $theme_list_default = $config->get('kalastatic_theme_list') ?: [];

    $form = [
      'description' => [
        '#markup' => '<p>' . t('Static site framework for building out prototypes and styleguides. See @link for more details.', ['@link' => $github_link]) . '</p>',
      ],
      'kalastatic_src_path_wrap' => [
        '#type' => 'fieldset',
        '#title' => t('Kalastatic'),
        '#collapsible' => FALSE,
        '#collapsed' => FALSE,
        'kalastatic_src_path' => [
          '#prefix' => '<h3>' . $this->t('Source Path') . ':</h3>',
          '#markup' => '<pre>' . $source_path . '</pre>',
        ],
        'kalastatic_build_path' => [
          '#prefix' => '<h3>' . $this->t('Build Path') . ':</h3>',
          '#markup' => '<pre>' . $build_path . '</pre>',
        ],
      ],
      'kalastatic_theme_list' => [
        '#type' => 'checkboxes',
        '#title' => $this->t('Include Kalastatic assets for'),
        '#options' => $theme_list,
        '#default_value' => $theme_list_default,
        '#description' => $this->t('The CSS and Javascript output by Kalastatic will be included when any of the chosen themes are set as the default theme.'),
      ],
      'kalastatic_brand_color' => [
        '#type' => 'textfield',
        '#title' => $this->t('Brand color'),
        '#default_value' => $config->get('kalastatic_brand_color'),
        '#size' => 60,
        '#description' => t("Provide the hex brand color for the site. Useful for favicons and mobile icon homescreens."),
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::service('config.factory')->getEditable('kalastatic.settings');
    $config
      ->set('kalastatic_brand_color', $form_state->getValue('kalastatic_brand_color'))
      ->set('kalastatic_theme_list', $form_state->getValue('kalastatic_theme_list'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
