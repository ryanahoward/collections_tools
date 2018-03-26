<?php

namespace Drupal\collections_tools\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceLabelFormatter;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'taxonomy term boostrap label' formatter.
 *
 * @FieldFormatter(
 *   id = "taxonomy_term_bootstrap_label",
 *   label = @Translation("Bootstrap Label"),
 *   description = @Translation("Display an icon representation of the term."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class TermBootstrapLabel extends EntityReferenceLabelFormatter {

	/**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $options = parent::defaultSettings();
    $options['color'] = 'default';
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['color'] = [
      '#type' => 'select',
      '#title' => t('Color'),
      '#options' => ['default' => 'Default','primary' => 'Primary','secondary' => 'Secondary','info' => 'Info','success' => 'Success','warning' => 'Warning', 'danger' => 'Danger'],
      '#default_value' => $this->getSetting('color'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    if ($this->getSetting('color')) {
      $summary[] = ucfirst($this->getSetting('color'));
    }
    return $summary;
  }
  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
		$color = $this->getSetting('color');
		foreach ($elements as $delta => $element) {
			$elements[$delta]['#attributes']['class'] = ['label', 'label-'.$color];
		}


    return $elements;
  }
    
	public static function isApplicable(FieldDefinitionInterface $field_definition) {
			// By default, formatters are available for all fields.
			return $field_definition->getFieldStorageDefinition()->getSetting('target_type') == 'taxonomy_term';
	}

}
