/**
 * Handles side menu toggling
 */
var sideMenu = {
   /**
    * Makes side menu toggleable by clicking menu button.
    */
   bindUI: function() {
      $("#menu-toggle").click(function(e) {
         e.preventDefault();
         $("#wrapper").toggleClass("toggled");
      });
   }
}

/**
 * Creates fileInput for attachments.
 */
var fileInputFactory = {
   create: function(handler) {
      handler.fileinput({
         theme: "explorer-fas",
         uploadAsync: false,
         autoOrientImage: false,
         language: "cs",
         hideThumbnailContent: true
      });
   }
}

/**
 * Creates bootstrap datepicker.
 */
var datePickerFactory = {
   create: function(handler) {
      handler.datepicker({
         startDate: "0d",
         language: "cs",
         todayHighlight: true
      });
   }
}

var textHider = {
   bindUI: function() {
      $('[data-toggle="collapse"]').click(function() {
         $(this).toggleClass("active");
         
         if ($(this).hasClass("active")) {
            $(this).text("Skrýt");
         } else {
            $(this).text("Zobrazit");
         }
      });
   }
}