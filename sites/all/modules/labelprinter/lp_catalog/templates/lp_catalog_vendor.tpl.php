<?php if (isset($tags)) {?><div class="upper-tags"><?php print theme('lp_catalog_tags', array('tags' => $tags));?></div><?php } ?>
<div class="ltab"><?php if(isset($term['field_image'])) : print render($term['field_image']); endif; ?>
<?php if(isset($term['uc_catalog_image'])) : print render($term['uc_catalog_image']); endif; ?>
<?php if (isset($menu)) {print theme('lp_catalog_links', array('menu' => $menu, 'terms_description' => $terms_description));} ?>
</div>
<?php print render($term['description']); ?>
<?php print theme('lp_catalog_series', array('series' => $series)); ?>