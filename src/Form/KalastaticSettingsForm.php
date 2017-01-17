<?php
/**
 * @file
 * Contains \Drupal\kalastatic\Form\KalastaticSettingsForm.
 */

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
    $config = $this->config('kalastatic.settings');

    $github_url = 'https://github.com/kalamuna/kalastatic';
    $github_link = Link::fromTextAndUrl($github_url, Url::fromUri($github_url))->toString();

    $form = array(
      'description' => array(
        '#markup' => '<p>' . t('Static site framework for prototyping and building out CMS-less websites. See @link for more details.', array('@link' => $github_link)) . '</p>',
      ),
      'kalastatic_file_path_wrap' => array(
        '#type' => 'fieldset',
        '#title' => t('Path to Kalastatic'),
        '#collapsible' => FALSE,
        '#collapsed' => FALSE,
        'kalastatic_file_path' => array(
          '#type' => 'textfield',
          '#title' => $this->t('Path'),
          '#default_value' => $config->get('kalastatic_file_path'),
          '#size' => 60,
          '#description' => t("Provide the path to Kalastatic, relative to Drupal root. If no path is supplied then it will be assumed that Kalastatic is inside a folder called 'kalastatic' in the root of the currently enabled theme."),
        ),
      ),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::service('config.factory')->getEditable('kalastatic.settings');
    $config->set('kalastatic_file_path', $form_state->getValue('kalastatic_file_path'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
