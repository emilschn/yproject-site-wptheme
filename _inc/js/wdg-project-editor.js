jQuery(document).ready( function($) {
    ProjectEditor.init();
});

var ProjectEditor = (function($) {
	return {
		elements: [],
		isInit: false,
		intervalID: null,
		softCancel: null,
		iniContent: null,
		
		//Initialisation : création du bouton en haut de page permettant de switcher d'un mode à l'autre
		init: function() {
			$("#wdg-edit-project").show();
			$("#wdg-edit-project").click(function() {
				ProjectEditor.clickEditProject(this);
			});
			$("#wdg-edit-project-add-lang").click(function() {
				ProjectEditor.clickShowAddLang();
			});
			$("#wdg-edit-project-add-lang button.add-button").click(function() {
				ProjectEditor.clickAddLang();
			});
			
			window.addEventListener( 'beforeunload', function (e) {
				if ( WDGProjectPageFunctions.isEditing !== '' ) {
					var confirmationMessage = "Vous avez réservé une des parties du projet pour l'éditer, prenez le temps de la sauvegarder ou d'annuler la réservation. Êtes vous sûr de vouloir quitter ?";
					(e || window.event).returnValue = confirmationMessage; //Gecko + IE
					return confirmationMessage; //Webkit, Safari, Chrome
				}
			} );
			
			ProjectEditor.analyseImageFiles();
		},
		
		//Permet de switcher du texte Nouvelle langue vers le sélecteur de langue
		clickShowAddLang: function() {
			$("#wdg-edit-project-add-lang span").hide();
			$("#wdg-edit-project-add-lang select, #wdg-edit-project-add-lang button.add-button").show();
		},
		
		//Ajoute effectivement une langue
		clickAddLang: function() {
			
		},
		
		//Permet de switcher du mode édition au mode prévisualisation
		clickEditProject: function(clickedElement) {
			if (!$(clickedElement).hasClass("btn-edit-validate")) {
				$("#content").addClass("editing");
				ProjectEditor.initEdition();
				$(clickedElement).addClass("btn-edit-validate");
				$("#wdg-edit-project-add-lang").show();
			} else {
				if (WDGProjectPageFunctions.isEditing !== "") {
					alert("Vous ne pouvez pas valider si un champ est en cours d'édition");
				} else {
					$("#content").removeClass("editing");
					ProjectEditor.stopEdition();
					$(clickedElement).removeClass("btn-edit-validate");
					$("#wdg-edit-project-add-lang").hide();
					var background = $("#project-banner-picture").css('background-image');
					if(background){
						$("#project-banner-picture").attr('style','display:inline-block; left: 387px; top: 506px; background-image:'+background+'; background-repeat:no-repeat;');
					}else{
						$("#project-banner-picture").css('background-image', 'none');
						$("#project-banner-picture").attr('style','display:inline-block; left: 387px; top: 506px;');
					}
				}
			}
		},
	    
		//Démarre l'édition de la page projet
		initEdition: function() {
			if (!ProjectEditor.isInit) { ProjectEditor.initElements(); }
			for (elementKey in ProjectEditor.elements) {
				ProjectEditor.initEditable(elementKey);
				if (!ProjectEditor.isInit) { ProjectEditor.addEditButton(elementKey); }
				else { ProjectEditor.showEditButton(elementKey); }
			}
			WDGProjectPageFunctions.refreshEditable();
			if (!ProjectEditor.isInit) { ProjectEditor.initClick(); }
			ProjectEditor.isInit = true;
		},
		
		//Arrête l'édition de la page projet
		stopEdition: function() {
			for (elementKey in ProjectEditor.elements) {
				$(ProjectEditor.elements[elementKey].elementId).removeClass("editable");
				ProjectEditor.hideEditButton(elementKey);
			}
			WDGProjectPageFunctions.refreshEditable();
		},
		
		//Liste tous les éléments qui peuvent être édités
		//Un élément contient une référence à son conteneur et à son contenu
		initElements: function() {
			ProjectEditor.elements["title"] = {elementId: ".project-banner .project-banner-title h1", contentId: ".project-banner .project-banner-title h1"};
			ProjectEditor.elements["subtitle"] = {elementId: ".project-banner .subtitle", contentId: ".project-banner .subtitle"};
			ProjectEditor.elements["summary"] = {elementId: ".project-banner .project-banner-content .project-pitch-text", contentId: ".project-banner .project-banner-content .project-pitch-text"};
//			ProjectEditor.elements["rewards"] = {elementId: "#projects-right-desc #project-rewards-custom", contentId: "#projects-right-desc #project-rewards-custom"};
			ProjectEditor.elements["description"] = {elementId: ".project-description #project-content-description .zone-content", contentId: ".project-description #project-content-description .zone-edit"};
			ProjectEditor.elements["societal_challenge"] = {elementId: ".project-description #project-content-societal_challenge .zone-content", contentId: ".project-description #project-content-societal_challenge .zone-edit"};
			ProjectEditor.elements["added_value"] = {elementId: ".project-description #project-content-added_value .zone-content", contentId: ".project-description #project-content-added_value .zone-edit"};
			ProjectEditor.elements["economic_model"] = {elementId: ".project-description #project-content-economic_model .zone-content", contentId: ".project-description #project-content-economic_model .zone-edit"};
			ProjectEditor.elements["implementation"] = {elementId: ".project-description #project-content-implementation .zone-content", contentId: ".project-description #project-content-implementation .zone-edit"};
//			ProjectEditor.elements["picture-head"] = {elementId: ".project-banner .project-banner-img", contentId: ".project-admin"};
			ProjectEditor.elements["video-zone"] = {elementId: ".project-banner .project-banner-content .banner-half.left", contentId: ".project-admin"};
//			ProjectEditor.elements["project-owner"] = {elementId: ".project-banner-content .author-info", contentId: ".project-admin"};
		},
		
		//Ajoute le bouton d'édition d'un élément en paramètre
		addEditButton: function(property) {
			var buttonEdit = '<div id="wdg-edit-'+property+'" class="edit-button" data-property="'+property+'"></div>';
			$(ProjectEditor.elements[elementKey].elementId).after(buttonEdit);
			switch (property) {
				case "societal_challenge":
				case "added_value":
				case "economic_model":
				case "implementation":
					$("#wdg-edit-"+property).hide();
					break;
					
				default:
					ProjectEditor.showEditButton(property);
					break;
			}
		},
		
		//Affiche le bouton d'édition d'un élément en paramètre
		showEditButton: function(property) {
			if (ProjectEditor.elements[property] !== undefined) {
				if (property !== "picture-head") {
					var elementId = ProjectEditor.elements[property].elementId;
					$("#wdg-edit-"+property).css("left", $(elementId).position().left + $(elementId).outerWidth());
					var marginTop = Number($(elementId).css("marginTop").replace("px", ""));
					$("#wdg-edit-"+property).css("top", $(elementId).position().top + marginTop);
				}
				$("#wdg-edit-"+property).show();
			}
			
		},
		
		//Masque le bouton d'édition d'un élément en paramètre
		hideEditButton: function(property) {
			$("#wdg-edit-"+property).hide();
		},

		hideAllEditButton: function() {
			ProjectEditor.hideEditButton('description');
			ProjectEditor.hideEditButton('societal_challenge');
			ProjectEditor.hideEditButton('added_value');
			ProjectEditor.hideEditButton('economic_model');
			ProjectEditor.hideEditButton('implementation');
		},

		requestLockProject: function(sProperty) {
    		var value = $("#project-content-"+sProperty).data("md5");
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': {
					'action':	'try_lock_project_edition',
					'property':	sProperty,
					'value':	value,
					'id_campaign':  $("#content").data("campaignid"),
					'lang':		$("html").attr("lang").split("-").join("_")
				}
		    }).done(function(result) {
				result = JSON.parse(result);
		    	if ( result.response == 'error') {
		     		alert( result.values + " édite déjà cette partie du projet." );
		     		WDGProjectPageFunctions.isEditing = "";
		     		$("#wdg-edit-"+sProperty).removeClass("wait-button");
		     	} else if ( result.response == 'different_content') {
		     		alert( "Cette partie a été modifiée récemment, merci d'actualiser la page afin d'afficher le bon contenu." );
		     		WDGProjectPageFunctions.isEditing = "";
		     		$("#wdg-edit-"+sProperty).removeClass("wait-button"); 
		        } else {
		            ProjectEditor.showEditableZone(result.values);
		            ProjectEditor.keepUserLockProject();
		        }
			});
		},
		
		//Définit les événements de clicks sur les différents boutons d'édition
		initClick: function() {
			$(".edit-button").click(function() {
				var sProperty = $(this).data("property");
				if ($(this).attr("id") !== "wdg-edit-project") {
					WDGProjectPageFunctions.isEditing = sProperty;
				}
				
				switch (sProperty) {
					case "title":
					case "subtitle":
						ProjectEditor.createInput(sProperty);
						break;
					case "summary":
					case "rewards":
						ProjectEditor.createTextArea(sProperty);
						break;
					case "description":
					case "societal_challenge":
					case "added_value":
					case "economic_model":
					case "implementation":
						$("#wdg-edit-"+sProperty).addClass("wait-button");
						ProjectEditor.requestLockProject(sProperty);
						break;
					case "picture-head":
						ProjectEditor.createfile(sProperty);
						break;
					case "video-zone":
						ProjectEditor.editImageVideo(sProperty);
						break;
					case "project-owner":
						ProjectEditor.redirectOrganization(sProperty);
						break;
				}
			});
		},

		//Mise à jour des données de l'édition en cours
		keepUserLockProject: function() {
			if ( ProjectEditor.intervalID ) {
				clearInterval(ProjectEditor.intervalID);
				ProjectEditor.intervalID = null;
			} else {
				ProjectEditor.intervalID = setInterval( function() { 
					$.ajax({
						'type' : "POST",
						'url' : ajax_object.ajax_url,
						'data': {
							'action':	'keep_lock_project_edition',
							'property':	WDGProjectPageFunctions.isEditing,		
							'id_campaign':  $("#content").data("campaignid"),
							'lang':		$("html").attr("lang").split("-").join("_")
						}
					}).done(function(result) {					     	
						result = JSON.parse(result);
						if ( result.response == 'error') {
						    ProjectEditor.lockProjectFail(result.user, result.values );
				        } 
				    });
			}, 120000); // La mise à jour se fait toutes les 2 minutes.
			}
		},

		lockProjectFail: function(user, property) {
			alert( "Une inactivité prolongée a entraîné la perte de votre session. \n" + user + " édite ce projet actuellement, vos modifications seront donc perdues. \n\n Il vous est conseillé de copier/coller vos modifications dans un document afin de les conserver et de les mettre en commun avec les autres éditeurs. Merci de votre compréhension." );
			$("#wdg-validate-"+property).removeClass();
			$("#wdg-validate-"+property).css( 'cursor', 'default' );
			$("#wdg-validate-"+property).addClass("edit-button-validate-locked");
			$("#wdg-edit-"+property).removeClass("wait-button");
			ProjectEditor.keepUserLockProject();
			ProjectEditor.softCancel = true;
		},

		//Affiche un cadre sur certaines zones éditables
		initEditable: function(property) {
			switch (property) {
				case "title":
				case "subtitle":
				case "summary":
				case "rewards":
				case "description":
				case "video-zone":
				case "project-owner":
					$(ProjectEditor.elements[property].elementId).addClass("editable");
					break;
			}
		},
		
		//Récupère la valeur modifiable de certains champs éditables
		getInitValue: function(property) {
			var buffer = '';
			
			switch (property) {
				case "title":
				case "subtitle":
				case "summary":
				case "rewards":
					buffer = $(ProjectEditor.elements[property].contentId).text();
					break;
			}
			
			return buffer;
		},

		//Enregistre la bannière
		createfile: function(property){
			var url_image_start=$(".project-banner-img img").attr('src');

			var newElement_1 = '<form id="upload-img-form" enctype="multipart/form-data"> <input type="hidden" name="action" value="save_image_head" /> <input name="image_header_blur" type="hidden"/> <input type="hidden" name="campaign_id" value="'+$("#content").data("campaignid")+'" /> <input id="wdg-edit-picture-head-next" type="file" class="input_image_home" name="image_header"/> </form>';
			$(ProjectEditor.elements[property].elementId).after(newElement_1);
    		$("#wdg-edit-picture-head-next").css("display","none");

			var newElement_1_input = '<button id="wdg-edit-picture-head-next_update">Télécharger une image</button>';
			$(ProjectEditor.elements[property].elementId).after(newElement_1_input);
			$("#wdg-edit-picture-head-next_update").css("left", $(".project-banner-content").position().left);
			$("#wdg-edit-picture-head-next_update").css("top", $(".project-banner-content").position().top);
			$("#wdg-edit-picture-head-next_update").css("z-index", "150");
    		$("#wdg-edit-picture-head-next_update").css("position","absolute");

			var newElement_2 = '<input type="submit" id="wdg-edit-picture-head-next_valid" value="Valider"/>';
			$(ProjectEditor.elements[property].elementId).after(newElement_2);
			$("#wdg-edit-picture-head-next_valid").css("left", $(".project-banner-content").position().left + $("#wdg-edit-picture-head-next_update").outerWidth());
			$("#wdg-edit-picture-head-next_valid").css("top", $(".project-banner-content").position().top);
			$("#wdg-edit-picture-head-next_valid").css("z-index", "150");
    		$("#wdg-edit-picture-head-next_valid").css("position","absolute");


			var newElement_3 = '<input type="submit" id="wdg-edit-picture-head-next_cancel" value="Annuler"/>';
			$(ProjectEditor.elements[property].elementId).after(newElement_3);
			$("#wdg-edit-picture-head-next_cancel").css("left", $(".project-banner-content").position().left + $("#wdg-edit-picture-head-next_update").outerWidth() + $("#wdg-edit-picture-head-next_valid").outerWidth());
			$("#wdg-edit-picture-head-next_cancel").css("top", $(".project-banner-content").position().top);
			$("#wdg-edit-picture-head-next_cancel").css("z-index", "150");
    		$("#wdg-edit-picture-head-next_cancel").css("position","absolute");
			

			var button_waiting = '<input type="submit" id="wdg-validate-picture-wait"/>';
			$(ProjectEditor.elements[property].elementId).after(button_waiting);
			$("#wdg-validate-picture-wait").addClass("wait-button");
			$("#wdg-validate-picture-wait").unbind("click");
			$("#wdg-validate-picture-wait").attr('style','display:none; ');
			$("#wdg-validate-picture-wait").innerHTML = "";

			var newElement_span = '<span id="extra-comment">(Max. 2Mo ; idéalement 370px de hauteur et au minimum 960px de largeur)</span>';
			$(ProjectEditor.elements[property].elementId).after(newElement_span);
			$("#extra-comment").css("left", $(".project-banner-content").position().left);
			$("#extra-comment").css("top", $(".project-banner-content").position().top + $("#wdg-edit-picture-head-next_update").outerHeight(true));
			$("#extra-comment").css("z-index", "150");
    		$("#extra-comment").css("position","absolute");

			$("#wdg-move-picture-head").hide();

			$("#wdg-edit-picture-head-next_cancel").click(function() {
				ProjectEditor.validateInputDone(true);
				$("#wdg-edit-"+property).show();
				$("#wdg-edit-picture-head-next").remove();
				$("#wdg-edit-picture-head-next_update").remove();
				$("#wdg-edit-picture-head-next_valid").remove();
				$("#wdg-edit-picture-head-next_cancel").remove();
				$("#wdg-move-picture-head").show();
				$("#extra-comment").remove();
				$("#project-banner-src").remove();
				$('.project-banner-img').append('<img id="project-banner-src" src="'+url_image_start+'">');
				$(".project-banner-content").css("background", "none");
			});
			
			$("#wdg-edit-picture-head-next_update").click(function() {
				$("#wdg-edit-picture-head-next").click();
			});

			$("#wdg-edit-picture-head-next_valid").click(function() {

  				var formData = new FormData($('form#upload-img-form')[0]);
  				$("#wdg-edit-picture-head-next").remove();
				$("#wdg-edit-picture-head-next_update").remove();
				$("#wdg-edit-picture-head-next_valid").remove();
				$("#wdg-edit-picture-head-next_cancel").remove();
				$("#wdg-move-picture-head").css("left", $("#wdg-edit-picture-head").outerWidth(true));
				$("#wdg-move-picture-head").css("top", 0);
				$('.project-banner-img').css("top", 0);
				$("#extra-comment").remove();
				$("#wdg-validate-picture-wait").attr('style',' border: medium none; background-color:#41ACB1; font-size: 0px; display:inline-block; z-index:2001;');
				
				$.ajax({
					'type' : "POST",
					'url' :ajax_object.ajax_url,
					'data': formData,
		            'cache': false,
		            'contentType': false,
		            'processData': false
				}).done(function(result) {
					$("#wdg-edit-"+property).show();
					$("#wdg-validate-picture-wait").attr('style','display:none;');
					$("#wdg-move-picture-head").show();
					ProjectEditor.validateInputDone(result);

				});


			});

			$(".input_image_home").change(function(){
				$("#project-banner-src").remove();
				if (this.files) {
					$.each(this.files, function(index, file) {
						switch (file.type) {
						case "image/jpeg":
						case "image/jpg":
						case "image/png":
						case "image/gif":
						var reader = new FileReader();
						reader.onload = function (e) {
							$('.project-banner-img').append('<img id="project-banner-src" src="'+e.target.result+'">');
						}

						reader.readAsDataURL(file);
						default:
						break;
						}
					});
				}
			});
			$("#wdg-edit-"+property).hide();
		},
	 
		// Enregistre l'image et/ou l'url de la vidéo
		editImageVideo: function(property){
			var button_waiting = '<input type="submit" id="wdg-validate-video-wait" class="wait-button" />';
			$("#wdg-edit-video-zone").after(button_waiting);
			$("#wdg-validate-video-wait").unbind("click");
			$("#wdg-validate-video-wait").innerHTML = "";
			$("#wdg-validate-video-wait").css("left", $("#wdg-edit-video-zone").position().left +  $("#clearfix").outerWidth());
			$("#wdg-validate-video-wait").css("top", $("#wdg-edit-video-zone").position().top);
			$("#wdg-validate-video-wait").val("");
			$("#wdg-validate-video-wait").hide();
			$("#wdg-edit-"+property).hide();
			$("#project-banner-picture").hide();

			var div_test = "<div class='project-pitch-video-bis'><div class='block_overview_image'></div><div class='block_overview_video'></div> <div class='block_url_image'></div> <div class='block_url_video'></div> <div class='block_boutons'></div> </div>";
			$("#url_video_link").after(div_test);

			var url_video_link = $("#url_video_link").val();
			var video_preview = "<div id='apercu_video'><iframe width='290' height='100%' src='"+url_video_link+"' frameborder='0' id='myFrame' allowfullscreen/></div>";
			$(".block_overview_video").after(video_preview);

			var image_link = $("#url_image_link").val();
			var Element_image_view = '<div id="apercu_image"><img style="margin:10px;" height="200" id="video-zone-image" src="'+image_link+'"></div>';
			$(".block_overview_image").after(Element_image_view);

			var newElement = '<form id="upload-video-form" enctype="multipart/form-data"> <input type="hidden" name="action" value="save_image_url_video" /> <input type="hidden" name="campaign_id" value="'+$("#content").data("campaignid")+'" /> <input type="text" class="url_video" name="url_video" id="text_url_video" placeholder="Saissisez l\'url de votre vidéo" value="'+url_video_link+'"> <input style="display:none;" id="wdg-edit-video-image" type="file" class="image_video_zone" name="image_video_zone"/> </form>';
			$(".block_url_video").after(newElement);

			newElement = '<input type="button" id="wdg-edit-video-image_update" value="Télécharger une image d\'aperçu ..."/>';
			$(".block_url_image").after(newElement);
			var span_image = '<span id="extra-comment-image">(Max. 2Mo ; idéalement 870px de largeur * 460px de hauteur)</span>';
			$("#upload-video-form").after(span_image);

			$("#wdg-edit-video-image_update").click(function() {
				$("#wdg-edit-video-image").click();
			});
			
			var image_check = false;
			var image_src = '';
			$(".image_video_zone").change(function(){
				if (this.files) {
					$.each(this.files, function(index, file) {
						switch (file.type) {
							case "image/jpeg":
							case "image/jpg":
							case "image/png":
							case "image/gif":
								var reader = new FileReader();
								reader.onload = function (e) {
									$("#apercu_image").remove();
									image_src = e.target.result;
									var Element_image_view = '<div id="apercu_image"><img style="margin:10px;" height="200" id="video-zone-image" src="'+image_src+'"></div>';
									$(".block_overview_image").after(Element_image_view);
									$("#url_image_link").val( image_src );
								}
								reader.readAsDataURL(file);
							default:
							break;
						}
					});
					image_check = true;
				}
			});

			var video_check = false; 
			var video_number = '';
			$(".url_video").change(function(){
				$("#apercu_video").remove();
				var youtube_id = false;
				var vimeo_id = '';
				if ($("#text_url_video").val().indexOf('watch?v=') > -1) {
					youtube_id = $("#text_url_video").val().split('watch?v=')[1];
				} else if ($("#text_url_video").val().indexOf('youtu.be') > -1) {
					youtube_id = $("#text_url_video").val().split('youtu.be/')[1];
				} else if ( $("#text_url_video").val().indexOf('vimeo') > -1 ) {
					var urlVimeoSplit = $("#text_url_video").val().split('/');
					vimeo_id = urlVimeoSplit[ urlVimeoSplit.length - 1 ];
					if ( vimeo_id === '' ) {
						vimeo_id = urlVimeoSplit[ urlVimeoSplit.length - 2 ];
					}
				}
				
				if (youtube_id) {
					var link = "https://www.youtube.com/embed/"+video_number+"?feature=oembed&rel=0&wmode=transparent";
					var video_preview = "<div id='apercu_video'><iframe width='290' height='100%' src='"+link+"' frameborder='0' id='myFrame' allowfullscreen/></div>";
					$(".block_overview_video").after(video_preview);
					video_check = true;
					$("#url_video_link").val($("#text_url_video").val());
					$("#text_url_video").addClass("input_text_good");
					
				} else if ( vimeo_id !== '' ) {
					var link = "https://player.vimeo.com/video/"+vimeo_id;
					var video_preview = "<div id='apercu_video'><iframe src=\""+link+"\" width=\"290\" height=\"100%\" frameborder=\"0\" allowfullscreen></iframe></div>";
					$(".block_overview_video").after(video_preview);
					video_check = true;
					$("#url_video_link").val($("#text_url_video").val());
					$("#text_url_video").addClass("input_text_good");
					
					
				} else {
					video_number = $("#text_url_video").val().split('dailymotion')[1];
					if(video_number){
						var video_preview = "<div id='apercu_video'><iframe width='290' height='100%' src='"+$("#text_url_video").val()+"' frameborder='0' id='myFrame' allowfullscreen/></div>";
						$(".block_overview_video").after(video_preview);
						video_check = true;
						$("#url_video_link").val($("#text_url_video").val());
						$("#text_url_video").addClass("input_text_good");
					}else{
						$("#text_url_video").addClass("input_text_error");
						$("#text_url_video").val(url_video_link);
						var video_preview = "<div id='apercu_video' ><iframe width='290' height='100%' src='"+url_video_link+"' frameborder='0' id='myFrame' allowfullscreen/></div>";
						$(".block_overview_video").after(video_preview);
					}
				}
			});

			newElement = '<input type="submit" id="wdg-edit-video-zone-next_valid" value="Valider"/>';
			$(".block_boutons").after(newElement);
			newElement = '<input type="submit" id="wdg-edit-video-zone-next_cancel" value="Annuler"/>';
			$(".block_boutons").after(newElement);

			$("#wdg-edit-video-zone-next_cancel").click(function() {
				ProjectEditor.validateInputDone(true);
				$("#upload-video-form").remove();
				$("#wdg-edit-video-image_update").remove();
				$("#wdg-edit-video-zone-next_valid").remove();
				$("#wdg-edit-video-zone-next_cancel").remove();
				$(".project-pitch-video-bis").remove();
				$("#extra-comment-image").remove();
				var background = $("#project-banner-picture").css('background-image');
				if(background)
					$("#project-banner-picture").attr('style','display:inline-block; background-image:'+background+'; background-repeat:no-repeat;');
				else
					$("#project-banner-picture").attr('style','display:inline-block;');
				$("#wdg-edit-"+property).show();
				$("#project-banner-picture").show();
			});

			$("#wdg-edit-video-zone-next_valid").click(function() {
				$("#wdg-edit-video-zone-next_valid").remove();
				$("#wdg-validate-video-wait").show();
				$("#extra-comment-image").remove();

  				var formData = new FormData($('form#upload-video-form')[0]);
				$("#wdg-edit-video-image_update").remove();
				$("#wdg-edit-video-zone-next_cancel").remove();
				$("#apercu_image").remove();
				$("#apercu_video").remove();
				$("#text_url_video").attr('style','display:none;');
  				$.ajax({
					'type' : "POST",
					'url' :ajax_object.ajax_url,
					'data': formData,
		            'cache': false,
		            'contentType': false,
		            'processData': false
				}).done(function(result) {
					ProjectEditor.validateInputDone(result);
					if ( video_check ){
						$("#project-banner-picture").remove();
						var youtube_id = false;
						var vimeo_id = '';
						if ($("#text_url_video").val().indexOf('watch?v=') > -1) {
							youtube_id = $("#text_url_video").val().split('watch?v=')[1];
						} else if ($("#text_url_video").val().indexOf('youtu.be') > -1) {
							youtube_id = $("#text_url_video").val().split('youtu.be/')[1];
						} else if ( $("#text_url_video").val().indexOf('vimeo') > -1 ) {
							var urlVimeoSplit = $("#text_url_video").val().split('/');
							vimeo_id = urlVimeoSplit[ urlVimeoSplit.length - 1 ];
							if ( vimeo_id === '' ) {
								vimeo_id = urlVimeoSplit[ urlVimeoSplit.length - 2 ];
							}
						}
						var link = $("#url_video_link").val();
						if (youtube_id) {
							link = "https://www.youtube.com/embed/"+youtube_id+"?feature=oembed&rel=0&wmode=transparent";
						} else if ( vimeo_id !== '' ) {
							link = "https://player.vimeo.com/video/"+vimeo_id;
						}
						var div_video = '<div id="project-banner-picture"><iframe width="578" height="325" src="'+link+'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
						$("#url_video_link").after(div_video);
						video_check = false;
						
					}else if( image_check ){
						if (!url_video_link) {
							image_src = result.split("|")[0];
							$("#project-banner-picture").remove();
							var div_video = '<div id="project-banner-picture"><img id="project-banner-src" src="'+image_src+'"></div>';
							$("#url_video_link").after(div_video);
						}else{
							$("#project-banner-picture").attr('style','display:inline-block;');
						}
						image_check = false;
					}
					$("#wdg-validate-video-wait").hide();
					$("#wdg-validate-video-wait").remove();
					$("#upload-video-form").remove();
					$(".project-pitch-video-bis").remove();
					$("#wdg-edit-"+property).show();
					$("#project-banner-picture").show();
				});
			});

		},



		//Création d'un champ input pour certaines valeurs
		createInput: function(property) {
			var initValue = ProjectEditor.getInitValue(property);
			var placeholder = (property === "subtitle") ? 'Slogan de la levée de fonds' : '';
			var newElement = '<input type="text" id="wdg-input-'+property+'" class="edit-input" value="'+initValue+'" placeholder="'+placeholder+'" />';
			$(ProjectEditor.elements[property].elementId).after(newElement);
			
			var buttonValidate = '<div id="wdg-validate-'+property+'" class="edit-button-validate" data-property="'+property+'"></div>';
			$("#wdg-input-"+property).after(buttonValidate);
			$("#wdg-validate-"+property).css("left", $("#wdg-input-"+property).position().left + $("#wdg-input-"+property).outerWidth());
			$("#wdg-validate-"+property).css("top", $("#wdg-input-"+property).position().top - $("#wdg-input-"+property).outerHeight());
			$("#wdg-validate-"+property).click(function() {
				ProjectEditor.validateInput($(this).data("property"));
			});
			
			$(ProjectEditor.elements[property].elementId).hide();
			$("#wdg-edit-"+property).hide();
		},
		
		//Création d'un champ textarea pour certaines valeurs
		createTextArea: function(property) {
			var initValue = ProjectEditor.getInitValue(property);
			var placeholder = (property === "rewards") ? 'X% par an et un avantage' : '';
			var newElement = '<textarea id="wdg-input-'+property+'" class="edit-input" placeholder="'+placeholder+'">'+initValue+'</textarea>';
			$(ProjectEditor.elements[property].elementId).after(newElement);
			$("#wdg-input-"+property).css("left", $(ProjectEditor.elements[property].elementId).position().left);
			var marginTop = Number($(ProjectEditor.elements[property].elementId).css("marginTop").replace("px", ""));
			$("#wdg-input-"+property).css("top", $(ProjectEditor.elements[property].elementId).position().top + marginTop);
			var width = $(ProjectEditor.elements[property].elementId).width() - 4;
			$("#wdg-input-"+property).width(width);
			$("#wdg-input-"+property).height($(ProjectEditor.elements[property].elementId).height());
			
			$("#wdg-input-"+property).css("font-family","Arial,sans-serif");
			$("#wdg-input-"+property).css("font-size","14px");
                        
			var buttonValidate = '<div id="wdg-validate-'+property+'" class="edit-button-validate" data-property="'+property+'"></div>';
			$("#wdg-input-"+property).after(buttonValidate);
			$("#wdg-validate-"+property).css("left", $("#wdg-input-"+property).position().left + $("#wdg-input-"+property).outerWidth());
			$("#wdg-validate-"+property).css("top", $("#wdg-input-"+property).position().top - $("#wdg-input-"+property).outerHeight());
			$("#wdg-validate-"+property).click(function() {
				ProjectEditor.validateInput($(this).data("property"));
			});
			
			$(ProjectEditor.elements[property].elementId).hide();
			$("#wdg-edit-"+property).hide();
		},
		
		//Création d'un champ textarea avancé pour certaines valeurs
		showEditableZone: function(property) {
			$(ProjectEditor.elements[property].elementId).hide();
			$("#wdg-edit-"+property).hide();
			
			$(ProjectEditor.elements[property].contentId).show();
			$(ProjectEditor.elements[property].contentId + "> div").show();
			var buttonValidate = '<div id="wdg-validate-'+property+'" class="edit-button-validate" data-property="'+property+'" title="Enregistrer"></div>';
			$(ProjectEditor.elements[property].contentId).after(buttonValidate);
			$("#wdg-validate-"+property).css("left", $(ProjectEditor.elements[property].contentId).position().left + $(ProjectEditor.elements[property].contentId).outerWidth());
			$("#wdg-validate-"+property).css("top", $(ProjectEditor.elements[property].contentId).position().top);
			$("#wdg-validate-"+property).click(function() {
				ProjectEditor.validateInput($(this).data("property"));
			});
			
			var buttonCancel = '<div id="wdg-cancel-'+property+'" class="cancel-button" data-property="'+property+'" title="Annuler l\'édition"></div>';
			$(ProjectEditor.elements[property].contentId).after(buttonCancel);
			$("#wdg-cancel-"+property).css("left", $(ProjectEditor.elements[property].contentId).position().left + $(ProjectEditor.elements[property].contentId).outerWidth());
			$("#wdg-cancel-"+property).css("top", $(ProjectEditor.elements[property].contentId).position().top);
			$("#wdg-cancel-"+property).click(function() {
				ProjectEditor.cancelInput(property);
			});

			ProjectEditor.hideAllEditButton(); 
			ProjectEditor.initContent = tinyMCE.get("wdg-input-"+property).getContent();
			$("#wdg-edit-"+property).removeClass("wait-button");

			$(window).scroll(function() {
				var scrollFromTop = window.scrollY;
				var heightNavBar = $("div#content.version-3 nav.project-navigation").height(); // hauteur de la barre du menu
				var topButtonValidate = $(ProjectEditor.elements[property].contentId).position().top; // position du bouton enregistré par rapport à l'encadrer de la partie
				var margin = 10; // marge entre la barre de menu et la position de bouton
      			var validatePosition = $("#wdg-validate-"+property);
                var cancelPosition = $("#wdg-cancel-"+property);
                var buttonsHeight = validatePosition.height();                                                
      			var container = $(ProjectEditor.elements[property].contentId);
			    var containerHeight = container.height();
			    var containerOffset = (container.offset().top);
      			var maxScroll = containerOffset + containerHeight;

      			if ( scrollFromTop < maxScroll ) {
         			var size = scrollFromTop - containerOffset + topButtonValidate + heightNavBar + margin;
         			if ( size > topButtonValidate && size < containerHeight + buttonsHeight ) {                        
                        validatePosition.css('top', (size)+"px");
                        cancelPosition.css('top', (size)+"px");
             		}
             	}
			});
		},
		
		//Redirige vers la page Paramètres
		redirectParams: function(property) {
			window.location.href = $(".project-admin").data("link-project-settings") + "#" + property;
		},
                
		//Redirections pour l'édition de l'organisation
		redirectOrganization: function(property) {
			window.location.href = $(".project-banner-info-item.author-info").data("link-edit") + "#" + property;
		},
		
		//Enregistre le contenu d'un élément saisi
		validateInput: function(property) {
			var value = $("#wdg-input-"+property).val();
			switch (property) {
				case "summary":
				case "rewards":
					value = (value + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + '<br />' + '$2');
					break;
				case "description":
				case "societal_challenge":
				case "added_value":
				case "economic_model":
				case "implementation":
					value = tinyMCE.get("wdg-input-"+property).getContent();
					break;
			}
		    
			$("#wdg-validate-"+property).addClass("wait-button");
			$("#wdg-validate-"+property).unbind("click");
			if ( $( '#wdg-cancel-' + property ).length > 0 ) {
				$( '#wdg-cancel-' + property ).remove();
			}
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': { 
					'action':	'save_edit_project',
					'property':	property,
					'value':	value,
					'id_campaign':  $("#content").data("campaignid"),
					'lang':		$("html").attr("lang").split("-").join("_")
				}
			}).done(function(result) {
				result = JSON.parse(result);
				if ( result.response == 'error') {
					ProjectEditor.lockProjectFail(result.user, result.values);
				} else {
					ProjectEditor.validateInputDone(result.values);
					$("#project-content-"+property).data("md5", result.md5content);
				}
			});
		},
		
		//Fin de validation d'un élément précis, donc réaffichage normal
		validateInputDone: function(property) { 
			switch (property) {
				case "title":
				case "subtitle":
				case "summary":
				case "rewards":
					ProjectEditor.updateText(property);
					$(ProjectEditor.elements[property].elementId).show();
					$("#wdg-input-"+property).remove();
					ProjectEditor.showEditButton(property);
					$("#wdg-validate-"+property).remove();
					break;
				case "description":
				case "societal_challenge":
				case "added_value":
				case "economic_model":
				case "implementation":
					ProjectEditor.updateText(property);
					$(ProjectEditor.elements[property].elementId).show();
					$(ProjectEditor.elements[property].contentId).hide();
					ProjectEditor.showEditButton(property);
					$("#wdg-validate-"+property).remove();
					$("#wdg-cancel-"+property).remove();
					break;
			}
			ProjectEditor.keepUserLockProject();
			WDGProjectPageFunctions.initClick();
			WDGProjectPageFunctions.isEditing = "";
		},

		backToEditMode: function(property) {
			$(ProjectEditor.elements[property].elementId).show();
			$(ProjectEditor.elements[property].contentId).hide();
			ProjectEditor.showEditButton(property);
			$("#wdg-validate-"+property).remove();
			$("#wdg-cancel-"+property).remove();
			WDGProjectPageFunctions.initClick();
			WDGProjectPageFunctions.isEditing = "";
			tinyMCE.get("wdg-input-"+property).setContent(ProjectEditor.initContent);
		},
		
		//Gère l'annulation de l'édition
		cancelInput: function(property) {
			if ( ProjectEditor.softCancel ) {
				ProjectEditor.backToEditMode(property);
				ProjectEditor.softCancel = null;
			} else {
				var confirmCancel = window.confirm("Attention, vous êtes sur le point d'arrêter l'édition et de perdre toutes vos modifications, voulez-vous continuer ?");
				if ( confirmCancel ) {
					$("#wdg-cancel-"+property).addClass("wait-button");
					$( '#wdg-validate-' + property ).remove();
					$.ajax({
						'type' : "POST",
						'url' : ajax_object.ajax_url,
						'data': { 
							'action': 'delete_lock_project_edition',
							'property':	property,
							'id_campaign': $("#content").data("campaignid"),
							'lang':		$("html").attr("lang").split("-").join("_")
						}
					}).done(function(property) {
						ProjectEditor.keepUserLockProject();
						ProjectEditor.backToEditMode(property);
					});
				}
			}		    
		},
		
		//Mise à jour du texte dans l'affichage
		updateText: function(property) { 
			switch (property) {
				case "title":
				case "subtitle":
					$(ProjectEditor.elements[property].contentId).text($("#wdg-input-"+property).val());
					break;
				case "summary":
				case "rewards":
					var value = $("#wdg-input-"+property).val();
					value = (value + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + '<br />' + '$2');
					$(ProjectEditor.elements[property].contentId).html(value);
					break;
				case "description":
				case "societal_challenge":
				case "added_value":
				case "economic_model":
				case "implementation":
					$(ProjectEditor.elements[property].elementId).html(tinyMCE.get("wdg-input-"+property).getContent());
					break;
			}
		},
		
		
		//Déplacement de l'image dans le header
		moveHeaderPicture:function() {
			$('.project-banner-img').draggable({ axis: "y" });
			$('.project-banner-img').draggable('enable');
			$('#wdg-edit-picture-head').hide();
			$('#wdg-move-picture-head').addClass("edit-button-validate");
			$('#wdg-move-picture-head').removeClass('move-button');
			$(".project-banner-content").css({ opacity: 0 });
			$(".project-banner-content").css({ 'z-index': -1 });
			$(".project-banner-deco").css({ opacity: 0 });
			$(".project-banner-deco").css({ 'z-index': -1 });
		},

		//Enregistrement de la position de l'image dans le header
		saveHeaderPicturePosition:function(){
//			$('.project-banner-img').draggable('disable');
			$('#wdg-move-picture-head').addClass('wait-button');
			$('#wdg-move-picture-head').removeClass("edit-button-validate");
			$(".project-banner-content").css({ opacity: 1 });
			$(".project-banner-content").css({ 'z-index': "auto" });
			$(".project-banner-deco").css({ opacity: 1 });
			$(".project-banner-deco").css({ 'z-index': "auto" });
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': { 
					'action':   'setCoverPosition',
					'top':	    $('.project-banner-img').css('top'),
					'id_campaign': $("#content").data("campaignid")
				}
			}).done(function() {
				$('#wdg-move-picture-head').addClass('move-button');
				$('#wdg-move-picture-head').removeClass('wait-button');
				$('#wdg-edit-picture-head').show();
			});
		},

		//Déplacement du curseur de localisation
		moveCursor:function(){
			$('#map-cursor').draggable({
				containment: '#project-map'
			});
			$('#map-cursor').draggable('enable');
			$('#wdg-move-picture-location').addClass("edit-button-validate");
			$('#wdg-move-picture-location').removeClass('move-button');
		},

		//Enregistrement de la position du curseur de localisation
		saveCursorPosition:function(){
			$('#map-cursor').draggable('disable');
			$('#wdg-move-picture-location').addClass('wait-button');
			$('#wdg-move-picture-location').removeClass("edit-button-validate");
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': { 
					'action': 'setCursorPosition',
					'top': $('#map-cursor').css('top'),
					'left': $('#map-cursor').css('left'),
					'id_campaign': $("#content").data("campaignid")
				}
			    
			}).done(function() {
				$('#wdg-move-picture-location').addClass('move-button');
				$('#wdg-move-picture-location').removeClass('wait-button');
			}); 
		},
		
		analyseImageFiles: function() {
			if ( ( '#project-banner-picture img' ).length > 0 ) {
				var sSrc = $( '#project-banner-picture img' ).attr( 'src' );
				ProjectEditor.analyseImageSingleFile( sSrc, 'image de présentation' );
			}
			
			$( '#project-content-description .zone-content img' ).each( function() {
				var sSrc = $( this ).attr( 'src' );
				if ( sSrc.indexOf( '/wp-content/uploads/' ) > -1 ) {
					ProjectEditor.analyseImageSingleFile( sSrc, 'partie Pitch' );
				}
			} );
			
			$( '#project-content-societal_challenge .zone-content img' ).each( function() {
				var sSrc = $( this ).attr( 'src' );
				if ( sSrc.indexOf( '/wp-content/uploads/' ) > -1 ) {
					ProjectEditor.analyseImageSingleFile( sSrc, 'partie Impacts positifs' );
				}
			} );
			
			$( '#project-content-added_value .zone-content img' ).each( function() {
				var sSrc = $( this ).attr( 'src' );
				if ( sSrc.indexOf( '/wp-content/uploads/' ) > -1 ) {
					ProjectEditor.analyseImageSingleFile( sSrc, 'partie Stratégie' );
				}
			} );
			
			$( '#project-content-economic_model .zone-content img' ).each( function() {
				var sSrc = $( this ).attr( 'src' );
				if ( sSrc.indexOf( '/wp-content/uploads/' ) > -1 ) {
					ProjectEditor.analyseImageSingleFile( sSrc, 'partie Données financières' );
				}
			} );
			
			$( '#project-content-implementation .zone-content img' ).each( function() {
				var sSrc = $( this ).attr( 'src' );
				if ( sSrc.indexOf( '/wp-content/uploads/' ) > -1 ) {
					ProjectEditor.analyseImageSingleFile( sSrc, 'partie Equipe' );
				}
			} );
		},
		
		analyseImageSingleFile: function( sSrc, sLocation ) {
			var xhr = $.ajax({
				type: "HEAD",
				url: sSrc,
				success: function(){
					var nBytes = xhr.getResponseHeader( 'Content-Length' );
					var nKBytes = nBytes / 1024;
					if ( nKBytes > 200 ) {
						var sUrl = this.url;
						var aSplitUrl = sUrl.split( '/' );
						var sFileName = aSplitUrl[ aSplitUrl.length - 1 ];
						ProjectEditor.addEditIntroErrorMessage();
						if ( nKBytes > 500 ) {
							ProjectEditor.addEditErrorMessage( sFileName + ' (' + sLocation + ') dépasse 500ko.', true );
						} else {
							ProjectEditor.addEditErrorMessage( sFileName + ' (' + sLocation + ') dépasse 200ko.' );
						}
					}
				}
			});
		},
		
		addEditIntroErrorMessage: function() {
			if ( $( '.project-admin div.intro' ).length == 0 ) {
				$( '.project-admin' ).append( '<div class="intro">Afin de ne pas surcharger votre page et accélérer le temps d\'ouverture, nous vous encourageons à limiter le poids des images à 200 Ko.</div>' );
			}
		},
		
		addEditErrorMessage: function( sMsg, bIsBig ) {
			var sClass = 'error';
			if ( bIsBig ) {
				sClass += ' error-big';
			}
			$( '.project-admin' ).append( '<div class="' + sClass + '">' + sMsg + '</div>' );
		}
	};
    
})(jQuery);