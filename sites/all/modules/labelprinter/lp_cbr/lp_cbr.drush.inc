<?php
function lp_cbr_drush_command() {
  return array(
    'cbr_update' => array(
      'callback' => 'lp_cbr_cron', 
      'description' => dt('Automatic update of currency conversion rates and product prices using CBR API.'),
    ),
  );
}