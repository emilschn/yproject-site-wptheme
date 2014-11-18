jQuery(document).ready( function($) {
	WDGNewProjectPageFunctions.initUI();
});

BOPPFunctions = (function($) {
	return {
		move_picture:function(campaign_id) {
			$('#img-container').draggable({ axis: "y" });
			$('#img-container').draggable('enable');
			$('#reposition-cover').text('Sauvegarder');
			$('#reposition-cover').attr("onclick", "BOPPFunctions.save_position("+campaign_id+")");
			$("#head-content").css({ opacity: 0 });
			$("#head-content").css({ 'z-index': -1 });
		},

		save_position:function(campaign_id){
			$('#img-container').draggable('disable');
			$('#reposition-cover').text('Repositionner');
			$('#reposition-cover').attr("onclick", "BOPPFunctions.move_picture("+campaign_id+")");
			$("#head-content").css({ opacity: 1 });
			$("#head-content").css({ 'z-index': 2 });

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
	};
})(jQuery);

/* Projet */
WDGNewProjectPageFunctions=(function($) {
	return {
		currentDiv:0,
		initUI:function() {
			WDGNewProjectPageFunctions.save_image_home();
			WDGNewProjectPageFunctions.save_image();
			//WDGNewProjectPageFunctions.display_tooltips();

			$('#tabs').tabs().addClass('ui-tabs-vertical ui-helper-clearfix');

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
			  if( typeof WDGNewProjectPageFunctions[action] === "function" ) {
			  	WDGNewProjectPageFunctions[action].call(this, event);
			  }
			});

			$(".edit_video a[data-action]").on("click", function (event) {
				var link = $(this),
				action = link.data("action");

				event.preventDefault();

			  // If there's an action with the given name, call it
			  if( typeof WDGNewProjectPageFunctions[action] === "function" ) {
			  	WDGNewProjectPageFunctions[action].call(this, event);
			  }
			});

			$(".edit_projects a[data-action]").on("click", function (event) {
				var link = $(this),
				action = link.data("action");

				event.preventDefault();

			  // If there's an action with the given name, call it
			  if( typeof WDGNewProjectPageFunctions[action] === "function" ) {
			  	WDGNewProjectPageFunctions[action].call(this, event);
			  }
			});


			$(".edit_societal a[data-action]").on("click", function (event) {
				var link = $(this),
				action = link.data("action");

				event.preventDefault();

			  // If there's an action with the given name, call it
			  if( typeof WDGNewProjectPageFunctions[action] === "function" ) {
			  	WDGNewProjectPageFunctions[action].call(this, event);
			  }
			});

			$(".edit_economy a[data-action]").on("click", function (event) {
				var link = $(this),
				action = link.data("action");

				event.preventDefault();

			  // If there's an action with the given name, call it
			  if( typeof WDGNewProjectPageFunctions[action] === "function" ) {
			  	WDGNewProjectPageFunctions[action].call(this, event);
			  }
			});

			$(".edit_model a[data-action]").on("click", function (event) {
				var link = $(this),
				action = link.data("action");

				event.preventDefault();

			  // If there's an action with the given name, call it
			  if( typeof WDGNewProjectPageFunctions[action] === "function" ) {
			  	WDGNewProjectPageFunctions[action].call(this, event);
			  }
			});

			$(".edit_name a[data-action]").on("click", function (event) {
				var link = $(this),
				action = link.data("action");

				event.preventDefault();

			  // If there's an action with the given name, call it
			  if( typeof WDGNewProjectPageFunctions[action] === "function" ) {
			  	WDGNewProjectPageFunctions[action].call(this, event);
			  }
			});

			$(".edit_members a[data-action]").on("click", function (event) {
				var link = $(this),
				action = link.data("action");

				event.preventDefault();

			  // If there's an action with the given name, call it
			  if( typeof WDGNewProjectPageFunctions[action] === "function" ) {
			  	WDGNewProjectPageFunctions[action].call(this, event);
			  }
			});

		},

		move_cursor:function(campaign_id){
			$('#move-cursor').text('Sauvegarder la position du curseur');
			$('#move-cursor').attr("onclick", "WDGNewProjectPageFunctions.save_cursor_position("+campaign_id+")");
			$('#map-cursor').draggable({
				containment: '#project-map'
			});
			$('#map-cursor').draggable('enable');
		},

		save_cursor_position:function(campaign_id){
			$('#move-cursor').text('Modifier la position du curseur');
			$('#move-cursor').attr("onclick", "WDGNewProjectPageFunctions.move_cursor("+campaign_id+")");
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
			$('.jy-crois').attr("href", "javascript:WDGNewProjectPageFunctions.update_jycrois("+jy_crois_temp+","+campaign_id+",\""+home_url+"\")");
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
			$(".tooltips_name").show();
			$(this).next().show();
		},

		cancel_name: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-name-field").hide();
			$(".view-name-content").show();
			$(".tooltips_name").hide();
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
				$(".tooltips_name").hide();
			});
			$(this).parent().hide();
			$(this).parent().prev().show();
		},


		/******** DESCRIPTION ********/

		edit_description:   function (event) { 
			$(this).hide();
			$(".view-description-content").hide();
			$(".edit-description-field").show();
			$(".tooltips_description").show();
			$(this).next().show();
		},

		cancel_description: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-description-field").hide();
			$(".view-description-content").show();
			$(".tooltips_description").hide();
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
				if (obj.project_description == "") { $(".project_description").html("&nbsp;") } else { $(".project_description").text(obj.project_description)};
				$(".edit-description-field").hide();
				$(".view-description-content").show();
				$(".tooltips_description").hide();
			});
			$(this).parent().hide();
			$(this).parent().prev().show();
		},

		/******** VIDEO ********/

		edit_video:   function (event) { 
			$(this).hide();
			$(".view-video-content").hide();
			$(".edit-video-field").show();
			$(".tooltips_video").show();
			$(this).next().show();
		},

		cancel_video: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-video-field").hide();
			$(".view-video-content").show();
			$(".tooltips_video").hide();
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
				$(".tooltips_video").hide();
			});
			$(this).parent().hide();
			$(this).parent().prev().show();
		},


		/******** EN QUOI CONSISTE LE PROJET ********/

		edit_project:   function (event) { 
			$(this).hide();
			$(".view-projects-content").hide();
			$(".edit-projects-field").show();
			$(".tooltips_projects").show();
			$(this).next().show();
		},

		cancel_project: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-projects-field").hide();
			$(".view-projects-content").show();
			$(".tooltips_projects").hide();
		},

		save_project: function (event) {  
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
				$(".tooltips_projects").hide();
			});
		$(this).parent().hide();
		$(this).parent().prev().show();
		},


		/******** QUELLE EST L'UTILITÉ SOCIÉTALE DU PROJET ? ********/

		edit_societal:   function (event) { 
			$(this).hide();
			$(".view-societal-content").hide();
			$(".edit-societal-field").show();
			$(".tooltips_societal").show();
			$(this).next().show();
		},

		cancel_societal: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-societal-field").hide();
			$(".view-societal-content").show();
			$(".tooltips_societal").hide();
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
				$(".tooltips_societal").hide();
			});
		$(this).parent().hide();
		$(this).parent().prev().show();
		},

		/******** QUELLE EST L'OPPORTUNITÉ ÉCONOMIQUE DU PROJET ? ********/

		edit_economy:   function (event) { 
			$(this).hide();
			$(".view-economy-content").hide();
			$(".edit-economy-field").show();
			$(".tooltips_economy").show();
			$(this).next().show();
		},

		cancel_economy: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-economy-field").hide();
			$(".view-economy-content").show();
			$(".tooltips_economy").hide();
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
				if (obj.project_context_excerpt == "") { $(".project_context_excerpt").html("&nbsp;") } else { $(".project_context_excerpt").text(obj.project_context_excerpt)};
				if (obj.project_market_excerpt == "") { $(".project_market_excerpt").html("&nbsp;") } else { $(".project_market_excerpt").text(obj.project_market_excerpt)};
				if (obj.project_context== "") { $(".project_context").html("&nbsp;") } else { $(".project_context").html(obj.project_context)};
				if (obj.project_market == "") { $(".project_market").html("&nbsp;") } else { $(".project_market").html(obj.project_market)};

				$(".edit-economy-field").hide();
				$(".view-economy-content").show();
				$(".tooltips_economy").hide();
			});
			$(this).parent().hide();
			$(this).parent().prev().show();
		},


		/******** QUEL EST LE MODÈLE ÉCONOMIQUE DU PROJET ?********/

		edit_model:   function (event) { 
			$(this).hide();
			$(".view-model-content").hide();
			$(".edit-model-field").show();
			$(".tooltips_model").show();
			$(this).next().show();

		},

		cancel_model: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-model-field").hide();
			$(".view-model-content").show();
			$(".tooltips_model").hide();
		},

		save_model: function (event) { 
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
				$(".tooltips_model").hide();
			});
		$(this).parent().hide();
		$(this).parent().prev().show();
		},

		/******** QUI PORTE LE PROJET ? ********/

		edit_members:   function (event) { 
			$(this).hide();
			$(".view-members-content").hide();
			$(".edit-members-field").show();
			$(".tooltips_name").show();
			$(this).next().show();
		},

		cancel_members: function (event) {
			$(this).parent().hide();
			$(this).parent().prev().show();
			$(".edit-members-field").hide();
			$(".view-members-content").show();
			$(".tooltips_name").hide();
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
				$(".tooltips_name").hide();
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
				//$('#loading_image_cover').show();
				$('#submit_image_cover').hide();
				

			}

			function showResponse(responseText, statusText, xhr, $form)  {
				//do extra stuff after submit
				//$('#loading_image_cover').hide();
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
	}
})(jQuery);

