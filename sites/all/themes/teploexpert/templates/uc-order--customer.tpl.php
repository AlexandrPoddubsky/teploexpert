<?php

/**
 * @file
 * This file is the default customer invoice template for Ubercart.
 *
 * Available variables:
 * - $products: An array of product objects in the order, with the following
 *   members:
 *   - title: The product title.
 *   - model: The product SKU.
 *   - qty: The quantity ordered.
 *   - total_price: The formatted total price for the quantity ordered.
 *   - individual_price: If quantity is more than 1, the formatted product
 *     price of a single item.
 *   - details: Any extra details about the product, such as attributes.
 * - $line_items: An array of line item arrays attached to the order, each with
 *   the following keys:
 *   - line_item_id: The type of line item (subtotal, shipping, etc.).
 *   - title: The line item display title.
 *   - formatted_amount: The formatted amount of the line item.
 * - $shippable: TRUE if the order is shippable.
 *
 * Tokens: All site, store and order tokens are also available as
 * variables, such as $site_logo, $store_name and $order_first_name.
 *
 * Display options:
 * - $op: 'view', 'print', 'checkout-mail' or 'admin-mail', depending on
 *   which variant of the invoice is being rendered.
 * - $business_header: TRUE if the invoice header should be displayed.
 * - $shipping_method: TRUE if shipping information should be displayed.
 * - $help_text: TRUE if the store help message should be displayed.
 * - $email_text: TRUE if the "do not reply to this email" message should
 *   be displayed.
 * - $store_footer: TRUE if the store URL should be displayed.
 * - $thank_you_message: TRUE if the 'thank you for your order' message
 *   should be displayed.
 *
 * @see template_preprocess_uc_order()
 */
?>
<table width="95%" border="0" cellspacing="0" cellpadding="1" align="center" bgcolor="#006699" style="font-family: verdana, arial, helvetica; font-size: small;">
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="5" align="center" bgcolor="#FFFFFF" style="font-family: verdana, arial, helvetica; font-size: small;">
        <?php if ($business_header): ?>
        <tr valign="top">
          <td>
            <table width="100%" style="font-family: verdana, arial, helvetica; font-size: small;">
              <tr>
                <td>
                  <?php print $site_logo; ?>
                </td>
                <td width="98%">
                  <div style="padding-left: 1em;">
                  <span style="font-size: large;"><?php print $store_name; ?></span><br />
                  <?php print $site_slogan; ?>
                  </div>
                </td>
                <td nowrap="nowrap">
                  <?php print $store_address; ?><br /><?php print $store_phone; ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <?php endif; ?>

        <tr valign="top">
          <td>

            <?php if ($thank_you_message): ?>
            <p><b><?php print t('Thanks for your order, !order_first_name!', array('!order_first_name' => $order_first_name)); ?></b></p>
            <?php endif; ?>

            <table cellpadding="4" cellspacing="0" border="0" width="100%" style="font-family: verdana, arial, helvetica; font-size: small;">
              <tr>
                <td colspan="2" bgcolor="#006699" style="color: white;">
                  <b><?php print t('Purchasing Information:'); ?></b>
                </td>
              </tr>
              <tr>
                <td nowrap="nowrap">
                  <b><?php print t('E-mail Address:'); ?></b>
                </td>
                <td width="98%">
                  <?php print $order_email; ?>
                </td>
              </tr>
              <tr>
                <td colspan="2">

                  <table width="100%" cellspacing="0" cellpadding="0" style="font-family: verdana, arial, helvetica; font-size: small;">
                    <tr>
                      <td valign="top" width="50%">
                        <b><?php print t('Address:'); ?></b><br />
                        <?php print $order_shipping_address; ?><br />
                        <b><?php print t('Phone:'); ?></b><br />
                        <?php print $order_shipping_phone; ?><br />
                      </td>
                    </tr>
                  </table>

                </td>
              </tr>

              <tr>
                <td colspan="2" bgcolor="#006699" style="color: white;">
                  <b><?php print t('Order Summary:'); ?></b>
                </td>
              </tr>

              <tr>
                <td colspan="2">

                  <table border="0" cellpadding="1" cellspacing="0" width="100%" style="font-family: verdana, arial, helvetica; font-size: small;">
                    <tr>
                      <td nowrap="nowrap">
                        <b><?php print t('Order #:'); ?></b>
                      </td>
                      <td width="98%">
                        <?php print $order_link; ?>
                      </td>
                    </tr>

                    <tr>
                      <td nowrap="nowrap">
                        <b><?php print t('Order Date: '); ?></b>
                      </td>
                      <td width="98%">
                        <?php print $order_created; ?>
                      </td>
                    </tr>

                    <tr>
                      <td nowrap="nowrap">
                        <?php print t('Products Subtotal:'); ?>&nbsp;
                      </td>
                      <td width="98%">
                        <?php print $order_subtotal; ?>
                      </td>
                    </tr>

                    <?php foreach ($line_items as $item): ?>
                    <?php if ($item['type'] == 'subtotal' || $item['type'] == 'total')  continue; ?>

                    <tr>
                      <td nowrap="nowrap">
                        <?php print $item['title']; ?>:
                      </td>
                      <td>
                        <?php print $item['formatted_amount']; ?>
                      </td>
                    </tr>

                    <?php endforeach; ?>

                    <tr>
                      <td>&nbsp;</td>
                      <td>------</td>
                    </tr>

                    <tr>
                      <td nowrap="nowrap">
                        <b><?php print t('Total for this Order:'); ?>&nbsp;</b>
                      </td>
                      <td>
                        <b><?php print $order_total; ?></b>
                      </td>
                    </tr>

                    <tr>
                      <td colspan="2">
                        <br /><br /><b><?php print t('Products on order:'); ?>&nbsp;</b>

                        <table width="100%" style="font-family: verdana, arial, helvetica; font-size: small;">

                          <?php foreach ($products as $product): ?>
                          <tr>
                            <td valign="top" nowrap="nowrap">
                              <b><?php print $product->qty; ?> x </b>
                            </td>
                            <td width="98%">
                              <b><?php print $product->title; ?> - <?php print $product->total_price; ?></b>
                              <?php print $product->individual_price; ?><br />
                              <?php print t('SKU'); ?>: <?php print $product->model; ?><br />
                              <?php print $product->details; ?>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        </table>

                      </td>
                    </tr>
                  </table>

                </td>
              </tr>

              <?php if ($help_text || $email_text || $store_footer): ?>
              <tr>
                <td colspan="2">
                  <hr noshade="noshade" size="1" /><br />

                  <?php if ($email_text): ?>
                  <p><?php print t('Please note: This e-mail message is an automated notification. Please do not reply to this message.'); ?></p>

                  <p><?php print t('Thanks again for shopping with us.'); ?></p>
                  <?php endif; ?>

                  <?php if ($store_footer): ?>
                  <p><b><?php print $store_link; ?></b><br /><b><?php print $site_slogan; ?></b></p>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endif; ?>

            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
