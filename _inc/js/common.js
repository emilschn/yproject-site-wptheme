jQuery(document).ready( function() {
    YPMenuFunctions.initMenuBar();
    YPMenuFunctions.refreshMenuBar();
});
jQuery(window).scroll( function() {
    YPMenuFunctions.refreshMenuBar();
});

YPMenuFunctions = (function($){
    return {
	initMenuBar: function() {
	    $("#navigation").width($(window).width());
	    
	    $("#menu_item_facebook").hover(function() {
		$("#fb_infos").css("top", $("#navigation").position().top + $("#navigation").height());
		$("#fb_infos").css("left", $("#menu_item_facebook").position().left);
		$("#fb_infos").show();
		
	    }, function() {
		$("#fb_infos").hide();
	    });
	    
	    $("#menu_item_twitter").hover(function() {
		$("#twitter_infos").css("top", $("#navigation").position().top + $("#navigation").height());
		$("#twitter_infos").css("left", $("#menu_item_twitter").position().left);
		$("#twitter_infos").show();
		
	    }, function() {
		$("#twitter_infos").hide();
	    });
	},
	
	refreshMenuBar: function() {
	    $("#navigation").css("top", $(window).scrollTop());
	}
    }
})(jQuery);