<?php
/**
 * @file
 *
 * Theme file for non empty cart.
 */
?>
<div id="cart-block-contents-ajax">
        <?php print $items_text; ?><br>

        <label><?php print t('На сумму'); ?>: </label><?php print round($total) ;?>&nbsp;р.
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
      <tr class="odd">
        <td class="cart-image">
          <?php print render($item['image']); ?>
        </td>
        <td class="cart-block-item-title">
          <?php print $item['title']; print $item['descr']; ?>
        </td>
        <td class="cart-block-item-qty">
          <?php print $item['qty']; ?>
        </td>
        <td>
          <?php print round($item['total']) ?>&nbsp;р.
        </td>
      </tr>
      <tr>
        <td colspan="4" class="cart-block-item-desc">
          <?php print $item['remove_link'] ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<table>
  <tbody class="cart-summ">
    <tr>
      <td class="cart-block-summary-items">
        <?php print $items_text; ?>
      </td>
      <td class="cart-block-summary-total">
        <label><?php print t('Total'); ?>: </label><?php print round($total) ;?>&nbsp;р.
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