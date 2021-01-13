jQuery(document).ready( function($) {
	YPUIFunctions.initUI();
	WDGFormsFunctions.init();
});

JSHelpers = ( function( $ ) {
	return {
		urldecode: function(str) {
			return decodeURIComponent( ( str + '' ).replace( /\+/g, '%20' ) );
		},
		
		formatTextToNumber: function( sInput ) {
			var buffer = Number( sInput.split( ',' ).join( '.' ).split( ' ' ).join( '' ) );
			if ( isNaN( buffer ) ) {
				buffer = 0;
			}
			return buffer;
		},
		
		formatNumber: function( nInput, sSuffix ) {
			// On passe les entiers en float avec .00 pour qu'ils soient reconnus par le pattern en dessous
			if ( nInput === parseInt( nInput, 10 ) ) {
				nInput = parseFloat( nInput ).toFixed( 2 );
			}
			var sInput = nInput.toString();
			// Ecarts pour les milliers
			sInput = sInput.replace( /\d(?=(\d{3})+\.)/g, '$& ' );
			// Remplacement . par , pour les décimales
			sInput = sInput.split( '.' ).join( ',' );
			
			// Si c'est en fait un entier, on enlève les chiffres après la virgule
			var aCutDecimals = sInput.split( ',' );
			if ( aCutDecimals[ 1 ] === '00' ) {
				sInput = aCutDecimals[ 0 ];
			}
			
			var buffer = sInput;
			if ( sSuffix !== '' ) {
				buffer += ' ' + sSuffix;
			}
			
			return buffer;
		}
	};
} ) ( jQuery );

