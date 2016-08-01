<?php
/**
 * @file
 * Returns the HTML for a node.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728164
 */
?>
      <div class="views-field views-field-title"><a href="<?php print $node_url; ?>"><?php print $title; ?></a></div>
      <div class="views-field views-field-field-sku">    <span class="views-label views-label-field-sku">Артикул: </span>    <span class="field-content"><?php
        print render($content['field_sku']);
        ?></span>  </div>
        <div class="views-field views-field-uc-product-image">
        <?php
        print render($content['uc_product_image']);
        ?></div>
        <div class="views-field views-field-dimensions"><span class="views-label views-label-field-dimensions">ВхШхГ, мм: </span>
        <?php
        print render($content['field_dimensions']);
        ?></div>
        <div class="views-field views-field-weight"><span class="views-label views-label-field-weight">Вес, кг: </span>
        <?php
        print render($content['field_weight']);
        ?></div>
        <div class="views-field views-field-price"><span class="views-label views-label-price">Цена: </span>
        <?php
        print render($content['display_price']);
        ?></div>
      <?php
        print render($content['add_to_cart']);
      ?>

