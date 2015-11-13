jQuery(document).ready( function($) {
    ProjectViewer.init();
});


var ProjectViewer = (function($) {
	return {
		init: function() {
			$("a.trigger-menu").click(function(e) {
				e.preventDefault();
				var target = $(this).data("target");
				if ($("#triggered-menu-" + target).hasClass("triggered")) {
					$("#triggered-menu-" + target).removeClass("triggered");
				} else {
					$("#triggered-menu-" + target).addClass("triggered");
				}
			});
		},
	};
    
})(jQuery);