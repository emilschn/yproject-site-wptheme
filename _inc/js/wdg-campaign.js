jQuery(document).ready( function($) {
    WDGProjectViewer.init();
	WDGProjectVote.init();
});


var WDGProjectViewer = (function($) {
	return {
		aProjectParts: ["news", "description", "rewards", "banner"],
		nProjectParts: 4,
		
		init: function() {
			$(document).scroll(function() {
				if ($(document).scrollTop() > 600) {
					$("#content, nav#main").addClass("scrolled");
				} else {
					$("#content, nav#main").removeClass("scrolled");
				}
				WDGProjectViewer.refreshScroll();
			});
			WDGProjectViewer.refreshScroll();
			
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
					{ scrollTop: $("div.project-" + target).offset().top - 100 },
					"slow"
				); 
			});
			$("div#content.version-3 div.project-banner div.project-banner-title form select").change(function() {
				$(this).parent().submit();
			});
			$("a.update-follow").click(function(e) {
				e.preventDefault();
				if ($(this).data("following") == '1') {
					$(this).data("following", '0');
					$("a.update-follow span").text($(this).data("textfollow"));
					$("a.update-follow").removeClass("btn-followed");
				} else {
					$(this).data("following", '1');
					$("a.update-follow span").text($(this).data("textfollowed"));
					$("a.update-follow").addClass("btn-followed");
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
				if ($(".project-rewards-padder div.hidden").length > 0) {
					$(".project-rewards-padder div.hidden").show();
					
				} else {
					var inputVal = Number($(this).val());
					if (isNaN(inputVal) || inputVal < 0) inputVal = 0;
					var percentProject = Number($("input#roi_percent_project").val());
					var goalProject = Number($("input#roi_goal_project").val());

					var ratioOfGoal = inputVal / goalProject;
					var amountOfGoal = 0;
					var totalTurnover = 0;
					var nbYears = 0;
					var ratioOfPercent = ratioOfGoal * percentProject;
					var ratioOfPercentRound = Math.round(ratioOfPercent * 10000) / 10000;
					var ratioOfPercentRoundStr = ratioOfPercentRound.toString().replace('.', ',');
					$("span.roi_percent_user").text(ratioOfPercentRoundStr);

					$("div.project-rewards-content table tr:first-child td span.hidden").each(function(index) {
						nbYears++;
						var estTO = Number($(this).text());
						totalTurnover += estTO;
						var amountOfTO = estTO * ratioOfPercent / 100;
						amountOfGoal += amountOfTO;
						var amountOfTORound = Math.round(amountOfTO * 100) / 100;
						var amountOfTORoundStr = amountOfTORound.toString().replace('.', ',');
						$("span.roi_amount_user" + index).text(amountOfTORoundStr);
					});
					var amountOfGoalRound = Math.round(amountOfGoal * 100) / 100;
					var amountOfGoalRoundStr = amountOfGoalRound.toString().replace('.', ',');
					$("span.roi_amount_user").text(amountOfGoalRoundStr);
					var ratioOnInput = Math.round(amountOfGoalRound / inputVal * 100) / 100;
					var ratioOnInputStr = isNaN(ratioOnInput) ? '...' : ratioOnInput.toString().replace('.', ',');
					$("span.roi_ratio_on_total").text(ratioOnInputStr);
					var roiPercentTotal = Math.round( ( ( amountOfGoalRound / inputVal ) - 1 ) * 100 * 100 ) / 100;
					var roiPercentTotalStr = isNaN(roiPercentTotal) ? '...' : roiPercentTotal.toString().replace('.', ',');
					$("span.roi_percent_total").text(roiPercentTotalStr);
				}
			});

		},
		
		refreshScroll: function() {
			if ($("div#content.version-3 nav.project-navigation").length > 0) {
				$("div#content.version-3 nav.project-navigation ul li a").removeClass("selected");
				for (i = 0; i < WDGProjectViewer.nProjectParts; i++) {
					if ($(document).scrollTop() >= $("div.project-" + WDGProjectViewer.aProjectParts[i]).offset().top - $("nav#navigation").height() - $("nav.project-navigation").height()) {
						$("div#content.version-3 nav.project-navigation ul li a#target-" + WDGProjectViewer.aProjectParts[i]).addClass("selected");
						break;
					}
				}
			}
		}
	};
    
})(jQuery);

var WDGProjectVote = (function($) {
	return {
		currentSlide: 1,
		minSlide: 1,
		maxSlide: 3,
		init: function() {
			if ( $( 'div#vote-form' ).length > 0 ) {
				$( 'div#vote-form div#vote-form-buttons button.left' ).click( function( e ) {
					e.preventDefault();
					WDGProjectVote.slidePrevious();
				} );
				$( 'div#vote-form div#vote-form-buttons button.right' ).click( function( e ) {
					e.preventDefault();
					WDGProjectVote.slideNext();
				} );
			}
		},
		
		slidePrevious: function() {
			WDGProjectVote.currentSlide = Math.max( WDGProjectVote.minSlide, WDGProjectVote.currentSlide - 1 );
			WDGProjectVote.refresh();
		},
		
		slideNext: function() {
			WDGProjectVote.currentSlide = Math.min( WDGProjectVote.maxSlide, WDGProjectVote.currentSlide + 1 );
			WDGProjectVote.refresh();
		},
		
		refresh: function() {
			$( 'div.vote-form-slide' ).hide();
			$( 'div#vote-form-slide' + WDGProjectVote.currentSlide ).show();
			$( 'div#vote-form div#vote-form-buttons button' ).show();
			if ( WDGProjectVote.currentSlide === WDGProjectVote.minSlide ) {
				$( 'div#vote-form div#vote-form-buttons button.left' ).hide();
			}
			if ( WDGProjectVote.currentSlide === WDGProjectVote.maxSlide ) {
				$( 'div#vote-form div#vote-form-buttons button.right' ).hide();
			}
		}
	};
    
})(jQuery);