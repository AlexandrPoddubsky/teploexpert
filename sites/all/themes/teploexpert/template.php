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
  if (drupal_is_front_page()) {
  if (isset($variables['page']['content']['system_main'])) {
    unset($variables['page']['content']['system_main']);
  }
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

function teploexpert_preprocess_region(&$variables, $hook) {
  if ($variables['region'] == 'bottom') {
    $variables['content'] = render($variables['elements']['block_5']);
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

function teploexpert_uc_cart_block_title($variables) {
  $title = $variables['title'];
  $icon_class = $variables['icon_class'];
  $collapsible = $variables['collapsible'];
  $collapsed = $variables['collapsed'];

  $output = '';

    $output .= '<a class="cart-link" href="/cart">' . $title . '</a>';

  return $output;
}

function teploexpert_uc_cart_block_items($variables) {
  $items = $variables['items'];
  $class = $variables['collapsed'] ? 'cart-block-items collapsed' : 'cart-block-items';

  // If there are items in the shopping cart...
  if ($items) {
    $output = '<table><tbody>';

    // Loop through each item.
    $row_class = 'odd';
    foreach ($items as $item) {
      // Add the basic row with quantity, title, and price.
      $output .= '<tr class="' . $row_class . '"><td class="cart-block-item-qty">' . $item['qty'] . '</td>'
                . '<td class="cart-block-item-title">' . $item['title'] . '</td>'
                . '<td class="cart-block-item-price">' . theme('uc_price', array('price' => $item['price'])) . '</td></tr>';

      // Add a row of description if necessary.
      if ($item['desc']) {
        $output .= '<tr class="' . $row_class . '"><td colspan="3" class="cart-block-item-desc">' . $item['desc'] . '</td></tr>';
      }

      // Alternate the class for the rows.
      $row_class = ($row_class == 'odd') ? 'even' : 'odd';
    }

    $output .= '</tbody></table>';
    $output .= '<p class="cbox-cart-links">' . theme('links', array('links' => $variables['summary_links'])) . '</p>';
  }
  else {
    // Otherwise display an empty message.
    $output = '<p class="' . $class . ' uc-cart-empty">' . t('There are no products in your shopping cart.') . '</p>';
  }

  return '<div id="cart-items">' . $output . '</div>';
}

function teploexpert_uc_cart_block_summary($variables) {
  $item_count = $variables['item_count'];
  $item_text = $variables['item_text'];
  $total = $variables['total'];
  $summary_links = $variables['summary_links'];

  // Build the basic table with the number of items in the cart and total.
  $output = ''
           . '<p>' . $item_text . '</p>'
           . '<p><label>' . t('Total:')
           . '</label> ' . theme('uc_price', array('price' => $total)) . '</p>';

  return $output;
}

function teploexpert_uc_cart_block_content($variables) {
  $help_text = $variables['help_text'];
  $items = $variables['items'];
  $item_count = $variables['item_count'];
  $item_text = $variables['item_text'];
  $total = $variables['total'];
  $summary_links = $variables['summary_links'];
  $collapsed = $variables['collapsed'];

  $output = '';

  // Add the help text if enabled.
  if ($help_text) {
    $output .= '<span class="cart-help-text">' . $help_text . '</span>';
  }

  // Add a table of items in the cart or the empty message.
  $output .= theme('uc_cart_block_items', array('items' => $items, 'collapsed' => $collapsed, 'summary_links' => $summary_links));

  // Add the summary section beneath the items table.
  $output .= theme('uc_cart_block_summary', array('item_count' => $item_count, 'item_text' => $item_text, 'total' => $total, 'summary_links' => $summary_links));

  return $output;
}

/**
 * Remove counts from facets
 */
function teploexpert_facetapi_link_inactive($variables) {
  // Builds accessible markup.
  // @see http://drupal.org/node/1316580
  $accessible_vars = array(
    'text' => $variables['text'],
    'active' => FALSE,
  );
  $accessible_markup = theme('facetapi_accessible_markup', $accessible_vars);

  // Sanitizes the link text if necessary.
  $sanitize = empty($variables['options']['html']);
  $variables['text'] = ($sanitize) ? check_plain($variables['text']) : $variables['text'];

  // Resets link text, sets to options to HTML since we already sanitized the
  // link text and are providing additional markup for accessibility.
  $variables['text'] .= $accessible_markup;
  $variables['options']['html'] = TRUE;
  return theme_link($variables);
}
/**
 * Implements hook_page_alter(). For moving term description to the bottom
 */
function teploexpert_page_alter(&$page) {
  $menu_item = menu_get_item();
  if ($menu_item['path'] == 'taxonomy/term/%') {
    $term = $menu_item['page_arguments'][0];

      $main = &$page['content']['system_main'];
      $term_heading = $main['term_heading'];
      unset($main['term_heading']);
      $main['term_heading'] = $term_heading;

  }
}

function teploexpert_uc_ajax_cart_cart_links() {
  $items[] = array(
    'data' => l(t('Продолжить покупки'), '#', array('attributes' => array('rel' => 'nofollow'), 'fragment' => 'refresh', 'external' => TRUE)),
    'class' => array('closinger'),
  );
  // Only add the checkout link if checkout is enabled.
  if (variable_get('uc_checkout_enabled', TRUE)) {
    $items[] = array(
      'data' => l(t('Checkout'), 'cart/checkout', array('attributes' => array('rel' => 'nofollow'))),
      'class' => array('cart-block-checkout'),
    );
  }
  return theme('item_list', array('items' => $items, 'title' => NULL, 'type' => 'ul', 'attributes' => array('class' => array('links'))));
}
