<?php

namespace Drupal\collections_tools\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;

/**
 * Plugin implementation of the 'taxonomy term fontawesome icon' formatter.
 *
 * @FieldFormatter(
 *   id = "taxonomy_term_icon",
 *   label = @Translation("Icon"),
 *   description = @Translation("Display an icon representation of the term."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class TermIcon extends EntityReferenceFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
        
        if (!($entity->field_term_icon->isEmpty())) {
            $image = $entity->field_term_icon->view('icon');
            $output = render($image);
        } else {
            $output = $entity->label();
        }
        
      if ($entity->id()) {
        $elements[$delta] = [
          '#markup' => $output,
          // Create a cache tag entry for the referenced entity. In the case
          // that the referenced entity is deleted, the cache for referring
          // entities must be cleared.
          '#cache' => [
            'tags' => $entity->getCacheTags(),
          ],
        ];
      }
    }

    return $elements;
  }
    
    public static function isApplicable(FieldDefinitionInterface $field_definition) {
        // By default, formatters are available for all fields.
        return $field_definition->getFieldStorageDefinition()->getSetting('target_type') == 'taxonomy_term';
    }

}
