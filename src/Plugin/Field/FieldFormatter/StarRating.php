<?php

namespace Drupal\collections_tools\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'list_key' formatter.
 *
 * @FieldFormatter(
 *   id = "star_rating",
 *   label = @Translation("Star Rating"),
 *   field_types = {
 *     "list_integer",
 *   }
 * )
 */
class StarRating extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'size' => '',
    ] + parent::defaultSettings();
  }
    
  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $options = [
        ''  => t('- None -'),
        'fa-xs' => t('XS'),
        'fa-sm' => t('SM'),
        'fa-lg' => t('LG'),
        'fa-2x' => t('2X'),
        'fa-3x' => t('3X'),
        'fa-4x' => t('4X'),
        'fa-5x' => t('5X'),
        'fa-6x' => t('6X'),
        'fa-7x' => t('7X'),
        'fa-8x' => t('8X'),
        'fa-9x' => t('9X'),
        'fa-10x' => t('10X'),
    ];
    $elements['size'] = [
      '#type' => 'select',
      '#title' => t('Icon Size'),
      '#options' => $options,
      '#default_value' => $this->getSetting('size'),
      '#weight' => 0,
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
      $summary = [];
      $summary[] = $this->getSetting('size');
      return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    
    $size = $this->getSetting('size');
   
    $ratings = array(
        0 => '<i class="far fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i>',
        1 => '<i class="fas fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i>',
        15 => '<i class="fas fa-star '.$size.'"></i><i class="fas fa-star-half '.$size.'"></i><i class="far fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i>',
        2 => '<i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i>',
        25 => '<i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i><i class="fas fa-star-half '.$size.'"></i><i class="far fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i>',
        3 => '<i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i>',
        35 => '<i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i><i class="fas fa-star-half '.$size.'"></i><i class="far fa-star '.$size.'"></i>',
        4 => '<i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i><i class="far fa-star '.$size.'"></i>',
        45 => '<i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i><i class="fas fa-star-half '.$size.'"></i>',
        5 => '<i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i><i class="fas fa-star '.$size.'"></i>',
    );   


    foreach ($items as $delta => $item) {
        $value = $item->value;

        $rating = $ratings[$value];
        
      $elements[$delta] = [
        '#markup' => '<span class="star-rating text-warning">'.$rating.'</span>',
      ];
    }

    return $elements;
  }
    
    public static function isApplicable(FieldDefinitionInterface $field_definition) {
        // By default, formatters are available for all fields.
        return $field_definition->getFieldStorageDefinition()->get('field_name') == 'field_rating';
    }

}

