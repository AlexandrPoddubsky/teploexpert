<?php $in_comparison = lp_product_in_comparison($tid, $nid); ?>
<?php $id = $tid . '-' . $nid; ?>
<div class="compare-product">
  <input type="checkbox" id="compare-<?php print $id; ?>" name="compare" <?php print $in_comparison ? 'checked="checked"' : ''; ?> />
  <label for="compare-<?php print $id; ?>">
    <?php if ($in_comparison) : ?>
      <?php print l(t('compare'), 'compare/' . $tid); ?>
    <?php else : ?>
      <?php print t('add to comparison'); ?>
    <?php endif; ?>
  </label>
</div>