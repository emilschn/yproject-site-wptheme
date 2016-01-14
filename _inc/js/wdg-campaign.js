jQuery(document).ready( function($) {
    WDGProjectViewer.init();
    WDGProjectDescription.init();
});


var WDGProjectViewer = (function($) {
	return {
		init: function() {
			$(document).scroll(function() {
				if ($(document).scrollTop() > 100) {
					$("#content").addClass("scrolled");
				} else {
					$("#content").removeClass("scrolled");
				}
			});
			
			$("a.trigger-menu").click(function(e) {
				e.preventDefault();
				var target = $(this).data("target");
				if ($("#triggered-menu-" + target).hasClass("triggered")) {
					$("#triggered-menu-" + target).removeClass("triggered");
				} else {
					$("#triggered-menu-" + target).addClass("triggered");
				}
			});
			$("ul.menu-project li a").click(function(e) {
				e.preventDefault();
				var target = $(this).data("target");
				$('html, body').animate(
					{ scrollTop: $("div.project-" + target).offset().top - $("nav.project-navigation").height() },
					"slow"
				); 
			});
			$("div#content.version-3 nav.project-navigation ul.menu-actions li.login-item form select").change(function() {
				$(this).parent().submit();
			});
			$("a.update-follow").click(function(e) {
				e.preventDefault();
				if ($(this).data("following") === '1') {
					$(this).data("following", '0');
					$("a.update-follow span").text($(this).data("textfollow"));
				} else {
					$(this).data("following", '1');
					$("a.update-follow span").text($(this).data("textfollowed"));
				}
	   			$.ajax({
					'type' : "POST",
					'url' : ajax_object.ajax_url,
					'data': { 
						  'action':'update_jy_crois',
						  'jy_crois' : $(this).data("following"),
						  'id_campaign' : $("#content").data("campaignid")
						}
				}).done(function(){});
			});
			
			$("button.init_invest_count").click(function(e) {
				e.preventDefault();
			});
			$("input.init_invest").change(function() {
				var inputVal = Number($(this).val());
				var percentProject = Number($("input#roi_percent_project").val());
				var goalProject = Number($("input#roi_goal_project").val());
				
				var ratioOfGoal = inputVal / goalProject;
				var amountOfGoal = 0;
				var totalTurnover = 0;
				var nbYears = 0;
				var ratioOfPercent = ratioOfGoal * percentProject;
				var ratioOfPercentRound = Math.round(ratioOfPercent * 1000) / 1000;
				
				$("span.roi_percent_user").text(ratioOfPercentRound);
				$("div.project-rewards-content table tr:first-child td span.hidden").each(function(index) {
					nbYears++;
					var estTO = Number($(this).text());
					totalTurnover += estTO;
					var amountOfTO = estTO * ratioOfPercent / 100;
					amountOfGoal += amountOfTO;
					var amountOfTORound = Math.round(amountOfTO * 1000) / 1000;
					$("span.roi_amount_user" + index).text(amountOfTORound);
				});
				var amountOfGoalRound = Math.round(amountOfGoal * 1000) / 1000;
				$("span.roi_amount_user").text(amountOfGoalRound);
				var ratioOnInput = Math.round(amountOfGoalRound / inputVal * 100) / 100;
				$("span.roi_ratio_on_total").text(ratioOnInput);
				var averageROI = Math.round((Math.pow(( (percentProject / 100) * totalTurnover / goalProject), (1 / nbYears)) - 1) * 100 * 100) / 100;
				$("span.roi_percent_average").text(averageROI);
			});
		}
	};
    
})(jQuery);

//TODO : utiliser pour supprimer de common.js à terme
var WDGProjectDescription = (function($) {
	return {
		init: function() {
		}
	};
    
})(jQuery);