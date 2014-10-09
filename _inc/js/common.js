jQuery(document).ready( function($) {
    YPUIFunctions.initUI();
});


YPUIFunctions = (function($) {
	return {
		memberTabs: ["activity", "projects", "community"],
	    
		initUI: function() {
			YPMenuFunctions.initMenuBar();
			WDGProjectPageFunctions.initUI();
			
			$(document).scroll(function() {
				if ($(document).scrollTop() > 110) {
					$(".page_item_logo a").children().eq(0).hide();
					$(".page_item_logo a").children().eq(1).show();
					$(".page_item_logo").height(51);
					$("#nav").height(50);
					$("#nav > li").css("paddingTop", 20);
					$("#nav > li").height(30);
					$("#nav #menu_item_facebook, #nav #menu_item_twitter").css("paddingTop", 17);
					$(".page_item_inverted").css("paddingBottom", 0);
				} else {
					$(".page_item_logo a").children().eq(0).show();
					$(".page_item_logo a").children().eq(1).hide();
					$(".page_item_logo").height(100);
					$("#nav").height(100);
					$("#nav > li").css("paddingTop", 50);
					$("#nav > li").height(50);
					$("#nav #menu_item_facebook, #nav #menu_item_twitter").css("paddingTop", 47);
					$(".page_item_logo").css("paddingTop", 0);
					$(".page_item_inverted").css("paddingBottom", 7);
				}
			});

			if ($("#finish_subscribe").length > 0) {		
			    $("#container").css('padding-top', "55px");		
			}
			
			$(".expandator").css("cursor", "pointer");
			$(".expandable").hide();
			$(".expandator").click(function() {
				var targetId = $(this).data("target");
				if ($("#extendable-" + targetId).is(":visible")) $("#extendable-" + targetId).hide();
				else $("#extendable-" + targetId).show();
			});

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

			if ($(".wp-editor-wrap")[0]) {
			    setInterval(YPUIFunctions.onRemoveUploadInterval, 1000);
			}

			if ($(".home-large-project").length > 0) {
			    $(".home-large-project").each(function() {
				var descdiv_elmt = $(this).find(".description-zone");
				var descsum_elmt = $(this).find(".description-summary");
				var descdisc_elmt = $(this).find(".description-discover");
				var videodiv_elmt = $(this).find(".video-zone");
				var descmiddiv_elmt = $(this).find(".description-middle");
				var iframe_elmt = $(this).find(".video-zone>iframe");
				if (iframe_elmt.length > 0) $(descdiv_elmt).height($(iframe_elmt).height());
				else $(descdiv_elmt).height($(videodiv_elmt).height());
				var remainheight = $(descdiv_elmt).height() - $(descsum_elmt).height() - $(descdisc_elmt).height();
				$(descmiddiv_elmt).css("top", $(descsum_elmt).height() - $(descmiddiv_elmt).height() / 2 + remainheight / 2);
			    });
			}
			if ($(".home-activity-list").length > 0) {
			    setTimeout(function() {YPUIFunctions.onSlideHomeActivity(); }, YPUIFunctions.homeslideInterval);
			}
			if ($(".home-blog-list-nav").length > 0) {
			    $(".home-blog-list-nav a").click(function() {
				$(".home-blog-list-nav a").removeClass("selected");
				$(this).addClass("selected");
				$(".home-blog-list").animate(
				    { marginLeft: - $(this).data('targetitem') * YPUIFunctions.homeblogItemWidth}, 
				    500
				);
			    });
			}

			if ($("#scroll-to-utilite-societale").length > 0) {
			    $("#scroll-to-utilite-societale").click(function() {
			       $('html, body').animate({scrollTop: $('#anchor-social').offset().top - $("#navigation").height()}, "slow"); 
			    });
			}
 	
			if ($("#user-id").length) { 
			    YPUIFunctions.getProjects(); 
			}
			
			if ($("#item-submenu").length > 0) {
				$("#item-submenu").children().each(function() {
					$(this).click(function() {
						var sId = $(this).attr("id");
						sId = sId.split("-").pop();
						YPUIFunctions.switchProfileTab(sId);
					});
				});
			}
		},
		
		getProjects: function() {// Permet de récupérer tous les projets ou un utilisateur est impliqué
			var userID = $('#user-id').attr('data-value');

			//Requete pour obtenir les projets
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': { 
				    'user_id': userID,
				    'action' : 'print_user_projects'
				}
			}).done(function(result){
				//Une fois les projets obtenus
				$('#ajax-loader').after(result);// On insert le résultat après la roue de chargement.
				$("#item-body-projects").height("auto");
				$('#ajax-loader-img').hide();//On cache la roue de chargement.
				YPUIFunctions.togglePayments();//On cache tous les paiements effectués et on affiche Détails des paiements
				$(".history-projects").each(function(){//On cache chaque projet
					$(this).hide();
				});
				function filterProjects(){
					var o = new Object();
					var tab = [ "jycrois", "invested","voted"];
					$('#filter-projects :checkbox').each(function(){//On regarde quelles sont les checkbox cochées
						if(this.checked){// Si elle est cochée, on met un "1"
							o[this.value]=1;
						}
						else{//Sinon un "0"
							o[this.value]=0;
						}
						$(".history-projects").each(function(){// On affiche les projets selon les checkbox cochées
							var show_project=false;
							for (var i=0;i<=tab.length;i++){
								if($(this).attr('data-'+tab[i])==1&&o[tab[i]]==1){//Exemple : L'utilisateur crois au projet  -> data-jycrois=1 (dans le HTML) et J'y crois est coché
									show_project=true;
								}
							}
							if(show_project){// On affiche le projet s'il n'est pas déja visible
								if (! $(this).is(':visible') ){
									$(this).show();
								}
							} else {
								if ($(this).is(':visible') ){// On cache le projet s'il n'est pas déja caché
									$(this).hide();
								}
							}
						});
					});

				}
				filterProjects();// On applique cette fonction une première fois afin d'afficher les projets investi
				$('#filter-projects :checkbox').change(function() {// On met un listener sur les checkbox
					filterProjects();
				});
			});
		},

		onRemoveUploadInterval: function() {
			if ($(".media-frame-menu")[0]) $(".media-frame-menu").remove();
			if ($(".media-frame-router")[0]) $(".media-frame-router").show();
		},

		homeblogItemWidth: 570,
		homeslideItemWidth: 960,
		homeslideInterval: 3000,
		onSlideHomeActivity: function() {
			var currentMargin = parseInt($(".home-activity-list").css("margin-left").replace("px", ""));
			currentMargin -= YPUIFunctions.homeslideItemWidth;
			if ($(".home-activity-list").width() < (currentMargin * -1 + 1)) currentMargin = 0;
			$(".home-activity-list").animate(
			    { marginLeft: currentMargin}, 
			    500
			);

			setTimeout(function() {YPUIFunctions.onSlideHomeActivity(); }, YPUIFunctions.homeslideInterval);
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
			for (var i = 0; i < YPUIFunctions.memberTabs.length; i++) {
			    $("#item-body-" + YPUIFunctions.memberTabs[i]).hide();
			    $("#item-submenu-" + YPUIFunctions.memberTabs[i]).removeClass("selected");
			}
			$("#item-body-" + sType).show();
			$("#item-submenu-" + sType).addClass("selected");
		},

		togglePayments: function(){
			$('.user-history-payments-list').each(function(){
				$(this).hide();
			});
			$('.history-projects').each(function(){
				$(this).find('.show-payments').each(function(){
					$(this).css("cursor", "pointer");
					$(this).click(function(){
						campaign_id=$(this).attr('data-value');
						$('.history-projects').each(function(){
							if($(this).attr('data-value')===campaign_id){
								$(this).find('.user-history-payments-list').show(400);
							}
							else{
								$(this).find('.user-history-payments-list').hide(400);
							}
						});
					});
				});
			});
		}
	}
    
})(jQuery);