YPUIFunctions = (function($) {
	return {
		
		currentLightbox: '',
		currentRequest: '',

		initUI: function() {
			WDGLightboxFunctions.init();
			WDGNavFunctions.init();

			$(document).scroll(function() {
				if ( YPUIFunctions.currentLightbox === '' ) {
					if ( $(document).scrollTop() > 50 ) {
						$( 'nav#main').css( 'marginTop', 0 );
						if ( $(document).scrollTop() > 250 ) {
							$( '.responsive-fixed' ).addClass( 'fixed' );
						} else {
							$( '.responsive-fixed' ).removeClass( 'fixed' );
						}
					} else {
						$( 'nav#main' ).css( 'marginTop', 10 );
					}
				}
			});

			$(".expandator").css("cursor", "pointer");
			$(".expandable").not(".default-expanded").hide();
			$(".expandator").click(function() {
				var targetId = $(this).data("target");
				if ($("#extendable-" + targetId).is(":visible")) $("#extendable-" + targetId).hide();
				else $("#extendable-" + targetId).show();
			});
			
			$("footer span.footer-subtitle.clickable").click(function() {
				if ($(window).width() < 998) {
					if ($(this).next().is(":visible")) {
						$(this).removeClass("expanded");
						$(this).next().hide();
					} else {
						$(this).addClass("expanded");
						$(this).next().show()
					}
				}
			});

			// Affichage des info-bulles des impacts
			$(".impacts-container .impact-logo").mouseover(function(){
				var pos = YPUIFunctions.findPos(this);
				var posX = pos.x;
				$(this).next().addClass("visible").removeClass("invisible");
				$(this).next().css("left", posX);
			});
			$(".impacts-container .impact-logo").mouseout(function(){
				$(this).next().addClass("invisible").removeClass("visible");
			});

			$(".home_video .button-video, .home_video .button-video-shadows").click(function() {
				$(".home_video .button-video, .home_video .button-video-shadows").hide();
				var sContainer = ".home_video .video-container";
				var sW = '320';
				var sH = '180';
				if ($(window).width() > 570) {
					sContainer += ".w570";
					sW = '570';
					sH = '321';
				} else {
					sContainer += ".w320";
				}
				$(sContainer).append( '<iframe src="https://www.youtube.com/embed/QJmhrCG5acU?feature=oembed&amp;rel=0&amp;wmode=transparent&amp;autoplay=1" style="border: none" allow="autoplay; encrypted-media" width="'+sW+'" height="'+sH+'"></iframe>' );
				$(sContainer).show();
			});
			
			if ( $( '#cookies-alert' ).length > 0 ) {
				var hidecookiealert = YPUIFunctions.getCookie( 'hidecookiealert' );
				if ( hidecookiealert === '1' ) {
					$( '#cookies-alert' ).hide();
				} 
				$( '#cookies-alert-close' ).click(function() {
					$( '#cookies-alert' ).hide();
					var date = new Date();
					var days = 100;
					date.setTime(date.getTime()+(days*24*60*60*1000));
					var expires = '; expires=' + date.toGMTString();
					document.cookie = 'hidecookiealert=1' + expires + '; path=/';
				});
			}

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
					$("#fundingdevelopment_param").show();
					if ($('input[name=fundingtype]:checked').val() == "fundingproject") {
						$(".min_amount_value").text($("#min_amount_project").val());
					}
					if ($('input[name=fundingtype]:checked').val() == "fundingdevelopment") {
						$(".min_amount_value").text($("#min_amount_development").val());
					}
					if ($('input[name=fundingtype]:checked').val() == "fundingdonation") {
						$("#fundingdevelopment_param").hide();
						$(".min_amount_value").text($("#min_amount_donation").val());
					}
				});
			}

			if ($("#company_status").length > 0) {
				$("#company_status").change(function() {
					if ($("#company_status").val() == "Autre") $("#company_status_other_zone").show();
					else $("#company_status_other_zone").hide();
				});
			}

			if ($(".wp-editor-wrap")[0]) {
				setInterval(YPUIFunctions.onRemoveUploadInterval, 1000);
			}
			
			$(".alert-confirm").click( function(e) {
				e.preventDefault();
				if ( confirm( $(this).data("alertconfirm") ) ) {
					window.location.href = $(this).attr("href");
				}
			});

			//Si chargement données investisseurs/investissements nécessaire
			if ($('.ajax-investments-load').length > 0) {
				campaign_id = $('.ajax-investments-load').attr('data-value');
				YPUIFunctions.getInvestments(campaign_id, false);
			}
			if ($('.ajax-investments-load-short').length > 0) {
				campaign_id = $('.ajax-investments-load-short').attr('data-value');
				YPUIFunctions.getInvestments(campaign_id, true);
			}
			
			// Page les projets : affichage slider de projets (en cours / financés)
			if ($(".projects-current .wdg-component-projects-preview .project-slider .block-projects").width() > $(".projects-current .wdg-component-projects-preview .project-slider").width()) {
				
				if ($(".projects-current .wdg-component-projects-preview .project-slider").length > 0) {
					$(".projects-current .wdg-component-projects-preview .block-projects").width( ($(".projects-current .wdg-component-projects-preview .project-container").width() + 5) * $(".projects-current .wdg-component-projects-preview .project-container").length );
					$(".projects-current .wdg-component-projects-preview .project-slider").scrollLeft( ($(".projects-current .wdg-component-projects-preview .block-projects").width() - $(".projects-current .wdg-component-projects-preview .project-slider").width()) / 2 );
					// On affiche une zone suffisamment grande pour accueillir tous les projets d'entreprise
					$(".projects-funded .wdg-component-projects-preview .block-projects").width( $(".projects-funded .wdg-component-projects-preview .project-container").width() * $(".projects-funded .wdg-component-projects-preview .project-container.cat-entreprises").length );

				}
			}
			if ($(".projects-after-end-date .wdg-component-projects-preview .project-slider .block-projects").width() > $(".projects-after-end-date .wdg-component-projects-preview .project-slider").width()) {
				if ($(".projects-after-end-date .wdg-component-projects-preview .project-slider").length > 0) {
					$(".projects-after-end-date .wdg-component-projects-preview .block-projects").width( ($(".projects-after-end-date .wdg-component-projects-preview .project-container").width() + 5) * $(".projects-after-end-date .wdg-component-projects-preview .project-container").length );
					$(".projects-after-end-date .wdg-component-projects-preview .project-slider").scrollLeft( ($(".projects-after-end-date .wdg-component-projects-preview .block-projects").width() - $(".projects-after-end-date .wdg-component-projects-preview .project-slider").width()) / 2 );
				}
			}
			
			if ($("#project-filter").length > 0) {
				$("#project-filter > span").click(function() {
					if ($("#project-filter span").hasClass("show")) {
						$("#project-filter span").removeClass("show");
					} else {
						$("#project-filter span").addClass("show");
					}
					$("#project-filter select").toggle();
				});
				
				$("#project-filter .project-filter-select").change(function() {
					$( '#loader-project-list' ).show();
					setTimeout( function() {
						$( '#loader-project-list' ).hide();
						var step = $("#project-filter-step").val();
						var location = $("#project-filter-location").val();
						var impact = $("#project-filter-impact").val();
						YPUIFunctions.refreshProjectList( step, location, impact );
					}, 500 );
				});
				$("#project-filter .project-filter-select").trigger( 'change' );
				
				$("div.padder.projects-funded button").click(function() {
					var lineHeight = 620;
					var height = $("div.padder.projects-funded .block-projects").height() + lineHeight;
					$("div.padder.projects-funded .block-projects").css( 'max-height', height );
					var maxLines = Math.ceil($("div.padder.projects-funded .block-projects .project-container").length / 4);
					if (height >= maxLines * lineHeight) {
						$("div.padder.projects-funded button").hide();
					}
				});
			}
			
			$( '.avoid-enter-validation' ).on( 'keyup keypress', function(e) {
				var keyCode = e.keyCode || e.which;
				if  (keyCode === 13 ) { 
					e.preventDefault();
					return false;
				}
			} );

			$( '.copy-clipboard' ).click( function( e ) {
				e.stopPropagation();
				var clipboardId = $( this ).data( 'clipboard' );
				$( '#' + clipboardId ).after('<input id="new-element-to-select" type="text" value="' + $( '#' + clipboardId ).text() + '">');
				$( '#new-element-to-select' ).select();
				document.execCommand('copy');
				$( '#new-element-to-select' ).remove();
				$( this ).siblings( 'span.hidden' ).show();
			} );
		},
		/**
		 * Fonction pour récupérer la position x,y d'un élément
		 * @param {type} el : élément du DOM
		 * @returns left et top en px
		 */
		findPos: function (el) {
			var x = y = 0;
			if(el.offsetParent) {
				x = el.offsetLeft;
				y = el.offsetTop;
				while(el === el.offsetParent) {
					x += el.offsetLeft;
					y += el.offsetTop;
				}
			}
			return {'x':x, 'y':y};
		},

		getInvestments: function(campaign_id, bShortVersion){
			YPUIFunctions.currentRequest = $.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': {
					'action':'get_investments_data',
					'id_campaign' : campaign_id,
					'is_short_version' : bShortVersion ? '1' : '0'
				}
			}).done(function(result){
				YPUIFunctions.currentRequest = '';
				inv_data = JSON.parse(result);

				//Injecte les données directement affichées dans leurs emplacements
				$.each(inv_data, function(key, value) {
					if ( key == 'investors_string' ) {
						value = value.split('&amp;').join('&');
					}
					$('.data-inv-'+key).html(value);
				});
				$('.ajax-data-inv-loader-img').slideUp();

				//Crée le tableau de contacts si besoin
                if ($("#ajax-contacts-load").length > 0) {
                    wdgCampaignDashboard.getContactsTable(JSON.stringify(inv_data),campaign_id);
                }

			}).fail(function(){});
		},

		onRemoveUploadInterval: function() {
			if ($(".media-frame-menu")[0]) $(".media-frame-menu").remove();
			if ($(".media-frame-router")[0]) $(".media-frame-router").show();
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

		refreshProjectList: function( step, location, impact ) {
			var locationList = location.split(',');
			$(".wdg-component-projects-preview .block-projects .project-container").show();
			$(".wdg-component-projects-preview .block-projects .project-container").each(function() {
				var categoryList = $(this).data("categories");
				if ( step !== "all" && $(this).data("step") !== step ) {
					$(this).hide();
				}
				if ( location !== "all" && locationList.indexOf( $(this).data("location").toString() ) === -1 ) {
					$(this).hide();
				}
				if ( impact !== "all" && categoryList.indexOf( impact ) === -1 ) {
					$(this).hide();
				}
			});
		},
		
		getCookie: function(cookieName) {
			var name = cookieName + "=";
			var ca = document.cookie.split(';');
			for (var i = 0; i <ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0)===' ') {
					c = c.substring(1);
				}
				if (c.indexOf(name) === 0) {
					return c.substring(name.length,c.length);
				}
			}
			return "";
		},
		
		getStringsSimilarity: function(s1, s2) {
			var longer = s1;
			var shorter = s2;
			if (s1.length < s2.length) {
			  longer = s2;
			  shorter = s1;
			}
			var longerLength = longer.length;
			if (longerLength == 0) {
			  return 1.0;
			}
			var buffer = (longerLength - YPUIFunctions.getStringsDistance(longer, shorter)) / parseFloat(longerLength);
			return buffer;
		},
		getStringsDistance: function(s1, s2) {
			s1 = s1.toLowerCase();
			s2 = s2.toLowerCase();
		  
			var costs = new Array();
			for (var i = 0; i <= s1.length; i++) {
			  var lastValue = i;
			  for (var j = 0; j <= s2.length; j++) {
				if (i == 0)
				  costs[j] = j;
				else {
				  if (j > 0) {
					var newValue = costs[j - 1];
					if (s1.charAt(i - 1) != s2.charAt(j - 1))
					  newValue = Math.min(Math.min(newValue, lastValue),
						costs[j]) + 1;
					costs[j - 1] = lastValue;
					lastValue = newValue;
				  }
				}
			  }
			  if (i > 0)
				costs[s2.length] = lastValue;
			}
			return costs[s2.length];
		}
	};

})(jQuery);

