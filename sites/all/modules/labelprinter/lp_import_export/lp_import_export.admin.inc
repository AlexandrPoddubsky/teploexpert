<?php
function lp_import_form() {
  $form = array();
  $form['description'] = array(
    '#markup' => t('This form allows to import product data.') . '<br />',
  );
  $form['products'] = array(
    '#type' => 'file',
    '#title' => t('Product data file'),
    '#description' => t('File of XLS format.'),
    '#upload_validators' => array('file_validate_extensions' => array('xls')),
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Import'),
  );
  return $form;
}

function lp_export_form() {
  //dpm(field_info_instances('node', 'boiler'));
  //dpm(lp_import_export_get_fields());
  $form = array();
  $form['description'] = array(
    '#markup' => t('This form allows to export product data.') . '<br />',
  );
  $node_types = node_type_get_types();
  $types = array();
  foreach (uc_product_types() as $type) {
    $types[$type] = $node_types[$type]->name;
  }
  $form['types'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Product types'),
    '#options' => $types,
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Export'),
  );
  return $form;
}

function lp_import_form_validate($form, &$form_state) {
  $name = $_FILES['files']['name']['products'];
  if (empty($name)) {
    form_set_error('products', t('Please select product data file.'));
    return;
  }
  if (!preg_match('/\.(xls|XLS)$/', $name)) {
    form_set_error('products', t('Unsupported file format.'));
  }
}

