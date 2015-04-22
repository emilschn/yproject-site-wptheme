jQuery(document).ready( function($) {
    YPUIFunctions.initUI();
});


YPUIFunctions = (function($) {
	return {
		memberTabs: ["activity", "projects", "community"],
	    
		initUI: function() {
			YPMenuFunctions.initMenuBar();
			WDGProjectPageFunctions.initUI();
			YPUIFunctions.refreshProjectList();
			
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
				if ($('input[name=fundingtype]:checked').val() == "fundingproject") {
				    $(".min_amount_value").text($("#min_amount_project").val());
				}
				if ($('input[name=fundingtype]:checked').val() == "fundingdevelopment") {
				    $(".min_amount_value").text($("#min_amount_development").val());
				}
			    });
			}

			if ($("#input_invest_amount_part").length > 0) {
			    $("#input_invest_amount_part").change(function() {
				YPUIFunctions.checkInvestInput();
			    });

			    $("#link_validate_invest_amount").click(function() {
				$("#validate_invest_amount_feedback").show();
				$('html, body').animate({scrollTop: $('#link_validate_invest_amount').offset().top - $("#navigation").height()}, "slow"); 
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
			
			$( window ).resize(function() {
				YPUIFunctions.refreshProjectList();
			});

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
 	
			if ($("#user-id").length > 0) { 
				var sCurrentTab = window.location.hash.substring(1);
				if (sCurrentTab != '') YPUIFunctions.switchProfileTab(sCurrentTab);
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
			
			
			if ($(".wdg-lightbox").length > 0) {
				$(".wdg-button-lightbox-open").click(function() {
					var target = $(this).data("lightbox");
					$("#wdg-lightbox-" + target).show();
				});
				$(".wdg-lightbox .wdg-lightbox-button-close a").click(function() {
					$(".wdg-lightbox").hide();
				});
				$(".wdg-lightbox .wdg-lightbox-click-catcher").click(function() {
					$(".wdg-lightbox").hide();
				});
				var sHash = window.location.hash.substring(1);
				if ($("#wdg-lightbox-" + sHash).length > 0) {
					$("#wdg-lightbox-" + sHash).show();
				}
			}
			
			
			if ($("#blog-archives form#add-news").length > 0) {
				$("#blog-archives #add-news-opener").click(function() {
					if ($("#blog-archives form#add-news").is(":visible")) {
						$("#blog-archives form#add-news").hide();
					} else {
						$("#blog-archives form#add-news").show();
					}
				});
			}
			
			if ($("#email-selector").length > 0) {
				$(".select-options").change(function() {
					$("#email-selector-list span").hide();
					$(".select-options:checked").each(function() {
						$("#email-selector-list span."+$(this).data("selection")).show();
					});
				});
			}
                        
                        if ($(".check-users-columns").length > 0) {
                            //Page investisseurs/investors : Actions à la sélection des colonnes du tableau
                            $(".check-users-columns").click(function() {
                                //Case "toutes les colonnes
                                if(this.value==="all") {
                                    if (this.checked===true) {
                                        $('.check-users-columns').prop('checked', true);
                                        $('#investors-table td').removeAttr('hidden');
                                    } else {
                                        $('.check-users-columns').prop('checked', false);
                                        $('#investors-table td').attr('hidden','');
                                        $('#cbcoluname').prop('checked', true);
                                        $('.coluname').removeAttr('hidden');
                                    }
                                }
                                
                                //Autres cases
                                $selector = ".";
                                $selector += this.value;
                                if (this.checked===true) {
                                    $($selector).removeAttr('hidden');
                                } else {
                                    $($selector).attr('hidden','');
                                }
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
		},
		
		refreshProjectPreview:function () {
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
		},
		
		refreshProjectList: function() {
			if ($("#project-list-menu").length > 0) {
				$("#project-list-menu a").click(function() {
					$(".home-large-project").hide();
					$(".home-small-project").hide();
					$(".status-" + $(this).data("status")).show();
					$("#project-list-menu a").removeClass("selected");
					$(this).addClass("selected");
				});
				if ($("#project-list-menu").is(":visible")) {
					$(".home-large-project").hide();
					$(".home-small-project").hide();
					if ($(".status-collecte").length == 0) $("#project-list-menu [data-status='collecte']").remove();
					if ($(".status-vote").length == 0) $("#project-list-menu [data-status='vote']").remove();
					if ($(".status-preview").length == 0) $("#project-list-menu [data-status='preview']").remove();
					$("#project-list-menu a").first().trigger("click");
				} else {
					$(".home-large-project").show();
					$(".home-small-project").show();
				}
			}
			YPUIFunctions.refreshProjectPreview();
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
	    
	    $("#mobile-menu").click(function() {
		$("#submenu-mobile").toggle();
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
		currentDiv: 0,
		isInit: false,
		isEditing: false,
		initUI:function() {
			WDGProjectPageFunctions.initClick();
			$('.project-content-icon').click(function(){
				var contentDiv = $("#project-content-" + $(this).data("content"));
				contentDiv.trigger("click"); 	
			});
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
		},

		update_jycrois:function(jy_crois,campaign_id,home_url){
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
		    $('html, body').animate({scrollTop: $("#invest-button").offset().top - $("#navigation").height()}, "fast"); 
		    $("#vote-form").animate({ 
	        	top: "370px"
		    }, 500 );
		    $(".description-discover").css('background-color', '#7B7B7B');
		    $("#project-description-title-padding").height($("#vote-form").height() - $("#projects-right-desc").height());
		},
		
		
		
		//Initialisation du comportement des différentes parties
		initClick: function() {
			WDGProjectPageFunctions.currentDiv = 0;
			$(".projects-more").remove();
			$('.projects-desc-content').each(function(){
				//Si il y a plus d'un paragraphe, on initialise le clic
				if ($(this).find('p').length > 1) {
					$(this).css("cursor", "pointer");
					var sDisplay = '';
					if (!WDGProjectPageFunctions.isInit && WDGProjectPageFunctions.currentDiv === 0) sDisplay = 'style="display:none"';
					var sProjectMore = '<div class="projects-more" data-value="' + WDGProjectPageFunctions.currentDiv + '" '+sDisplay+'>Lire plus !</div>';
					$(this).find('div *:lt(1)').append(sProjectMore);
					$(this).click(function(){
						WDGProjectPageFunctions.clickItem($(this));
					});
				}

				//Rétractation des parties qui ne sont pas la description
				if (WDGProjectPageFunctions.isInit || WDGProjectPageFunctions.currentDiv > 0) {
					//On prend toutes les balises de la description
					var children = $(this).children().children();
					//On les masque sauf la première
					$(this).find(children.not('*:eq(0)')).hide();
				}
				WDGProjectPageFunctions.currentDiv++;
			});
			WDGProjectPageFunctions.refreshEditable();
			WDGProjectPageFunctions.isInit = true;
   		},
		
		//Clic sur une partie
		clickItem: function(clickedElement) {
			if (!WDGProjectPageFunctions.isEditing) {
				//Si la balise "lire plus" de l'élément cliqué est affichée
				var projectMore = clickedElement.find('.projects-more');
				if (projectMore.is(':visible')) {
					//il faut la masquer puis afficher les éléments qui suivent
					projectMore.hide(400, function(){
						$('html, body').animate({scrollTop: clickedElement.offset().top - $("#navigation").height()}, "slow"); 
						clickedElement.find('.zone-content > p, ul, table, blockquote').slideDown(400);
						WDGProjectPageFunctions.refreshEditable();
					});
					//on masque aussi toutes les autres parties
					WDGProjectPageFunctions.hideOthers(parseInt(projectMore.attr("data-value")));
				//Sinon on masque tout
				} else {
					WDGProjectPageFunctions.hideOthers(-1);
					WDGProjectPageFunctions.refreshEditable();
				}
			}
		},

		//Masque des parties non utilisées
		hideOthers:function(currentDiv){
			//Parcours des différentes parties
			var index = 0;
	 		$('.projects-desc-content').each(function(){
				//On teste pour masquer toutes celles qui ne sont pas celle clickée
		 		if (index !== currentDiv) {
		 			$(this).find('.projects-more').slideDown(200);
		 			$(this).children().children().not('*:eq(0)').slideUp(400);
		 		}
		 		index++;
			});
		},
		
		//Rafraichit chacune des zones pour savoir si elles sont éditables
		refreshEditable: function() {
			$(".projects-desc-content .zone-content").removeClass("editable");
			$('.projects-desc-content').each(function(){
				var projectMore = $(this).find('.projects-more');
				var property = $(this).attr("id").substr(("project-content-").length);
				//Si le Lire plus est visible, la zone n'est pas éditable
				if (projectMore.is(':visible')) {
					WDGProjectPageFunctions.hideEditButton(property);
				//Si le Lire plus n'est pas visible & si la page est en cours d'édition, la zone est éditable
				} else if ($("#content").hasClass("editing")) {
					$(this).children(".zone-content").addClass("editable");
					WDGProjectPageFunctions.showEditButton(property);
				//Sinon, la zone n'est pas éditable
				} else {
					WDGProjectPageFunctions.hideEditButton(property);
				}
			});
		},
		
		//Affiche le bouton d'édition d'une zone en particulier
		showEditButton: function(property) {
			if (typeof ProjectEditor !== 'undefined') {
				ProjectEditor.showEditButton(property);
			}
		},
		
		//Masque le bouton d'édition d'une zone en particulier
		hideEditButton: function(property) {
			if (typeof ProjectEditor !== 'undefined') {
				ProjectEditor.hideEditButton(property);
			}
		}
		
	};
})(jQuery);
