function WDGCampaignSimulator() {
	//A l'ouverture de l'onglet besoin de financement : calculs de tous les élements
	this.calculAndShowResult();
	//Rattachement des events sur modif du CA
	this.attachEventOnCa();
	// Validation du passage à l'étape suivante
	$( '#form-changing-from-vote' ).submit( function( e ) {
		if ( !confirm( 'Attention, le choix de la date de fin est définitif. Êtes-vous sûr(e) de valider cette date de fin ?' ) ) {
			e.preventDefault();
		}
	} );
}

/**
 * Récupération de tous les CA en fonction de la durée de financement
 * @returns {Array} tableau avec le montant des CA pour chaque année
 */
WDGCampaignSimulator.prototype.createCaTab = function(){           
	if(new_funding_duration!== "0"){
		var nbYears = parseInt(new_funding_duration);
		var caTab = new Array;
		for (var ii=0; ii < nbYears; ii++){
			var new_estimated_turnover = ($("#new_estimated_turnover_"+ii).val() == null) ? ($.trim($("td[data-id=new_estimated_turnover_"+ii+"]").text())) : $("#new_estimated_turnover_"+ii).val();
			caTab.push(parseFloat(new_estimated_turnover));
		}
		return caTab;
	}
};

/**
 * Calcul du CA total sur les années de CA renseignées
 */
WDGCampaignSimulator.prototype.calculTotalCa = function(){
	caTab = this.createCaTab();
	totalca = 0; 
	//boucler sur le nb d'années renseignées pour le cas où celui-ci diminue
	//(ce qui cache les années en moins) sans pour autant perdre les données
	//on peut rajouter une année et retrouver le montant de CA précédemment
	//rempli (tant qu'on n'a pas enregistré)

	$.each(caTab, function(year, ca){
		if(ca >= 0){
			totalca += ca;
		}
	});
};

/**
 * Calcul des royalties reversées par année de CA renseignée
 */
WDGCampaignSimulator.prototype.calculRoiPerYear = function(){
	if(new_roi_percent_estimated !== ""){
		percent = parseFloat(new_roi_percent_estimated)/100;
	} else { percent = false; }
	
	for (var ii=0; ii < caTab.length; ii++ ) {
		if (caTab[ii] > 0) {
			if(percent){
				var roi_amount = percent * caTab[ii];
				var roi_amount_format = this.numberFormat(roi_amount);
				$("#roi-amount-"+ii).html(roi_amount_format+" " + $('#estimated-turnover').data('symbol'));
			}else{ $("#roi-amount-"+ii).html("0 " + $('#estimated-turnover').data('symbol')); }
		}else{ $("#roi-amount-"+ii).html("0 " + $('#estimated-turnover').data('symbol')); }
	}
};

/**
 * Calcul du total de royalties reversées sur le nombre d'années de CA
 * renseigné
 */
WDGCampaignSimulator.prototype.calculReturn = function(){
	totalRoi = 0;
	if(percent){
		totalRoi = percent * totalca;
		if(totalRoi){
			var totalRoi_format = this.numberFormat(totalRoi);
			$("#total-roi").html(totalRoi_format+" " + $('#estimated-turnover').data('symbol'));
		}
	}
};

/**
 * Calcul du montant total de la collecte incluant la commission WDG
 * need = montant max incluant la commission de WDG
 */
WDGCampaignSimulator.prototype.calculCollect = function (){
	if (need!==""){
		collect = need;
		var collect_format = this.numberFormat(collect);
		$("#total-funding").html(collect_format);
	}
};

/**
 * Fonction de calcul du rendement annuel investisseur
 */
WDGCampaignSimulator.prototype.calculAnnualRend = function(){
	var nbYears = 0;
	for (var ii=0; ii < caTab.length; ii++ ) {
		if (caTab[ii] > 0) {
			nbYears++;
		}
	}
	// Ecarter les dénominateurs à zéro
	finalRend = null;
	if(collect != "0" && nbYears != 0){
		//mediumRend = (Math.pow((percent*totalca/collect),(1/nbYears))-1)*100;
		finalRend = Math.round( ( ( totalRoi / need ) - 1 ) * 100 * 100 ) / 100;
		var finalRend_format = this.numberFormat(finalRend);
		if (finalRend > 0) finalRend_format = "+" + finalRend_format;
		$("#medium-rend").html(finalRend_format+' %');
	} else if(collect == "0" || nbYears == 0){
		this.initResultCalcul();
	}
};

/**
 * Vérification d'un rendement minimum supérieur à 3%
 */
WDGCampaignSimulator.prototype.verifMediumRend = function () {
	var rend = $("#medium-rend");
	var errorHtml = "<br/>(insuffisant)";
	if (finalRend < 0 ) {
		rend.css('color', '#EA4F51').css('display','inline-block').css('margin', 0);
		rend.append(errorHtml);
	}
	else{
		rend.css('color','#2B2C2C');
	}
};
		
/**
 * Calcul le gain (montant ROI reçues/montant maximum de la collecte)
 */
WDGCampaignSimulator.prototype.calculGain = function(){
	if(need != "" && need != "0" && new_roi_percent_estimated != "" && new_roi_percent_estimated != "0"){
		var gain = totalRoi/need;
		var gain_format = this.numberFormat(gain);
		var gain_text = "x"+gain_format+" en "+nb_years+" ans";
		$("#gain").html(gain_text);
	}
	else{
		$("#gain").html("");
	}
}

/**
 * Processus de calcul des investissements et du rendement investisseur
 * et mise à jour des résultats dans l'interface
 */
WDGCampaignSimulator.prototype.simuProcess = function(){
	this.calculTotalCa();
	this.calculRoiPerYear();
	this.calculReturn();
	this.calculCollect();
	this.calculAnnualRend();
	this.verifMediumRend();
	this.calculGain();
};

/**
 * Initialisation de l'affichage lorsque les calculs ne peuvent être 
 * réalisés par manque de données
 */
WDGCampaignSimulator.prototype.initResultCalcul = function(){
	$("#total-roi").html("0 " + $('#estimated-turnover').data('symbol'));
	$("#total-funding").html("---");
	$("#medium-rend").html("--- %").css('color','#2B2C2C');
	$("#gain").html("");
	caTab = this.createCaTab();
	for (var ii=0; ii < caTab.length; ii++ ) {
		$("#roi-amount-"+ii).html("0 " + $('#estimated-turnover').data('symbol'));
	}
};

/**
 * Calcul des royalties reversés par année et du rendement (si données déjà renseignées)
 * et rattachement des events sur la modif du CA
 */
WDGCampaignSimulator.prototype.calculAndShowResult = function(){
	this.getDataCalculator();
	if(new_minimum_goal!="" && need!="" && new_funding_duration!="" && new_roi_percent_estimated!="" && new_estimated_turnover_0!=""){
		this.simuProcess();
	} else {
		this.initResultCalcul();
	}
};

/**
 * Récupère les données renseignées dans l'onglet "besoin de financement"
 * selon qu'elles sont encore modifiables (input) ou non (span)
 */
WDGCampaignSimulator.prototype.getDataCalculator = function(){
	new_minimum_goal = $("#new_minimum_goal").val() == null ? $.trim($("span[data-id=new_minimum_goal] span").text()) : $("#new_minimum_goal").val();
	need = $("#new_maximum_goal").val() == null ? $.trim($("span[data-id=new_maximum_goal] span").text()) : $("#new_maximum_goal").val();
	new_roi_percent_estimated = $("#new_roi_percent_estimated").val() == null ? $.trim($("span[data-id=new_roi_percent_estimated] span").text()) : $("#new_roi_percent_estimated").val();
	new_funding_duration = ($("#select-new_funding_duration").val() == null) ? $.trim($("span[data-id=new_funding_duration] span").text()) : $("#select-new_funding_duration").val();
	if ( new_funding_duration == 0 ) {
		new_funding_duration = 5;
	}
	nb_years = new_funding_duration;
	new_estimated_turnover_0 = $("#new_estimated_turnover_0").val() == null ? $.trim($("td[data-id=new_estimated_turnover_0]").text()) : $("#new_estimated_turnover_0").val();
};

/**
 * Attache les events click et keyup sur les inputs des CA de chaque année
 * et déclenche les calculs
 */
WDGCampaignSimulator.prototype.attachEventOnCa = function(){
	var self = this;
	for(var ii = 0; ii < parseInt(new_funding_duration); ii++){
		var new_estimated_turnover = ($("#new_estimated_turnover_"+ii).val() == null) ? $("td[data-id=new_estimated_turnover_"+ii+"]") : $("#new_estimated_turnover_"+ii);
		$(new_estimated_turnover).bind('click keyup',function(){
			self.calculAndShowResult();
		});
	}
};

