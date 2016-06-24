jQuery(document).ready( function($) {
    WDGProjectViewer.init();
    WDGProjectDescription.init();
});


var WDGProjectViewer = (function($) {
	return {
		aProjectParts: ["news", "description", "rewards", "banner"],
		nProjectParts: 4,
		
		init: function() {
			$(document).scroll(function() {
				if ($(document).scrollTop() > 100) {
					$("#content, #navigation").addClass("scrolled");
				} else {
					$("#content, #navigation").removeClass("scrolled");
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
					{ scrollTop: $("div.project-" + target).offset().top - $("nav.project-navigation").height() },
					"slow"
				); 
			});
			$("div#content.version-3 nav.project-navigation ul.menu-actions li.lang-item form select").change(function() {
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
					var ratioOfPercentRound = Math.round(ratioOfPercent * 1000) / 1000;
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
					var averageROI = Math.round((Math.pow(( (percentProject / 100) * totalTurnover / goalProject), (1 / nbYears)) - 1) * 100 * 100) / 100;
					var averageROIStr = averageROI.toString().replace('.', ',');
					$("span.roi_percent_average").text(averageROIStr);
				}
			});

		 	var phase=0;

			masquer_div = function(idok,id1,id2)
			{
				if(idok=='#phase1'){
	  				if (1>phase)
	  				{
						$(idok).attr('class','left');
	  				}else{
						$(idok).attr('class','right');
	  				}
	  				phase=1;
				};
				if(idok=='#phase2'){
	  				if (2>phase)
	  				{
						$(idok).attr('class','left');
	  				}else{
						$(idok).attr('class','right');
	  				}
	  				phase=2;
				};
				if(idok=='#phase3'){
	  				if (3>phase)
	  				{
						$(idok).attr('class','left');
	  				}else{
						$(idok).attr('class','right');
	  				}
	  				phase=3;		  				
				};
		       $(idok).attr('style','display:block;');
		       $(id1).attr('style','display:none;');
		       $(id2).attr('style','display:none;');

			};

			masquer_sauf_div1 = function()
			{
				masquer_div('#phase1','#phase2','#phase3');
			};

			masquer_sauf_div2 = function()
			{
				masquer_div('#phase2','#phase1','#phase3');
			};

			masquer_sauf_div3 = function()
			{
				masquer_div('#phase3','#phase1','#phase2');
			};

			afficher_div_true = function()
			{
				$('#validate_project-true').attr('style','display:block;');
				$('#validate_project-false').attr('style','display:none;');
			};

			afficher_div_false = function ()
			{
				$('#validate_project-true').attr('style','display:block;');
				$('#validate_project-false').attr('style','display:none;');
			};
			
			AfficheRange1 = function (newVal){
				val='';
				if(newVal==1)
					val='Très peu';
				if(newVal==2)
					val='Peu';
				if(newVal==3)
					val='Moyen';
				if(newVal==4)
					val='Bon';
				if(newVal==5)
					val='Très bon';
	 			$('span#valBox1').html(val);
			};

			AfficheRange2 = function (newVal){
				val='';
				if(newVal==1)
					val='Très peu';
				if(newVal==2)
					val='Peu';
				if(newVal==3)
					val='Moyen';
				if(newVal==4)
					val='Bon';
				if(newVal==5)
					val='Très bon';
	 			$('span#valBox2').html(val);
			};


			AfficheRange3 = function(newVal){
				val='';
				if(newVal==1)
					val='Très peu';
				if(newVal==2)
					val='Peu';
				if(newVal==3)
					val='Moyen';
				if(newVal==4)
					val='Bon';
				if(newVal==5)
					val='Très bon';
				$('#valBox3').html(val);
			};

			AfficheRange4 = function(newVal){
				var resultat='';
				if (newVal == 1){
					resultat = 'tr&egrave;s faible';
				};
				if (newVal == 2){
					resultat = 'plut&ocirc;t faible';
				};					
				if (newVal == 3){
					resultat = 'mod&eacute;r&eacute;';
				};	
				if (newVal == 4){
					resultat = '&eacute;lev&eacute;';
				};
				if (newVal == 5){
					resultat = 'tr&egrave;s &eacute;lev&eacute;';
				};
				$("span#valBox4").html(resultat);
				};
		},
		
		refreshScroll: function() {
			$("div#content.version-3 nav.project-navigation ul li a").removeClass("selected");
			for (i = 0; i < WDGProjectViewer.nProjectParts; i++) {
				if ($(document).scrollTop() >= $("div.project-" + WDGProjectViewer.aProjectParts[i]).offset().top - $("nav#navigation").height() - $("nav.project-navigation").height()) {
					$("div#content.version-3 nav.project-navigation ul li a#target-" + WDGProjectViewer.aProjectParts[i]).addClass("selected");
					break;
				}
			}
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