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
	
			// Initialisation des divs
	        $("#phase1").attr('style','display:block;');
	        $("#phase2").attr('style','display:none;');
		    $("#phase3").attr('style','display:none;');

			$("#wdg-lightbox-voteform-validated").attr('style','display:block !important;');

		 	var phase=0;

			$("#hide_except_div1").attr("onclick","hide_except_div1()");
			$("#hide_except_div2_phase1").attr("onclick","hide_except_div2()");
			$("#hide_except_div2_phase3").attr("onclick","hide_except_div2()");
			$("#hide_except_div3").attr("onclick","hide_except_div3()");
			$("#btn-validate_project-true-v3").attr("onclick","see_dev_true()");
			$("#btn-validate_project-false-v3").attr("onclick","see_dev_false()");
			$("#vote-form-v3-link").attr("onclick","see_div1()");
			$("#vote-form-v3-link-responsive").attr("onclick","see_div1()");
			$("#hide_except_div1").attr("onclick","hide_except_div1()");
			$("#hide_except_div1").attr("onclick","hide_except_div1()");
			$("#go-block1").attr("onclick","hide_except_div1()");
			$("#go-block2").attr("onclick","hide_except_div2()");
			$("#go-block3").attr("onclick","hide_except_div3()");

			hide_div = function(idok,id1,id2)
			{
				if(idok=='#phase1'){
 					if($("#phase2").css('display') == 'none'){
						phase=0;
					}
	  				if (1 > phase)
	  				{
						$(idok).attr('class','left');
	  				}else{
						$(idok).attr('class','right');
	  				}
	  				phase=1;    
			       $('#go-block1').attr('style','color: #EA4F51 !important; font-size: 200%;');
			       $('#go-block2').attr('style','color: #333333 !important;');
			       $('#go-block3').attr('style','color: #333333 !important;');
				};
				if(idok == '#phase2'){
	  				if (2 > phase)
	  				{
						$(idok).attr('class','left');
	  				}else{
						$(idok).attr('class','right');
	  				}
	  				phase = 2;
			       $('#go-block1').attr('style','color: #333333 !important;');
			       $('#go-block2').attr('style','color: #EA4F51 !important; font-size: 200%;');
			       $('#go-block3').attr('style','color: #333333 !important; ');
				};
				if(idok == '#phase3'){
	  				if (3 > phase)
	  				{
						$(idok).attr('class','left');
	  				}else{
						$(idok).attr('class','right');
	  				}
	  				phase = 3;		  				
			       $('#go-block1').attr('style','color: #333333 !important;');
			       $('#go-block2').attr('style','color: #333333 !important;');
			       $('#go-block3').attr('style','color: #EA4F51 !important; font-size: 200%;');
				};
		    	$(idok).attr('style','display:block;');
		    	$(id1).attr('style','display:none;');
		    	$(id2).attr('style','display:none;');

			};

			see_div1 = function()
			{
				hide_div('#phase1','#phase2','#phase3');
			};


			hide_except_div1 = function()
			{
				function wait(){
					hide_div('#phase1','#phase2','#phase3');
			   	};
				$("#phase2").attr('class','left_disappearance')
				$("#phase3").attr('class','left_disappearance')
			   	window.setTimeout( wait, 400 ); 
			};

			hide_except_div2 = function()
			{
				function wait(){
					hide_div('#phase2','#phase1','#phase3');
			   	};
			   	if(phase==3)
			  	 	$("#phase3").attr('class','left_disappearance')
			   	if(phase==1)
			  	 	$("#phase1").attr('class','right_disappearance')
			   window.setTimeout( wait, 400 ); 
			};

			hide_except_div3 = function()
			{
				function wait(){
					hide_div('#phase3','#phase1','#phase2');
			   	};
			   	$("#phase2").attr('class','right_disappearance')
			   	$("#phase1").attr('class','right_disappearance')
			   window.setTimeout( wait, 400 ); 
			};

			see_dev_true = function()
			{
				$('#validate_project-true').attr('style','display:block;');
				$('#validate_project-true_sum').attr('style','display:block;');
				$('#validate_project-false').attr('style','display:none;');
			};

			see_dev_false = function ()
			{
				$('#validate_project-true').attr('style','display:none;');
				$('#validate_project-true_sum').attr('style','display:none;');
				$('#validate_project-false').attr('style','display:block;');
			};


			
			display_range1 = function (newVal){
				$('span#valBox1_1').attr('style','display:none;');
				$('span#valBox1_2').attr('style','display:none;');
				$('span#valBox1_3').attr('style','display:none;');
				$('span#valBox1_4').attr('style','display:none;');
				$('span#valBox1_5').attr('style','display:none;');
				if(newVal==1)
					$('span#valBox1_1').attr('style','display:inline-block;');
				if(newVal==2)
					$('span#valBox1_2').attr('style','display:inline-block;');
				if(newVal==3)
					$('span#valBox1_3').attr('style','display:inline-block;');
				if(newVal==4)
					$('span#valBox1_4').attr('style','display:inline-block;');
				if(newVal==5)
					$('span#valBox1_5').attr('style','display:inline-block;');
			};


			display_range2 = function (newVal){
				$('span#valBox2_1').attr('style','display:none;');
				$('span#valBox2_2').attr('style','display:none;');
				$('span#valBox2_3').attr('style','display:none;');
				$('span#valBox2_4').attr('style','display:none;');
				$('span#valBox2_5').attr('style','display:none;');
				if(newVal==1)
					$('span#valBox2_1').attr('style','display:inline-block;');
				if(newVal==2)
					$('span#valBox2_2').attr('style','display:inline-block;');
				if(newVal==3)
					$('span#valBox2_3').attr('style','display:inline-block;');
				if(newVal==4)
					$('span#valBox2_4').attr('style','display:inline-block;');
				if(newVal==5)
					$('span#valBox2_5').attr('style','display:inline-block;');
			};


			display_range3 = function(newVal){
				$('span#valBox3_1').attr('style','display:none;');
				$('span#valBox3_2').attr('style','display:none;');
				$('span#valBox3_3').attr('style','display:none;');
				$('span#valBox3_4').attr('style','display:none;');
				$('span#valBox3_5').attr('style','display:none;');
				if(newVal==1)
					$('span#valBox3_1').attr('style','display:inline-block;');
				if(newVal==2)
					$('span#valBox3_2').attr('style','display:inline-block;');
				if(newVal==3)
					$('span#valBox3_3').attr('style','display:inline-block;');
				if(newVal==4)
					$('span#valBox3_4').attr('style','display:inline-block;');
				if(newVal==5)
					$('span#valBox3_5').attr('style','display:inline-block;');
			};


			display_range4 = function(newVal){
				$('span#valBox4_1').attr('style','display:none;');
				$('span#valBox4_2').attr('style','display:none;');
				$('span#valBox4_3').attr('style','display:none;');
				$('span#valBox4_4').attr('style','display:none;');
				$('span#valBox4_5').attr('style','display:none;');
				if (newVal == 1){
					$('span#valBox4_1').attr('style','display:inline-block;');
				};
				if (newVal == 2){
					$('span#valBox4_2').attr('style','display:inline-block;');
				};					
				if (newVal == 3){
					$('span#valBox4_3').attr('style','display:inline-block;');
				};	
				if (newVal == 4){
					$('span#valBox4_4').attr('style','display:inline-block;');
				};
				if (newVal == 5){
					$('span#valBox4_5').attr('style','display:inline-block;');
				};
			};

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

//TODO : utiliser pour supprimer de common.js à terme
var WDGProjectDescription = (function($) {
	return {
		init: function() {
		}
	};
    
})(jQuery);