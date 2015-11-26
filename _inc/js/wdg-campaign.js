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
			
			$("input.init_invest").change(function() {
				var inputVal = Number($(this).val());
				var percentProject = Number($("span.roi_percent_project").text());
				var goalProject = Number($("span.roi_goal_project").text());
				
				var ratioOfGoal = inputVal / goalProject;
				var amountOfGoal = 0;
				var ratioOfPercent = ratioOfGoal * percentProject;
				var ratioOfPercentRound = Math.round(ratioOfPercent * 10000) / 10000;
				
				$("span.roi_percent_user").text(ratioOfPercentRound);
				$("div.project-rewards-content table tr:first-child td span.hidden").each(function(index) {
					var estTO = Number($(this).text());
					var amountOfTO = estTO * ratioOfPercent / 100;
					amountOfGoal += amountOfTO;
					var amountOfTORound = Math.round(amountOfTO * 10000) / 10000;
					$("div.project-rewards-content table tr:last-child td span.roi_amount_user" + index).text(amountOfTORound);
				});
				var amountOfGoalRound = Math.round(amountOfGoal * 10000) / 10000;
				$("span.roi_amount_user").text(amountOfGoalRound);
			});
		},
	};
    
})(jQuery);