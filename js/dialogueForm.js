/**
 * @file
 */

(function ($, Drupal) {
  "use strict";

  /**
   * Drupal behavior to handle auto submitting form.
   */
  Drupal.behaviors.linkitMediaHiddenSubmit = {
    attach: function (context, settings) {
      $("#linkit-media-creation-dialogue-submit", context).hide();
      $('#linkit-media-creation-dialogue-options', context)
        .change(function() {
          $("#linkit-media-creation-dialogue-submit", context).click();
        });
    }
  }

})(jQuery, Drupal);
