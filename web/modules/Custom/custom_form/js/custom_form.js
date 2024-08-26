(function ($, Drupal) {
  Drupal.behaviors.customForm = {
    attach: function (context, settings) {
      $('#edit-phone-number', context).once('customForm').on('change', function () {
        $(this).trigger('change');
      });
      $('#edit-email', context).once('customForm').on('change', function () {
        $(this).trigger('change');
      });
    }
  };
})(jQuery, Drupal);