/**
* Formate les nombres pour afficher au maximum 2 décimales
* @param {number} number
* @returns {string}
*/
WDGCampaignSimulator.prototype.numberFormat = function(number){
	var nb_format = number.toString().replace(",",".");
	nb_format = parseFloat(nb_format);
	nb_format = nb_format.toFixed(2);
	nb_format = nb_format.toString().replace(".",",");
	//Suppression des "00" après la virgule
	if(nb_format.substr(nb_format.length - 2, nb_format.length) === "00"){
		nb_format = nb_format.substr(0, nb_format.length - 3);
	}
	return nb_format;
};

var wdgCampaignSimulator;
jQuery(document).ready( function($) {
    wdgCampaignSimulator = new WDGCampaignSimulator();
} );




//******************************************************************************





function WDGCampaignDashboard() {
	this.walletTimetableDatatable;
	this.createTableRequest;
    this.initWithHash();
    this.initLinks();
	this.initMenu();
	this.initStatsSubTabs();
	this.drawTimetable();
	this.initAjaxForms();
	this.initHome();
	this.initContacts();
	this.initOrgaForms();
	this.initTeam();
	this.initRoyalties();
	this.initFinance();
	this.initCampaign();
}

/**
 * Initialise l'affichage avec le # de l'url
 */
WDGCampaignDashboard.prototype.initWithHash = function() {

	var sCurrentTab = window.location.hash.substring(1);
	if (sCurrentTab !== '') {
		this.switchTab( sCurrentTab );
	} else {
		this.switchTab( 'home' );
	}
	
};

/**
 * Initialise les liens pour couper d'éventuelles requêtes si nécessaire
 */
WDGCampaignDashboard.prototype.initLinks = function() {
	
	var self = this;
	$( 'a' ).click( function() {
		// On ne couple la requete que si il n'y a pas de # dans le lien
		if ( $( this ).attr( 'href' ) !== undefined && $( this ).attr( 'href' ) !== '' && $( this ).attr( 'href' ).indexOf( '#' ) === -1 ) {
			if ( self.createTableRequest !== undefined ) {
				self.createTableRequest.abort();
			}
			if ( YPUIFunctions.currentRequest !== '' ) {
				YPUIFunctions.currentRequest.abort();
			}
		}
	} );
	
};

/**
 * Initialise le menu
 */
WDGCampaignDashboard.prototype.initMenu = function() {
	
	var self = this;
	$( 'ul.nav-menu li a' ).each( function() {
		$( this ).click( function() {
			self.switchTab( $( this ).data( 'tab' ) );
		} );
	} );
	$( 'a.switch-tab' ).click( function() {
		self.switchTab( $( this ).attr( 'href' ).substr( 1 ) );
	} );
	
};

/**
 * Initialise le sous-menu de l'onglet Statistiques
 */
WDGCampaignDashboard.prototype.initStatsSubTabs = function() {
	
	var self = this;
	$( 'ul.menu-onglet li a' ).each( function() {
		$( this ).click( function() {
			if ( $( this ).data( 'subtab' ) !== '' ) {
				$( '.stat-subtab' ).hide();
				$( '#stat-subtab-' + $( this ).data( 'subtab' ) ).show();
				$( 'ul.menu-onglet li a' ).removeClass( 'focus' );
				$( this ).addClass( 'focus' );
				if ( $( this ).data( 'subtab' ) == 'leveedefonds' ) {
					$('#sup-stats-chart').width(600);
				}
			}
		} );
	} );
	
};

/**
 * Change d'onglet
 */
WDGCampaignDashboard.prototype.switchTab = function(sType) {
	
	$( 'ul.nav-menu li' ).removeClass( 'selected' );
	$( 'div#item-body > div.item-body-tab' ).hide();
	
	$( 'ul.nav-menu li#menu-item-' + sType ).addClass( 'selected' );
	$( 'div#item-body > div#item-body-' + sType ).show();
	
	// Mise à jour des datatables pour éviter les décalages de header
	if ( sType == 'royalties' && this.walletTimetableDatatable != undefined ) {
		this.walletTimetableDatatable.draw();
	}
	if ( sType == 'contacts' && this.table != undefined ) {
		this.table.draw();
	}
	
	this.scrollTo( $( '#item-body' ) );
	
};

/**
 * Gère les formulaires ajax
 */
WDGCampaignDashboard.prototype.initAjaxForms = function() {
	
	var self = this;
	$( 'form.ajax-db-form' ).submit( function( e ) {
		
		if ( $(this).attr( 'action' ) != '' && $(this).attr( 'action' ) != undefined ) {
			return;
		}
		e.preventDefault();
		if ( $( this ).data( 'action' ) == undefined ) {
			return false;
		}
		var thisForm = $(this);

		//Receuillir informations du formulaire
		var data_to_update = {
			'action': $( this ).data( 'action' ),
			'campaign_id': campaign_id
		};

		$( this ).find( '.field' ).each( function( index ){
			var id = $( this ).data( 'id' );
			if ( id != undefined ) {
				switch ( $( this ).data( 'type' ) ) {
					case 'datetime':
						var sDate = $( this ).find( 'input:eq(0)' ).val();
						var aDate = sDate.split( '/' );
						data_to_update[ id ] = aDate[ 1 ] + '/' + aDate[ 0 ] + '/' + aDate[ 2 ] + "\ "
							+ $( this ).find( 'select:eq(0)' ).val() + ':'
							+ $( this ).find( 'select:eq(1)' ).val();
						break;
					case 'editor':
						data_to_update[ id ] = tinyMCE.get( id ).getContent();
						break;
					case 'check':
						data_to_update[ id ] = $( '#' + id ).is( ':checked' );
						break;
					case 'multicheck':
						var data_temp = new Array();
						$( 'input', this ).each( function() {
							if ( $( this ).is( ':visible' ) && $( this ).is( ':checked' ) ) {
								data_temp.push( $( this ).val() );
							}
						} );
						data_to_update[ id ] = data_temp;
						break;
					case 'checkboxes':
						$( 'input', this ).each( function() {
							if ( $( this ).is( ':checked' ) ) {
								data_to_update[ id ] = $( this ).is( ':checked' );
							}
						} );
						break;
					case 'text':
					case 'number':
					case 'date':
					case 'link':
					case 'textarea':
					case 'select':
					default:
						data_to_update[ id ] = $( ':input', this ).val();
						break;
				}
				if( data_to_update[ id ] == undefined ){
					delete data_to_update[ id ];
				}
			}
		} );

		//Désactive les champs
		var save_button = $("#"+$(this).attr("id")+"_button");
		save_button.find(".button-text").hide();
		save_button.find(".button-waiting").show();
		$(":input", this).prop('disabled', true);

		thisForm.find('.feedback_save span').fadeOut();

		//Envoi de requête Ajax
		$.ajax({
			'type': "POST",
			'url': ajax_object.ajax_url,
			'data': data_to_update
		}).done(function (result) {
			if (result != "") {
				var jsonResult = JSON.parse(result);
				feedback = jsonResult;

				//Affiche les erreurs
				for(var input in feedback.errors){
					self.fieldError(thisForm.find('#'+input), feedback.errors[input])
				}

				for(var input in feedback.success){
					var thisinput = thisForm.find('#'+input);
					if ( thisinput.length == 0 ) {
						thisinput = thisForm.find('input[name='+input+'],select[name='+input+']');
					}
					self.removeFieldError(thisinput);
					thisinput.closest(".field-value").parent().find('i.fa.validation').remove();
					thisinput.addClass("validation");
					thisinput.closest(".field-value").after('<i class="fa fa-check validation" aria-hidden="true"></i>');
				}

				//Scrolle jusqu'à la 1ère erreur et la sélectionne
				var firsterror = thisForm.find(".error").first();
				if(firsterror.length == 1){
					self.scrollTo(firsterror);
					//La sélection (ci-dessous) Ne fonctione ne marche pas
					firsterror.focus();
					thisForm.find('.save_errors').fadeIn();
				} else {
					thisForm.find('.save_ok').fadeIn();                          
				}


				// Enregistrer l'organisation liée au projet dans tab-organization
				if ( $( this ).data( 'action' ) == "save_project_organization" ){
					//Afficher le bouton d'édition de l'organisation après enregistrement de la liaison
					self.updateEditOrgaBtn(thisForm);
					//Mise à jour du formulaire d'édition après enregistrement de la liaison
					self.updateOrgaForm(feedback);
					//Mise à jour des liens de téléchargement des docs du formulaire d'édition
					self.updateOrgaFormDoc(feedback);
					$("#save-mention").hide();
					$("#orgainfo_form_button").hide();
					thisForm.find('.save_ok').hide();
					$("#wdg-lightbox-valid-changeOrga").css('display', 'block');
					new_project_organization = $("#select-new_project_organization option:selected").val();
					
				}
			}
		}).fail(function() {
			thisForm.find('.save_fail').fadeIn();
		}).always(function() {
			//Réactive les champs
			save_button.find(".button-waiting").hide();
			save_button.find(".button-text").show();
			thisForm.find(":input").prop('disabled', false);
		});
	});
};