YPMenuFunctions = (function($){
    return {
	initMenuBar: function() {
	    $("#menu_item_connection").mouseenter(function(){
		$("#submenu_item_connection").css("top", $(document).scrollTop() + $("#navigation").height());
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
		$("#share_btn_zone").show();
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

/* Projet */
WDGProjectPageFunctions=(function($) {
	return {
		currentDiv:0,
		initUI:function() {
			// $('.projects-desc-content').each(function(){WDGProjectPageFunctions.initClick(this)});
			// $('.project-content-icon').click(function(){
			// 	var contentDiv = $("#project-content-" + $(this).data("content"));
			// 	contentDiv.trigger("click"); 	
			// });
var moreText = "Lire plus...";
$('.more').hide();
$('.indent').find('.readmore').click(function(event){
	event.preventDefault();
			    //Expand or collapse this panel
			    $(this).parent().next().slideToggle('fast');
			    $(this).hide();
			    $('.indent').find('.readless').show();
			    //Hide the other panels
			    //$(".more").not($(this).parent().next()).slideUp('fast');
			});

WDGProjectPageFunctions.save_image_home();
WDGProjectPageFunctions.save_image();
WDGProjectPageFunctions.display_tooltips();
$('#tabs')
.tabs()
.addClass('ui-tabs-vertical ui-helper-clearfix');

			//$('#image_home').on('change', WDGProjectPageFunctions.save_image_home());

			$('.project-content-icon').css("cursor", "pointer");  

			$("#btn-validate_project-true").click(function(){ 
				$("#validate_project-true").show();
				$("#validate_project-false").hide();
				$("#project-description-title-padding").height($("#vote-form").height() - $("#projects-right-desc").height());
			});
			$("#btn-validate_project-false").click(function(){ 
				$("#validate_project-false").show();
				$("#validate_project-true").hide();
				$("#project-description-title-padding").height($("#vote-form").height() - $("#projects-right-desc").height());
			});

			$("#jcrois_pas").click(function () {
				$("#tab-count-jycrois").load('single-campaign.php');
			});

			$("#jcrois").click(function() {
				$("#tab-count-jycrois").load('single-campaign.php');
			});


			$(".edit_description a[data-action]").on("click", function (event) {
				var link = $(this),
				action = link.data("action");

				event.preventDefault();

			  // If there's an action with the given name, call it
			  if( typeof WDGProjectPageFunctions[action] === "function" ) {
			  	WDGProjectPageFunctions[action].call(this, event);
			  }
			});

			$(".edit_video a[data-action]").on("click", function (event) {
				var link = $(this),
				action = link.data("action");

				event.preventDefault();

			  // If there's an action with the given name, call it
			  if( typeof WDGProjectPageFunctions[action] === "function" ) {
			  	WDGProjectPageFunctions[action].call(this, event);
			  }
			});

			$(".edit_projects a[data-action]").on("click", function (event) {
				var link = $(this),
				action = link.data("action");

				event.preventDefault();

			  // If there's an action with the given name, call it
			  if( typeof WDGProjectPageFunctions[action] === "function" ) {
			  	WDGProjectPageFunctions[action].call(this, event);
			  }
			});


			$(".edit_societal a[data-action]").on("click", function (event) {
				var link = $(this),
				action = link.data("action");

				event.preventDefault();

			  // If there's an action with the given name, call it
			  if( typeof WDGProjectPageFunctions[action] === "function" ) {
			  	WDGProjectPageFunctions[action].call(this, event);
			  }
			});

			$(".edit_economy a[data-action]").on("click", function (event) {
				var link = $(this),
				action = link.data("action");

				event.preventDefault();

			  // If there's an action with the given name, call it
			  if( typeof WDGProjectPageFunctions[action] === "function" ) {
			  	WDGProjectPageFunctions[action].call(this, event);
			  }
			});

			$(".edit_model a[data-action]").on("click", function (event) {
				var link = $(this),
				action = link.data("action");

				event.preventDefault();

			  // If there's an action with the given name, call it
			  if( typeof WDGProjectPageFunctions[action] === "function" ) {
			  	WDGProjectPageFunctions[action].call(this, event);
			  }
			});

			$(".edit_name a[data-action]").on("click", function (event) {
				var link = $(this),
				action = link.data("action");

				event.preventDefault();

			  // If there's an action with the given name, call it
			  if( typeof WDGProjectPageFunctions[action] === "function" ) {
			  	WDGProjectPageFunctions[action].call(this, event);
			  }
			});

			$(".edit_members a[data-action]").on("click", function (event) {
				var link = $(this),
				action = link.data("action");

				event.preventDefault();

			  // If there's an action with the given name, call it
			  if( typeof WDGProjectPageFunctions[action] === "function" ) {
			  	WDGProjectPageFunctions[action].call(this, event);
			  }
			});

		},
		move_picture:function(campaign_id) {
			$('#img-container').draggable({
				axis: "y"
		    }); // appel du plugin
			$('#img-container').draggable('enable');
			$('#reposition-cover').text('Sauvegarder');
			$('#reposition-cover').attr("onclick", "WDGProjectPageFunctions.save_position("+campaign_id+")");
			$("#head-content").css({ opacity: 0 });
			$("#head-content").css({ 'z-index': -1 });
		},

		save_position:function(campaign_id){
			$("#head-content").css({ opacity: 1 });
			$("#head-content").css({ 'z-index': 2 });
			$('#img-container').draggable('disable');
			$('#reposition-cover').text('Repositionner');
			$('#reposition-cover').attr("onclick", "WDGProjectPageFunctions.move_picture("+campaign_id+")");
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': { 
					'action':'setCoverPosition',
					'top' : $('#img-container').css('top'),
					'id_campaign' : campaign_id
				}
			}).done()
		},

		move_cursor:function(campaign_id){
			$('#move-cursor').text('Sauvegarder la position du curseur');
			$('#move-cursor').attr("onclick", "WDGProjectPageFunctions.save_cursor_position("+campaign_id+")");
			$('#map-cursor').draggable({
				containment: '#project-map'
			});
			$('#map-cursor').draggable('enable');
		},

		save_cursor_position:function(campaign_id){
			$('#move-cursor').text('Modifier la position du curseur');
			$('#move-cursor').attr("onclick", "WDGProjectPageFunctions.move_cursor("+campaign_id+")");
			$('#map-cursor').draggable('disable');
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': { 
					'action':'setCursorPosition',
					'top' : $('#map-cursor').css('top'),
					'left' : $('#map-cursor').css('left'),
					'id_campaign' : campaign_id
				}
			}).done(); 
		},

		update_jy_crois:function(jy_crois,campaign_id,home_url){
			var img_url=home_url+'/images/';
			if(jy_crois==0) {
				jy_crois_temp=1;
				img_url+='grenage_projet.jpg';
				$('#jy-crois-btn').css('background-image','url("'+img_url+'")');
				$('#jy-crois-txt').text('J\'y crois');
			}else{
				jy_crois_temp=0;
				img_url+='jycrois_gris.png';
				$('#jy-crois-txt').text('');
				$('#jy-crois-btn').css('background-image','url("'+img_url+'")');
			}
			var actual_text=$('#nb-jycrois').text();
			if (jy_crois==1) {
				$('#nb-jycrois').text(parseInt(actual_text)+1);
			}
			else{
				$('#nb-jycrois').text(parseInt(actual_text)-1);
			}
			$('.jy-crois').attr("href", "javascript:WDGProjectPageFunctions.update_jycrois("+jy_crois_temp+","+campaign_id+",\""+home_url+"\")");
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': { 
					'action':'update_jy_crois',
					'jy_crois' : jy_crois,
					'id_campaign' : campaign_id
				}
			}).done(function(){});
		},

		/******** NAME ********/

		edit_name:   function (event) { 
			$(this).hide();
			$(".view-name-content").hide();
			$(".edit-name-field").show();
			$(".tooltips").show();
			$(this).next().show();
		},

		cancel_name: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-name-field").hide();
			$(".view-name-content").show();
			$(".tooltips").hide();
		},

		save_name: function (event) {  
			campaign_id = $('*[data-action="save_name"]').data('campaign');
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': 
				{ 
					'action':'update_name',
					'wpProjectId' : campaign_id,
					'projectName' : $("#projectName").val(),
					'projectSlogan' : $("#projectSlogan").val(),
				}
			}).done(function(data){
				var obj = $.parseJSON(data);
				if (obj.project_name == "") { $(".project_name").html("&nbsp;") } else { $(".project_name").empty().text(obj.project_name)};
				if (obj.project_slogan == "") { $(".project_slogan").html("&nbsp;") } else { $(".project_slogan").empty().text(obj.project_slogan)};

				$(".edit-name-field").hide();
				$(".view-name-content").show();
				$(".tooltips").hide();
			});
			$(this).parent().hide();
			$(this).parent().prev().show();
		},


		/******** DESCRIPTION ********/

		edit_description:   function (event) { 
			$(this).hide();
			$(".view-description-content").hide();
			$(".edit-description-field").show();
			$(".tooltips").show();
			$(this).next().show();
		},

		cancel_description: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-description-field").hide();
			$(".view-description-content").show();
			$(".tooltips").hide();
		},

		save_description: function (event) {  
			campaign_id = $('*[data-action="save_description"]').data('campaign');
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': 
				{ 
					'action':'update_description',
					'wpProjectId' : campaign_id,
					'projectDescription' : $("#projectDescription").val()
				}
			}).done(function(data){
				var obj = $.parseJSON(data);
				console.log(obj);
				if (obj.project_description == "") { $(".project_description").html("&nbsp;") } else { $(".project_description").text(obj.project_description)};
				$(".edit-description-field").hide();
				$(".view-description-content").show();
				$(".tooltips").hide();
			});
			$(this).parent().hide();
			$(this).parent().prev().show();
		},

		/******** VIDEO ********/

		edit_video:   function (event) { 
			$(this).hide();
			$(".view-video-content").hide();
			$(".edit-video-field").show();
			$(".tooltips").show();
			$(this).next().show();
		},

		cancel_video: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-video-field").hide();
			$(".view-video-content").show();
			$(".tooltips").hide();
		},

		save_video: function (event) {  
			campaign_id = $('*[data-action="save_video"]').data('campaign');
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': 
				{ 
					'action':'update_video',
					'wpProjectId' : campaign_id,
					'projectVideo' : $("#projectVideo").val(),
				}
			}).done(function(data){
				var obj = $.parseJSON(data);
				if (obj.project_video == "") { $(".project_video").html("&nbsp;") } else { $(".project_video").text(obj.project_video)};
				$("#video-project").html("Rechargez la page pour afficher la video");
				$(".edit-video-field").hide();
				$(".view-video-content").show();
				$(".tooltips").hide();
			});
			$(this).parent().hide();
			$(this).parent().prev().show();
		},


		/******** EN QUOI CONSISTE LE PROJET ********/

		edit_project:   function (event) { 
			$(this).hide();
			$(".view-projects-content").hide();
			$(".edit-projects-field").show();
			$(".tooltips").show();
			$(this).next().show();
		},

		cancel_project: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-projects-field").hide();
			$(".view-projects-content").show();
			$(".tooltips").hide();
		},

		save_project: function (event) {  
			console.log($("#project_summary_ifr").contents().find('#tinymce').html() );
			campaign_id = $('*[data-action="save_project"]').data('campaign');
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': 
				{ 
					'action':'update_project',
					'wpProjectId' : campaign_id,
					'projectCategory' : $('select[name=projectCategory]').val(),
					'projectBusinessSector' : $('select[name=projectBusinessSector]').val(),
					'projectFundingType' : $('select[name=projectFundingType]').val(),
					'projectFundingDuration' : $("#projectFundingDuration").val(),
					'projectReturnOnInvestment' :$("#projectReturnOnInvestment").val(),
					'projectInvestorBenefit' : $("#projectInvestorBenefit").val(),
					'projectSummary' : $("#project_summary_ifr").contents().find('#tinymce').html()

				}
			}).done(function(data){
				var obj = $.parseJSON(data);

				if (obj.project_category == "") { $(".project_category").html("&nbsp;") } else { $(".project_category").text(obj.project_category)};
				if (obj.project_business_sector == "") { $(".project_business_sector").html("&nbsp;") } else { $(".project_business_sector").text(obj.project_business_sector)};
				if (obj.project_funding_type == "") { $(".project_funding_type").html("&nbsp;") } else { $(".project_funding_type").text(obj.project_funding_type)};
				if (obj.project_funding_duration == "") { $(".project_funding_duration").html("&nbsp;") } else { $(".project_funding_duration").text(obj.project_funding_duration)};
				if (obj.project_return_on_investment == "") { $(".project_return_on_investment").html("&nbsp;") } else { $(".project_return_on_investment").text(obj.project_return_on_investment)};
				if (obj.project_investor_benefit == "") { $(".project_investor_benefit").html("&nbsp;") } else { $(".project_investor_benefit").text(obj.project_investor_benefit)};
				if (obj.project_summary == "") { $(".project_summary").html("&nbsp;") } else { $(".project_summary").html(obj.project_summary)};

				$(".edit-projects-field").hide();
				$(".view-projects-content").show();
				$(".tooltips").hide();
			});
			$(this).parent().hide();
			$(this).parent().prev().show();
		},


		/******** QUELLE EST L'UTILITÉ SOCIÉTALE DU PROJET ? ********/

		edit_societal:   function (event) { 
			$(this).hide();
			$(".view-societal-content").hide();
			$(".edit-societal-field").show();
			$(".tooltips").show();
			$(this).next().show();
		},

		cancel_societal: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-societal-field").hide();
			$(".view-societal-content").show();
			$(".tooltips").hide();
		},

		save_societal: function (event) {  
			campaign_id = $('*[data-action="save_societal"]').data('campaign');
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': 
				{ 
					'action':'update_societal',
					'wpProjectId' : campaign_id,
					'projectEconomyExcerpt' : $("#projectEconomyExcerpt").val(),
					'projectSocialExcerpt' : $("#projectSocialExcerpt").val(),
					'projectEnvironmentExcerpt' : $("#projectEnvironmentExcerpt").val(),
					'projectMission' : $("#project_mission_ifr").contents().find('#tinymce').html(),
					'projectEconomy' : $("#project_economy_ifr").contents().find('#tinymce').html(),
					'projectSocial' : $("#project_social_ifr").contents().find('#tinymce').html(),
					'projectEnvironment' : $("#project_environment_ifr").contents().find('#tinymce').html(),
					'projectMeasurePerformance' : $("#project_measure_performance_ifr").contents().find('#tinymce').html(),
					'projectGoodPoint' : $("#project_good_point_ifr").contents().find('#tinymce').html()
				}
			}).done(function(data){
				var obj = $.parseJSON(data);
				console.log(obj.project_social_excerpt);
				if (obj.project_economy_excerpt == "") { $(".project_economy_excerpt").html("&nbsp;") } else { $(".project_economy_excerpt").text(obj.project_economy_excerpt)};
				if (obj.project_social_excerpt == "") { $(".project_social_excerpt").html("&nbsp;") } else { $(".project_social_excerpt").text(obj.project_social_excerpt)};
				if (obj.project_environment_excerpt == "") { $(".project_environment_excerpt").html("&nbsp;") } else { $(".project_environment_excerpt").text(obj.project_environment_excerpt)};
				if (obj.project_mission == "") { $(".project_mission").html("&nbsp;") } else { $(".project_mission").html(obj.project_mission)};
				if (obj.project_economy == "") { $(".project_economy").html("&nbsp;") } else { $(".project_economy").html(obj.project_economy)};
				if (obj.project_social == "") { $(".project_social").html("&nbsp;") } else { $(".project_social").html(obj.project_social)};
				if (obj.project_environment == "") { $(".project_environment").html("&nbsp;") } else { $(".project_environment").html(obj.project_environment)};
				if (obj.project_measure_performance == "") { $(".project_measure_performance").html("&nbsp;") } else { $(".project_measure_performance").html(obj.project_measure_performance)};
				if (obj.project_good_point == "") { $(".project_good_point").html("&nbsp;") } else { $(".project_good_point").html(obj.project_good_point)};

				$(".edit-societal-field").hide();
				$(".view-societal-content").show();
				$(".tooltips").hide();
			});
		$(this).parent().hide();
		$(this).parent().prev().show();
		},

		/******** QUELLE EST L'OPPORTUNITÉ ÉCONOMIQUE DU PROJET ? ********/

		edit_economy:   function (event) { 
			$(this).hide();
			$(".view-economy-content").hide();
			$(".edit-economy-field").show();
			$(".tooltips").show();
			$(this).next().show();
		},

		cancel_economy: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-economy-field").hide();
			$(".view-economy-content").show();
			$(".tooltips").hide();
		},

		save_economy: function (event) {  
			campaign_id = $('*[data-action="save_economy"]').data('campaign');
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': 
				{ 
					'action':'update_economy',
					'wpProjectId' : campaign_id,
					'projectContextExcerpt' : $("#projectContextExcerpt").val(),
					'projectMarketExcerpt' : $("#projectMarketExcerpt").val(),
					'projectContext' : $("#project_context_ifr").contents().find('#tinymce').html(),
					'projectMarket' : $("#project_market_ifr").contents().find('#tinymce').html()
				}
			}).done(function(data){
				var obj = $.parseJSON(data);
				console.log(obj);
				if (obj.project_context_excerpt == "") { $(".project_context_excerpt").html("&nbsp;") } else { $(".project_context_excerpt").text(obj.project_context_excerpt)};
				if (obj.project_market_excerpt == "") { $(".project_market_excerpt").html("&nbsp;") } else { $(".project_market_excerpt").text(obj.project_market_excerpt)};
				if (obj.project_context== "") { $(".project_context").html("&nbsp;") } else { $(".project_context").html(obj.project_context)};
				if (obj.project_market == "") { $(".project_market").html("&nbsp;") } else { $(".project_market").html(obj.project_market)};

				$(".edit-economy-field").hide();
				$(".view-economy-content").show();
				$(".tooltips").hide();
			});
		$(this).parent().hide();
		$(this).parent().prev().show();
		},


		/******** QUEL EST LE MODÈLE ÉCONOMIQUE DU PROJET ?********/

		edit_model:   function (event) { 
			$(this).hide();
			$(".view-model-content").hide();
			$(".edit-model-field").show();
			$(".tooltips").show();
			$(this).next().show();

		},

		cancel_model: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-model-field").hide();
			$(".view-model-content").show();
			$(".tooltips").hide();
		},

		save_model: function (event) { 
			console.log( $("#project_activities_canvas").val());
			console.log( $("#project_collaborators_canvas").val() );
			console.log( $("#project_ressources_canvas").val() );
			console.log( $("#project_worth_offer_canvas").val() );
			console.log( $("#project_customers_relations_canvas").val() );
			console.log( $("#project_chain_distribution_canvas").val() );
			console.log( $("#project_cost_structure_canvas").val() );

			
			campaign_id = $('*[data-action="save_model"]').data('campaign');
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': 
				{ 
					'action':'update_model',
					'wpProjectId' : campaign_id,
					'projectWorthOffer' : $("#projectWorthOffer").val(),
					'projectClientCollaborator' : $("#projectClientCollaborator").val(),
					'projectBusinessCore' : $("#projectBusinessCore").val(),
					'projectIncome' : $("#projectIncome").val(),
					'projectCost' : $("#projectCost").val(),
					'projectCollaboratorsCanvas' : $("#project_collaborators_canvas").val(),
					'projectActivitiesCanvas' : $("#project_activities_canvas").val(),
					'projectRessourcesCanvas' : $("#project_ressources_canvas").val(),
					'projectWorthOfferCanvas' : $("#project_worth_offer_canvas").val(),
					'projectCustomersRelationsCanvas' : $("#project_customers_relations_canvas").val(),
					'projectChainDistributionsCanvas' : $("#project_chain_distribution_canvas").val(),
					'projectClientsCanvas' : $("#project_clients_canvas").val(),
					'projectCostStructureCanvas' : $("#project_cost_structure_canvas").val(),
					'projectSourceOfIncomeCanvas' : $("#project_source_of_income_canvas").val(),
					'projectFinancialBoard' : $("#project_financial_board_ifr").contents().find('#tinymce').html(),
					'projectPerspectives' : $("#project_perspectives_ifr").contents().find('#tinymce').html(),
				}
			}).done(function(data){
				var obj = $.parseJSON(data);
				console.log(obj);
				if (obj.project_worth_offer == "") { $(".project_worth_offer").html("&nbsp;") } else { $(".project_worth_offer").text(obj.project_worth_offer)};
				if (obj.project_client_collaborator == "") { $(".project_client_collaborator").html("&nbsp;") } else { $(".project_client_collaborator").text(obj.project_client_collaborator)};
				if (obj.project_business_core == "") { $(".project_business_core").html("&nbsp;") } else { $(".project_business_core").text(obj.project_business_core)};
				if (obj.project_income == "") { $(".project_income").html("&nbsp;") } else { $(".project_income").text(obj.project_income)};
				if (obj.project_cost == "") { $(".project_cost").html("&nbsp;") } else { $(".project_cost").text(obj.project_cost)};
				if (obj.project_collaborators_canvas == "") { $(".project_collaborators_canvas").html("&nbsp;") } else { $(".project_collaborators_canvas").text(obj.project_collaborators_canvas)};
				if (obj.project_activities_canvas == "") { $(".project_activities_canvas").html("&nbsp;") } else { $(".project_activities_canvas").text(obj.project_activities_canvas)};
				if (obj.project_ressources_canvas == "") { $(".project_ressources_canvas").html("&nbsp;") } else { $(".project_ressources_canvas").text(obj.project_ressources_canvas)};
				if (obj.project_worth_offer_canvas == "") { $(".project_worth_offer_canvas").html("&nbsp;") } else { $(".project_worth_offer_canvas").text(obj.project_worth_offer_canvas)};
				if (obj.project_customers_relations_canvas == "") { $(".project_customers_relations_canvas").html("&nbsp;") } else { $(".project_customers_relations_canvas").text(obj.project_customers_relations_canvas)};
				if (obj.project_chain_distribution_canvas == "") { $(".project_chain_distribution_canvas").html("&nbsp;") } else { $(".project_chain_distribution_canvas").text(obj.project_chain_distribution_canvas)};
				if (obj.project_clients_canvas == "") { $(".project_clients_canvas").html("&nbsp;") } else { $(".project_clients_canvas").text(obj.project_clients_canvas)};
				if (obj.project_cost_structure_canvas == "") { $(".project_cost_structure_canvas").html("&nbsp;") } else { $(".project_cost_structure_canvas").text(obj.project_cost_structure_canvas)};
				if (obj.project_source_income_canvas == "") { $(".project_source_of_income_canvas").html("&nbsp;") } else { $(".project_source_of_income_canvas").text(obj.project_source_of_income_canvas)};
				if (obj.project_financial_board == "") { $(".project_financial_board").html("&nbsp;") } else { $(".project_financial_board").html(obj.project_financial_board)};
				if (obj.project_perspectives == "") { $(".project_perspectives").html("&nbsp;") } else { $(".project_perspectives").html(obj.project_perspectives)};

				$(".edit-model-field").hide();
				$(".view-model-content").show();
				$(".tooltips").hide();
			});
		$(this).parent().hide();
		$(this).parent().prev().show();
		},

		/******** QUI PORTE LE PROJET ? ********/

		edit_members:   function (event) { 
			$(this).hide();
			$(".view-members-content").hide();
			$(".edit-members-field").show();
			$(this).next().show();
		},

		cancel_members: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-members-field").hide();
			$(".view-members-content").show();
		},

		save_members: function (event) {  
			campaign_id = $('*[data-action="save_members"]').data('campaign');
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': 
				{ 
					'action':'update_members',
					'wpProjectId' : campaign_id,
					'projectOtherInformation' : $("#project_other_information_ifr").contents().find('#tinymce').html(),

				}
			}).done(function(data){
				var obj = $.parseJSON(data);

				if (obj.project_other_information == "") { $(".project_other_information").html("&nbsp;") } else { $(".project_other_information").html(obj.project_other_information)};
				$(".edit-members-field").hide();
				$(".view-members-content").show();
			});
			$(this).parent().hide();
			$(this).parent().prev().show();
		},

		save_image: function(event) {
			var options = { 
		        target:        '#output2',      // target element(s) to be updated with server response 
		        beforeSubmit:  showRequest,     // pre-submit callback 
		        success:       showResponse,   // post-submit callback 
		        url:    ajax_object.ajax_url              // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php     
		    }; 

		    // bind form using 'ajaxForm' 
		    $('#image_upload').ajaxForm(options); 

		    function showRequest(formData, jqForm, options) {
				//do extra stuff before submit like disable the submit button
				$('#loading_image_cover').show();
				$('#submit_image_cover').hide();
			}

			function showResponse(responseText, statusText, xhr, $form)  {
				//do extra stuff after submit
				$('#loading_image_cover').hide();
				$('#submit_image_cover').fadeIn('fast');;
				$(".cover-img").load(function() {
					$(this).hide();
					$(this).fadeIn('slow');
				}).attr('src', responseText+ '?' + new Date().getTime());
			}
		},


		save_image_home: function(event) {
			var options = { 
		        target:        '#output12',      // target element(s) to be updated with server response 
		        beforeSubmit:  showRequest,     // pre-submit callback 
		        success:       showResponse,    // post-submit callback 
		        url:    ajax_object.ajax_url              // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php     
		    }; 

		    // bind form using 'ajaxForm' 
		    $('#image_home_upload').ajaxForm(options); 

		    function showRequest(formData, jqForm, options) {
				//do extra stuff before submit like disable the submit button
				$('#output1').html('Téléchargement en cours...');
				
			}

			function showResponse(responseText, statusText, xhr, $form)  {
				//do extra stuff after submit
				$(".update-field-img-home img").load(function() {
					$(this).hide();
					$(this).fadeIn('slow');
				}).attr('src', responseText+ '?' + new Date().getTime());
			}
		},


		display_tooltips: function(event) {
			console.log("test");
    		$('span.project_return_on_investment_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_return_on_investment_tooltips') // my target
        		}
    		});
    		$('span.project_investor_benefit_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_investor_benefit_tooltips') // my target
        		}
    		});

    		$('span.project_economy_excerpt_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_economy_excerpt_tooltips') // my target
        		}
    		});

    		$('span.project_social_excerpt_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_social_excerpt_tooltips') // my target
        		}
    		});

    		$('span.project_environment_excerpt_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_environment_excerpt_tooltips') // my target
        		}
    		});

    		$('span.project_economy_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_economy_tooltips') // my target
        		}
    		});

    		$('span.project_social_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_social_tooltips') // my target
        		}
    		});

    		$('span.project_environment_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_environment_tooltips') // my target
        		}
    		});

    		$('span.project_measure_performance_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_measure_performance_tooltips') // my target
        		}
    		});

    		$('span.project_good_point_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_good_point_tooltips') // my target
        		}
    		});

    		$('span.project_context_excerpt_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_context_excerpt_tooltips') // my target
        		}
    		});

    		$('span.project_market_excerpt_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_market_excerpt_tooltips') // my target
        		}
    		});

    		$('span.project_context_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_context_tooltips') // my target
        		}
    		});

    		$('span.project_market_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_market_tooltips') // my target
        		}
    		});








    		$('span.project_worth_offer_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_worth_offer_tooltips') // my target
        		}
    		});

    		$('span.project_client_collaborator_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_client_collaborator_tooltips') // my target
        		}
    		});

    		$('span.project_business_core_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_business_core_tooltips') // my target
        		}
    		});

    		$('span.project_income_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_income_tooltips') // my target
        		}
    		});

    		$('span.project_cost_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_cost_tooltips') // my target
        		}
    		});

    		$('span.project_collaborators_canvas_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_collaborators_canvas_tooltips') // my target
        		}
    		});

    		$('span.project_activities_canvas_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_activities_canvas_tooltips') // my target
        		}
    		});


    		$('span.project_ressources_canvas_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_ressources_canvas_tooltips') // my target
        		}
    		});


    		$('span.project_customers_relations_canvas_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_customers_relations_canvas_tooltips') // my target
        		}
    		});

    		$('span.project_chain_distributions_canvas_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_chain_distributions_canvas_tooltips') // my target
        		}
    		});

    		$('span.project_clients_canvas_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_clients_canvas_tooltips') // my target
        		}
    		});

    		$('span.project_cost_structure_canvas_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_cost_structure_canvas_tooltips') // my target
        		}
    		});

    		$('span.project_financial_board_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_financial_board_tooltips') // my target
        		}
    		});

    		$('span.project_perspectives_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_perspectives_tooltips') // my target
        		}
    		});

    		$('span.project_other_information_tooltips').qtip({
    			position: {
      			  	my: 'center right',  // Position my top left...
       			  	at: 'center left', // at the bottom right of...
        			target: $('span.project_other_information_tooltips') // my target
        		}
    		});

		},

		share_btn_click:function() {
			$("#dialog").dialog({
				width: '350px',
				zIndex: 5,
				draggable: false,
				resizable: false,
				autoOpen: false,
				modal: true,
				show: {
					effect: "blind",
					duration: 300
				},
				hide: {
					effect: "blind",
					duration: 300
				}
			});
			$("#dialog").dialog("open"); 
		},

		print_vote_form:function(){
			$("#vote-form").animate({ 
				top: "370px"
			}, 500 );
			$(".description-discover").css('background-color', '#7B7B7B');
			$("#project-description-title-padding").height($("#vote-form").height() - $("#projects-right-desc").height());
		},
		
