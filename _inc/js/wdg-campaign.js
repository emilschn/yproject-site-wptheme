jQuery(document).ready( function($) {
    WDGProjectViewer.init();
	WDGProjectVote.init();
	WDGProjectPageFunctions.initUI();
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

			if ($("#scroll-to-utilite-societale").length > 0) {
				$("#scroll-to-utilite-societale").click(function() {
					$('html, body').animate({scrollTop: $('#anchor-societal_challenge').offset().top - $("#navigation").height()}, "slow");
				});
			}
			
			if ( $( document ).width() < 997 ) {
				if ( $( 'div#project-banner-picture .video-element iframe' ).length > 0 ) {
					var nWidthIframe = $( 'div#project-banner-picture .video-element iframe' ).attr( 'width' );
					var nWidth = $( 'div#project-banner-picture .video-element iframe' ).width();
					var nCoef = nWidthIframe / nWidth;
					var nHeight = $( 'div#project-banner-picture .video-element iframe' ).attr( 'height' );
					$( 'div#project-banner-picture .video-element iframe' ).height( nHeight / nCoef );
				}
				
				$( 'div.projects-desc-content iframe' ).each( function() {
					var nWidthIframe = $( this ).attr( 'width' );
					var nWidth = $( 'div.projects-desc-content' ).width() - 20;
					var nCoef = nWidthIframe / nWidth;
					var nHeight = $( this ).attr( 'height' );
					$( this ).height( nHeight / nCoef );
				} );
			}
			
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
			$("input#init_invest").change(function() {
				if ($(".project-rewards-padder div.hidden").length > 0) {
					$(".project-rewards-padder div.hidden").show();
					
				} else {
					var percentProject = Number( $( 'input#roi_percent_project' ).val() );
					var goalProject = Number( $( 'input#roi_goal_project' ).val() );
					var maxProfit = Number( $( 'input#roi_maximum_profit' ).val() );
					var estimatedTurnoverUnit = $( 'input#estimated_turnover_unit' ).val();
					$( '#error-maximum, #error-input, #error-amount' ).hide( 100 );
					
					var bIsCorrectInput = true;
					var sInput =  $( this ).val();
					sInput = sInput.split( ' ' ).join( '' );
					sInput = sInput.split( ',' ).join( '.' );
					var inputVal = Number( sInput );
					if ( isNaN( inputVal ) ) {
						inputVal = 0;
						$( '#error-input' ).show( 100 );
						bIsCorrectInput = false;
					}
					if ( inputVal < 0 || inputVal !== parseInt( inputVal, 10 ) ) {
						inputVal = 0;
						$( '#error-amount' ).show( 100 );
						bIsCorrectInput = false;
					}
					if ( inputVal > goalProject ) {
						$( '#error-maximum' ).show( 100 );
						bIsCorrectInput = false;
					}
					
					if ( bIsCorrectInput ) {
						var maxRoi = inputVal * maxProfit;
						var maxRoiRemaining = maxRoi;
						var ratioOfGoal = inputVal / goalProject;
						var amountOfGoal = 0;
						var ratioOfPercent = ratioOfGoal * percentProject;
						var ratioOfPercentRound = Math.round(ratioOfPercent * 100000) / 100000;
						var ratioOfPercentRoundStr = ratioOfPercentRound.toString().replace('.', ',');
						$("span.roi_percent_user").text(ratioOfPercentRoundStr);

						$("div.project-rewards-content table tr:first-child td span.hidden").each(function(index) {
							var estTO = Number($(this).text());
							var amountOfTO = 0;
							if ( estimatedTurnoverUnit == 'percent' ) {
								amountOfTO = Math.round( inputVal * estTO ) / 100;
							} else {
								amountOfTO = estTO * ratioOfPercent / 100;
							}
							// Gestion du plafond de versement
							if ( maxRoiRemaining < amountOfTO ) {
								amountOfTO = maxRoiRemaining;
							}
							maxRoiRemaining -= amountOfTO;
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
				}
			});
			
			if ( $( '#wdg-lightbox-project-warning button.close' ).length > 0 ) {
				var hideprojectwarning = YPUIFunctions.getCookie( 'hideprojectwarning' );
				if ( hideprojectwarning != '1' ) {
					$( '#wdg-lightbox-project-warning' ).show();
				}
				$( '#wdg-lightbox-project-warning button.close' ).click( function() {
					var date = new Date();
					var days = 10;
					date.setTime(date.getTime()+(days*24*60*60*1000));
					var expires = "; expires="+date.toGMTString();
					document.cookie = "hideprojectwarning=1"+expires+"; path=/";
				} );
			}

			$( '.project-description-item a' ).attr( 'target', '_blank' );
			$( '.project-description-item a > img, .project-news-item a > img' ).parent().css( 'cursor', 'default' );
			$( '.project-description-item a > img, .project-news-item a > img' ).parent().click( function( e ) { e.preventDefault(); } );
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
		currentSlide: 0,
		minSlide: 1,
		maxSlide: 4,
		init: function() {
			if ( $( 'div#vote-form' ).length > 0 ) {
				$( 'div#vote-form div#vote-form-buttons button.previous' ).click( function( e ) {
					e.preventDefault();
					WDGProjectVote.slidePrevious();
				} );
				$( 'div#vote-form div#vote-form-buttons button.next' ).click( function( e ) {
					e.preventDefault();
					WDGProjectVote.slideNext();
				} );
			
				// Sondage
				if ( $( '#field-would-invest-more-amount' ).length > 0 ) {
					$( $( '#would-invest-more-amount-yes' ) ).change( function() {
						$( '#field-would-invest-amount-with-warranty' ).show( 100 );
					} );
					$( $( '#would-invest-more-amount-no, #would-invest-more-amount-maybe' ) ).change( function() {
						$( '#field-would-invest-amount-with-warranty' ).hide( 100 );
					} );
				}
				if ( $( '#field-would-invest-more-number' ).length > 0 ) {
					$( $( '#would-invest-more-number-yes' ) ).change( function() {
						$( '#field-would-invest-number-per-year-with-warranty' ).show( 100 );
					} );
					$( $( '#would-invest-more-number-no, #would-invest-more-number-maybe' ) ).change( function() {
						$( '#field-would-invest-number-per-year-with-warranty' ).hide( 100 );
					} );
				}

				$( '#wdg-lightbox-preinvest-warning button.transparent' ).click( function() {
					setTimeout( function() {
						var currentAddress = location.href;
						var newUrl = currentAddress.split( '#' )[ 0 ];
						window.location = newUrl + '#vote-share';
						location.reload( true );
					}, 100 );
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
			
			$( 'div#vote-form div#vote-form-buttons button' ).hide();
			if ( WDGProjectVote.currentSlide > WDGProjectVote.minSlide ) {
				$( 'div#vote-form div#vote-form-buttons button.previous' ).show();
			}
			if ( WDGProjectVote.currentSlide < WDGProjectVote.maxSlide ) {
				$( 'div#vote-form div#vote-form-buttons button.next' ).show();
			}
			if ( WDGProjectVote.currentSlide === WDGProjectVote.maxSlide ) {
				$( 'div#vote-form div#vote-form-buttons button.save' ).show();
			}
			$( '#wdg-lightbox-vote .wdg-lightbox-padder' ).animate( { scrollTop: 0 }, "slow" );
		},
		
		saveVoteCallback: function( result ) {
			var jsonResult = JSON.parse( result );
			if ( jsonResult.errors.length > 0 ) {
				WDGProjectVote.currentSlide = jsonResult.gotoslide;
				WDGProjectVote.refresh();
				$( '#wdg-lightbox-vote .wdg-lightbox-padder' ).animate( { scrollTop: 0 }, "slow" );
			} else {
				if ( $( '#wdg-lightbox-vote #invest-sum' ).val() > 0 ) {
					$( '#wdg-lightbox-user-details-vote' ).show();
				} else {
					$( '#wdg-lightbox-vote-simple-confirmation' ).show();
					setTimeout( function() {
						var currentAddress = location.href;
						var newUrl = currentAddress.split( '#' )[ 0 ];
						window.location = newUrl + '#vote-share';
						location.reload( true );
					}, 1000 );
				}
				$( '#wdg-lightbox-vote' ).remove();
				$( 'a[href="#vote"]' ).click( function( e ) { e.preventDefault(); } );
				$( 'a[href="#vote"]' ).text( $( 'a[href="#vote"]' ).data( 'thankyoumsg' ) );
				$( 'a[href="#vote"]' ).addClass( 'disabled' );
				$( 'a[href="#vote"]' ).removeClass( 'wdg-button-lightbox-open' );
				$( 'a[href="#vote"]' ).data( 'lightbox', '' );
				$( 'a[href="#vote"]' ).attr( 'href', '#' );
			}
		}
	};
    
})(jQuery);

/* Projet */
WDGProjectPageFunctions=(function($) {
	return {
		currentDiv: 0,
		isInit: false,
		isEditing: "",
		isClickBlocked: false,
		navigationHeight: 0,
		initUI:function() {
			WDGProjectPageFunctions.navigationHeight = ($("nav.project-navigation").height() > 0 && $("nav.project-navigation").is(':visible')) ? $("nav.project-navigation").height() : $("nav").height();
			WDGProjectPageFunctions.initClick();
			$('.project-content-icon').click(function(){
				var contentDiv = $("#project-content-" + $(this).data("content"));
				contentDiv.trigger("click");
			});
			$('.project-content-icon').css("cursor", "pointer");

			$("#btn-validate_project-true").click(function(){
				$("#validate_project-true").show();
				$("#validate_project-false").hide();
			});
			$("#btn-validate_project-false").click(function(){
				$("#validate_project-false").show();
				$("#validate_project-true").hide();
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


		//Initialisation du comportement des différentes parties
		initClick: function() {
			WDGProjectPageFunctions.currentDiv = 0;
			$(".projects-more").remove();
			$('.projects-desc-content').each(function(){
				//Si il y a plus d'un paragraphe, on initialise le clic
				if ($(this).find('p').length > 1) {
					$(this).css("cursor", "pointer");
					var sDisplay = '';
					if ((!WDGProjectPageFunctions.isInit && WDGProjectPageFunctions.currentDiv === 0) || $(this).attr("id") === "project-content-" + WDGProjectPageFunctions.isEditing) sDisplay = 'style="display:none"';
					var sProjectMore = '<div class="projects-more" data-value="' + WDGProjectPageFunctions.currentDiv + '" '+sDisplay+'><button class="button transparent">Lire plus</button></div>';
					$(this).find('div *:lt(1)').append(sProjectMore);
					$(this).unbind("click");
					$(this).click(function(){
						WDGProjectPageFunctions.clickItem($(this));
					});
				}

				//Rétractation des parties qui ne sont pas la description
				if ((WDGProjectPageFunctions.isInit || WDGProjectPageFunctions.currentDiv > 0) && $(this).attr("id") !== "project-content-" + WDGProjectPageFunctions.isEditing) {
					//On prend toutes les balises de la description
					var children = $(this).children().children();
					//On les masque sauf la première
					$(this).find(children.not('*:eq(0)')).hide();
				}
				WDGProjectPageFunctions.currentDiv++;
			});
			$('.projects-desc-content img, .projects-desc-content .expandator, .projects-desc-content .edit-button, .projects-desc-content .edit-button-validate').click(function() {
				WDGProjectPageFunctions.isClickBlocked = true;
			});
			WDGProjectPageFunctions.refreshEditable();
			WDGProjectPageFunctions.isInit = true;
		},

		//Clic sur une partie
		clickItem: function(clickedElement) {
			//Ne déclenche pas d'action si l'utilisateur sélectionnait du texte
			var select = getSelection().toString();
			if (!select && WDGProjectPageFunctions.isEditing === "" && !WDGProjectPageFunctions.isClickBlocked) {
				//Si la balise "lire plus" de l'élément cliqué est affichée
				var projectMore = clickedElement.find('.projects-more');
				if (projectMore.is(':visible')) {
					//il faut la masquer puis afficher les éléments qui suivent
					projectMore.hide(400, function(){
						setTimeout( function() {
							var offset = - 60;
							if ( $( document ).width() < 997 ) {
								offset = - 45;
							}
							$('html, body').animate({scrollTop: clickedElement.offset().top - WDGProjectPageFunctions.navigationHeight + offset}, "slow");
							clickedElement.find('.zone-content > div, p, ul, table, blockquote, h1, h2, h3, h4, h5, h6').slideDown(400);
							WDGProjectPageFunctions.refreshEditable();
						}, 200 );
					});
					//on masque aussi toutes les autres parties
					WDGProjectPageFunctions.hideOthers(parseInt(projectMore.attr("data-value")));
					//Sinon on masque tout
				} else {
					WDGProjectPageFunctions.hideOthers(-1);
					WDGProjectPageFunctions.refreshEditable();
				}
			}
			WDGProjectPageFunctions.isClickBlocked = false;
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
				} else if ($("#content").hasClass("editing") && property !== "statistics") {
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