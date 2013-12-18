jQuery(document).ready( function() {
    YPUIFunctions.initUI();
    YPVoteFormFunctions.voteformcontrole();
    YPJycroisFunctions.loadJycrois();
    
});

YPUIFunctions = (function($) {
    return {
	initUI: function() {
	    YPMenuFunctions.initMenuBar();

	    if ($("#finish_subscribe").length > 0) {		
		$("#container").css('padding-top', "55px");		
	    }
	    
	    if ($("#project_vote_link").length > 0) {
		$("#project_vote_link").click(function() {$("#project_vote_zone").show();});
	    }

	    if ($("#fundingproject").val()) { 				
		$("#goalsum_fixe").click(function() { $("#goalsum_flexible_param").hide(); $("#goalsum_fixe_param").show();}); 		
		$("#goalsum_flexible").click(function() { $("#goalsum_flexible_param").show(); $("#goalsum_fixe_param").hide();});
		
		$("#goal_search").change(function() {
		    $("#goal").val(Math.round($("#goal_search").val() * $("#campaign_multiplier").val()));
		    $("#goalsum_campaign_multi").text($("#goal").val() + $("#monney").val());
		});
		$("#minimum_goal_search").change(function() {
		    $("#minimum_goal").val(Math.round($("#minimum_goal_search").val() * $("#campaign_multiplier").val()));
		    $("#goalsum_min_campaign_multi").text($("#minimum_goal").val() + $("#monney").val());
		});
		$("#maximum_goal_search").change(function() {
		    $("#maximum_goal").val(Math.round($("#maximum_goal_search").val() * $("#campaign_multiplier").val()));
		    $("#goalsum_max_campaign_multi").text($("#maximum_goal").val() + $("#monney").val());
		});
	    
		$(".radiofundingtype").change(function(){
		    $("#goal").val("");
		    if ($("#fundingproject").attr("checked") == "checked") {
			$("#fundingdevelopment_param").hide();
			$(".min_amount_value").html($("#min_amount_project").val());
		    }
		    if ($("#fundingdevelopment").attr("checked") == "checked") {
			$("#fundingdevelopment_param").show();
			$(".min_amount_value").html($("#min_amount_development").val());
		    }
		});
	    }
	    
	    if ($("#input_invest_amount_part").length > 0) {
		$("#input_invest_amount_part").change(function() {
		    YPUIFunctions.checkInvestInput();
		});
		
		$("#link_validate_invest_amount").click(function() {
		    $("#validate_invest_amount_feedback").show();
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
	    
	    if ($("#item-body").length > 0) {
		var aTabs = ["activity", "following", "followers", "projects"];
		var nHeight = 100;
		for (var i = 0; i < aTabs.length; i++) {
		    nHeight = Math.max(nHeight, $("#item-body-" + aTabs[i]).height());
		}
		for (var i = 0; i < aTabs.length; i++) {
		    $("#item-body-" + aTabs[i]).height(nHeight);
		}
	    }
	    
	    if ($(".wp-editor-wrap")[0]) {
		setInterval(YPUIFunctions.onRemoveUploadInterval, 1000);
	    }
	},
	
	onRemoveUploadInterval: function() {
	    if ($(".media-frame-menu")[0]) $(".media-frame-menu").remove();
	    if ($(".media-frame-router")[0]) $(".media-frame-router").show();
	},
	
	checkInvestInput: function() {
	    $(".invest_error").hide();
	    $(".invest_success").hide();
	    
	    var bValidInput = true;
	    if (!$.isNumeric($("#input_invest_amount_part").val())) {
		$("#invest_error_general").show();
		bValidInput = false;
	    } else {
		$("#input_invest_amount").text($("#input_invest_part_value").val() * $("#input_invest_amount_part").val());
		
		if ($("#input_invest_amount").text() != Math.floor($("#input_invest_amount").text())) {
		    $("#invest_error_integer").show();
		    bValidInput = false;
		}
		if (parseInt($("#input_invest_amount").text()) < $("#input_invest_min_value").val()) {
		    $("#invest_error_min").show();
		    bValidInput = false;
		}
		if (parseInt($("#input_invest_amount").text()) > $("#input_invest_max_value").val()) {
		    $("#invest_error_max").show();
		    bValidInput = false;
		}
		var nAmountInterval = $("#input_invest_max_value").val() - parseInt($("#input_invest_amount").text()); 		
		if (nAmountInterval < $("#input_invest_min_value").val() && nAmountInterval > 0) { 		
		    $("#invest_error_interval").show(); 		
		    bValidInput = false; 		
		}
	    }
	    if (bValidInput) {
		$("#invest_success_amount").text( parseInt($("#input_invest_amount_total").val()) + parseInt($("#input_invest_amount").text()));
		$(".invest_success").show();
	    }
	    
	    $("#input_invest_amount_part").css("color", bValidInput ? "green" : "red");
	    return bValidInput;
	},
	
	switchProfileTab: function(sType) {
	    var aTabs = ["activity", "following", "followers", "projects"];
	    for (var i = 0; i < aTabs.length; i++) {
		$("#item-body-" + aTabs[i]).hide();
		$("#item-submenu-" + aTabs[i]).removeClass("selected");
	    }
	    $("#item-body-" + sType).show();
	    $("#item-submenu-" + sType).addClass("selected");
	}
    }
})(jQuery);

YPMenuFunctions = (function($){
    return {
	initMenuBar: function() {
	    /*$("#menu_item_facebook").hover(function() {
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
	    });*/
	    
	    $("#menu_item_connection").mouseenter(function(){
		$("#submenu_item_connection").css("top", $("#navigation").position().top + $("#navigation").height());
		$("#submenu_item_connection").css("left", $("#menu_item_connection").position().left + $("#menu_item_connection").width() - $("#submenu_item_connection").width() - 1);
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

 
/* FORMULAIRE VOTE*/
YPVoteFormFunctions = (function($) {
    return {
	voteformcontrole:function() { 	
	    $("#impact-positif").click(function(){ 
		$("#impact-positif-content").show();
		$("#impact-negatif-content").hide();
	    });
	    $("#desaprouve").click(function(){
		$("#impact-positif-content").hide();
		$("#impact-negatif-content").show();
	    });

	    $("#pret").click(function(){ 
		$("#retravaille-content").hide(); 
		$("#pret-content").show();
	    }); 		
	    $("#retravaille").click(function(){
		$("#retravaille-content").show(); 
		$("#pret-content").hide();

	    });
    	}
    }   
})(jQuery);

/* FIN FORMULAIRE VOTE*/


/* J'Y CROIS*/
YPJycroisFunctions = (function($){
    return {
	loadJycrois: function() {

			$("#jcrois_pas").click(function () 
			{
		    	$("#tab-count-jycrois").load('single-campaign.php');
		    });


	        $("#jcrois").click(function()
			{
	        	$("#tab-count-jycrois").load('single-campaign.php');
	        });
	    }

	}
})(jQuery);
/* FIN J'Y CROIS */



   