function lp_import_form_submit($form_id, &$form_state) {
  global $user;

  $tmp_name = $_FILES['files']['tmp_name']['products'];

  require_once(libraries_get_path('phpexcel') . '/PHPExcel.php');
  $objPHPExcel = PHPExcel_IOFactory::load($tmp_name);
  $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

  if (count($sheetData) < 2) {
    drupal_set_message(t('File is empty.'), 'error');
    return;
  }

  $fields = lp_import_export_get_fields();
  $node_types = node_type_get_types();
  $types = array();
  foreach (uc_product_types() as $type) {
    $types[$node_types[$type]->name] = $type;
  }
  $categories = array();
  foreach (lp_misc_get_terms_by_vocabulary_name('categories') as $term) {
    $categories[$term->name] = $term->tid;
  }
  $vendors = array();
  foreach (lp_misc_get_terms_by_vocabulary_name('brands') as $term) {
    $vendors[$term->name] = $term->tid;
  }

  $tags_vocabulary = taxonomy_vocabulary_machine_name_load('tags');

  $folder = drupal_realpath('public://import');
  $ext = array('jpeg', 'jpg', 'png', 'gif');

  $created = $updated = 0;
  $ignored = $tags = array();

  $related_fields = lp_import_export_get_related_fields();
  $product_related_models = $models = array();

  foreach ($sheetData as $i => $row) {
    if ($i == 1) {
      continue;
    }

    $row = array_combine(array_keys($fields), $row);
// From here ================================================================================
  $err_vend = false;
  $err_cat = false;
  /*if((empty($row['vendor']) || !isset($vendors[$row['vendor']])) && ($row['type']!='Этикетки' && $row['type']!='Модификации' )){
    $err_vend = true;
  }*/
  if((empty($row['field_category']) || !isset($categories[$row['field_category']])) &&  ($row['type']!='Модификации' && $row['type']!='Аксессуар')){
    $err_cat = true;
  }
    $error = empty($row['model'])
      || empty($row['type']) || !isset($types[$row['type']])
      || $err_cat
      || $err_vend;
    if ($error) {
      $ignored[] = "$i. {$row['model']}";
     continue;
    }

    $product = db_select('uc_products', 'p')
      ->fields('p')
      ->condition('p.model', $row['model'])
      ->execute()->fetch();

    if (empty($product)) {
      $node = new stdClass;
      $node->shippable = false;
      $node->language = 'ru';
      $node->uid = $user->uid;
      $node->type = $types[$row['type']];
      $new = true;
    } else {
      $node = node_load($product->nid);
      $new = false;
    }

    foreach ($row as $field => $value) {
      if(!empty($value)){
        switch ($fields[$field]['type']) {
          case 'raw':
            if ($field != 'type') $node->$field = $value;
            break;
          case 'text_textarea_with_summary':
            $node->{$field}[LANGUAGE_NONE][0] = array(
              'value' => $value,
              'format' => 'full_html',
              );
          break;
          case 'text_textfield':
          case 'options_buttons':
          case 'number':
            $node->{$field}[LANGUAGE_NONE][0]['value'] = $value;
          break;
          case 'taxonomy_autocomplete' :
          case 'term_reference_tree' :
            switch ($field) {
            case 'field_brand':
              $node->field_brand[LANGUAGE_NONE][0]['tid'] = $vendors[$value];
              break;
            case 'field_category':
              $node->field_category[LANGUAGE_NONE][0]['tid'] = $categories[$value];
              break;
            case 'field_tags':
              $terms = preg_split('/\s*,\s*//*', $value);
            $node->field_tags[LANGUAGE_NONE] = array();
            foreach ($terms as $term_name) {
              if (!isset($tags[$term_name])) {
                $term = taxonomy_get_term_by_name($term_name, 'tags');
                if (empty($term)) {
                  $term = new stdClass;
                  $term->vid = $tags_vocabulary->vid;
                  $term->name = $term_name;
                  taxonomy_term_save($term);
                } else {
                  $term = array_shift($term);
                }
                $tags[$term_name] = $term->tid;
              }
              $node->field_tags[LANGUAGE_NONE][] = array('tid' => $tags[$term_name]);
            }
              break;

            default:
              # code...
              break;
          }
          break;
          case 'image_image':
            if (empty($node->uc_product_image)) {
              $filepath = 'http://teploexpert.com' . $value;
              $mime_raw = explode('.', $value);
              $mime = array_pop($mime_raw);
              $land = 'public://'. $node->model . '.' . $mime;
              if (copy($filepath, $land)) {
                $file = new stdClass;
                $file->uid = $user->uid;
                $file->uri = $land;
                $file->filemime = $mime;
                $file->status = 1;
                file_save($file);

                $node->uc_product_image[LANGUAGE_NONE][0] = (array) $file;
                break;
              }
          }
            break;
          default:
            # code...
            break;
        }
      }
    }

    if($node->type == 'mod') $node->status = 0;
    node_save($node);
    if($node->type == 'mod'){
      $node = node_load($node->nid);
    $option = new stdClass;
      $option->aid = 1;
      $option->name = $node->title;
      $option->cost = $node->cost;
      $option->price = $node->sell_price;
     $opt_query = db_select('uc_product_as_attribute_option', 'u')
     ->fields('u', array('oid'))
     ->condition('u.nid', $node->nid)
     ->execute()
     ->fetchField();
     if(!empty($opt_query)) $option->oid = $opt_query;
      uc_attribute_option_save($option);
      uc_product_as_attribute_option_save($option->oid, $node->nid);
    }

    if ($new) {
      $created++;
    } else {
      $updated++;
    }

    /*foreach ($related_fields as $field) {
      $row[$field] = trim($row[$field]);
      if (!empty($row[$field])) {
        $node_models = preg_split('/\s*,\s*//*', $row[$field], -1, PREG_SPLIT_NO_EMPTY);
        if (empty($node_models)) {
            continue;
        }
        $models = array_merge($models, $node_models);
        $product_related_models[$node->nid][$field] = $node_models;
      }
    }*/
  }

  if(!empty($models)){$models = array_unique($models);
  //$nids = array_unique($nids);
  $result = db_select('uc_products', 'p')
    ->fields('p', array('nid', 'model'))
    ->condition('p.model', $models, 'IN')
    ->execute()->fetchAll();
  $nids = array();
  foreach ($result as $row) {
    $nids[$row->model] = $row->nid;
  }
  foreach ($product_related_models as $nid => $node_models) {
    $node = node_load($nid);
    foreach ($related_fields as $field) {
      $values = array();
	  if (isset($node_models[$field])) {
		  foreach ($node_models[$field] as $model) {
			if (isset($nids[$model])) {
			  $values[] = array('nid' => $nids[$model]);
			}
		  }
		  $node->{$field}[LANGUAGE_NONE] = $values;
		}

    }
    node_save($node);
  }
}

  drupal_set_message(t('@total products in file. @created products were created, @updated products were updated.', array(
    '@total' => count($sheetData) - 1,
    '@created' => $created,
    '@updated' => $updated,
  )));
  if (!empty($ignored)) {
    drupal_set_message(t('The following SKUs were ignored (row. model): !ignored', array(
      '!ignored' => '<br />' . implode('<br />', $ignored),
    )), 'error');
  }
}

