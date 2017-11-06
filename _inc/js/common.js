jQuery(document).ready( function($) {
	YPUIFunctions.initUI();
	WDGFormsFunctions.init();
});

YPUIFunctions = (function($) {
	return {
		
		currentLightbox: '',

		initUI: function() {
			WDGProjectPageFunctions.initUI();
			YPUIFunctions.initQtip();
			WDGLightboxFunctions.init();

			$(document).scroll(function() {
				if ($(".menu-client").length > 0) {
					if ($(document).scrollTop() > 110) {
						$("#nav").hide();
						$(".menu-client").css("position", "fixed");
						$("#content.theme-myphotoreporter #projects-stats-content").css("top", 60);
						$("#content.theme-myphotoreporter #post_bottom_bg").css("marginTop", 60);
					} else {
						$("#nav").show();
						$(".menu-client").css("position", "relative");
						$("#content.theme-myphotoreporter #projects-stats-content").css("top", 0);
						$("#content.theme-myphotoreporter #post_bottom_bg").css("marginTop", 0);
					}


				} else {
					if ($(document).scrollTop() > 50) {
						$("nav#main").css("marginTop", 0);
					} else {
						$("nav#main").css("marginTop", 10);
					}
				}

				if ($(document).scrollTop() > 250) {
					$(".responsive-fixed").addClass("fixed");
				} else {
					$(".responsive-fixed").removeClass("fixed");
				}
				
				if ( YPUIFunctions.currentLightbox !== '' ) {
					var maxDocumentScroll = $('body').height();
					maxDocumentScroll -= $( "#wdg-lightbox-" + YPUIFunctions.currentLightbox + " .wdg-lightbox-corner" ).innerHeight();
					maxDocumentScroll -= $( "#wdg-lightbox-" + YPUIFunctions.currentLightbox + " .wdg-lightbox-padder" ).innerHeight();
					maxDocumentScroll -= 100;
					var documentScroll = Math.max( Math.min( maxDocumentScroll, $(document).scrollTop() ) + 10, 15 );
					$( "#wdg-lightbox-" + YPUIFunctions.currentLightbox + " .wdg-lightbox-corner" ).css( 'marginTop', documentScroll );
				}
			});
			
			
			$("nav#main a.lines").click(function(e) {
				$("nav#main a.lines").removeClass("current");
				$(this).addClass("current"); 
                                $(this).addClass("select-nav");
			});
            
			// Navbar : bouton compte utilisateur
			$('.btn-user').click(function(e){
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
			});
			// Navbar : bouton recherche
			$('#btn-search, #btn-burger').click(function(e){
				e.preventDefault();
				if ($('#btn-search, #btn-burger').hasClass('active')) {
					$('#btn-search, #btn-burger').removeClass('active').addClass('inactive');
					$('#submenu-search').hide();
				} else {
					$('#btn-search, #btn-burger').addClass('active').removeClass('inactive');
					$('#submenu-search').show();
					$('#submenu-search-input').focus();
					$('.btn-user').removeClass('active').addClass('inactive');
					$('#submenu-user').hide();
				}
			});
			$("#submenu-search-input").keyup(function() {
				var search = $("#submenu-search-input").val().toLowerCase();
				$("#submenu-search .submenu-list li").addClass("hidden");
				
				if (search != "") {
					$("#submenu-search .submenu-list li").each(function() {
						var itemText = $(this).find('a').text().toLowerCase();
						if (itemText.indexOf(search) > -1) {
							$(this).removeClass("hidden");
						}
					});
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
			});
			
			
			//Apparition bouton OK pour connexion
			showOkConnect = function(){
				$('.model-form .submit-center').css('display', 'inline');
				$('.model-form input#password').addClass('pwd_submit');
				$('.model-form input.connect').addClass('ok_valid');
			};
			$('#menu .btn-user').click(function(){
				if ($('.model-form #identifiant').val() !== "" && $('.model-form #password').val() !== "") {
					showOkConnect();
				}
			});
			$('.model-form #identifiant').bind("keypress click", function(){ showOkConnect(); });
			$('.model-form #password').bind("keypress click", function(){ showOkConnect(); });

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
						'action':'get_connect_to_facebook_url'
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
                                			
			$("#subscribe-nl-mail").keypress(function() {
				$("#subscribe-nl-mail").addClass("retracted");
				$("#subscribe-nl-submit").show();
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
				if ($(window).width() > 570) {
					sContainer += ".w570";
				} else {
					sContainer += ".w320";
				}
				$(sContainer).show();
				var src = $(sContainer + " iframe").attr("src");
				src += '&autoplay=1';
				$(sContainer + " iframe").attr("src", src);
			});
			
			if ($("#cookies-alert").length > 0) {
				$("#cookies-alert-close").click(function() {
					$("#cookies-alert").hide();
					var date = new Date();
					var days = 100;
					date.setTime(date.getTime()+(days*24*60*60*1000));
					var expires = "; expires="+date.toGMTString();
					document.cookie = "hidecookiealert=1"+expires+"; path=/";
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
					$('html, body').animate({scrollTop: $('#anchor-societal_challenge').offset().top - $("#navigation").height()}, "slow");
				});
			}
			
			$(".alert-confirm").click( function(e) {
				e.preventDefault();
				if ( confirm( $(this).data("alertconfirm") ) ) {
					window.location.href = $(this).attr("href");
				}
			});

			//Si chargement données investisseurs/investissements nécessaire
			if ($(".ajax-investments-load").length > 0) {
				campaign_id = $(".ajax-investments-load").attr('data-value');
				YPUIFunctions.getInvestments(campaign_id);
			}
			//Formulaire envoi mail
			$("#jycrois-send-mail-selector").change(function(){
				if(this.checked){
					$("#voted-send-mail-selector").prop('disabled',true);
					$("#invested-send-mail-selector").prop('disabled',true);
					$("#voted-send-mail-selector").prop('checked',true);
					$("#invested-send-mail-selector").prop('checked',true);
				} else {
					$("#voted-send-mail-selector").prop('disabled',false);
					$("#invested-send-mail-selector").prop('disabled',false);
				}
			});

			//Modification des contreparties
			if ($(".reward-table-param").length > 0){

				checkNeedNewLines = function(){
					if ($(".reward-text").filter(function() { return $(this).val() == ""; }).length <= 1){
						//Ajouter des lignes
						i = parseInt($(".reward-text").last().prop("name").substring(12))+1;
						newline = '<tr>'
							+'<td><input name="reward-name-'+i+'" type="text" name="" value="" placeholder="Nommez et d&eacute;crivez bri&egrave;vement la contrepartie" class="reward-text"/></td>'
							+'<td><input name="reward-amount-'+i+'" type="number" min="0" name="" value="" placeholder=""/></td>'
							+'<td><input name="reward-limit-'+i+'" type="number" min="0" name="" value="" placeholder=""/></td>'
							+'</tr>';

						$(".reward-table-param table tbody").append(newline);
						addListeners();
					}
				};
				addListeners = function(){
					$(".reward-text").off().on( 'keyup change', function(){
						if(this.value===''){
							$(this).closest("tr").find("input").css("background-color","#CCC");
						} else {
							$(this).closest("tr").find("input").css("background-color","");
							//Si toutes les cases sont utilisées
							checkNeedNewLines();
						}
					}).trigger('change');
				};

				addListeners();

			}


			if ($("#wdg-lightbox-connexion").length > 0) {
				$(".wdg-button-lightbox-open").click(function(){
					$("#wdg-lightbox-connexion .redirect-page").attr("value", $(this).data("redirect"));
				});
			}
			if ($("#wdg-lightbox-newproject").length > 0) {
				$("#wdg-lightbox-newproject #connect-form .wdg-button-lightbox-open").click(function(e){
					e.preventDefault();
					$("#wdg-lightbox-newproject #connect-form").hide();
					$("#wdg-lightbox-newproject #newproject-register-user").show();
					var action = $("#wdg-lightbox-newproject #newproject-register-user form").attr("action");
					console.log(action);
					action = action.split("#register").join("#newproject");
					$("#wdg-lightbox-newproject #newproject-register-user form").attr("action", action);
				});
				$("#wdg-lightbox-newproject #newproject-register-user .wdg-button-lightbox-open").click(function(e){
					e.preventDefault();
					$("#wdg-lightbox-newproject #newproject-register-user").hide();
					$("#wdg-lightbox-newproject #connect-form").show();
				});
			}

			if ($("#turnover-declaration").length > 0) {
				if ($("#turnover-total").length > 0) {
					$("#turnover-total").change(function() {
						YPUIFunctions.refreshTurnoverAmountToPay();
					});
				}
				var i = 0;
				while ($("#turnover-" + i).length > 0) {
					$("#turnover-" + i).change(function() {
						YPUIFunctions.refreshTurnoverAmountToPay();
					});
					i++;
				}
			}
			
			// Page accueil : affichage slider de projets
			if ($("body.home.page").length > 0) {
				if ($(".wdg-component-projects-preview .project-slider").length > 0) {
					$(".wdg-component-projects-preview .block-projects").width( ($(".wdg-component-projects-preview .project-container").width() + 5) * $(".wdg-component-projects-preview .project-container").length );
					$(".wdg-component-projects-preview .project-slider").scrollLeft( ($(".wdg-component-projects-preview .block-projects").width() - $(".wdg-component-projects-preview .project-slider").width()) / 2 );
				}
			}
			// Page les projets : affichage slider de projets (en cours / financés)
			if ($(".projects-current .wdg-component-projects-preview .project-slider .block-projects").width() > $(".projects-current .wdg-component-projects-preview .project-slider").width()) {
				
				if ($(".projects-current .wdg-component-projects-preview .project-slider").length > 0) {
					$(".projects-current .wdg-component-projects-preview .block-projects").width( ($(".projects-current .wdg-component-projects-preview .project-container").width() + 5) * $(".projects-current .wdg-component-projects-preview .project-container").length );
					$(".projects-current .wdg-component-projects-preview .project-slider").scrollLeft( ($(".projects-current .wdg-component-projects-preview .block-projects").width() - $(".projects-current .wdg-component-projects-preview .project-slider").width()) / 2 );

					$(".projects-funded .wdg-component-projects-preview .block-projects").width( ($(".projects-funded .wdg-component-projects-preview .project-container").width() + 5) * $(".projects-funded .wdg-component-projects-preview .project-container").length );

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
				
				$("#project-filter .project-filter-select").click(function() {
					var step = $("#project-filter-step").val();
					var location = $("#project-filter-location").val();
					var activity = $("#project-filter-activity").val();
					var impact = $("#project-filter-impact").val();
					YPUIFunctions.refreshProjectList( step, location, activity, impact );
				});
				$("#project-filter-activity").val( 'entreprises' );
				$("#project-filter .project-filter-select").trigger("click");
				
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

		refreshTurnoverAmountToPay: function() {
			var roiPercent = $("#turnover-declaration").data("roi-percent");
			var costsOrga = $("#turnover-declaration").data("costs-orga");
			var total = 0;
			if ($("#turnover-total").length > 0) {
				total = Number($("#turnover-total").val());
			} else {
				var i = 0;
				while ($("#turnover-" + i).length > 0) {
					total += Number($("#turnover-" + i).val());
					i++;
				}
			}
			var amount = total * roiPercent / 100;
			var amount_with_fees = amount + (amount * costsOrga / 100);
			amount_with_fees += $("#turnover-declaration").data("adjustment");
			amount_with_fees = Math.round(amount_with_fees * 100) / 100;

			$(".amount-to-pay").text(amount_with_fees);
		},

		getInvestsGraph : function(inv_data, campaign_id) {
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': {
					'action':'get_invests_graph',
					'id_campaign' : campaign_id,
					'data' : inv_data
				}
			}).done(function(result){
				$('#ajax-invests-graph-load').after(result);
				$('#ajax-graph-loader-img').hide();//On cache la roue de chargement.
				$('#canvas-line-block').slideDown();
			}).fail(function(){
				$('#ajax-invests-graph-load').after("<em>Le chargement du graphe a échoué</em>");
				$('#ajax-graph-loader-img').hide();//On cache la roue de chargement.
				$('#canvas-line-block').slideDown();
			});
		},

		getInvestments: function(campaign_id){
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': {
					'action':'get_investments_data',
					'id_campaign' : campaign_id
				}
			}).done(function(result){
				inv_data = JSON.parse(result);

				//Injecte les données directement affichées dans leurs emplacements
				$.each(inv_data, function(key, value) {
					$('.data-inv-'+key).html(value);
				});
				$('.ajax-data-inv-loader-img').slideUp();

				//Liste des ID pour l'envoi de mail
				if ($("#ajax-id-investors-load").length > 0) {
					$('#ajax-id-investors-load #img-investors').slideDown();
					$('#ajax-id-investors-load #invested-send-mail-selector').slideDown();
					list_id_inv = Object.keys(inv_data.investors_list).map(function (key) {return inv_data.investors_list[key];});
					$('#ajax-id-investors-load #invested-send-mail-list').val(list_id_inv);
				}

				// Crée le graphe des investissements si besoin
				if ($("#ajax-invests-graph-load").length > 0) {
					YPUIFunctions.getInvestsGraph(JSON.stringify(inv_data),campaign_id);
				}

				//Crée le tableau de contacts si besoin
                if ($("#ajax-contacts-load").length > 0) {
                    WDGProjectDashboard.getContactsTable(JSON.stringify(inv_data),campaign_id);
                }

			}).fail(function(){});
		},

		initQtip: function(){
			var i=0;
			$('.infobutton, .qtip-element').each(function () {
				//Check if doesn't exist yet
				if($(this).data("hasqtip")==undefined){
					var contentTip;
					if($(this).attr("title")!=undefined){
						contentTip = $(this).attr("title");
					} else {
						contentTip = $(this).next('.tooltiptext');
					}

					var settings = {
						content: contentTip,
						position: {
							my: 'bottom center',
							at: 'top center',
						},
						style: {
							classes: 'wdgQtip qtip-dark qtip-rounded qtip-shadow'
						},
						hide: {
							fixed: true,
							delay: 300
						}
					};

					if($(this).is("input[type=text], input[type=number], textarea")){
						settings['show']='focus'
						settings['hide']='blur'
					}

					var personnalised_settings = $(this).data("tooltip");
					if(personnalised_settings!=undefined){
						var data_settings = JSON.parse(personnalised_settings);
						for (var attrname in data_settings) { settings[attrname] = data_settings[attrname]; }
					}

					if (contentTip != ""){
						$(this).qtip(settings);
						i++;
					}
				}
			});
			return i;
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

		refreshProjectList: function( step, location, activity, impact ) {
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
				if ( activity !== "all" && categoryList.indexOf( activity ) === -1 ) {
					$(this).hide();
				}
				if ( impact !== "all" && categoryList.indexOf( impact ) === -1 ) {
					$(this).hide();
				}
			});
		},

		//Scroll en haut d'une ligthbox
		scrollTo: function(target){
            $('.wdg-lightbox-padder').scrollTop (target.offset().top - 75);
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
			}

			//Lightbox de nouveau projet
			if( $("#newproject_form").length > 0){
				$('#newproject_form input#new-company-name').val(" ");
				$('#newproject_form input#new-company-name').parent().parent().parent().hide();
				if($('#newproject_form input#company-name').val() === ""){
					$('#newproject_form #project-name').val("");
				}
				$('#newproject_form #company-name').on("keyup change", function() {
					$('#newproject_form input#new-company-name').parent().parent().parent().hide();
					var val = "";
					if($('#newproject_form input#company-name').length > 0 && $('#newproject_form input#company-name').val() !== "" ) {
						val = $('#newproject_form input#company-name').val();
						$('#newproject_form #project-name').val("Projet de "+val);
						$('#newproject_form input#new-company-name').val(" ");
					} else {
						if($('#newproject_form select[name=company-name]').length > 0) {
							var option = $('#newproject_form select[name=company-name] option:selected').val();
							if(option !== "new_orga"){
								val = $('#newproject_form select[name=company-name] option:selected').text();
								$('#newproject_form #project-name').val("Projet de "+val);
								$('#newproject_form input#new-company-name').val(" ");
							} else {
								$('#newproject_form input#new-company-name').val("");
								$('#newproject_form #project-name').val('');
								$('#newproject_form input#new-company-name').parent().parent().parent().show();
								$('#newproject_form input#new-company-name').on("keyup change", function() {
									var val = $('#newproject_form input#new-company-name').val();
									if (val!="") {
										$('#newproject_form #project-name').val("Projet de "+val);
									} else {
										$('#newproject_form #project-name').val("");
									}
								});
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
			console.log(sLightboxId);
			$( ".wdg-lightbox" ).hide();
			$( "#wdg-lightbox-" + sLightboxId ).height( $( 'body' ).height() );
			$( "#wdg-lightbox-" + sLightboxId ).show();
			if( $( "#wdg-lightbox-" + sLightboxId ).data( "scrolltop" ) == "1" ){
				YPUIFunctions.scrollTo( $( ".wdg-lightbox-padder" ) );
			}
			YPUIFunctions.currentLightbox = sLightboxId;
		},
		
		hideAll: function() {
			$(".wdg-lightbox").hide();
			YPUIFunctions.currentLightbox = '';
		}
		
	};
})(jQuery);

var WDGFormsFunctions = (function($) {
	return {

		init: function() {
			WDGFormsFunctions.initSaveButton();
			WDGFormsFunctions.initRateCheckboxes();
			WDGFormsFunctions.initDatePickers();
		},
		
		initSaveButton: function() {
			$( '.wdg-lightbox-ref .ajax-form button.save' ).click( function( e ) {
				e.preventDefault();
				$( this ).siblings( '.loading' ).show();
				$( this ).siblings( 'button' ).hide();
				$( this ).hide();
				var formId = $( this ).parent().parent().parent().attr( 'id' );
				WDGFormsFunctions.postForm( 'div#' + formId, WDGFormsFunctions.postFormCallback );
			} );
			$( '.wdg-lightbox-ref button.close' ).click( function( e ) {
				$( this ).parents( 'div.wdg-lightbox' ).hide();
			} );
		},
		
		initRateCheckboxes: function() {
			if ( $( 'input[type=checkbox].rate' ).length > 0 ) {
				$( 'input[type=checkbox].rate + span' ).click( function() {
					var sRateType = $( this ).data( 'rate' );
					$( 'input[type=checkbox].' + sRateType ).attr( 'checked', false );
					var thisVal = $( this ).data( 'value' );
					WDGFormsFunctions.setRateCheckboxes( sRateType, thisVal );
				} );
			}
		},
		
		initDatePickers: function() {
            $( 'input[type=text].adddatepicker' ).datepicker({
                dateFormat: "dd/mm/yy",
                regional: "fr",
                changeMonth: true,
                changeYear: true
            });
		},
		
		setRateCheckboxes: function( sRateType, nRate ) {
			for ( var i = 1; i <= nRate; i++ ) {
				$( 'input[type=checkbox]#' + sRateType + '-' + i )[0].checked = true;
			}
			$( 'span#' + sRateType + '-description' ).text( $( 'input[type=checkbox]#' + sRateType + '-' + nRate ).data( 'description' ) );
		},
		
		postForm: function( formid, callback ) {
			$( formid+ ' div.field' ).removeClass( 'error' );
			var sentData = {};
			$( formid+' input, '+formid+' select, '+formid+' textarea' ).each( function() {
				if ( $( this ).attr( 'type' ) === 'checkbox' || $( this ).attr( 'type' ) === 'radio' ) {
					if ( $( this ).is(':checked') ) {
						sentData[ $( this ).attr( 'name' ) ] = $( this ).val();
					}
				} else {
					sentData[ $( this ).attr( 'name' ) ] = $( this ).val();
				}
			} );
			
			$.ajax({
				'type': "POST",
				'url': ajax_object.ajax_url,
				'data': sentData
				
			}).done(function (result) {
				
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
				}
				callback( result, formid );
				
			});
		},
		
		postFormCallback: function( result, formid ) {
			$( formid+' .loading' ).hide();
			$( formid+' button.save' ).show();
			if ( $( formid+' button.previous' ).length > 0 ) {
				$( formid+' button.previous' ).show();
			}
			var jsonResult = JSON.parse(result);
			if ( jsonResult.errors === undefined || jsonResult.errors.length === 0 ) {
				if ( $( formid+' button.save' ).data( 'close' ) !== undefined && $( formid+' button.save' ).data( 'close' ) !== '' ) {
					$( '#wdg-lightbox-' + $( formid+' button.save' ).data( 'close' ) ).hide();
				}
				if ( $( formid+' button.save' ).data( 'open' ) !== undefined && $( formid+' button.save' ).data( 'open' ) !== '' ) {
					$( '#wdg-lightbox-' + $( formid+' button.save' ).data( 'open' ) ).show();
				}
			}
			
			var callbackFunctionName = $( formid+' button.save' ).data( 'callback' );
			if ( callbackFunctionName !== undefined && callbackFunctionName !== '' ) {
				( eval( callbackFunctionName ) )( result );
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
			WDGProjectPageFunctions.navigationHeight = ($("nav.project-navigation").height() > 0) ? $("nav.project-navigation").height() : $("#navigation").height();
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
					var sProjectMore = '<div class="projects-more" data-value="' + WDGProjectPageFunctions.currentDiv + '" '+sDisplay+'></div>';
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
						$('html, body').animate({scrollTop: clickedElement.offset().top - WDGProjectPageFunctions.navigationHeight}, "slow");
						clickedElement.find('.zone-content > div, p, ul, table, blockquote, h1, h2, h3, h4, h5, h6').slideDown(400);
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



WDGInvestPageFunctions=(function($) {
	return {
		initUI:function() {
			//Interactions choix de contrepartie
			if ($("#invest_form").length > 0) {
				//Changement de montant
				$("#input_invest_amount_part").on( 'keyup change', function () {
					$("#validate_invest_amount_feedback").slideUp();
					$("#link_validate_invest_amount").slideDown();
					WDGInvestPageFunctions.checkInvestInput();
				});

				//Changement de contrepartie
				if($("#reward-selector").length>0){
					$("#reward-selector input:checked").closest("li").addClass("selected");

					$("#reward-selector input").click(function() {
						$("#validate_invest_amount_feedback").slideUp();
						$("#link_validate_invest_amount").slideDown();
						WDGInvestPageFunctions.changeInvestInput();
						WDGInvestPageFunctions.checkInvestInput();
					});
				}

				//Clic sur valider
				$("#link_validate_invest_amount").click(function() {
					WDGInvestPageFunctions.checkInvestInput();
					$("#link_validate_invest_amount").slideUp();
					$("#validate_invest_amount_feedback").show();
					$('html, body').animate({scrollTop: $('#link_validate_invest_amount').offset().top - $("#navigation").height()}, "slow");
				});

				//Validation du formulaire
				$("#invest_form").submit(function(e) {
					var formSelf = this;
					if ($(formSelf).data("hasfilledinfos") != "1" && $("#invest_type").val() == "user") {
						e.preventDefault();
						$("#invest_form_button").hide();
						$("#invest_form_loading").show();
						$.ajax({
							'type' : "POST",
							'url' : ajax_object.ajax_url,
							'data': {
								'action': 'check_invest_input',
								'campaign_id': $(formSelf).data("campaignid"),
								'invest_value': $("#input_invest_amount_part").val(),
								'invest_type' : $("#invest_type").val()
							}
						}).done(function(result){
							WDGInvestPageFunctions.formInvestReturnEvent(result);
						});
					}
				});
			}
		},

		checkInvestInput: function() {
			$(".invest_error").hide();
			$(".invest_success").hide();

			var bValidInput = true;
			$("#input_invest_amount_part").val(($("#input_invest_amount_part").val()).replace(/,/g,"."));

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

				//Vérification Contreparties
				if($("#reward-selector").length>0){
					var rewardSelectedAmount = parseInt($("#reward-selector input:checked~.reward-amount").text());
					var rewardSelectedRemaining = parseInt($("#reward-selector input:checked~.reward-remaining").text());

					if(rewardSelectedRemaining <= 0) {
						$("#invest_error_reward_remaining").show();
						bValidInput = false;
					}

					if (parseInt($("#input_invest_amount").text()) < rewardSelectedAmount){
						$("#invest_error_reward_insufficient").show();
						bValidInput = false;
					}
				}
			}

			if (bValidInput) {
				$("#invest_success_amount").text( parseInt($("#input_invest_amount_total").val()) + parseInt($("#input_invest_amount").text()));
				$("#invest_show_amount").text( parseInt($("#input_invest_amount").text()));
				$("#invest_show_reward").text( ($("#reward-selector input:checked").closest("li").find(".reward-name").text()));
				$(".invest_success").show();
			}

			$("#input_invest_amount_part").css("color", bValidInput ? "green" : "red");
			return bValidInput;
		},

		changeInvestInput: function(){
			//Change apparence élément sélectionné
			$("#reward-selector li").removeClass("selected");
			$("#reward-selector input:checked").closest("li").addClass("selected");

			//Ajuster le champ de montant choisi à la contrepartie selectionnee
			var rewardSelectedAmount = parseInt($("#reward-selector input:checked~.reward-amount").text());
			$("#input_invest_amount_part").val(rewardSelectedAmount);
		},

		isUserInfosFormDisplayed: false,
		showUserInfosForm: function(jsonInfos) {
			$("#wdg-lightbox-userinfos").show();
			$("#lightbox_userinfo_form_button").show();
			$("#lightbox_userinfo_form_loading").hide();
			$("#lightbox_userinfo_form_errors").empty();
			for (var i = 0; i < jsonInfos.errors.length; i++) {
				$("#lightbox_userinfo_form_errors").append("<li>"+jsonInfos.errors[i]+"</li>");
			}

			if (!WDGInvestPageFunctions.isUserInfosFormDisplayed) {
				WDGInvestPageFunctions.isUserInfosFormDisplayed = true;
				$("#lightbox_userinfo_form").submit(function(e) {
					e.preventDefault();
					$("#lightbox_userinfo_form_button").hide();
					$("#lightbox_userinfo_form_loading").show();
					$.ajax({
						'type' : "POST",
						'url' : ajax_object.ajax_url,
						'data': {
							'action': 'save_user_infos',
							'campaign_id': $("#invest_form").data("campaignid"),
							'invest_type': $("#invest_type").val(),
							'gender': $("#update_gender").val(),
							'firstname': $("#update_firstname").val(),
							'lastname': $("#update_lastname").val(),
							'birthday_day': $("#update_birthday_day").val(),
							'birthday_month': $("#update_birthday_month").val(),
							'birthday_year': $("#update_birthday_year").val(),
							'birthplace': $("#update_birthplace").val(),
							'nationality': $("#update_nationality").val(),
							'address': $("#update_address").val(),
							'postal_code': $("#update_postal_code").val(),
							'city': $("#update_city").val(),
							'country': $("#update_country").val(),
							'telephone': $("#update_mobile_phone").val()
						}
					}).done(function(result){
						WDGInvestPageFunctions.formInvestReturnEvent(result);
					});
				});
			}
		},

		formInvestReturnEvent: function(result) {
			var response = "";
			if (result != "") {
				var jsonResult = JSON.parse(result);
				response = jsonResult.response;
			}
			switch (response) {
				case "edit_user":
					WDGInvestPageFunctions.showUserInfosForm(jsonResult);
					break;
				case "new_organization":
					break;
				case "edit_organization":
					break;
				case "kyc":
					break;
				default:
					$("#invest_form").data("hasfilledinfos", "1");
					$("#invest_form").submit();
					break;
			}
		}

	};
})(jQuery);