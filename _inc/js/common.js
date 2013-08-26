jQuery(document).ready( function() {
    YPUIFunctions.initUI();
});

YPUIFunctions = (function($) {
    return {
	initUI: function() {
	    $(document).load($(window).bind("resize", YPUIFunctions.onWidthChange));
	    YPMenuFunctions.initMenuBar();
	    YPUIFunctions.onWidthChange();
	    
	    if ($("#fundingproject").val()) { 		
		$("#fundingproject").click(function() { $("#fundingdevelopment_param").hide(); }); 		
		$("#fundingdevelopment").click(function() { $("#fundingdevelopment_param").show(); }); 		
		$("#goalsum_fixe").click(function() { $("#goalsum_flexible_param").hide(); $("#goalsum_fixe_param").show();}); 		
		$("#goalsum_flexible").click(function() { $("#goalsum_flexible_param").show(); $("#goalsum_fixe_param").hide();});
		$("#goal_search").change(function() {
		    $("#goal").val($("#goal_search").val() * $("#campaign_multiplier").val());
		    $("#goalsum_campaign_multi").text($("#goal").val() + $("#monney").val());
		});
	    }
	    
	    if ($("#input_invest_amount").length > 0) {
		$("#input_invest_amount").change(function() {
		    YPUIFunctions.checkInvestInput();
		});
		
		$("#invest_form").submit(function() {
		    return YPUIFunctions.checkInvestInput();
		});
	    }
	    
	    if ($("#company_status").length > 0) {
		$("#company_status").change(function() { 
		    if ($("#company_status").val() == "Autre") $("#company_status_other_zone").show(); 
		    else  $("#company_status_other_zone").hide(); 
		});
	    }
	},
	
	checkInvestInput: function() {
	    $(".invest_error").hide();
	    $(".invest_success").hide();
	    
	    var bValidInput = true;
	    if (!$.isNumeric($("#input_invest_amount").val())) {
		$("#invest_error_general").show();
		bValidInput = false;
	    } else {
		if ($("#input_invest_amount").val() != Math.floor($("#input_invest_amount").val())) {
		    $("#invest_error_integer").show();
		    bValidInput = false;
		}
		if (parseInt($("#input_invest_amount").val()) < $("#input_invest_min_value").val()) {
		    $("#invest_error_min").show();
		    bValidInput = false;
		}
		if (parseInt($("#input_invest_amount").val()) > $("#input_invest_max_value").val()) {
		    $("#invest_error_max").show();
		    bValidInput = false;
		}
		var nAmountInterval = $("#input_invest_max_value").val() - parseInt($("#input_invest_amount").val()); 		
		if (nAmountInterval < $("#input_invest_min_value").val() && nAmountInterval > 0) { 		
		    $("#invest_error_interval").show(); 		
		    bValidInput = false; 		
		}
	    }
	    if (bValidInput) {
		$("#invest_success_amount").text( parseInt($("#input_invest_amount_total").val()) + parseInt($("#input_invest_amount").val()));
		$(".invest_success").show();
	    }
	    
	    $("#input_invest_amount").css("color", bValidInput ? "green" : "red");
	    return bValidInput;
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

 
YPVoteFormFunctions = (function(){ 	
	$("#impact-positif").click(function() { $("#content-impact-positif").show();
	 									    $("#local").prop('disabled', false);
    										$("#environmental").prop('disabled', false);
											$("#social").prop('disabled', false);
											$("#autre").prop('disabled', false);
											$("#precision").prop('disabled', false);
	}); 		
	$("#desaprouve").click(function() { $("#local").prop('disabled', true);
    								    $("#environmental").prop('disabled', true);
										$("#social").prop('disabled', true);
										$("#autre").prop('disabled', true);
										$("#precision").prop('disabled', true);
											
	});

	$("#pret").click(function() { $("#content-retravaille").hide(); $("#content-pret").show();}); 		
	$("#retravaille").click(function() { $("#content-retravaille").show(); $("#content-pret").hide();});
	    
});
	

	