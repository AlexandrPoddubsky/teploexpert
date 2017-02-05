<?php
/**
 * @file
 * ws_feature_refs.features.field_instance.inc
 */

/**
 * Implements hook_field_default_field_instances().
 */
function ws_feature_refs_field_default_field_instances() {
  $field_instances = array();

  // Exported field_instance: 'node-water_heater-field_accessories'.
  $field_instances['node-water_heater-field_accessories'] = array(
    'bundle' => 'water_heater',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'views_field_formatter',
        'settings' => array(
          'views_field_formatter' => array(
            'implode_character' => '+',
            'multiple' => 1,
            'view' => 'recommended::block_1',
          ),
        ),
        'type' => 'views_field_formatter',
        'weight' => 43,
      ),
      'search_index' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_accessories',
    'label' => 'Аксессуары',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'entityreference',
      'settings' => array(
        'match_operator' => 'CONTAINS',
        'path' => '',
        'size' => 60,
      ),
      'type' => 'entityreference_autocomplete',
      'weight' => 83,
    ),
  );

  // Exported field_instance: 'node-water_heater-field_complect'.
  $field_instances['node-water_heater-field_complect'] = array(
    'bundle' => 'water_heater',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'views_field_formatter',
        'settings' => array(
          'views_field_formatter' => array(
            'implode_character' => '+',
            'multiple' => 1,
            'view' => 'recommended::block_1',
          ),
        ),
        'type' => 'views_field_formatter',
        'weight' => 42,
      ),
      'search_index' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_complect',
    'label' => 'Составляющие комплекта',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'entityreference',
      'settings' => array(
        'match_operator' => 'CONTAINS',
        'path' => '',
        'size' => 60,
      ),
      'type' => 'entityreference_autocomplete',
      'weight' => 82,
    ),
  );

  // Exported field_instance: 'node-water_heater-field_recommended'.
  $field_instances['node-water_heater-field_recommended'] = array(
    'bundle' => 'water_heater',
    'default_value' => NULL,
    'deleted' => 0,
    'description' => '',
    'display' => array(
      'default' => array(
        'label' => 'above',
        'module' => 'views_field_formatter',
        'settings' => array(
          'views_field_formatter' => array(
            'implode_character' => '+',
            'multiple' => 1,
            'view' => 'recommended::block_1',
          ),
        ),
        'type' => 'views_field_formatter',
        'weight' => 41,
      ),
      'search_index' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
      'teaser' => array(
        'label' => 'above',
        'settings' => array(),
        'type' => 'hidden',
        'weight' => 0,
      ),
    ),
    'entity_type' => 'node',
    'field_name' => 'field_recommended',
    'label' => 'Рекомендуемые товары',
    'required' => 0,
    'settings' => array(
      'user_register_form' => FALSE,
    ),
    'widget' => array(
      'active' => 1,
      'module' => 'entityreference',
      'settings' => array(
        'match_operator' => 'CONTAINS',
        'path' => '',
        'size' => 60,
      ),
      'type' => 'entityreference_autocomplete',
      'weight' => 81,
    ),
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Аксессуары');
  t('Рекомендуемые товары');
  t('Составляющие комплекта');

  return $field_instances;
}
