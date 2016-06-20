(function ($) {
  Drupal.behaviors.compare = {
    attach : function(context, settings) {
      $('.compare-product input').unbind('click').click(function(e) {
        var parent = $(this).parent();
        var label = parent.find('label');
        var match = $(this).attr('id').match(/(\d+)-(\d+)/);
        $.post('/compare/manage/' + match[1] + '/' + match[2], function(data) {
          if (data.error) {
            alert(data.error);
          }
          parent.replaceWith(data.html);
          Drupal.attachBehaviors();
        });
        e.preventDefault();
      });
    }
  };
})(jQuery);