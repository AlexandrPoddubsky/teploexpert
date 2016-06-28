<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728096
 */


/**
 * Override or insert variables into the maintenance page template.
 *
 * @param array $variables
 *   Variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("maintenance_page" in this case.)
 */
/* -- Delete this line if you want to use this function
function teploexpert_preprocess_maintenance_page(&$variables, $hook) {
  // When a variable is manipulated or added in preprocess_html or
  // preprocess_page, that same work is probably needed for the maintenance page
  // as well, so we can just re-use those functions to do that work here.
  teploexpert_preprocess_html($variables, $hook);
  teploexpert_preprocess_page($variables, $hook);
}
// */

/**
 * Override or insert variables into the html templates.
 *
 * @param array $variables
 *   Variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("html" in this case.)
 */

function teploexpert_preprocess_html(&$variables, $hook) {
 if(array_search('uc-product-node', $variables['classes_array'])){
  $variables['classes_array'] = array_diff($variables['classes_array'], array('one-sidebar sidebar-first'));
  }
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param array $variables
 *   Variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("page" in this case.)
 */

function teploexpert_preprocess_page(&$variables, $hook) {
  if (isset($variables['node']) && uc_product_is_product($variables['node'])) {
    $variables['theme_hook_suggestions'][] = 'page__product';
  }
}
// */

/**
 * Override or insert variables into the region templates.
 *
 * @param array $variables
 *   Variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("region" in this case.)
 */
/* -- Delete this line if you want to use this function
function teploexpert_preprocess_region(&$variables, $hook) {
  // Don't use Zen's region--no-wrapper.tpl.php template for sidebars.
  if (strpos($variables['region'], 'sidebar_') === 0) {
    $variables['theme_hook_suggestions'] = array_diff(
      $variables['theme_hook_suggestions'], array('region__no_wrapper')
    );
  }
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param array $variables
 *   Variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function teploexpert_preprocess_block(&$variables, $hook) {
  // Add a count to all the blocks in the region.
  // $variables['classes_array'][] = 'count-' . $variables['block_id'];

  // By default, Zen will use the block--no-wrapper.tpl.php for the main
  // content. This optional bit of code undoes that:
  if ($variables['block_html_id'] == 'block-system-main') {
    $variables['theme_hook_suggestions'] = array_diff(
      $variables['theme_hook_suggestions'], array('block__no_wrapper')
    );
  }
}
// */

/**
 * Override or insert variables into the node templates.
 *
 * @param array $variables
 *   Variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("node" in this case.)
 */

function teploexpert_preprocess_node(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
  $node = $variables['node'];
  // Optionally, run node-type-specific preprocess functions, like
  // teploexpert_preprocess_node_page() or teploexpert_preprocess_node_story().
  if (uc_product_is_product($node)) {
    $variables['theme_hook_suggestions'][] = 'node__product';
    $variables['theme_hook_suggestions'][] = 'node__product__' . $variables['view_mode'];
    $function = __FUNCTION__ . '_product';
  if (function_exists($function)) {
    $function($variables, $hook);
  }
  }
}
// */

/**
 * Override or insert variables into the comment templates.
 *
 * @param array $variables
 *   Variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function teploexpert_preprocess_comment(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
}
// */

function teploexpert_uc_cart_block_summary($variables) {
  $item_count = $variables['item_count'];
  $item_text = $variables['item_text'];
  $total = $variables['total'];
  $summary_links = $variables['summary_links'];

  // Build the basic table with the number of items in the cart and total.
  $output = ''
           . '<p>' . $item_text . '</td>'
           . '<p><label>' . t('Total:')
           . '</label> ' . theme('uc_price', array('price' => $total)) . '</p>';

  // If there are products in the cart...
  if ($item_count > 0) {
    // Add a view cart link.
    $output .= '<p>'
             . theme('links', array('links' => $summary_links)) . '<p>';
  }

 // $output .= '</tbody></table>';

  return $output;
}