WDGCampaignDashboard.prototype.initHome = function() {

	//Preview date fin collecte sur LB étape suivante
	if(($("#innbdayvote").length > 0)||($("#innbdaycollecte").length > 0)) {

		updateDate = function(idfieldinput, iddisplay) {
			$("#"+iddisplay).empty();
			if($("#"+idfieldinput).val()<=$("#"+idfieldinput).prop("max") && $("#"+idfieldinput).val()>=$("#"+idfieldinput).prop("min")){
				var d = new Date();
				var jsupp = $("#"+idfieldinput).val();
				d.setDate(d.getDate()+parseInt(jsupp));
				$("#"+iddisplay).prepend(' '+d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear());
			} else {
				$("#"+iddisplay).prepend("La durée doit être comprise entre "+($("#"+idfieldinput).prop("min"))+" et "+($("#"+idfieldinput).prop("max"))+" jours");
			}
		};

		updateDate("innbdaycollecte","previewenddatecollecte");
		updateDate("innbdayvote","previewenddatevote");

		$("#innbdaycollecte").on( 'keyup change', function () {
			updateDate("innbdaycollecte","previewenddatecollecte");});

		$("#innbdayvote").on( 'keyup change', function () {
			updateDate("innbdayvote","previewenddatevote");});
	}
	
};

WDGCampaignDashboard.prototype.initContacts = function() {

	var self = this;
	var mail_content, mail_title, originalText;
	$("#direct-mail #mail-preview-button").click(function () {
		mail_content = tinyMCE.get('mail_content').getContent();
		mail_title = $("#direct-mail #mail-title").val();

		if (mail_title == ""){
			self.fieldError($("#direct-mail #mail-title"),"L'objet du mail ne peut être vide");
		} else {
			self.removeFieldError($("#direct-mail #mail-title"));
			originalText = $(this).html();
			$(this).html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');

			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': {
					'action':'preview_mail_message',
					'id_campaign':campaign_id,
					'mail_content' : mail_content,
					'mail_title' : mail_title
				}
			}).done(function(result){
				var res = JSON.parse(result);

				$("#direct-mail .preview-title").html('<i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;'+res.content.title);
				$("#direct-mail .preview").html(res.content.body);
				$("#direct-mail .step-write").slideUp();
				$("#direct-mail .step-confirm").slideDown();
				$("#direct-mail #mail-preview-button").html(originalText);
			})
		}
	});

	$("#direct-mail #mail-back-button").click(function () {
		$("#direct-mail .step-confirm").slideUp();
		$("#direct-mail .step-write").slideDown();
	});

	$( '#add-check-search-email' ).click( function( e ) {
		e.preventDefault();
		$( '#add-check-search-email' ).addClass( 'disabled' );
		$( '.add-check-feedback' ).hide();
		$( '#add-check-search-loading' ).show();
		$.ajax({
			'type' : "POST",
			'url' : ajax_object.ajax_url,
			'data': {
				'action':'search_user_by_email',
				'email' : $( '#add-check-input-email' ).val()
			}
		}).done(function(result){
			var jsonResult = JSON.parse(result);
			$( '#add-check-search-email' ).removeClass( 'disabled' );
			$( '#add-check-search-loading' ).hide();
			switch ( jsonResult.user_type ) {
				case 'user': $("#add-check-feedback-found-user").show(); break;
				case 'orga': $("#add-check-feedback-found-orga").show(); break;
				default: $("#add-check-feedback-not-found").show(); break;
			}
			if ( jsonResult.user_type == 'user' || jsonResult.user_type == 'orga' ) {
				$( '#wdg-lightbox-add-check #add-check-input-username' ).val( jsonResult.user_data.user.login );
				$( '#wdg-lightbox-add-check #add-check-input-gender' ).val( jsonResult.user_data.user.gender );
				$( '#wdg-lightbox-add-check #add-check-input-firstname' ).val( jsonResult.user_data.user.firstname );
				$( '#wdg-lightbox-add-check #add-check-input-lastname' ).val( jsonResult.user_data.user.lastname );
				$( '#wdg-lightbox-add-check #add-check-input-birthday-day' ).val( jsonResult.user_data.user.birthday_day );
				var month = Number( jsonResult.user_data.user.birthday_month );
				$( '#wdg-lightbox-add-check #add-check-input-birthday-month' ).val( month );
				$( '#wdg-lightbox-add-check #add-check-input-birthday-year' ).val( jsonResult.user_data.user.birthday_year );
				$( '#wdg-lightbox-add-check #add-check-input-birthplace' ).val( jsonResult.user_data.user.birthplace );
				$( '#wdg-lightbox-add-check #add-check-input-nationality' ).val( jsonResult.user_data.user.nationality );
				$( '#wdg-lightbox-add-check #add-check-input-address' ).val( jsonResult.user_data.user.address );
				$( '#wdg-lightbox-add-check #add-check-input-postal-code' ).val( jsonResult.user_data.user.postal_code );
				$( '#wdg-lightbox-add-check #add-check-input-city' ).val( jsonResult.user_data.user.city );
				$( '#wdg-lightbox-add-check #add-check-input-country' ).val( jsonResult.user_data.user.country );
			}
			if ( jsonResult.user_type == 'orga' ) {
				$( '#wdg-lightbox-add-check #add-check-input-orga-email' ).val( jsonResult.user_data.orga.email );
				$( '#wdg-lightbox-add-check #add-check-input-orga-name' ).val( jsonResult.user_data.orga.name );
			}
		});
	} );
	
	$( '#show-notifications-preinvestment, #show-notifications-prelaunch' ).click( function( e ) {
		e.preventDefault();
		if ( $( this ).attr( 'id' ) == 'show-notifications-preinvestment' ) {
			$( '#form-notifications-preinvestment #mail_type' ).val( 'preinvestment' );
		}
		if ( $( this ).attr( 'id' ) == 'show-notifications-prelaunch' ) {
			$( '#form-notifications-preinvestment #mail_type' ).val( 'prelaunch' );
		}
		$( '#form-notifications-preinvestment' ).hide();
		$( '#form-notifications-preinvestment' ).slideDown( 100 );
	} );
};

/**
 * Gestion des formulaires de mise à jour d'organisation
 */