var WDGNavFunctions = (function($) {
	return {

		isConnectionChecked: false,
		currentHref: '',
		
		init: function() {
			
			$("nav#main a.lines").click(function(e) {
				$("nav#main a.lines").removeClass("current");
				$(this).addClass("current"); 
				$(this).addClass("select-nav");
			});
            
			// Navbar : bouton compte utilisateur
			$('.btn-user').click(function(e){
				if ( $( this ).attr( 'href' ) == '#' ) {
					e.preventDefault();
					if ($('.btn-user').hasClass('active')) {
						$('.btn-user').removeClass('active').addClass('inactive');
						$('#submenu-user').hide();
					} else {
						$('.btn-user').addClass('active').removeClass('inactive');
						$('#submenu-user').show();
						$('#btn-search, #btn-burger').removeClass('active').addClass('inactive');
						$('#submenu-search').hide();
					}
				}
			});
						
			if ( $( 'nav#main .btn-user' ).hasClass( 'not-connected' ) ) {
				WDGNavFunctions.checkUserConnection();
			}
			
			// Navbar : bouton recherche
			$('#btn-search, #btn-burger').click(function(e){
				e.preventDefault();
				$( '#submenu-switch-lang' ).hide();
				if ($('#btn-search, #btn-burger').hasClass('active')) {
					$('#btn-search, #btn-burger').removeClass('active').addClass('inactive');
					$('#submenu-search').hide();
				} else {
					$('#btn-search, #btn-burger').addClass('active').removeClass('inactive');
					$('#submenu-search').show();
					$('#submenu-search-input').focus();
					$('.btn-user').removeClass('active').addClass('inactive');
					$('#submenu-user').hide();
					
					if ( $( '#submenu-search ul.submenu-list li' ).length == 0 ) {
						$.ajax({
							'type' : "POST",
							'url' : ajax_object.ajax_url,
							'data': {
								'action':'get_searchable_projects_list'
							}
							
						}).done(function(result){
							var aProjectList = JSON.parse( result );
							var nProjects = aProjectList.length;
							for ( var i = 0; i < nProjects; i++ ) {
								$( '#submenu-search ul.submenu-list' ).append(
									'<li class="hidden"><a href="https://www.wedogood.co/'+aProjectList[i].url+'">'+aProjectList[i].name+'<span class="hidden">'+aProjectList[i].url+' '+aProjectList[i].organization_name+'</span></a></li>'
								);
							}
							$("#submenu-search-input").trigger( 'keyup' );
						});
					}
				}
			});
			$("#submenu-search-input").keyup(function(e) {
				var keyCode = e.key;
				if ( keyCode === 'Enter' ) {
					$( '#submenu-search .submenu-list li a' ).each( function() {
						if ( $( this ).is( ':visible' ) ) {
							var sURL = $( this ).attr( 'href' );
							window.location = sURL;
							return false;
						}
					} );

				} else {
					var search = $("#submenu-search-input").val().toLowerCase();
					$("#submenu-search .submenu-list li").addClass("hidden");
					$( '.empty-list-info' ).addClass("hidden");
					
					if (search != "") {
						var bFoundProject = false;
						// Découpe par mot recherchés (on considère les tirets comme des mots séparés)
						var aSplitSearch = search.split( '-' ).join( ' ' ).split( ' ' );
						$("#submenu-search .submenu-list li").each(function() {
							var itemText = $(this).find('a').text().toLowerCase();
							if (itemText.indexOf(search) > -1) {
								$(this).removeClass("hidden");
								bFoundProject = true;
							}
							
							if (!bFoundProject) {
								var aSplitItem = itemText.split( '-' ).join( ' ' ).split( ' ' );
								var bFoundInItem = false;
								for (var i = 0; i < aSplitSearch.length; i++ ) {
									if ( aSplitSearch[i].length > 2 ) {
										for (var j = 0; j < aSplitItem.length; j++) {
											if (aSplitItem[j] != undefined && YPUIFunctions.getStringsSimilarity(aSplitItem[j], aSplitSearch[i]) > 0.6) {
												bFoundInItem = true;
												break;
											}
										}
									}
									if (bFoundInItem) {
										break;
									}
								}
								if (bFoundInItem) {
									$(this).removeClass("hidden");
									bFoundProject = true;
								}
							}
						});
						if ( !bFoundProject ) {
							$( '.empty-list-info' ).removeClass("hidden");
						}
					}
					$("#submenu-search").height("auto");
					
					if ( search === 'get funky!' ) {
						$( '#container' ).empty();
						$( '#container' ).append( '<div class="align-center" style="padding-top: 80px;"><iframe width="560" height="315" src="https://www.youtube.com/embed/kxopViU98Xo?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe></div>' );
						WDGGETFUNKY_MARGINLEFT = 500;
						WDGGETFUNKY();
						function WDGGETFUNKY() {
							WDGGETFUNKY_MARGINLEFT *= -1;
							$( '#container div' ).animate(
								{ marginLeft: WDGGETFUNKY_MARGINLEFT }, 
								2000, 
								"swing",
								function() { WDGGETFUNKY(); }
							);
						}
					}
				}
			});

			$( '#menu #btn-switch-lang' ).click( function() {
				$( '#submenu-switch-lang' ).toggle();
			} );
			$( '#submenu-switch-lang a' ).click( function( e ) {
				e.preventDefault();
				$( '#submenu-switch-lang ul' ).hide();
				$( '#submenu-switch-lang img' ).show();
				WDGNavFunctions.currentHref = $( this ).attr( 'href' );
				$.ajax({
					'type' : "POST",
					'url' : ajax_object.ajax_url,
					'data': {
						'action':'save_user_language',
						'language_key':$( this ).data( 'key' )
					}
				}).always(function() {
					window.location = WDGNavFunctions.currentHref;
				});
			} );

			$( '#footer-switch-lang' ).change( function() {
				window.location = $( this ).val();
			} );
			
			
			$('#menu .btn-user').click(function(){
				if ($('.model-form #identifiant').val() !== "" && $('.model-form #password').val() !== "") {
					WDGNavFunctions.showOkConnect();
				}
			});
			$('.model-form #identifiant').bind("keypress click", function(){ WDGNavFunctions.showOkConnect(); });
			$('.model-form #password').bind("keypress click", function(){ WDGNavFunctions.showOkConnect(); });

			//Fermeture des box connexion et recherche au clic dans la fenêtre
			$(window).mouseup(function(e){
				var boxUser = $('#submenu-user');
				var btnUser = $('#menu .btn-user');
				var imgUser = $('#menu .btn-user img');
				var boxSearch = $('#submenu-search');
				var btnSearch = $('#menu #btn-search');
				var imgSearch = $('#menu #btn-search img');
				var btnBurger = $('#btn-burger');
				var imgBurger = $('#btn-burger img');

				//connexion
				if(!boxUser.is(e.target) && !btnUser.is(e.target) && !imgUser.is(e.target)
					&& boxUser.css('display')==='block' && boxUser.has(e.target).length === 0) {
					boxUser.hide();
					btnUser.removeClass('active').addClass('inactive');
				}
				//recherche
				else if(!boxSearch.is(e.target) && !btnSearch.is(e.target) && !imgSearch.is(e.target)
					&& !imgBurger.is(e.target) && boxSearch.css('display') === 'block' && boxSearch.has(e.target).length === 0){
					boxSearch.hide();
					btnSearch.removeClass('active').addClass('inactive');
					btnBurger.removeClass('active').addClass('inactive');
				}
			});
			
			$(".social_connect_login_facebook").click( function(e) {
				e.preventDefault();
				$(".social_connect_login_facebook_loading").show();
				$.ajax({
					'type' : "POST",
					'url' : ajax_object.ajax_url,
					'data': {
						'action':'get_connect_to_facebook_url',
						'redirect':$( '.social_connect_login_facebook' ).data( 'redirect' )
					}
				}).done(function(result){
					if (result.indexOf('http') > -1) {
						window.location = result;
					} else {
						alert( "Facebook Connection URL Error" );
					}
				}).fail(function(){
					alert( "Facebook Connection Error" );
				}).always(function() {
					$(".social_connect_login_facebook_loading").hide();
				});
			} );
		},
		
		//Apparition bouton OK pour connexion
		showOkConnect: function() {
			$('.model-form .submit-center').css('display', 'inline');
			$('.model-form input#password').addClass('pwd_submit');
			$('.model-form input.connect').addClass('ok_valid');
		},
		
		checkUserConnection: function() {
			if ( WDGNavFunctions.isConnectionChecked ) {
				return;
			}
			WDGNavFunctions.isConnectionChecked = true;
			
			var strPageInfo = '';
			if ( $( '#content' ).length > 0 && $( '#content' ).data( 'campaignstatus' ) !== undefined && $( '#content' ).data( 'campaignstatus' ) === 'funded' ) {
				strPageInfo = $( '#content' ).data( 'campaignid' );
			}
			$.ajax({
				'type': "POST",
				'url': ajax_object.ajax_url,
				'data': {
					'action': 'get_current_user_info',
					'pageinfo': strPageInfo
				}, 
				'timeout' : 30000 // sets timeout to 30 seconds
			}).done( function( result ){
				if ( result === '0' ) {
					$( '#submenu-user.not-connected .menu-loading-init' ).hide();
					$( '#submenu-user.not-connected .menu-connection-forms' ).show();
				} else {
					var infoDecoded = JSON.parse( result );
					$( '#menu .btn-user' ).addClass( 'connected' ).removeClass( 'not-connected' );
					if ( infoDecoded[ 'userinfos' ][ 'display_need_authentication' ] == '1' ) {
						$( '#menu .btn-user' ).addClass( 'needs-authentication' );
					}
					$( '#menu .btn-user img' ).remove();
					$( '#menu .btn-user' ).text( infoDecoded[ 'userinfos' ][ 'my_account_txt' ] );
					
					$( '#submenu-user.not-connected .menu-loading-init' ).hide();
					$( '#submenu-user.not-connected .menu-connected #submenu-user-hello .hello-user-name' ).html( infoDecoded[ 'userinfos' ][ 'username' ] );
					var lengthInfoProjects = infoDecoded[ 'projectlist' ].length;
					for ( var i = 0; i < lengthInfoProjects; i++ ) {
						itemProject = infoDecoded[ 'projectlist' ][ i ];
						$( '#submenu-user.not-connected .menu-connected .submenu-list' ).append( '<li><a href="' +itemProject[ 'url' ]+ '" class="' +( itemProject[ 'display_need_authentication' ] === '1' ? 'needs-authentication' : '' )+ '">' +itemProject[ 'name' ]+ '</a></li>' );
					}
					if ( infoDecoded[ 'userinfos' ][ 'display_need_authentication' ] == '1' ) {
						$( '#submenu-user.not-connected .menu-connected #button-logout a' ).addClass( 'needs-authentication' );
					}
					$( '#submenu-user.not-connected .menu-connected #button-logout a' ).attr( 'href', infoDecoded[ 'userinfos' ][ 'logout_url' ] );
					$( '#submenu-user.not-connected .menu-connected' ).show();
					
					if ( infoDecoded[ 'context' ] != undefined && infoDecoded[ 'context' ][ 'dashboard_url' ] != undefined ) {
						if ( $( '#content .project-admin' ).length == 0 ) {
							// Fix temporaire (hum...) : ne devrait pas être derrière cette condition
							// Mais correct tant que les scripts ne concernent que les pages projets terminés
							var lengthScripts = infoDecoded[ 'scripts' ].length;
							for ( var i = 0; i < lengthScripts; i++ ) {
								$( 'body' ).append( '<script type="text/javascript" src="' +infoDecoded[ 'scripts' ][ i ]+ '"></script>' );
							}

							$( '#content' ).append( '<div class="project-admin"></div>' );
							$( '#content .project-admin' ).append( '<a href="' +infoDecoded[ 'context' ][ 'dashboard_url' ]+ '" class="btn-dashboard">Tableau de bord</a>' );
							if ( $( '.zone-edit' ).length > 0 ) {
								$( '#content .project-admin' ).append( '<div id="wdg-edit-project" class="btn-edit"></div>' );
							} else {
								$( '#content .project-admin' ).append( '<div id="wdg-remove-cache" class="btn-edit"></div>' );
							}
							// Reinit de l'édition
							ProjectEditor.isInit = false;
							ProjectEditor.init();
						}
					}

					dataLayer.push({
						'user_id': infoDecoded[ 'userinfos' ][ 'userid' ]
					});
				}

				wdg_gtm_call();
			} );
		}
	};
})(jQuery);

