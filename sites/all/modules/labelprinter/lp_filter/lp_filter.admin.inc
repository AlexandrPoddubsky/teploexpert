<?php
function lp_filter_admin() {
  $form = array();
  $classes = uc_product_types();
  $types = node_type_get_types();
  $options = array(0 => t('None'));
  foreach ($classes as $class) {
    $options[$class] = $types[$class]->name;
  }
  $vid = taxonomy_vocabulary_machine_name_load('catalog')->vid;
  $sections = taxonomy_get_tree($vid, 0, 1);
  foreach ($sections as $section) {
    $form['lp_filter_' . $section->tid] = array(
      '#type' => 'select',
      '#title' => $section->name,
      '#options' => $options,
      '#default_value' => variable_get('lp_filter_' . $section->tid),
    );    
  }
  $form['lp_filter_price'] = array(
    '#type' => 'textarea',
    '#title' => 'Price filter',
    '#default_value' => variable_get('lp_filter_price'),
  );
  return system_settings_form($form);
}