WDGCampaignDashboard.prototype.initOrgaForms = function() {

	var self = this;
	$("#orgainfo_form_button").hide();//suppression bouton enregistrer
	if($("#select-new_project_organization").val() !== ""){
		var new_project_organization = $("#select-new_project_organization option:selected").val();
		$("#edit-orga-button").show();
	}
	$("#select-new_project_organization").change(function(e){
		e.preventDefault();
		$("#orgainfo_form_button").hide();//suppression bouton enregistrer
		if($("#select-new_project_organization option:selected").val() !== new_project_organization) {
			$("#edit-orga-button").hide();
			$("#orgainfo_form_button").show();//apparition bouton enregistrer
			//Suppression des éléments d'une validation précédente
			if($(".save_ok").length > 0) $(".save_ok").hide();
			if($("#orgainfo_form i.fa.validation").length > 0) $("#orgainfo_form i.fa.validation").remove();
			if($("#select-new_project_organization").hasClass("validation")) $("#select-new_project_organization").removeClass("validation");
			if($("#save-mention").is(":hidden")) $("#save-mention").show();
			//
		}else{
			if($("#save-mention").is(":visible")) $("#save-mention").hide();
			$("#edit-orga-button").show();
		}
		$("#wdg-lightbox-editOrga ul.errors li").remove();
	});
	//Suppression du feedback "enregistré" à l'ouverture de la lightbox
	$("#orgainfo_form #edit-orga-button").click(function(){
		$("#orgaedit_form").find('.save_ok').fadeOut();
	});

	//Création objet FormData (Envoi des fichiers uploadés en ajax dans le formulaire d'édition)
	$("#wdg-lightbox-editOrga form#orgaedit_form").submit(function(e){
		e.preventDefault();
		var thisForm = $(this);
		var fd = new FormData($('#wdg-lightbox-editOrga #orgaedit_form')[0]);

		//Désactive les champs
		var save_button = $("#"+$(this).attr("id")+"_button");
		save_button.find(".button-text").hide();
		save_button.find(".button-waiting").show();
		$(":input", this).prop('disabled', true);
		thisForm.find('.feedback_save span').fadeOut();

		$.ajax({
			'type' : "POST",
			'url' :ajax_object.ajax_url,
			'data': fd,
			'cache': false,
			'contentType': false,
			'processData': false,
		}).done(function(result) {
			if(result === "FALSE"){//user non connecté
				window.location.reload();//affiche message de non permission
			}else{
				var jsonResult = JSON.parse(result);
				feedback = jsonResult;
				//Vérification s'il y a des erreurs sur l'envoi de fichiers
				$("#wdg-lightbox-editOrga p.errors").remove();
				var fdFileInfo = feedback.files_info;
				var count_files_errors = 0;
				for (var doc in fdFileInfo){
					if(fdFileInfo[doc] != null) {
						if (fdFileInfo[doc]['code'] === 1){//erreur
							count_files_errors += 1;
							var errFile = $('<p class="errors">'+fdFileInfo[doc]['info']+'</p>');
							errFile.insertAfter($("#orgaedit_form input[name="+doc+"]"));
						}
						else {
							self.updateOrgaDoc(fdFileInfo, doc);//mise à jour des liens de téléchargement
						}
					}
				}
				//Vérification s'il y a des erreurs sur les champs
				var fdErrorsData = feedback.errors;
				var count_data_errors = 0;
				for (var error in fdErrorsData){
					if(error !== "") {
						count_data_errors += 1;
						var err = $("<p class='errors'>"+fdErrorsData[error]+"</p>");
						err.insertAfter($("#orgaedit_form input[name="+error+"]"));
					}
				}
				if(count_files_errors > 0 || count_data_errors > 0) {
					var err = $("<p class='errors'>Certains champs n'ont pas été validés.</p>");
					err.insertAfter($("#orgaedit_form_button button"));
				}
				//Affichage confirmation enregistrement
				if (count_files_errors === 0 && count_data_errors === 0){
					$("#wdg-lightbox-editOrga p.errors").hide();
					thisForm.find('.save_ok').fadeIn();
					$("#wdg-lightbox-editOrga").hide();
					$("#wdg-lightbox-valid-editOrga").css('display', 'block');

					//Mise à jour du reste du formulaire d'édition (input type text)
					self.updateOrgaForm(feedback);
				}
			}
		}).fail(function() {
			thisForm.find('.save_fail').fadeIn();
		}).always(function() {
			//Réactive les champs
			save_button.find(".button-waiting").hide();
			save_button.find(".button-text").show();
			thisForm. find(":input").prop('disabled', false);
		});
	});
	//Vider les champs à l'ouverture de la lightbox de création
	//Suppression du feedback "enregistré" à l'ouverture de la lightbox
	$('#orgainfo_form #btn-new-orga').click(function(){
		$(':input', '#orgacreate_form')
			.not(':hidden')
			.val('')
			.removeAttr('checked')
			.removeAttr('selected');
		$("#orgacreate_form").find('.save_ok').fadeOut();
		$("#wdg-lightbox-newOrga p.errors").remove();
	});

	//fermeture de la lightbox de création d'organisation après enregistrement
	$("#wdg-lightbox-newOrga form.wdg-forms").submit(function(e){
		e.preventDefault();
		var thisForm = $(this);

		var campaign_id, org_name, org_email, org_representative_function, org_description, org_legalform,
		org_idnumber, org_rcs,org_capital, org_ape, org_vat, org_fiscal_year_end_month, org_address_number, org_address_number_comp, org_address, org_postal_code,
		org_city, org_nationality, org_bankownername, org_bankowneraddress,
		org_bankowneriban, org_bankownerbic, org_capable;

		campaign_id = $('#wdg-lightbox-newOrga input[name=campaign_id]').val();
		org_name = $('#wdg-lightbox-newOrga input[name=org_name]').val();
		org_email = $('#wdg-lightbox-newOrga input[name=org_email]').val();
		org_representative_function = $('#wdg-lightbox-newOrga input[name=org_representative_function]').val();
		org_description = $('#wdg-lightbox-newOrga input[name=org_description]').val();
		org_legalform = $('#wdg-lightbox-newOrga input[name=org_legalform]').val();
		org_idnumber = $('#wdg-lightbox-newOrga input[name=org_idnumber]').val();
		org_rcs = $('#wdg-lightbox-newOrga input[name=org_rcs]').val();
		org_capital = $('#wdg-lightbox-newOrga input[name=org_capital]').val();
		org_ape = $('#wdg-lightbox-newOrga input[name=org_ape]').val();
		org_vat = $('#wdg-lightbox-newOrga input[name=org_vat]').val();
		org_fiscal_year_end_month = $('#wdg-lightbox-newOrga select[name=org_fiscal_year_end_month]').val();
		org_address_number = $('#wdg-lightbox-newOrga input[name=org_address_number]').val();
		org_address_number_comp = $('#wdg-lightbox-newOrga input[name=org_address_number_comp]').val();
		org_address = $('#wdg-lightbox-newOrga input[name=org_address]').val();
		org_postal_code = $('#wdg-lightbox-newOrga input[name=org_postal_code]').val();
		org_city = $('#wdg-lightbox-newOrga input[name=org_city]').val();
		org_nationality = $('#wdg-lightbox-newOrga #org_nationality option:selected').text();
		org_bankownername = $('#wdg-lightbox-newOrga input[name=org_bankownername]').val();
		org_bankowneraddress = $('#wdg-lightbox-newOrga input[name=org_bankowneraddress]').val();
		org_bankowneriban = $('#wdg-lightbox-newOrga input[name=org_bankowneriban]').val();
		org_bankownerbic = $('#wdg-lightbox-newOrga input[name=org_bankownerbic]').val();
		org_capable = $('#wdg-lightbox-newOrga input[name=org_capable]').is(':checked');

		//Désactive les champs
		var save_button = $("#"+$(this).attr("id")+"_button");
		save_button.find(".button-text").hide();
		save_button.find(".button-waiting").show();
		$(":input", this).prop('disabled', true);
		thisForm.find('.feedback_save span').fadeOut();

		$.ajax({  
			'type': "POST",
			'url': ajax_object.ajax_url,
			'data': {
				'action': 'save_new_organization',
				'campaign_id': campaign_id,
				'org_name': org_name,
				'org_email': org_email,
				'org_representative_function': org_representative_function,
				'org_description': org_description,
				'org_legalform': org_legalform,
				'org_idnumber': org_idnumber,
				'org_rcs': org_rcs,
				'org_capital': org_capital,
				'org_ape': org_ape,
				'org_vat': org_vat,
				'org_fiscal_year_end_month': org_fiscal_year_end_month,
				'org_address_number': org_address_number,
				'org_address_number_comp': org_address_number_comp,
				'org_address': org_address,
				'org_postal_code': org_postal_code,
				'org_city': org_city,
				'org_nationality': org_nationality,
				'org_bankownername': org_bankownername,
				'org_bankowneraddress': org_bankowneraddress,
				'org_bankowneriban': org_bankowneriban,
				'org_bankownerbic': org_bankownerbic,
				'org_capable': org_capable
			}
		}).done(function(result){
			if(result === "FALSE"){//user non connecté
				window.location.reload();//affiche message de non permission
			}else{
				var jsonResult = JSON.parse(result);
				feedback = jsonResult;

				//Vérification s'il y a des erreurs dans le formulaire
				$("#wdg-lightbox-newOrga p.errors").remove();//supprime les erreurs éventuellement affichées après un 1er enregistrement
				var errors = feedback.errors;
				var count_errors = 0;
				for (var error in errors){
					if(error !== ""){
						count_errors+=1;
						var err = $('<p class="errors">'+errors[error]+'</p>');
						if(error !== "org_capable"){
							err.insertAfter($("#orgacreate_form input[name="+error+"]"));
						}
						if(error === "org_nationality") {
							err.insertAfter($("#orgacreate_form select#org_nationality"));
						}
						if(error === "org_capable") {
							err.insertAfter($("#orgacreate_form input[name="+error+"]").next());
						}
					}
				}
				if(count_errors > 0) {
					var firsterror = thisForm.find(".errors").first();
					
					if(firsterror.length === 1){
						self.scrollTo(firsterror);
					}
				}
				//Affichage confirmation enregistrement
				if(count_errors === 0){
					$("#wdg-lightbox-newOrga p.errors").hide();//cache les erreurs éventuellement affichées après un 1er enregistrement
					thisForm.find('.save_ok').fadeIn();
					$("#wdg-lightbox-newOrga").hide();
					$("#wdg-lightbox-valid-newOrga").css('display', 'block');
					//Mise à jour de l'input select
					self.updateOrgaSelectInput(feedback);

					//Mise à jour du bouton d'édition
					var newname = $("#select-new_project_organization").find('option:selected').text();
					var edit_btn = $('#orgainfo_form').find($("#edit-orga-button"));
					edit_btn.attr("href","#");
					edit_btn.text("Editer "+newname);

					//Mise à jour du formulaire d'édition
					self.updateOrgaForm(feedback);
				}
			}
		}).fail(function() {
			thisForm.find('.save_fail').fadeIn();
		}).always(function() {
			//Réactive les champs
			save_button.find(".button-waiting").hide();
			save_button.find(".button-text").show();
			thisForm. find(":input").prop('disabled', false);

		});

	});


	$("#update_project_organization").change(function(e){
		var newval = $("#update_project_organization").val();

		if(newval!=''){
			$("#edit-orga-button").show();
			var newname = $("#update_project_organization").find('option:selected').text();
			$("#edit-orga-button").attr("href",$("#edit-orga-button").data("url-edit")+newval);

		};

	});	
};

