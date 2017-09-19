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
        '#markup' => '<p>' . t('Static site framework for building out prototypes and styleguides. See @link for more details.', array('@link' => $github_link)) . '</p>',
      ),
      'kalastatic_src_path_wrap' => array(
        '#type' => 'fieldset',
        '#title' => t('Kalastatic'),
        '#collapsible' => FALSE,
        '#collapsed' => FALSE,
        'kalastatic_src_path' => array(
          '#prefix' => '<h3>' . $this->t('Source Path') . ':</h3>',
          '#markup' => '<pre>' . $config->get('kalastatic_src_path') . '</pre>',
        ),
        'kalastatic_build_path' => array(
          '#prefix' => '<h3>' . $this->t('Build Path') . ':</h3>',
          '#markup' => '<pre>' . $config->get('kalastatic_build_path') . '</pre>',
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
    $config->set('kalastatic_src_path', $form_state->getValue('kalastatic_src_path'))
      ->save();
    $config->set('kalastatic_build_path', $form_state->getValue('kalastatic_build_path'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
