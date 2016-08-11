<?php
/**
 * @file
 *
 * Theme file for non empty cart.
 */
?>
<div id="cart-block-contents-ajax">
        <?php print $items_text; ?><br>

        <label><?php print t('На сумму'); ?>: </label><?php print number_format($total, 0 , '.', ' ') ;?>&nbsp;р.
<div id="cart-overlay">
<div id="cart-items">
<div id="closinger"></div>
<div class="cart-title">
  <div>
    Корзина
  </div>
</div>
  <table class="cart-block-items">
    <tbody>
      <?php foreach ( $items as $item ): ?>
      <tr class="line-item">
        <td class="cart-image">
          <?php print render($item['image']); ?>
        </td>
        <td class="cart-block-item-title">
          <?php print $item['title']; print $item['descr']; ?>
        </td>
        <td class="cart-block-item-qty">
          <?php print $item['qty']; ?>&nbsp;шт.
          <?php print $item['remove_link'] ?>
        </td>
        <td class="item-price">
          <?php print number_format($item['total'], 0 , '.', ' ') ?>&nbsp;р.
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<table>
  <tbody class="cart-summ">
    <tr>
      <td class="cart-block-summary-items">
        <?php //print $items_text; ?>
      </td>
      <td class="cart-block-summary-total">
        <label><?php print t('Total'); ?>: </label><span class="total"><?php print number_format($total, 0 , '.', ' ') ;?>&nbsp;р.</span>
      </td>
    </tr>
    <tr class="cart-block-summary-links">
      <td colspan="2">
        <?php print $cart_links; ?>
      </td>
    </tr>
  </tbody>
</table>
</div>
</div>
</div>