<?php
function lp_cbr_admin() {
  $form['currency']['cbr']['lp_cbr_autoupdate'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable automatic currency update'),
    '#description' => t('Automatic update of currency conversion rates and product prices using CBR API.'),
    '#default_value' => variable_get('lp_cbr_autoupdate', false),
  );
  foreach (lp_cbr_get_currencies() as $currency) {
    $form['currency']['cbr']['lp_cbr_conversion_rate_' . strtolower($currency)] = array(
      '#type' => 'textfield',
      '#title' => t($currency . ' to RUB converstion rate'),
      '#default_value' => variable_get('lp_cbr_conversion_rate_' . strtolower($currency), 1),
      '#size' => 10,
    );
  }
  $form['#submit'][] = 'lp_cbr_settings_form_submit';
  return system_settings_form($form);
}

function lp_cbr_settings_form_submit($form, &$form_state) {
  variable_set('lp_cbr_update_prices', true);
}
