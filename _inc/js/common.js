jQuery(document).ready( function() {
    YPUIFunctions.initUI();
    YPMenuFunctions.refreshMenuBar();
});
jQuery(window).scroll( function() {
    YPMenuFunctions.refreshMenuBar();
});

YPUIFunctions = (function($) {
    return {
	initUI: function() {
	    $(document).load($(window).bind("resize", YPUIFunctions.onWidthChange));
	    YPMenuFunctions.initMenuBar();
	    YPUIFunctions.onWidthChange();
	},
	
	onWidthChange: function(e) {
	    $("#navigation").width($(window).width());
	    if ($(window).width() < 481) {
		$("#projects_vote").remove().insertAfter($("#projects_current"));
	    } else {
		$("#projects_current").remove().insertAfter($("#projects_vote"));
	    }
	}
    }
})(jQuery);

YPMenuFunctions = (function($){
    return {
	initMenuBar: function() {
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
	    
	    $("#menu_item_connection").mouseenter(function(){
		$("#submenu_item_connection").css("top", $("#navigation").position().top + $("#navigation").height());
		$("#submenu_item_connection").css("left", $("#menu_item_connection").position().left + $("#menu_item_connection").width() - $("#submenu_item_connection").width());
		clearTimeout($("#menu_item_connection").data('timeoutId'));
		$("#submenu_item_connection").fadeIn("slow");
	    }).mouseleave(function(){
		var timeoutId = setTimeout(function(){
		    $("#submenu_item_connection").fadeOut("slow");
		}, 650);
		$("#menu_item_connection").data('timeoutId', timeoutId); 
	    });
	    
	    $("#submenu_item_connection").mouseenter(function(){
		clearTimeout($("#menu_item_connection").data('timeoutId'));
		$("#submenu_item_connection").fadeIn("slow");
	    }).mouseleave(function(){
		var timeoutId = setTimeout(function(){
		    $("#submenu_item_connection").fadeOut("slow");
		}, 650);
		$("#menu_item_connection").data('timeoutId', timeoutId); 
	    });
	    
	    $("#share_btn").mouseup(function() {
		$("#popup_share").css("top", $("#share_btn").position().top + $("#share_btn").height() + 20);
		$("#popup_share").css("left", $("#share_btn").position().left);
		$("#popup_share").width($("#share_btn").width() - 2);
		$("#popup_share").toggle();
	    });
	    
	    $("#popup_share_close").mouseup(function() {
		$("#popup_share").toggle();
	    });
	},
	
	refreshMenuBar: function() {
	    $("#navigation").css("top", $(window).scrollTop());
	}
    }
})(jQuery);