<?php
function lp_import_form() {

  $form = array();
  $form['description'] = array(
    '#markup' => t('This form allows to import product data.') . '<br />',
  );
  $node_types = node_type_get_types();
  $types = array();
  foreach (uc_product_types() as $type) {
    $types[$type] = $node_types[$type]->name;
  }
  $form['types'] = array(
    '#type' => 'radios',
    '#title' => t('Product types'),
    '#options' => $types,
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
    '#type' => 'radios',
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
  $type = $form_state['values']['types'];
  $fields = lp_import_export_get_fields($type);
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

  $operations = array();
  foreach ($sheetData as $i => $row) {
    if ($i == 1) {
      continue;
    }

    $row = array_combine(array_keys($fields), $row);
// From here ================================================================================
    $operations[] = array('lp_import_export_process_import', array($row, $fields, $categories, $tags, $vendors, $types));
  //To here
}
$batch = array (
  // Массив операций и их параметров
    'operations' => $operations,
    // Функция, которая будет выполнена после окончания всех операций
    'finished' => 'lp_import_export_process_import_finished',
    // Заголовок страницы с прогресс баром.
    // Опционально, по умолчанию t('Processing')
    'title' => 'Импорт товаров',
    // Сообщение, показываемое при инициализации.
    // Опционально, по умолчанию t('Initializing.')
    'init_message' => 'Импорт товаров',
    // Сообщение, показывемое при выполнении операций.
    // Опционально, по умолчанию t('Completed @current of @total.')
    'progress_message' => 'Выполнено @current из @total.',
    // Сообщение показываемое при ошибке выполнения операции.
    // Опционально, по умолчанию t('An error has occurred.')
    'error_message' => 'Произошла ошибка.'
  );
batch_set($batch);

}

function lp_export_form_submit($form_id, &$form_state) {
  $types = $form_state['values']['types'];
  if (empty($types)) {
    drupal_set_message(t('No product types were selected'), 'error');
    return;
  }

  $result = db_select('node', 'n')
    ->fields('n', array('nid'))
    ->condition('n.type', $types)
    ->execute()->fetchAll();
  $nids = array();
  foreach ($result as $row) {
    $nids[] = $row->nid;
  }

  $nodes = node_load_multiple($nids);

  require_once(libraries_get_path('phpexcel') . '/PHPExcel.php');
  $objPHPExcel = new PHPExcel();

  $fields = lp_import_export_get_fields($types);
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
  $series = array();
  foreach (lp_misc_get_terms_by_vocabulary_name('series') as $term) {
    $series[$term->tid] = $term->name;
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
        case 'meta':
          if (empty($node->metatags)) {
            $value = null;
            break;
          }
          switch ($field) {
            case 'meta_title':
              $value = isset($node->metatags['ru']['title']['value']) ? $node->metatags['ru']['title']['value']: null;
              break;
            case 'meta_description':
              $value = isset($node->metatags['ru']['description']['value']) ? $node->metatags['ru']['description']['value']: null;
              break;

            default:

              break;
          }
        break;
        case 'raw':
        if ($field == 'type') {
          $value = $node_types[$node->type]->name; }
          else
          $value = isset($node->$field) ? $node->$field: null;
          break;
        case 'text_textarea_with_summary':
        case 'text_textfield':
        case 'options_buttons':
        case 'number':
        if (empty($node->$field)) {
            $value = null;
            break;
          }
            $val_array = array();
            foreach ($node->{$field}[LANGUAGE_NONE] as $val) {
              $val_array[] = $val['value'];
            }
            $value = implode(';', $val_array);
            break;
        case 'taxonomy_autocomplete' :
        case 'term_reference_tree' :
          if (empty($node->$field)) {
            $value = null;
            break;
          }
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
			case 'field_series':
              $array_series = array();
              foreach ($node->{$field}[LANGUAGE_NONE] as $val) {
                $array_series[] = $series[$val['tid']];
              }
              $value = implode(', ', $array_series);
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

function lp_import_export_get_fields($type) {
  $common_fields = field_info_instances('node', $type);
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
    'meta_title' => array(
      'label' => t('meta_title'),
      'type' => 'meta',
      ),
    'meta_description' => array(
      'label' => t('meta_description'),
      'type' => 'meta',
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
}

function lp_import_export_get_related_fields() {
  return array('field_recommended', 'field_similar', 'field_accessories');
}