var WDGLightboxFunctions = (function($) {
	return {

		init: function() {
			if ($(".wdg-lightbox").length > 0) {
				$( ".wdg-lightbox" ).each( function(){
					if ( $( this ).data( 'autoopen' ) == '1' ) {
						WDGLightboxFunctions.displaySingle( $(this).attr('id').split('wdg-lightbox-')[1] );
					}
				});
				$(".wdg-button-lightbox-open").not("#wdg-lightbox-newproject .wdg-button-lightbox-open").click(function() {
					WDGLightboxFunctions.displaySingle( $(this).data("lightbox") );
				});
				$(".wdg-lightbox .wdg-lightbox-button-close a").click(function(e) {
					e.preventDefault();
					WDGLightboxFunctions.hideAll();
				});
				$(".wdg-lightbox #wdg-lightbox-welcome-close").click(function(e) {
					WDGLightboxFunctions.hideAll();
				});
				$(".wdg-lightbox .wdg-lightbox-click-catcher").click(function(e) {
					if ( !$( this ).hasClass( 'disable' ) ) {
						WDGLightboxFunctions.hideAll();
					}
				});
				var sHash = window.location.hash.substring(1);
				if ( (sHash.indexOf("=") === -1) && ($("#wdg-lightbox-" + sHash).length > 0) ) {
					WDGLightboxFunctions.displaySingle( sHash );
				}
			}
			
			if ($(".timeout-lightbox").length > 0) {
				var nTimeout = 2000;
				if ($(".timeout-lightbox").data("duration") > 0) nTimeout = $(".timeout-lightbox").data("duration");
				setTimeout(function() { $(".timeout-lightbox").fadeOut(); }, nTimeout);
				$( '.timeout-lightbox .wdg-lightbox-padder' ).click( function() { $( this ).parent().hide() } );
			}


			if ($("#wdg-lightbox-connexion").length > 0) {
				$(".wdg-button-lightbox-open").click(function(){
					$("#wdg-lightbox-connexion .redirect-page").attr("value", $(this).data("redirect"));
				});
			}
			if ($("#wdg-lightbox-register").length > 0) {
				$(".wdg-button-lightbox-open").click(function(){
					$("#wdg-lightbox-register .redirect-page").attr("value", $(this).data("redirect"));
				});
			}

			//Lightbox de nouveau projet
			if( $("#newproject_form").length > 0){
				$("#wdg-lightbox-newproject #connect-form .wdg-button-lightbox-open").click(function(e){
					e.preventDefault();
					$("#wdg-lightbox-newproject #connect-form").hide();
					$("#wdg-lightbox-newproject #newproject-register-user").show();
					var action = $("#wdg-lightbox-newproject #newproject-register-user form").attr("action");
					action = action.split("#register").join("#newproject");
					$("#wdg-lightbox-newproject #newproject-register-user form").attr("action", action);
				});
				$("#wdg-lightbox-newproject #newproject-register-user .wdg-button-lightbox-open").click(function(e){
					e.preventDefault();
					$("#wdg-lightbox-newproject #newproject-register-user").hide();
					$("#wdg-lightbox-newproject #connect-form").show();
				});
				
				$('#newproject_form input#new-company-name').val(" ");
				$('#newproject_form div#field-new-company-name').hide();
				
				if ( $('#newproject_form select[name=company-name]').length > 0 ) {
					$('#newproject_form input#email-organization').val(" ");
					$('#newproject_form div#field-email-organization').hide();
				}

				if($('#newproject_form input#company-name').val() === ""){
					$('#newproject_form #project-name').val("");
				}
				$('#newproject_form #select-company-name').on("keyup change", function() {
					$('#newproject_form div#field-new-company-name').hide();
					$('#newproject_form div#field-email-organization').hide();
					var val = "";
					if($('#newproject_form input#company-name').length > 0 && $('#newproject_form input#company-name').val() !== "" ) {
						val = $('#newproject_form input#company-name').val();
						$('#newproject_form #project-name').val("Projet de "+val);
						$('#newproject_form input#new-company-name').val(" ");
						$('#newproject_form input#email-organization').val(" ");
					} else {
						if($('#newproject_form select[name=company-name]').length > 0) {
							var option = $('#newproject_form select[name=company-name] option:selected').val();
							if(option !== "new_orga"){
								val = $('#newproject_form select[name=company-name] option:selected').text();
								$('#newproject_form #project-name').val("Projet de "+val);
								$('#newproject_form input#new-company-name').val(" ");
								$('#newproject_form input#email-organization').val(" ");
							} else {
								$('#newproject_form input#new-company-name').val("");
								$('#newproject_form #project-name').val('');
								$('#newproject_form div#field-new-company-name').show();
								$('#newproject_form input#new-company-name').on("keyup change", function() {
									var val = $('#newproject_form input#new-company-name').val();
									if (val!="") {
										$('#newproject_form #project-name').val("Projet de "+val);
									} else {
										$('#newproject_form #project-name').val("");
									}
								});
								$('#newproject_form input#email-organization').val("");
								$('#newproject_form input#new-company-name').val("");
								$('#newproject_form div#field-email-organization').show();
							}
						}
					}
				});
				$('#newproject_form input#new-company-name').focus(function(){
					$('#newproject_form input#new-company-name').val('');

				});
				if($('#newproject_form #company-name').val() !== ''){
					var val = $('#newproject_form #company-name option:selected').text();
					$('#newproject_form #project-name').val("Projet de "+val);
				}

				//Désactive bouton si champs incomplets
				$("input, textarea","#newproject_form").keyup(function(){
					$("#newProject_button").find("button").prop('disabled', ($("input, textarea","#newproject_form").filter(function() { return $(this).val() == ""; }).length > 0 || !$("#project-terms").is(':checked')) );
				});
				$("input, textarea","#newproject_form").trigger('keyup');
				$("#project-terms").change(function() {
					$("input, textarea","#newproject_form").trigger('keyup');
				});

				$("#newproject_form").submit(function(){
					$("#newProject_button").find(".button-text").hide();
					$("#newProject_button").find(".button-waiting").show();
					$("#newProject_button button").prop('disabled', true);
				});

			}
			
		},
		
		displaySingle: function( sLightboxId ) {
			if ( sLightboxId == '' ) {
				return FALSE;
			}
			if (typeof ga === 'function') {
				ga('set', { page: document.location.pathname + '#' + sLightboxId, title: document.title + ' | ' + sLightboxId });
				ga('send', 'pageview');
			}
			$( ".wdg-lightbox" ).hide();
			$( "#wdg-lightbox-" + sLightboxId ).show();
			if( $( "#wdg-lightbox-" + sLightboxId ).data( "scrolltop" ) == "1" ){
				WDGLightboxFunctions.scrollTop( $( ".wdg-lightbox-padder" ) );
			}
			YPUIFunctions.currentLightbox = sLightboxId;
		},
		
		hideAll: function() {
			$(".wdg-lightbox").hide();
			YPUIFunctions.currentLightbox = '';
		},

		//Scroll en haut d'une ligthbox
		scrollTop: function(target){
            $('.wdg-lightbox-padder').scrollTop(target.offset().top - 75);
        }
		
	};
})(jQuery);

