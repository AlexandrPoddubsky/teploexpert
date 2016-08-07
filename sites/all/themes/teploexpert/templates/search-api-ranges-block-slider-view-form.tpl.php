<?php
/**
 * @file custom Search API ranges Min/Max UI slider widget
 */
?>
<div class="yui3-g">
  <div class="yui3-u range-box range-box-left">
    <?php print drupal_render($form['range-from']); ?>
  </div>
  <div class="yui3-u range-box range-box-right">
    <?php print drupal_render($form['range-to']); ?>
  </div>
  <div class="yui3-u range-slider-box">
  <div class="range-min">
  <?php print $form['range-min']['#value'];?>
  </div>
  <div class="range-max">
  <?php print $form['range-max']['#value'];?>
  </div>
    <?php print drupal_render($form['range-slider']); ?>
  </div>
</div>
<?php print drupal_render($form['submit']); ?>
<?php
  // Render required hidden fields.
  print drupal_render_children($form);
?>
