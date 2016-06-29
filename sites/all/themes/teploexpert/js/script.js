/**
 * @file
 * A JavaScript file for the theme.
 *
 * In order for this JavaScript to be loaded on pages, see the instructions in
 * the README.txt next to this file.
 */

// JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - https://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth
(function ($, Drupal, window, document) {

  'use strict';

  // To understand behaviors, see https://drupal.org/node/756722#behaviors
  Drupal.behaviors.my_custom_behavior = {
    attach: function (context, settings) {

      // Show more
      var cutText = '.desc, .specs, .taxonomy-term-description';
      var cutter = '.cutter, .specs .field:nth-child(5)';
      $('.desc .fields p').each(function () {
        if ($(this).html() === '<!--break-->') {
          $(this).addClass('cutter');
        }
      });
      $(cutText).append('<div class="readmore show-more">Показать все</div>');
      $(cutter).nextAll().hide();

      $('.readmore').click(function () {
        if ($(this).hasClass('show-more')) {
          $(this).text('Скрыть');
          $(this).parent().children().find(cutter).nextAll().show(200);
        }
        else {
          $(this).text('Показать все');
          $(this).parent().children().find(cutter).nextAll().hide(200);
        }
        $(this).toggleClass('show-less show-more');
      });
      // End of show more
    }
  };

})(jQuery, Drupal, this, this.document);
