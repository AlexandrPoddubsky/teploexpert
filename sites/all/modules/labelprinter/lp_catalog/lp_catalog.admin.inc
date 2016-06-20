<?php
function lp_catalog_admin() {
  $form['lp_catalog_alias_replacement'] = array(
    '#type' => 'textarea',
    '#title' => 'Alias replacement',
    '#default_value' => variable_get('lp_catalog_alias_replacement'),
    '#description' => t('Each replacement starts from new line. Example: example | new/example2. This means that <i>example</i> is replaced with <i>new/example</i>. To apply changes, please re-save any term which is related to replacement.'),
    '#rows' => 10,
  );
  return system_settings_form($form);
}

