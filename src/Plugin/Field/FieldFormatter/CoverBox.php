<?php

namespace Drupal\collections_tools\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter;
use Drupal\image\Entity\ImageStyle;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Component\Utility\Html;

/**
 * Plugin implementation of the 'cover_box' formatter.
 *
 * @FieldFormatter(
 *   id = "cover_box",
 *   label = @Translation("Box Overlay"),
 *   field_types = {
 *     "image"
 *   }
 * )
 */
class CoverBox extends ImageFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
     
      $elements = parent::viewElements($items, $langcode);
      $entity = $items->getEntity();
      $title = $entity->getTitle();
      
      $class = [];
      $format = '';
	  $box = new \stdClass();
      
      //add collection name to classes
      if (!empty($entity->field_collection) && !($entity->field_collection->isEmpty())) {
          $format = Html::getClass($entity->get('field_collection')->first()->get('entity')->getTarget()->get('name')->value);
          $class[] = $format;
      }
      
      //check first for media format
      if (!empty($entity->field_media_type) && !($entity->field_media_type->isEmpty())) {
          $media = $entity->get('field_media_type')->first()->get('entity')->getTarget();
          if (!($media->get('field_media_box')->isEmpty())) {
              $box = $media->get('field_media_box');
          }
          $format = Html::getClass($media->get('name')->value);
          $class[] = $format;
      }
      
      //add platform info and override box
      if (!empty($entity->field_platform) && !($entity->field_platform->isEmpty())) {
          $platform = $entity->get('field_platform')->first()->get('entity')->getTarget();
					//don't show the box for digital media
          if ($format != 'digital' && !($platform->get('field_platform_box')->isEmpty())) {
            $box = $platform->get('field_platform_box');
          }
          $class[] = Html::getClass($platform->get('name')->value);
      }
      
      
      $files = $this->getEntitiesToView($items, $langcode);
      foreach ($files as $delta => $file) {
					$uri = $file->getFileUri();
          if (strpos($uri, 'default_images')) {
              $class[] = 'default-image';
          }
          if (!empty($box->target_id)){
							if (!empty($elements[$delta]['#image_style'])) {
								$image_style = ImageStyle::load($elements[$delta]['#image_style']);
								$url = $image_style->buildUrl($uri);
							} else {
								$url = file_create_url($uri);
							}
              $elements[$delta]['#item'] = $box;
							//box class only used for physical media
							if ($format != 'digital'){
								$class[] = 'box';
							}
              
              $elements[$delta]['#item_attributes'] = ['style' => "background-image:url('$url')"];   
          }
          $elements[$delta]['#item_attributes']['class'] = $class;
          $elements[$delta]['#prefix'] = '<div class="coverbox">';
          $elements[$delta]['#suffix'] = '<div class="coverbox-title">'.$title.'</div></div>';
          
      }
      

    return $elements;
  }

}