var WDGFormsFunctions = (function($) {
	return {

		init: function() {
			WDGFormsFunctions.initSaveButton();
			WDGFormsFunctions.initCheckboxes();
			WDGFormsFunctions.initRateCheckboxes();
			WDGFormsFunctions.initDatePickers();
			WDGFormsFunctions.initTextInput();
			WDGFormsFunctions.initFileInput();
			WDGFormsFunctions.initSelectMultiple();
		},
		
		initSaveButton: function() {
			$( '.wdg-lightbox-ref .ajax-form button.save' ).click( function( e ) {
				e.preventDefault();
				$( this ).siblings( '.loading' ).show();
				$( this ).siblings( 'button' ).hide();
				$( this ).hide();
				var formId = $( this ).parent().parent().parent().attr( 'id' );
				WDGFormsFunctions.postForm( '#' + formId, WDGFormsFunctions.postFormCallback, this );
			} );
			$( '.ajax-form button.save' ).click( function( e ) {
				e.preventDefault();
				$( this ).siblings( '.loading' ).show();
				$( this ).siblings( 'button' ).hide();
				$( this ).hide();
				var formId = $( this ).parent().parent().attr( 'id' );
				WDGFormsFunctions.postForm( '#' + formId, WDGFormsFunctions.postFormCallback, this );
			} );
			$( '.db-form button.confirm' ).click( function( e ) {
				var confirmSave = window.confirm("Etes-vous sûrs de vouloir enregistrer ces modifications ?");			
				if ( !confirmSave ) {
					e.stopPropagation();
					e.preventDefault();
				}
			} );
			$( '.wdg-lightbox button.close, .wdg-lightbox-ref button.close' ).click( function( e ) {
				WDGLightboxFunctions.hideAll();
			} );
			$( '.wdg-lightbox button.redirect, .wdg-lightbox-ref button.redirect' ).click( function( e ) {
				window.location = $( this ).data( 'redirecturl' );
			} );
		},
		
		initCheckboxes: function() {
			if ( $( '.db-form.v3 input[type=checkbox]' ).length > 0 ) {
				$( '.db-form.v3 input[type=checkbox]' ).each( function(){
					if ( !$( this ).hasClass( 'rate' ) && !$( this ).parent().hasClass( 'selectit' ) ) {
						// Permet de cliquer sur des liens qui sont dans des div de checkbox (ex : cgu...)
						$( this ).parent().children( 'a' ).click( function( e ) {
							e.stopImmediatePropagation();
						} );
						$( this ).parent().click( function( e ) {
							if ( $( this ).data( 'keepdefault' ) != '1' ) {
								e.preventDefault();
							}
							var checkboxItem = $( this ).children( 'input[type=checkbox]' )[0];
							checkboxItem.checked = !checkboxItem.checked;
							if ( $( this ).parent().hasClass( 'select-multiple-items' ) ) {
								WDGFormsFunctions.refreshSelectMultiple( $( this ).parent().parent() );
							}
						} );
					}
				} );
			}
		},
		
		initRateCheckboxes: function() {
			if ( $( '.db-form.v3 input[type=checkbox].rate' ).length > 0 ) {
				$( '.db-form.v3 input[type=checkbox].rate + span' ).click( function() {
					var sRateType = $( this ).data( 'rate' );
					$( 'input[type=checkbox].' + sRateType ).attr( 'checked', false );
					var thisVal = $( this ).data( 'value' );
					WDGFormsFunctions.setRateCheckboxes( sRateType, thisVal );
				} );
			}
		},
		
		initDatePickers: function() {
			if ( $( 'input[type=text].adddatepicker' ).length > 0 ) {
				$( 'input[type=text].adddatepicker' ).datepicker({
					dateFormat: "dd/mm/yy",
					yearRange: "-120:+10",
					regional: "fr",
					changeMonth: true,
					changeYear: true
				});
			}
		},
		
		initTextInput: function() {
			$( 'input.format-number' ).each( function() {
				var sInput = $( this ).val();
				var sInputFormatted = JSHelpers.formatNumber( JSHelpers.formatTextToNumber( sInput ), '' );
				$( this ).val( sInputFormatted );
			} );
			$( 'input.format-number' ).change( function() {
				var sInput = $( this ).val();
				var sInputFormatted = JSHelpers.formatNumber( JSHelpers.formatTextToNumber( sInput ), '' );
				$( this ).val( sInputFormatted );
			} );
		},
		
		initFileInput: function() {
			$( '.field-file input' ).on( 'change', function( e ) {
				var label_element = $( this ).next( 'label' );
				var labelVal = label_element.html();
				var fileName = '';

				if( this.files && this.files.length > 1 )
					fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
				else if( e.target.value )
					fileName = e.target.value.split( '\\' ).pop();

				if( fileName ) {
					label_element.find( 'span' ).html( fileName );
					label_element.find( 'span.hide-when-filled' ).hide();
				} else {
					label_element.html( labelVal );
				}
				
				// Exécutés quand chargement de fichier appelé depuis lightbox
				label_element.show();
				label_element.css( 'display', 'inline-block' );
				label_element.next( '.displayed-responsive' ).hide();
				WDGLightboxFunctions.hideAll();
			} );
						
			$( 'label.file-label' ).on( 'dragover', function( e ) {
				$( this ).addClass( 'dragover' );
				e.stopPropagation();
				e.preventDefault();
			} );
			$( 'label.file-label' ).on( 'dragleave', function( e ) {
				$( this ).removeClass( 'dragover' );
				e.stopPropagation();
				e.preventDefault();
			} );
			$( 'label.file-label' ).on( 'drop', function( e ) {
				e.stopPropagation();
				e.preventDefault();
				$( this ).removeClass( 'dragover' );
				var inputId = $( this ).data( 'input' );
				$( '#' + inputId ).prop( 'files', e.originalEvent.dataTransfer.files );
				$( '#' + inputId ).trigger( 'change' );
			} );
			
			$( 'button.take-picture, button.import-file' ).click( function( e ) {
				e.stopPropagation();
				e.preventDefault();
				var inputID = $( this ).data( 'input-id' );
				if ( $( this ).hasClass( 'take-picture' ) ) {
					$( '#' + inputID ).attr( 'accept', 'image/*' );
					$( '#' + inputID ).attr( 'capture', '1' );
				} else {
					$( '#' + inputID ).removeAttr( 'accept' );
					$( '#' + inputID ).removeAttr( 'capture' );
				}
				$( '#' + inputID ).click();
			} );
		},
		
		initSelectMultiple: function() {
			$( '.select-multiple-items-retracted' ).click( function() {
				var siblings = $( this ).siblings( '.select-multiple-items' );
				// On change l'état du bouton à l'inverse de celui de la liste, avant le changement car il y a un délai
				if ( siblings.is( ':visible' ) ) {
					$( this ).removeClass( 'reverse' );
				} else {
					$( this ).addClass( 'reverse' );
				}
				siblings.toggle( 50 );
			} );
		},
		
		refreshSelectMultiple: function( checkboxContainer ) {
			var selectSpanItem = checkboxContainer.find( 'span.select-multiple-items-retracted-values' )[ 0 ];
			
			var sLabelText = '';
			var checkboxLabelList = checkboxContainer.find( 'label.radio-label' );
			var checkboxList = checkboxContainer.find( 'label.radio-label input' );
			var nCheckbox = checkboxLabelList.length;
			for ( var i = 0; i <nCheckbox; i++ ) {
				if ( checkboxList[ i ].checked ) {
					if ( sLabelText !== '' ) {
						sLabelText += ', ';
					}
					sLabelText += $( checkboxLabelList[ i ] ).text();
				}
			}
			sLabelText = sLabelText.split( '\n' ).join( '' ).split( '\t' ).join( '' );
			$( selectSpanItem ).text( sLabelText );
		},
		
		setRateCheckboxes: function( sRateType, nRate ) {
			for ( var i = 1; i <= nRate; i++ ) {
				$( 'input[type=checkbox]#' + sRateType + '-' + i )[0].checked = true;
			}
			$( 'span#' + sRateType + '-description' ).text( $( 'input[type=checkbox]#' + sRateType + '-' + nRate ).data( 'description' ) );
		},
		
		postForm: function( formid, callback, clickedButton ) {
			$( formid+ ' div.field' ).removeClass( 'error' );
			var sentData = {};
			if ( $( formid ).hasClass( 'has-files' ) ) {
  				sentData = new FormData( $( formid )[0] );
				
			} else {
				$( formid+' input, '+formid+' select, '+formid+' textarea' ).each( function() {
					if ( $( this ).attr( 'type' ) === 'checkbox' || $( this ).attr( 'type' ) === 'radio' ) {
						if ( $( this ).is(':checked') ) {
							sentData[ $( this ).attr( 'name' ) ] = $( this ).val();
						}
					} else {
						sentData[ $( this ).attr( 'name' ) ] = $( this ).val();
					}
				} );
			}
			
			if ( $( formid ).hasClass( 'has-files' ) ) {
				var ajaxParams = {
					'type': "POST",
					'url': ajax_object.ajax_url,
					'data': sentData,
					'cache': false,
					'contentType': false,
					'processData': false
				}
			} else {
				var ajaxParams = {
					'type': "POST",
					'url': ajax_object.ajax_url,
					'data': sentData
				}
			}
			$.ajax( ajaxParams ).done(function (result) {
				
				var jsonResult = JSON.parse(result);
				if ( jsonResult.errors != undefined ) {
					var nErrors = jsonResult.errors.length;
					for ( var i = 0; i < nErrors; i++ ) {
						var errorItem = jsonResult.errors[ i ];
						if ( errorItem.element == 'general' ) {
							$( formid+' span.form-error-general' ).html( errorItem.text );
						} else {
							$( formid+' div#field-'+errorItem.element ).addClass( 'error' );
							$( formid+' div#field-'+errorItem.element+' span.field-error' ).html( errorItem.text );
						}
					}
				} else {
					if ( $( formid + '_success' ).length > 0 ) {
						$( formid + '_success' ).show();
					}
				}
				callback( result, formid, clickedButton );
				
			});
		},
		
		postFormCallback: function( result, formid, clickedButton ) {
			$( formid+' .loading' ).hide();
			$( formid+' button.save' ).show();
			if ( $( formid+' button.previous' ).length > 0 ) {
				$( formid+' button.previous' ).show();
			}
			
			try {
				var jsonResult = JSON.parse(result);
				if ( jsonResult.errors === undefined || jsonResult.errors.length === 0 ) {
					if ( $( clickedButton ).data( 'close' ) !== undefined && $( clickedButton ).data( 'close' ) !== '' ) {
						$( '#wdg-lightbox-' + $( clickedButton ).data( 'close' ) ).hide();
					}
					if ( $( clickedButton ).data( 'open' ) !== undefined && $( clickedButton ).data( 'open' ) !== '' ) {
						$( '#wdg-lightbox-' + $( clickedButton ).data( 'open' ) ).show();
					}
				}
			} catch(e) { }
			
			var callbackFunctionName = $( clickedButton ).data( 'callback' );
			if ( callbackFunctionName !== undefined && callbackFunctionName !== '' ) {
				( eval( callbackFunctionName ) )( result );
			}
		}
		
	};
})(jQuery);