/* Fonction de mise à jour du bouton d'édition d'une organisation
* une fois l'organisation sélectionnée et enregistrée
* @param {type} form : formulaire de saisie
*/
WDGCampaignDashboard.prototype.updateEditOrgaBtn = function(form){
   var newval = $("#select-new_project_organization").val();
   if(newval!== ''){
	   var edit_btn = form.find($("#edit-orga-button")).show();

	   var newname = $("#select-new_project_organization").find('option:selected').text();
	   edit_btn.attr("href","#");
	   edit_btn.text("Editer "+newname);

   } else {
	   edit_btn.hide();
   }
};

/**
* Fonction de mise à jour du formulaire d'édition d'une organisation
* une fois l'organisation sélectionnée et enregistrée
* @param {objet} feedback : retour ajax
*/
WDGCampaignDashboard.prototype.updateOrgaForm = function(feedback){
	if ( $("#wdg-lightbox-editOrga #org_name").length > 0 ) {
		$("#wdg-lightbox-editOrga #org_name").html(feedback.organization.name);
	} else {
		$("#wdg-lightbox-editOrga input[name=org_name]").val(feedback.organization.name);
	}
   $("#wdg-lightbox-editOrga input[name=org_email]").val(feedback.organization.email);
   $("#wdg-lightbox-editOrga input[name=org_representative_function]").val(feedback.organization.representative_function);
   $("#wdg-lightbox-editOrga input[name=org_description]").val(feedback.organization.description);
   $("#wdg-lightbox-editOrga input[name=org_legalform]").val(feedback.organization.legalForm);
   $("#wdg-lightbox-editOrga input[name=org_idnumber]").val(feedback.organization.idNumber);
   $("#wdg-lightbox-editOrga input[name=org_rcs]").val(feedback.organization.rcs);
   $("#wdg-lightbox-editOrga input[name=org_capital]").val(feedback.organization.capital);
   $("#wdg-lightbox-editOrga input[name=org_ape]").val(feedback.organization.ape);
   $("#wdg-lightbox-editOrga input[name=org_vat]").val(feedback.organization.vat);
   $("#wdg-lightbox-editOrga input[name=org_fiscal_year_end_month]").val(feedback.organization.fiscal_year_end_month);
   $("#wdg-lightbox-editOrga input[name=org_address_number]").val(feedback.organization.address_number);
   $("#wdg-lightbox-editOrga input[name=org_address_number_comp]").val(feedback.organization.address_number_comp);
   $("#wdg-lightbox-editOrga input[name=org_address]").val(feedback.organization.address);
   $("#wdg-lightbox-editOrga input[name=org_postal_code]").val(feedback.organization.postal_code);
   $("#wdg-lightbox-editOrga input[name=org_city]").val(feedback.organization.city);
   $("#wdg-lightbox-editOrga input[name=org_nationality]").val(feedback.organization.nationality);
   $("#wdg-lightbox-editOrga input[name=org_bankownername]").val(feedback.organization.bankownername);
   $("#wdg-lightbox-editOrga input[name=org_bankowneraddress]").val(feedback.organization.bankowneraddress);
   $("#wdg-lightbox-editOrga input[name=org_bankowneriban]").val(feedback.organization.bankowneriban);
   $("#wdg-lightbox-editOrga input[name=org_bankownerbic]").val(feedback.organization.bankownerbic);
};

/**
* Fonction de mise à jour des liens de téléchargement des documents
* uploadés de l'organisation après l'action save_project_organisation
* @param {object} feedback : infos renvoyées par l'action php
*/
WDGCampaignDashboard.prototype.updateOrgaFormDoc = function(feedback){
	if(feedback.organization.doc_bank.path != null){
		if($("#wdg-lightbox-editOrga a#org_doc_bank").length === 0){
			var link_bank = $('<a id="org_doc_bank" class="button blue-pale download-file" target="_blank" href="'+feedback.organization.doc_bank.path+'">'+feedback.organization.doc_bank.date_uploaded+'</a><br />');
			link_bank.insertBefore($("#wdg-lightbox-editOrga input[name=org_doc_bank]"));
		} else{
			$("#wdg-lightbox-editOrga a#org_doc_bank").attr("href", feedback.organization.doc_bank.path);
			$("#wdg-lightbox-editOrga a#org_doc_bank").html(feedback.organization.doc_bank.date_uploaded);
		}
	} else {
		$("#wdg-lightbox-editOrga a#org_doc_bank").remove();
	}

	if(feedback.organization.doc_kbis.path != null){
		if($("#wdg-lightbox-editOrga a#org_doc_kbis").length === 0){
			var link_kbis = $('<a id="org_doc_kbis" class="button blue-pale download-file" target="_blank" href="'+feedback.organization.doc_kbis.path+'">'+feedback.organization.doc_kbis.date_uploaded+'</a><br />');
			link_kbis.insertBefore($("#wdg-lightbox-editOrga input[name=org_doc_kbis]"));
		} else{
			$("#wdg-lightbox-editOrga a#org_doc_kbis").attr("href", feedback.organization.doc_kbis.path);
			$("#wdg-lightbox-editOrga a#org_doc_kbis").html(feedback.organization.doc_kbis.date_uploaded);
		}
	} else {
		$("#wdg-lightbox-editOrga a#org_doc_kbis").remove();
	}

	if(feedback.organization.doc_status.path != null){
		if($("#wdg-lightbox-editOrga a#org_doc_status").length === 0){
			var link_status = $('<a id="org_doc_status" class="button blue-pale download-file" target="_blank" href="'+feedback.organization.doc_status.path+'">'+feedback.organization.doc_status.date_uploaded+'</a><br />');
			link_status.insertBefore($("#wdg-lightbox-editOrga input[name=org_doc_status]"));
		} else{
			$("#wdg-lightbox-editOrga a#org_doc_status").attr("href", feedback.organization.doc_status.path);
			$("#wdg-lightbox-editOrga a#org_doc_status").html(feedback.organization.doc_status.date_uploaded);
		}
	} else {
		$("#wdg-lightbox-editOrga a#org_doc_status").remove();
	}

	if(feedback.organization.doc_id.path != null){
		if($("#wdg-lightbox-editOrga a#org_doc_id").length === 0){
			var link_id = $('<a id="org_doc_id" class="button blue-pale download-file" target="_blank" href="'+feedback.organization.doc_id.path+'">'+feedback.organization.doc_id.date_uploaded+'</a><br />');
			link_id.insertBefore($("#wdg-lightbox-editOrga input[name=org_doc_id]"));
		} else{
			$("#wdg-lightbox-editOrga a#org_doc_id").attr("href", feedback.organization.doc_id.path);
			$("#wdg-lightbox-editOrga a#org_doc_id").html(feedback.organization.doc_id.date_uploaded);
		}
	} else {
		$("#wdg-lightbox-editOrga a#org_doc_id").remove();
	}

	if(feedback.organization.doc_home.path != null){
		if($("#wdg-lightbox-editOrga a#org_doc_home").length === 0){
			var link_home = $('<a id="org_doc_home" class="button blue-pale download-file" target="_blank" href="'+feedback.organization.doc_home.path+'">'+feedback.organization.doc_home.date_uploaded+'</a><br />');
			link_home.insertBefore($("#wdg-lightbox-editOrga input[name=org_doc_home]"));
		} else{
			$("#wdg-lightbox-editOrga a#org_doc_home").attr("href", feedback.organization.doc_home.path);
			$("#wdg-lightbox-editOrga a#org_doc_home").html(feedback.organization.doc_home.date_uploaded);
		}
	} else {
		$("#wdg-lightbox-editOrga a#org_doc_home").remove();
	}
};

