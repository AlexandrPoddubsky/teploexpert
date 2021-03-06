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
      // var cutText = '.desc, .specs, .taxonomy-term-description, .boxx';
      var cutter = '.cutter, .specs .field:nth-child(5), .boxx li:nth-child(4)';
      $('.desc .fields p, .taxonomy-term-description p').each(function () {
        if ($(this).html() === '<!--break-->') {
          $(this).addClass('cutter');
        }
      });

        $(cutter).once('my_custom_behavior').parent().parent().append('<div class="readmore show-more">Показать все</div>');

      $(cutter).nextAll().hide();

      $('.readmore').once('my_custom_behavior').click(function () {
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

      $('.cart-link').click(function (e) {
        e.preventDefault();
        $('#cart-overlay').fadeIn(500);
      });
      $(document).delegate('#cart-overlay', 'click', function (e) {
        if ($(e.target).closest('#cart-items').length == 0) {
          $('#cart-overlay').fadeOut(500);
        }
      });
      $(document).delegate('#closinger, .closinger', 'click', function (e) {
        e.preventDefault();
        $('#cart-overlay').fadeOut(500);
      });

      // Checking ownership
      $('.ownership').change(function () {
        var x = '';
        $('.ownership').each(function () {
          if ($(this).attr('checked')) {
            x = ($(this).val());
            if (x === 'Юридическое лицо') {
              $('.address-pane-table').addClass('yur');
            }
            else {
              $('.address-pane-table').removeClass('yur');
            }
            $('#edit-panes-billing-address-billing-ucxf-ownership option:selected').attr('selected', 'false');
            $('#edit-panes-billing-address-billing-ucxf-ownership option').each(function () {
              if ($(this).val() === x) {
                $(this).attr('selected', 'selected');
              }
            });
          }
        });
      });
      $(document).bind('cbox_complete', function () {
        $('#cboxContent, #cboxLoadedContent').removeAttr('style');
        $('#colorbox').append('<div>trololo</div>');
      });
      // slider controls
      $('.tz_next').click(function () {

       $(this).parent().prev().children('.views-row:first').appendTo($(this).parent().prev());
       })
    $('.tz_prev').click(function () {

       $(this).parent().next().children('.views-row:last').prependTo($(this).parent().next());
       })
    $('#ajaxCartUpdate').ajaxComplete(function (event, request, settings) {
      if (request.responseURL.indexOf('uc_ajax_cart/show') >= 0) {
        $('.cart-link').click();
      }

    });
    }
  };

})(jQuery, Drupal, this, this.document);