function lp_export_form_submit($form_id, &$form_state) {
  $types = array_diff($form_state['values']['types'], array(0));
  if (empty($types)) {
    drupal_set_message(t('No product types were selected'), 'error');
    return;
  }

  $result = db_select('node', 'n')
    ->fields('n', array('nid'))
    ->condition('n.type', $types, 'IN')
    ->execute()->fetchAll();
  $nids = array();
  foreach ($result as $row) {
    $nids[] = $row->nid;
  }

  $nodes = node_load_multiple($nids);

  require_once(libraries_get_path('phpexcel') . '/PHPExcel.php');
  $objPHPExcel = new PHPExcel();

  $fields = lp_import_export_get_fields();
  $node_types = node_type_get_types();
  $categories = array();
  foreach (lp_misc_get_terms_by_vocabulary_name('categories') as $term) {
    $categories[$term->tid] = $term->name;
  }
  $vendors = array();
  foreach (lp_misc_get_terms_by_vocabulary_name('brands') as $term) {
    $vendors[$term->tid] = $term->name;
  }
  $tags = array();
  foreach (lp_misc_get_terms_by_vocabulary_name('tags') as $term) {
    $tags[$term->tid] = $term->name;
  }

  $j = 0;
  foreach ($fields as $field) {
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j, 1, $field['label']);
    $j++;
  }

  $related_fields = lp_import_export_get_related_fields();
  $nids = array();
  foreach ($nodes as $key => $node) {
    foreach ($related_fields as $field) {
      $node_related_nids = array();
      if (!empty($node->{$field})) {
        foreach ($node->{$field}[LANGUAGE_NONE] as $item) {
          $nids[] = $item['nid'];
          $node_related_nids[] = $item['nid'];
        }
      }
      $nodes[$key]->{$field} = $node_related_nids;
    }
  }
  $models = array();
  if(!empty($nids)){
  $nids = array_unique($nids);
  $result = db_select('uc_products', 'p')
    ->fields('p', array('nid', 'model'))
    ->condition('p.nid', $nids, 'IN')
    ->execute()->fetchAll();

  foreach ($result as $row) {
    $models[$row->nid] = $row->model;
	}
  }

  $i = 2;
  foreach ($nodes as $node) {
    $j = 0;
    foreach ($fields as $field => $field_info) {
      switch ($field_info['type']) {
        case 'raw':
          $value = isset($node->$field) ? $node->$field: null;
          break;
        case 'text_textarea_with_summary':
        case 'text_textfield':
        case 'options_buttons':
        case 'number':
            $value = empty($node->$field) ? null : $node->{$field}[LANGUAGE_NONE][0]['value'];
            break;
        case 'taxonomy_autocomplete' :
        case 'term_reference_tree' :
          if (empty($node->$field)) break;
          switch ($field) {
            case 'field_brand':
              $value = $vendors[$node->{$field}[LANGUAGE_NONE][0]['tid']];
              break;
            case 'field_category':
              $value = $categories[$node->{$field}[LANGUAGE_NONE][0]['tid']];
              break;
            case 'field_tags':
              $array_tags = array();
              foreach ($node->{$field}[LANGUAGE_NONE] as $val) {
                $array_tags[] = $tags[$val['tid']];
              }
              $value = implode(', ', $array_tags);
              break;
            default:
              # code...
              break;
          }
        break;
        case 'image_image':
          $value = empty($node->$field) ? null : $node->{$field}[LANGUAGE_NONE][0]['filename'];
          break;

        default:
          # code...
          break;
      }

      if (!empty($value)) {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j, $i, $value);
      }
      $j++;
    }
    $i++;
  }

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="export.xls"');

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
  $objWriter->save('php://output');

  exit;
}

function lp_import_export_get_fields() {
  $common_fields = field_info_instances('node', 'boiler');
  $fields = array(
    'model' => array(
      'label' => 'ID',
      'type' => 'raw',
      ),
    'title' => array(
      'label' => t('Title'),
      'type' => 'raw',
      ),
    'type' => array(
      'label' => t('Type'),
      'type' => 'raw',
      ),
    'original_price' => array(
      'label' => t('Price'),
      'type' => 'raw',
      ),
    'original_currency' => array(
      'label' => t('Currency'),
      'type' => 'raw',
      ),
    );
  foreach ($common_fields as $field => $value) {
    $fields[$field] = array(
        'label' => $value['label'],
        'type' => $value['widget']['type'],
        'module' => $value['widget']['module'],
      );
  }
  return $fields;

  /*return array(
    'model' => t('SKU'),
    'title' => t('Title'),
    'type' => t('Type'),
    'field_availability' => t('Availability'),
    'category' => t('Category'),
    'vendor' => t('Vendor'),
    'original_price' => t('Price'),
    'original_currency' => t('Currency'),
    'body' => t('Description'),
    'field_tags' => t('Tags'),
    'url' => t('URL'),
    'field_recommended' => t('Recommended products'),
    'field_similar' => t('Similar products'),
    'field_accessories' => t('Accessories'),
  );*/
}

function lp_import_export_get_related_fields() {
  return array('field_recommended', 'field_similar', 'field_accessories');
}

/**
 * Batch operation
 */

function lp_import_export_process_import ($row, $fields, $context) {

}