/**
* Fonction de mise à jour du lien de téléchargement du fichier uploadé
* @param {array} fileInfo : tableau des infos sur tous les fichiers uploadés
* @param {String} document : nom du document uploadé
*/
WDGCampaignDashboard.prototype.updateOrgaDoc = function(fileInfo, document){
	if(fileInfo[document]['info'] !== null) { //il y a un fichier à uploader
		if($("#wdg-lightbox-editOrga a#"+document).length === 0){
			var link = $('<a id="'+document+'" class="button blue-pale download-file" target="_blank" href="'+fileInfo[document]['info']+'">'+fileInfo[document]['date']+'</a><br />');
			link.insertBefore($("#wdg-lightbox-editOrga input[name="+document+"]"));
		}
		else{
			$("#wdg-lightbox-editOrga a#"+document).attr("href", fileInfo[document]['info']);
			$("#wdg-lightbox-editOrga a#"+document).html(fileInfo[document]['date']);
		}
	}
};

/**
* Fonction de mise à jour du select pour le choix de l'organisation
* après la création d'une organisation depuis le tableau de bord
* @param {objet} feedback : retour ajax
*/
WDGCampaignDashboard.prototype.updateOrgaSelectInput = function(feedback){
	var orgaName = feedback.organization.name;
	var orgaWpref = feedback.organization.wpref;

	$("#orgainfo_form #select-new_project_organization").append(new Option(orgaName, orgaWpref));
	$("#orgainfo_form #select-new_project_organization option:selected").removeAttr('selected');
	$("#orgainfo_form #select-new_project_organization option[value="+orgaWpref+"]").attr("selected", "selected");
};

WDGCampaignDashboard.prototype.getContactsTable = function(inv_data, campaign_id) {
	var self = this;
	
	self.createTableRequest = $.ajax({
		'type' : "POST",
		'url' : ajax_object.ajax_url,
		'data': {
			'action':'create_contacts_table',
			'id_campaign':campaign_id,
			'data' : inv_data
		}
	}).done(function(result){
		self.createTableRequest = undefined;
		//Affiche resultat requete Ajax une fois reçue
		$('#ajax-contacts-load').after(result);
		$('#ajax-loader-img').hide();//On cache la roue de chargement.

		YPUIFunctions.initQtip();

		//Création du tableau dynamique dataTable
		self.table = $('#contacts-table').DataTable({
			scrollX: '100%',
			scrollY: '70vh', //Taille max du tableau : 70% de l'écran
			scrollCollapse: true, //Diminue taille du tableau si peu d'éléments*/

			paging: false, //Pas de pagination, affiche tous les éléments yolo
			order: [[result_contacts_table['default_sort'],"desc"]],

			colReorder: { //On peut réorganiser les colonnes
				fixedColumnsLeft: result_contacts_table['id_column_index']+1 //Les 5 colonnes à gauche sont fixes
			},
			fixedColumns : {
				leftColumns: result_contacts_table['id_column_index']+1
			},


			columnDefs: [
				{
					targets: result_contacts_table['array_hidden'], //Cache colonnes par défaut
					visible: false
				},{
					targets: [result_contacts_table['id_column_index']], //Cache colonnes par défaut
					visible: false
				},{
					className: 'select-checkbox',
					targets : 0,
					orderable: false,
				},{
					width: "30px",
					className: "dt-body-center nopadding",
					targets: [2,3,4]
				}
			],

			//Permet la sélection de lignes
			select: {
				style: 'multi', //Sélection multiple
				selector: 'td:first-child'
			},

			dom: 'Bfrtip',
			buttons: [
				{
					text: '<i class="fa fa-square-o" aria-hidden="true"></i> Sélectionner les éléments affichés',
					action: function () {
						self.table.rows( { search: 'applied' } ).select();
					}
				},{
					//Bouton envoi de mail
					extend: 'selected',
					text: '<i class="fa fa-envelope" aria-hidden="true"></i> Envoyer un mail',
					action: function ( e, dt, button, config ) {
						$("#send-mail-tab").slideDown();
						var target = $(this).data("target");
						self.scrollTo($("#send-mail-tab"));
					}
					//TODO : Scroller jusqu'au panneau
				},


				{
					extend: 'collection',
					text: '<i class="fa fa-eye" aria-hidden="true"></i> Informations à afficher',
					buttons: [{
						//Bouton d'affichage de colonnes
						extend: 'colvis',
						text: '<i class="fa fa-columns" aria-hidden="true"></i> Colonnes à afficher',
						columns: ':gt('+result_contacts_table['id_column_index']+')', //On ne peut pas cacher les 5 premières colonnes
						collectionLayout: 'two-column'
					},{
						extend: 'colvisGroup',
						text: 'Tout afficher',
						show: ':gt('+result_contacts_table['id_column_index']+'):hidden'
					},{
						extend: 'colvisGroup',
						text: 'Tout masquer',
						hide: ':gt('+result_contacts_table['id_column_index']+')'
					},{
						extend: 'colvisRestore',
						text: '<i class="fa fa-refresh" aria-hidden="true"></i> Rétablir colonnes par défaut'
					}]
				},

				//Menu d'export
				{
					extend: 'collection',
					text: '<i class="fa fa-download" aria-hidden="true"></i> Exporter',
					buttons: [ {
						//Bouton d'export excel
						extend: 'excel',
						text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Fichier Excel',
						exportOptions: {
							modifier: {
								columns: ':visible'
							}
						}
					},{
						//Bouton d'export impression
						extend: 'print',
						text: '<i class="fa fa-print" aria-hidden="true"></i> Imprimer',
						exportOptions: {
							modifier: {
								columns: ':visible'
							}
						}
					} ]
				}
			],

			language : {
				"sProcessing":     "Traitement en cours...",
				"sSearch":         "Rechercher&nbsp;:",
				"sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
				"sInfo":           "Affichage de _TOTAL_ &eacute;l&eacute;ments",
				"sInfoEmpty":      "Aucun &eacute;l&eacute;ment &agrave; afficher",
				"sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
				"sInfoPostFix":    "",
				"sLoadingRecords": "Chargement en cours...",
				"sZeroRecords":    "Aucun &eacute;l&eacute;ment",
				"sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
				"oPaginate": {
					"sFirst":      "Premier",
					"sPrevious":   "Pr&eacute;c&eacute;dent",
					"sNext":       "Suivant",
					"sLast":       "Dernier"
				},
				"oAria": {
					"sSortAscending":  ": activer pour trier la colonne par ordre croissant",
					"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
				},
				select: {
					rows: {
						_: "<br /><b>%d</b> contacts sélectionnés",
						0: '<br />Cliquez sur un contact pour le sélectionner',
						1: "<br /><b>1</b> contact sélectionné"
					}
				}
			}
		});
		self.table.columns.adjust();

		var mailButtonDefault = self.table.button(1).text()
		self.table.on("select.dt deselect.dt", function ( e, dt, type, indexes ) {
			//Maj Bouton de Mail
			var selectedCount = self.table.rows({ selected: true }).count();
			if(selectedCount==0){
				self.table.button(1).text(mailButtonDefault);
				$("#send-mail-tab").slideUp();
			} else {
				self.table.button(1).text(mailButtonDefault+" ("+selectedCount+")");
			}


			//Maj Bouton de sélection
			var allContained = true;
			self.table.rows( { search:'applied' } ).every( function ( rowIdx, tableLoop, rowLoop ) {
				if($.inArray(rowIdx, self.table.rows({ selected: true }).indexes())==-1){
					allContained= false;
				}
			} );

			if(allContained){
				self.table.button(0).text('<i class="fa fa-check-square-o" aria-hidden="true"></i> Déselectionner les éléments affichés');
				self.table.button(0).action(function () {
					self.table.rows( { search: 'applied' } ).deselect();
				});
			} else {
				self.table.button(0).text('<i class="fa fa-square-o" aria-hidden="true"></i> Sélectionner les éléments affichés');
				self.table.button(0).action(function () {
					self.table.rows( { search: 'applied' } ).select();
				});
			}

			//Maj Champs de Mail
			$("#nb-mailed-contacts").text(selectedCount);

			//Maj liste des identifiants à mailer
			var recipients_array = [];
			$.each(self.table.rows({ selected: true }).data(), function(index, element){
				recipients_array.push(element[result_contacts_table['id_column_index']]);
			});
			$("#mail_recipients").val(recipients_array);
		} );

		// Champs de filtrage
		$( self.table.table().container() ).on( 'keyup', 'tfoot .text input', function () {
			self.table
				.column( $(this).data('index') )
				.search( this.value )
				.draw();
		} );
		$( self.table.table().container() ).on( 'change', 'tfoot .check input', function () {
			if($(this).is(":checked")){
				self.table
					.column( $(this).data('index') )
					.search("1")
					.draw();
			}
			else {
				self.table
					.column( $(this).data('index') )
					.search("")
					.draw();
			}
		} );


	}).fail(function(){
		$('#ajax-contacts-load').after("<em>Le chargement du tableau a échoué</em>");
		$('#ajax-loader-img').hide();//On cache la roue de chargement.
	});
	
};

