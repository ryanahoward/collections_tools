<?php

namespace Drupal\collections_tools\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;


/**
 * Plugin implementation of the 'completion_bar' formatter.
 *
 *
 * @FieldFormatter(
 *   id = "completion_bar",
 *   label = @Translation("Progress Bar"),
 *   field_types = {
 *     "integer"
 *   }
 * )
 */
class CompletionBar extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $value = $item->value;
        
        if ($value == 100) {
            $class = 'progress-bar-success';
        } elseif ($value > 50 ) {
            $class = 'progress-bar-info';
        } elseif ($value > 25) {
            $class = 'progress-bar-warning';
        } else {
            $class = 'progress-bar-danger';
        }
        
    $output = '<div class="progress">
  <div class="progress-bar '.$class.'" role="progressbar" aria-valuenow="{{ value }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ value }}%">
    <span>{{ value }}%</span>
  </div>
</div>';

      $elements[$delta] = ['#type' => 'inline_template', '#template' => $output, '#context' => ['value' => $value]];
    }

    return $elements;
  }

}