// 		/**
// 		 * Gestion des différentes parties de description du projet
// 		 */
// 		//Initialisation du comportement des différentes parties
// 		initClick: function(descContentElement) {
// 			//Si il y a plus d'un paragraphe, on initialise le clic
// 	  		if ($(descContentElement).find('p').length > 1) {
// 	  			$(descContentElement).css("cursor", "pointer");
// 				var sProjectMore = '<div class="projects-more" data-value="' + WDGProjectPageFunctions.currentDiv + '" >Lire plus !</div>';
// 		  		$(descContentElement).find('div div *:lt(1)').append(sProjectMore);
// 		  		$(descContentElement).click(function(){
// 					WDGProjectPageFunctions.clickItem($(this))
// 				});
// 	  		}
// 			//On prend toutes les balises de la description
// 			var children = $(descContentElement).children().children().children();
// 			//On les masque sauf la première
// 	   		$(descContentElement).find(children.not('*:eq(0)')).hide();
// 	   		WDGProjectPageFunctions.currentDiv++;
//    		},

// 		//Clic sur une partie
// 		clickItem: function(clickedElement) {
// 			var projectMore = clickedElement.find('.projects-more');
// 			//Si la balise "lire plus" de l'élément cliqué est affichée
// 			if (projectMore.is(':visible')) {
// 				//il faut la masquer puis afficher les éléments qui suivent
// 				projectMore.hide(400, function(){
// 					clickedElement.find('p, ul').slideDown(400);
// 				});
// 				//on masque aussi toutes les autres parties
// 				WDGProjectPageFunctions.hideOthers(projectMore.attr("data-value"));
// 			//Sinon on masque tout
// 			} else {
// 				WDGProjectPageFunctions.hideOthers(-1);
// 			}
// 		},

 		//Masque des parties non utilisées
 		hideOthers:function(currentDiv){
 		//Add Inactive Class To All Accordion Headers
 		$('.readmore').toggleClass('inactive-header');

		//Set The Accordion Content Width
		var contentwidth = $('.readmore').width();
		$('.more').css({'width' : contentwidth });
		
		//Open The First Accordion Section When Page Loads
		$('.readmore').first().toggleClass('active-header').toggleClass('inactive-header');
		$('.more').first().slideDown().toggleClass('open-content');
		
		// The Accordion Effect
		$('.readmore').click(function () {
			if($(this).is('.inactive-header')) {
				$('.active-header').toggleClass('active-header').toggleClass('inactive-header').next().slideToggle().toggleClass('open-content');
				$(this).toggleClass('active-header').toggleClass('inactive-header');
				$(this).next().slideToggle().toggleClass('open-content');
			}
			
			else {
				$(this).toggleClass('active-header').toggleClass('inactive-header');
				$(this).next().slideToggle().toggleClass('open-content');
			}
		});
		
		return false;
	}
}
})(jQuery);