WDGCampaignDashboard.prototype.drawTimetable = function() {
	// Ajoute mise en page et interactions du tableau
	// Ajoute un champ de filtre à chaque colonne dans le footer
	$('#wdg-timetable tfoot td').each( function () {
		$(this).prepend( '<input type="text" placeholder="Filtrer par :" class="col-filter"/><br/>' );
	} );

	// Ajoute les actions de filtrage
	$("#wdg-timetable tfoot input").on( 'keyup change', function () {
		walletTimetable
			.column( $(this).parent().index()+':visible' )
			.search( this.value )
			.draw();
	} );

	//Récupère le tri par défaut 
	sortColumn = 0;

	this.walletTimetableDatatable = $('#wdg-timetable').DataTable({
		scrollX: true,

		order: [[ sortColumn, "asc" ]], //Colonne à trier (date)

		dom: 'RC<"clear">lfrtip',
		lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Tous"]], //nombre d'élements possibles
		iDisplayLength: 50,//nombre d'éléments par défaut

		//Boutons de sélection de colonnes
		colVis: {
			buttonText: "Afficher/cacher colonnes",
			restore: "Restaurer",
			showAll: "Tout afficher",
			showNone: "Tout cacher",
			overlayFade: 100
		},
		language: {
			"sProcessing":     "Traitement en cours...",
			"sSearch":         "Rechercher&nbsp;:",
			"sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
			"sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
			"sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
			"sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
			"sInfoPostFix":    "",
			"sLoadingRecords": "Chargement en cours...",
			"sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
			"sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
			"oPaginate": {
				"sFirst":      "Premier",
				"sPrevious":   "Pr&eacute;c&eacute;dent",
				"sNext":       "Suivant",
				"sLast":       "Dernier"
			},
			"oAria": {
				"sSortAscending":  ": activer pour trier la colonne par ordre croissant",
				"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
			}
		}
	});
};

WDGCampaignDashboard.prototype.scrollTo = function( target ) {
	$( 'html, body, .wdg-lightbox-padder' ).animate(
		{ scrollTop: target.offset().top - 75 },
		'slow'
	);
};

WDGCampaignDashboard.prototype.fieldError = function( $param, errorText ) {
	$param.addClass("error");
	$param.removeClass("validation");
	$param.qtip({
		content: errorText,
		position: {
			my: 'bottom center',
			at: 'top center',
		},
		style: {
			classes: 'wdgQtip qtip-red qtip-rounded qtip-shadow'
		},
		show: 'focus',
		hide: 'blur'
	});
	$param.closest(".field-value").parent().find('i.fa.validation').remove();
};

WDGCampaignDashboard.prototype.removeFieldError = function( $param ){
	if ( $param.hasClass( "error" ) ) {
		$param.removeClass( "error" );
		$param.qtip().destroy();
	}
};

WDGCampaignDashboard.prototype.initTeam = function( $param ){
	var self = this;
	$(".project-manage-team").click(function(){
		var action, data
		action = $(this).attr('data-action');
		if(action==="yproject-add-member"){
			data=($("#new_team_member_string")[0].value);
		}
		else if (action==="yproject-remove-member"){
			data=$(this).attr('data-user');
		}
		self.manageTeam(action, data, campaign_id);
	});
};

WDGCampaignDashboard.prototype.initRoyalties = function(){
	var self = this;
	self.currentOpenedROI = 0;
	if ($(".transfert-roi-open").length > 0) {
		$(".transfert-roi-open").click(function () {
			if ($(this).data('roideclaration-id') !== self.currentOpenedROI) {
				//Affichage
				self.currentOpenedROI = $(this).data('roideclaration-id');
				$("#wdg-lightbox-transfer-roi #lightbox-content .loading-content").html("");
				$("#wdg-lightbox-transfer-roi #lightbox-content .loading-image").show();
				$("#wdg-lightbox-transfer-roi #lightbox-content .loading-form").hide();

				//Lancement de la requête pour récupérer les utilisateurs et les sommes associées
				$.ajax({
					'type': "POST",
					'url': ajax_object.ajax_url,
					'data': {
						'action': 'display_roi_user_list',
						'roideclaration_id': $(this).data('roideclaration-id')
					}
				}).done(function (result) {
					var content = '<table>';
					content += '<tr><td>Utilisateur</td><td>Investissement</td><td>Versement</td><td>Commission</td></tr>';
					content += result;
					content += '</table>';
					$("#wdg-lightbox-transfer-roi #lightbox-content .loading-content").html(content);
					$("#wdg-lightbox-transfer-roi #lightbox-content .loading-image").hide();
					$("#wdg-lightbox-transfer-roi #lightbox-content .loading-form input#hidden-roi-id").val(self.currentOpenedROI);
					$("#wdg-lightbox-transfer-roi #lightbox-content .loading-form").show();
				});
			}
		});
		
		$( '#proceed_roi_transfers_form' ).submit( function( e ) {
			e.preventDefault();
			self.proceedRoyalties();
		} );
	}

	if ($("#turnover-declaration").length > 0) {
		if ($("#turnover-total").length > 0) {
			$("#turnover-total").change(function() {
				self.refreshTurnoverAmountToPay();
			});
		}
		var i = 0;
		while ($("#turnover-" + i).length > 0) {
			$("#turnover-" + i).change(function() {
				self.refreshTurnoverAmountToPay();
			});
			i++;
		}
	}
	
	if ( $( '#new_declaration_adjustment_turnover_difference' ).length > 0 ) {
		$( '#new_declaration_adjustment_turnover_difference' ).change( function() {
			self.refreshAjustmentAmountToPay();
		} );
	}
};

WDGCampaignDashboard.prototype.refreshTurnoverAmountToPay = function() {
	var roiPercent = $( '#turnover-declaration' ).data( 'roi-percent' );
	var minCostsOrga = $( '#turnover-declaration' ).data( 'minimum-costs' );
	var costsOrga = $( '#turnover-declaration' ).data( 'costs-orga' );
	var total = 0;
	if ( $( '#turnover-total' ).length > 0 ) {
		total = Number( $( '#turnover-total' ).val().split(',').join('.') );
	} else {
		var i = 0;
		while ( $( '#turnover-' + i ).length > 0 ) {
			total += Number( $( '#turnover-' + i ).val().split(',').join('.') );
			i++;
		}
	}
	var amount = total * roiPercent / 100;
	var fees = Math.max( minCostsOrga, amount * costsOrga / 100 );
	var amount_with_fees = amount + fees;
	amount_with_fees += $( '#turnover-declaration' ).data( 'adjustment' );
	amount_with_fees = Math.round(amount_with_fees * 100) / 100;

	$( '.amount-to-pay' ).text(amount_with_fees);
	$( '.commission-to-pay' ).text(fees);
};

WDGCampaignDashboard.prototype.refreshAjustmentAmountToPay = function() {
	var roiPercent = $( '#form-declaration-adjustment' ).data( 'roi-percent' );
	var costsOrga = $( '#form-declaration-adjustment' ).data( 'costs-orga' );
	var total = Number( $( '#new_declaration_adjustment_turnover_difference' ).val() );
	var amount = total * roiPercent / 100;
	var amount_with_fees = amount + (amount * costsOrga / 100);
	amount_with_fees = Math.round(amount_with_fees * 100) / 100;
	
	$( '#new_declaration_adjustment_value' ).val( amount_with_fees );
};

