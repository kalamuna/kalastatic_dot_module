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

    // Obviously not ACTUALLY user input but how else do you create a url from
    // a dynamic route?
    $options = ['attributes' => ['target' => '_blank']];
    $proto_url = Url::fromUserInput('/kalastatic/prototype', $options);
    $style_url = Url::fromUserInput('/kalastatic/styleguide', $options);
    $links = [
      \Drupal::l(t('Visit prototype'), $proto_url),
      \Drupal::l(t('Visit styleguide'), $style_url)
    ];

    $form = array(
      'description' => array(
        '#markup' => '<p>' . t('Static site framework for prototyping and building out CMS-less websites and styleguides. See @link for more details.', array('@link' => $github_link)) . '</p>',
      ),
      'links' => array(
        '#theme' => 'item_list',
        '#items' => $links,
      ),
      'kalastatic_src_path_wrap' => array(
        '#type' => 'fieldset',
        '#title' => t('Kalastatic Paths'),
        '#collapsible' => FALSE,
        '#collapsed' => FALSE,
        'kalastatic_src_path' => array(
          '#type' => 'textfield',
          '#title' => $this->t('Source Path'),
          '#default_value' => $config->get('kalastatic_src_path'),
          '#size' => 60,
          '#description' => t("Provide the path to Kalastatic, relative to Drupal root. If no path is supplied then it will be assumed that Kalastatic is inside a folder called 'kalastatic' in the root of the currently enabled theme."),
        ),
        'kalastatic_build_path' => array(
          '#type' => 'textfield',
          '#title' => $this->t('Build Path'),
          '#default_value' => $config->get('kalastatic_build_path'),
          '#size' => 60,
          '#description' => t("Provide the path to the Kalastatic build folder, relative to the Drupal root. Defaults to the 'build' subfolder of the src path."),
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
