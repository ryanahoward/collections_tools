<?php

namespace Drupal\collections_tools\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use \Drupal\taxonomy\Entity\Term;

class ImportForm extends FormBase {
    public function getFormId() {
        return 'collections_import_form';
    }
	
    
    public function buildForm(array $form, FormStateInterface $form_state){
        $form['file'] = array(
            '#type' => 'file',
            '#title' => 'File to Import',
        );
        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => 'Import',
        );
        return $form;
    }
    
    public function validateForm(array &$form, FormStateInterface $form_state) {
        
        if(empty($_FILES['files']['type']['file']) || ($_FILES['files']['type']['file'] != 'text/xml')) {
            $form_state->setErrorByName('file', 'Not a valid file.');
        }
			
    }
    
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $tmp = $_FILES['files']['tmp_name']['file'];
        $file = file_get_contents($tmp);
        $xml = simplexml_load_string($file);
        $data = json_decode(json_encode($xml, TRUE));
        foreach ($data->item as $item) {

            $node = Node::create(array(
                'type' => 'movie',
                'title' => trim($item->title),
                'langcode' => 'en',
                'uid' => '1',
                'status' => 1,
            ));
					
						$node->set('field_own', 1);
            
						if (!empty($item->release)) {
							$date = strtotime($item->release);
							$date = date(DATETIME_DATE_STORAGE_FORMAT, $date);
							$node->set('field_release_date', $date);
						}
						
            $node->set('field_media_type', $item->format);
					
						if (!empty($item->studio)){
							$studio = db_query("SELECT tid FROM {taxonomy_term_field_data} WHERE name = :term", [':term' => $item->studio])->fetchObject();
							if (!empty($studio)) {
								$node->set('field_production_companies', $studio->tid);
							} else {
								$term = Term::create([
									'vid' => 'companies',
									'name' => $item->studio,
								]);
								$term->save();
								$node->set('field_production_companies', $term->id());
							}
						}

						if (!empty($item->director)){
							$directors = explode(';; ', $item->director);
							foreach ($directors as $director) {
								$d = db_query("SELECT tid FROM {taxonomy_term_field_data} WHERE name = :term", [':term' => $director])->fetchObject();
								if (!empty($d)) {
									$node->set('field_director', $d->tid);
								} else {
									$term = Term::create([
										'vid' => 'people',
										'name' => $director,
									]);
									$term->save();
									$node->set('field_director', $term->id());
								}
							}
						}


            $node->save();
    
        }
        

        
        $form_state->setRebuild();

        drupal_set_message('Form submitted.');
    }
}