WDGCampaignDashboard.prototype.proceedRoyalties = function(){
	var self = this;
	var data_to_update = {
		'action': 'proceed_roi_transfers',
		'campaign_id': $( '#hidden-campaign-id' ).val(),
		'roi_id': $( '#hidden-roi-id' ).val(),
		'send_notifications': $( '#check_send_notifications' ).is( ':checked' ),
		'transfer_remaining_amount': $( '#check_transfer_remaining_amount' ).is( ':checked' )
	};

	var save_button = $( '#proceed_roi_transfers_button' );
	save_button.find( '.button-text' ).hide();
	save_button.find( '.button-waiting' ).show();

	//Envoi de requête Ajax
	$.ajax( {
		'type': "POST",
		'url': ajax_object.ajax_url,
		'data': data_to_update

	} ).done( function ( result ) {
		if ( result == 100 ) {
			$( '#proceed_roi_transfers_percent' ).html( 'Versement effectu&eacute; !' );
			$( '#proceed_roi_transfers_button' ).hide();

		} else {
			var roundResult = parseFloat( result );
			roundResult = roundResult.toFixed(2);
			$( '#proceed_roi_transfers_percent' ).html( roundResult + ' %' );
			self.proceedRoyalties();
		}

	} ).fail( function() {
		$( '#proceed_roi_transfers_percent' ).html( '<span class="error">Erreur serveur (ROI1611)</p>' );
	} );
	
};

WDGCampaignDashboard.prototype.manageTeam = function(action, data, campaign_id){
	var self = this;
	//Clic pour ajouter un membre
	if(action==="yproject-add-member"){
		//Test si le champ de texte est vide
		if (data===""){
			//Champ vide, ne rien faire
		} else {
			//Bloque le champ de texte d'ajout
			$("#new_team_member_string").prop('disabled',true);
			$("#new_team_member_string").val('');
			tmpPlaceHolder = $("#new_team_member_string").prop('placeholder');
			$("#new_team_member_string").prop('placeholder',"Ajout de "+data+"...");
			$("#new_team_member_string").next().hide();

			//Lance la requête Ajax
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': {
					'action':'add_team_member',
					'id_campaign':campaign_id,
					'new_team_member' : data
				}
			}).done(function(result){
				if ( result == 'FALSE' ) {
					alert( "Cet utilisateur n'a pas de compte sur la plateforme." );
				}
				//Nettoie le champ de texte d'ajout
				$("#new_team_member_string").prop('disabled', false);
				$("#new_team_member_string").prop('placeholder',tmpPlaceHolder);
				$("#new_team_member_string").next().show();

				if(result==="FALSE"){
					$("#new_team_member_string").next().next().after("<div id=\"fail_add_team_indicator\"><br/><em>L'utilisateur "+data+" n'a pas été trouvé</em><div>");
					$("#fail_add_team_indicator").delay(4000).fadeOut(400);
				} else {
					res = JSON.parse(result);

					//Teste si l'user existait déjà
					doublon = false;
					$(".project-manage-team").each(function(){
						doublon = doublon || (res.id == $(this).attr('data-user'));
					});

					if(!doublon){
						if($("#team-list li").length==0){
							$("#team-list").html("");
						}
						newline ='<li style="display: none;">';
						newline+=res.firstName+" "+res.lastName+" ("+res.userLink+") ";
						newline+='<a class="project-manage-team button red" data-action="yproject-remove-member" data-user="'+res.id+'"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>';
						newline+="</li>";
						$("#team-list").append(newline);
						$("a[data-user="+res.id+"]").closest("li").slideDown();
						$( '#team-list-empty' ).hide();

						//Recharge l'UI pour ajouter listener au nouveau button
						$(".project-manage-team").click(function(){
							action = $(this).attr('data-action');
							if(action==="yproject-add-member"){
								data=($("#new_team_member_string")[0].value);
							}
							else if (action==="yproject-remove-member"){
								data=$(this).attr('data-user');
							}
							self.manageTeam(action, data, campaign_id);
						});
					}
				}
			});
		}
	}

	//Clic pour supprimer un membre
	else if(action==="yproject-remove-member") {
		//Affichage en attente de suppression
		$("a[data-user="+data+"]").closest("li").css("opacity",0.5);
		$("a[data-user="+data+"]").html('<i class="fa fa-spinner fa-spin fa-fw"></i>');

		$.ajax({
			'type' : "POST",
			'url' : ajax_object.ajax_url,
			'data': {
				'action':'remove_team_member',
				'id_campaign':campaign_id,
				'user_to_remove' : data
			}
		}).done(function(result){
			$("a[data-user="+data+"]").closest("li").slideUp("slow",function(){ $(this).remove();});
		});
	}
};

WDGCampaignDashboard.prototype.initFinance = function(){
	//Etiquettes de numéros d'années pour le CA prévisionnel
	$("#new_first_payment").change(function(){
		var start_year = 1;
		$("#estimated-turnover tr .year").each(function(index){
			$(this).html((parseInt(start_year)+index));
		});
	});

	//Cases pour le CA prévisionnel
	$("#select-new_funding_duration").change(function() {
		var nb_years_li_existing = ($("#estimated-turnover tr").length);
		var new_nb_years = parseInt($("#select-new_funding_duration").val());
		if ( new_nb_years == 0 ) {
			new_nb_years = 5;
		}
		"change nb year trigger "+new_nb_years+"(exist : "+nb_years_li_existing+")";

		//Ajoute des boîtes au besoin
		if(new_nb_years > nb_years_li_existing){
			var newlines = $("#estimated-turnover").html();
			if(new_nb_years <= 20){
				for(var i=0; i<new_nb_years-nb_years_li_existing;i++){
					newlines = newlines+
						'<tr>' +
						'<td>Année&nbsp;<span class="year">'+(i+1+nb_years_li_existing)+'</span></td>'+
						'<td class="field field-value" data-type="number" data-id="new_estimated_turnover_'+(i+nb_years_li_existing)+'">'+
						'<i class="right fa" aria-hidden="true"></i>'+
						'<input type="number" value="0" id="new_estimated_turnover_'+(i+nb_years_li_existing)+'" class="right-icon" />&nbsp;'+$('#estimated-turnover').data('symbol')+                                   
						'</td>'+
						'<td id="roi-amount-'+(i+nb_years_li_existing)+'">0 '+$('#estimated-turnover').data('symbol')+
						'</td>'+
						'</tr>';
				}
			}

			$("#estimated-turnover").html(newlines);

			//MAJ des étiquettes "Année XXXX"
			$("#new_first_payment").trigger("change");
			nb_years_li_existing = new_nb_years;
		} else {
			//N'affiche que les boites nécessaires
			$("#estimated-turnover tr").hide();
			$("#estimated-turnover tr").slice(0,new_nb_years).show();
		}
		nb_years_li_existing = Math.max(new_nb_years,nb_years_li_existing);
		//Calculs de tous les élements et rattachement du keyup/click sur changement de CA
		wdgCampaignSimulator.calculAndShowResult();                       
	});
	$("#select-new_funding_duration").trigger('change');
	$("#select-new_funding_duration").keyup(function(){
		if($("#select-new_funding_duration").val()!==""){
			$("#select-new_funding_duration").trigger('change');
		}
	});

	//Recalcul du rendement si modification de l'objectif max / % royalties / durée financement
	$("#new_maximum_goal, #new_roi_percent_estimated, #select-new_funding_duration").bind('keyup click', function(){
		//Rattachement des events sur modif du CA
		wdgCampaignSimulator.attachEventOnCa();

		if($("#new_maximum_goal").val()!=="" && ($("#new_minimum_goal").val()!=="" && $("#select-new_funding_duration").val()!==""
			&& $("#new_roi_percent_estimated").val()!=="" )){
			wdgCampaignSimulator.calculAndShowResult();
		} else{
			wdgCampaignSimulator.initResultCalcul();
		}
	});
};

WDGCampaignDashboard.prototype.initCampaign = function(){
	$( "#item-body-campaign ul input[type=checkbox]" ).prop( 'disabled', false );
};

var wdgCampaignDashboard;
jQuery(document).ready( function($) {
	// Initialisation uniquement si construit
	if ( $( 'ul.nav-menu' ).length > 0 ) {
		wdgCampaignDashboard = new WDGCampaignDashboard();
	}
} );