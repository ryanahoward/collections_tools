<?php

namespace Drupal\collections_tools\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\StringFormatter;

/**
 * Plugin implementation of the 'heading' formatter.
 *
 * @FieldFormatter(
 *   id = "heading",
 *   label = @Translation("Heading"),
 *   field_types = {
 *     "string",
 *   },
 *   quickedit = {
 *     "editor" = "plain_text"
 *   }
 * )
 */
class HeadingFormatter extends StringFormatter {


  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $options = parent::defaultSettings();
    $options['heading'] = 'h3';
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['heading'] = [
      '#type' => 'select',
      '#title' => t('Heading'),
      '#options' => ['h1' => 'H1','h2' => 'H2','h3' => 'H3','h4' => 'H4','h5' => 'H5','h6' => 'H6'],
      '#default_value' => $this->getSetting('heading'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    if ($this->getSetting('heading')) {
      $summary[] = $this->getSetting('heading');
    }
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $h = $this->getSetting('heading');
    $url = NULL;
    if ($this->getSetting('link_to_entity')) {
      // For the default revision this falls back to 'canonical'
      $url = $items->getEntity()->urlInfo('revision');
    }

    foreach ($items as $delta => $item) {
      $view_value = $this->viewValue($item);
      if ($url) {
        $elements[$delta] = [
          '#type' => 'link',
          '#title' => $view_value,
          '#url' => $url,
          '#prefix' => '<'.$h.'>',
          '#suffix' => '</'.$h.'>',
        ];
      }
      else {
        $view_value['#prefix'] = '<'.$h.'>';
        $view_value['#suffix'] = '</'.$h.'>';
        $elements[$delta] = $view_value;
      }
    }
    return $elements;
  }


}
