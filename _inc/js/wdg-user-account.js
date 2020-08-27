function UserAccountDashboard() {
	this.initWithHash();
	this.initMenu();
	this.initPhoneNotification();
}

/**
 * Initialise l'affichage avec le # de l'url
 */
UserAccountDashboard.prototype.initWithHash = function() {

	var sCurrentTab = window.location.hash.substring(1);
	if ( sCurrentTab !== '' && sCurrentTab !== '_=_' ) {
		this.switchTab( sCurrentTab, false );
	}
	
};

/**
 * Initialise le menu
 */
UserAccountDashboard.prototype.initMenu = function() {
	
	var self = this;
	$( 'ul.nav-menu li a' ).each( function() {
		$( this ).click( function() {
			self.switchTab( $( this ).data( 'tab' ), this );
		} );
	} );
	$( 'a.go-to-tab' ).each( function() {
		$( this ).click( function() {
			self.switchTab( $( this ).data( 'tab' ), this );
		} );
	} );
	if ( $( '#modify-iban' ).length > 0 ) {
		$( '#modify-iban' ).click( function() {
			$( '#form-modify-iban' ).toggle( 100 );
		} );
	}
	$( 'nav button#swap-menu' ).click( function() {
		if ( $( 'nav' ).hasClass( 'visible' ) ) {
			$( 'nav' ).removeClass( 'visible' );
			$( 'nav button#swap-menu' ).html( '&gt;' );
		} else {
			$( 'nav' ).addClass( 'visible' );
			$( 'nav button#swap-menu' ).html( '&lt;' );
		}
	} );
	if ( $( 'div.user-transactions-init button' ).length > 0 ) {
		$( 'div.user-transactions-init button' ).click( function() {
			$( this ).prop( 'disabled', true );
			$( this ).siblings( 'div.loading' ).show();
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': {
					'user_id': $( this ).data( 'userid' ),
					'action' : 'get_transactions_table'
				}

			}).done( function( result ){
				$( 'div.user-transactions-init button' ).hide();
				$( 'div.user-transactions-init div.loading' ).hide();
				$( 'div.user-transactions-init' ).append( result );
				$( 'table.user-transactions' ).DataTable( {
					order: [[ 0, 'desc' ]],
					dom: 'Bfrtip',
					buttons: [
						{
							extend: 'excelHtml5',
							text: 'Télécharger mon historique',
						}
					],
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
				} );
			} );
		} );
	}
};

/**
 * Change d'onglet
 */
UserAccountDashboard.prototype.switchTab = function( sType ) {
	
	if ( $( 'ul.nav-menu li#menu-item-' + sType ).length > 0 ) {
		$( 'ul.nav-menu li' ).removeClass( 'selected' );
		$( 'div#item-body > div.item-body-tab' ).hide();

		$( 'ul.nav-menu li#menu-item-' + sType ).addClass( 'selected' );
		$( 'div#item-body > div#item-body-' + sType ).show();

		if ( sType.indexOf( 'investments' ) > -1 ) {
			this.initProjectList();
		}

		if ( sType.indexOf( 'documents' ) > -1 ) {
			this.initTaxExemption();
		}
	}
};

/**
 * Récupère tous les projets ou un utilisateur est impliqué
 */
UserAccountDashboard.prototype.initProjectList = function() {
	var self = this;
	var userID = $('ul.nav-menu li.selected a').data('userid');
	var userType = $('ul.nav-menu li.selected a').data('usertype');
	
	// Si le picto de chargement n'est pas affiché, c'est qu'on a déjà fait le processus pour cet onglet
	if ( !$( '#ajax-loader-img-' + userID ).is( ':visible' ) ) {
		return;
	}
	
	$.ajax({
		'type' : "POST",
		'url' : ajax_object.ajax_url,
		'data': {
			'user_id': userID,
			'user_type': userType,
			'action' : 'display_user_investments'
		}
		
	// Une fois les projets obtenus
	}).done(function( result ){
		self.displayUserInvestments( result, userID, userType );

	}).fail(function() {
		var sBuffer = '<button id="reload-investments-' + userID + '" class="button blue">Recharger</button>';
		$( '#ajax-loader-' + userID ).after( sBuffer );
		$( '#ajax-loader-img-' + userID ).hide();
		$( '#reload-investments-' + userID ).click( function() { self.reloadUserInvestments(); } );
	});
};

/**
 * Tente de recharger la liste des investissements avec le nouveau système
 */
UserAccountDashboard.prototype.reloadUserInvestments = function() {
	var self = this;
	var userID = $('ul.nav-menu li.selected a').data('userid');
	var userType = $('ul.nav-menu li.selected a').data('usertype');
	
	// Si le picto de chargement n'est pas affiché, c'est qu'on a déjà fait le processus pour cet onglet
	if ( !$( '#ajax-loader-img-' + userID ).is( ':visible' ) ) {
		return;
	}
	$( '#ajax-loader-img-' + userID ).show();
	
	$.ajax({
		'type' : "POST",
		'url' : ajax_object.ajax_url,
		'data': {
			'user_id': userID,
			'user_type': userType,
			'action' : 'display_user_investments_optimized'
		}
		
	// Une fois les projets obtenus
	}).done(function( result ){
		self.displayUserInvestments( result, userID, userType );

	}).fail(function() {
		sBuffer = '<div class="align-center">Problème de chargement</div>';
	});
};

UserAccountDashboard.prototype.displayUserInvestments = function( result, userID, userType ) {
	var self = this;

	// Affichage par campagne
	var nInvestmentPublishCount = 0;
	var nInvestmentPendingCount = 0;
	var nProject = 0;
	var nAmountInvested = 0;
	var nAmountReceived = 0;
	var sBuffer = '';
	var aInvestmentCampaigns = new Array();
	if ( result !== '' ) {
		aInvestmentCampaigns = JSON.parse( result );
		
		for ( var nCampaignID in aInvestmentCampaigns ) {
			var oCampaignItem = aInvestmentCampaigns[ nCampaignID ];
			if ( oCampaignItem[ 'name' ] !== undefined && oCampaignItem[ 'name' ] !== null ) {
				var bCountProject = false;
				var sCampaignBuffer = '<h3 class="has-margin-top">Mes investissements sur ' + oCampaignItem[ 'name' ] + '</h3>';
				var aCampaignInvestments = oCampaignItem[ 'items' ];
				for ( var nIndex in aCampaignInvestments ) {
					var oInvestmentItem = aCampaignInvestments[ nIndex ];
					sCampaignBuffer += '<div class="investment-item">';

					sCampaignBuffer += '<div class="amount-date">';
					sCampaignBuffer += '<strong>' + oInvestmentItem[ 'amount' ] + ' €</strong><br>';
					sCampaignBuffer += oInvestmentItem[ 'date' ];
					sCampaignBuffer += '</div>';

					var bCountInGlobalStat = true;
					var sStatusStr = oInvestmentItem[ 'status_str' ];
					if ( oInvestmentItem[ 'status' ] === 'pending' ) {
						bCountInGlobalStat = false;
						nInvestmentPendingCount++;
					} else if ( oCampaignItem[ 'status' ] === 'archive' ) {
						bCountInGlobalStat = false;
					}
					if ( bCountInGlobalStat ) {
						bCountProject = true;
						nInvestmentPublishCount++;
						nAmountInvested += Number( oInvestmentItem[ 'amount' ] );
						nAmountReceived += Number( oInvestmentItem[ 'roi_amount' ] );
					}
					sCampaignBuffer += '<div class="single-line ' +oInvestmentItem[ 'status' ]+ ' campaign-' +oCampaignItem[ 'status' ]+ '">';
					sCampaignBuffer += sStatusStr;
					sCampaignBuffer += '</div>';

					sCampaignBuffer += '<div class="align-center">';
					sCampaignBuffer += 'Investissement sur ' + oCampaignItem[ 'funding_duration' ] + ' ans<br>';
					if ( oCampaignItem[ 'start_date' ] !== '' ) {
						sCampaignBuffer += 'à compter du ' + oCampaignItem[ 'start_date' ];
					}
					sCampaignBuffer += '</div>';

					sCampaignBuffer += '<div class="align-center">';
					sCampaignBuffer += 'Royalties reçues :<br><strong>' + oInvestmentItem[ 'roi_amount' ] + ' €</strong>';
					sCampaignBuffer += '</div>';

					sCampaignBuffer += '<div class="align-center">';
					sCampaignBuffer += 'Retour sur investissement :<br><strong>' + oInvestmentItem[ 'roi_return' ] + ' %</strong>';
					sCampaignBuffer += '</div>';

					if ( oInvestmentItem[ 'contract_file_path' ] != '' ) {
						sCampaignBuffer += '<div class="align-center single-line">';
						sCampaignBuffer += '<a href="' +oInvestmentItem[ 'contract_file_path' ]+ '" download="' +oInvestmentItem[ 'contract_file_name' ]+ '" class="button blue-pale" title="T&eacute;l&eacute;charger le contrat">';
						sCampaignBuffer += 'Contrat';
						sCampaignBuffer += '</a>';
						sCampaignBuffer += '<div class="clear"></div>';
						sCampaignBuffer += '</div>';
					} else if ( oInvestmentItem[ 'conclude-investment-url' ] != '' ) {
						sCampaignBuffer += '<div class="align-center single-line">';
						sCampaignBuffer += '<a href="' +oInvestmentItem[ 'conclude-investment-url' ]+ '" class="button red" title="Finaliser investissement">';
						sCampaignBuffer += 'Finaliser l&apos;investissement';
						sCampaignBuffer += '</a>';
						sCampaignBuffer += '<div class="clear"></div>';
						sCampaignBuffer += '</div>';
					} else {
						sCampaignBuffer += '<div class="align-center">';
						sCampaignBuffer += 'Contrat<br>inaccessible';
						sCampaignBuffer += '<div class="clear"></div>';
						sCampaignBuffer += '</div>';
					}

					sCampaignBuffer += '</div>';
					
					var nYears = oInvestmentItem[ 'rois_by_year' ].length;
					if ( nYears > 0 ) {
						sCampaignBuffer += '<div class="align-center">';
						sCampaignBuffer += '<button class="button-view-royalties-list button transparent" id="button-royalties-list-'+nCampaignID+'-'+nIndex+'" data-list="'+nCampaignID+'-'+nIndex+'">+</button>';
						sCampaignBuffer += '</div>';
						
						sCampaignBuffer += '<div class="royalties-list align-center hidden" id="royalties-list-'+nCampaignID+'-'+nIndex+'">';
						
						sCampaignBuffer += '<hr>';
						
						sCampaignBuffer += '<div>Versements trimestriels</div>';
						
						sCampaignBuffer += '<table class="roi-table">';
						for ( var i = 0; i < nYears; i++ ) {
							var oYearItem = oInvestmentItem[ 'rois_by_year' ][ i ];
							sCampaignBuffer += '<tr class="year-title yearly-title">';
							sCampaignBuffer += '<td>Ann&eacute;e ' +(i+1)+ '</td>';
							sCampaignBuffer += '<td></td>';
							sCampaignBuffer += '</tr>';
							
							sCampaignBuffer += '<tr class="yearly-title">';
							sCampaignBuffer += '<td>Chiffre d&apos;affaires</td>';
							if ( oYearItem[ 'estimated_turnover' ] != '-' ) {
								sCampaignBuffer += '<td>' +oYearItem[ 'amount_turnover' ]+ ' / ' +oYearItem[ 'estimated_turnover' ]+ ' <span>(pr&eacute;visionnel)</span></td>';
							} else {
								sCampaignBuffer += '<td></td>';
							}
							sCampaignBuffer += '</tr>';
							
							sCampaignBuffer += '<tr class="yearly-title">';
							sCampaignBuffer += '<td>Royalties</td>';
							if ( oYearItem[ 'estimated_rois' ] != '-' ) {
								sCampaignBuffer += '<td>' +oYearItem[ 'amount_rois' ]+ ' / ' +oYearItem[ 'estimated_rois' ]+ ' <span>(pr&eacute;visionnel)</span></td>';
							} else {
								sCampaignBuffer += '<td></td>';
							}
							sCampaignBuffer += '</tr>';



							
							var nRois = oYearItem[ 'roi_items' ].length;
							for ( var j = 0; j < nRois; j++ ) {
								var oRoiItem = oYearItem[ 'roi_items' ][ j ];
								sCampaignBuffer += '<tr>';
								sCampaignBuffer += '<td class="align-right">' +oRoiItem[ 'date' ]+ '</td>';
								sCampaignBuffer += '<td class="status ' +oRoiItem[ 'status' ]+ '">';
								if ( oRoiItem[ 'status' ] == 'finished' ) {
									sCampaignBuffer += oRoiItem[ 'amount' ];
								} else {
									sCampaignBuffer += oRoiItem[ 'status_str' ];
								}
								sCampaignBuffer += '</td>';
								sCampaignBuffer += '</tr>';
							}
						}
						
						if ( oInvestmentItem[ 'status' ] === 'publish' && oInvestmentItem[ 'roi_return' ] < 100 ) {
							sCampaignBuffer += '<tr class="year-title">';
							sCampaignBuffer += '<td></td>';
							sCampaignBuffer += '<td class="status future">';
							sCampaignBuffer += 'Autres échéances éventuelles jusqu&apos;à remboursement';
							sCampaignBuffer += '<div class="tooltip">';
							sCampaignBuffer += '<button type="button">i</button>';
							sCampaignBuffer += '<div class="tooltip-text">L&apos;entreprise est engagée à vous verser des royalties jusqu&apos;à remboursement, sauf en cas d&apos;arrêt définitif de l&apos;activité.</div>';
							sCampaignBuffer += '</div>';
							sCampaignBuffer += '</td>';
							sCampaignBuffer += '</tr>';
						}

						sCampaignBuffer += '</table>';
						
						sCampaignBuffer += '<div class="align-center">';
						sCampaignBuffer += '<button class="button-hide-royalties-list button transparent" data-list="'+nCampaignID+'-'+nIndex+'">-</button>';
						sCampaignBuffer += '</div>';
						
						sCampaignBuffer += '</div>';
						
					}
				}
				if ( bCountProject ) {
					nProject++;
				}

				// Pour les mettre dans l'ordre inverse
				sBuffer = sCampaignBuffer + sBuffer;
			}
		}
		
	}
	
	if ( result === '' || aInvestmentCampaigns.length === 0 ) {
		sBuffer = '<div class="align-center">';
		sBuffer += 'Aucun investissement valid&eacute; pour l&apos;instant.<br>';
		sBuffer += 'Si vous avez investi sur un projet en cours d&apos;&eacute;valuation, cet investissement est encore en attente de validation.';
		sBuffer += '</div>';
	} else {
		$( '#investment-synthesis-' + userID + ' .publish-count' ).text( nInvestmentPublishCount );
		if ( nInvestmentPendingCount > 0 ) {
			$( '#investment-synthesis-' + userID + ' .pending-str' ).show();
			$( '#investment-synthesis-' + userID + ' .pending-count' ).text( nInvestmentPendingCount );
		}
		$( '#investment-synthesis-pictos-' + userID + ' .funded-projects .data' ).text( nProject );
		$( '#investment-synthesis-pictos-' + userID + ' .amount-invested .data' ).html( JSHelpers.formatNumber( nAmountInvested, '&euro;' ) );
		nAmountReceived = Math.round( nAmountReceived * 100 ) / 100;
		$( '#investment-synthesis-pictos-' + userID + ' .royalties-received .data' ).html( JSHelpers.formatNumber( nAmountReceived, '&euro;' ) );
		$( '#investment-synthesis-' + userID ).removeClass( 'hidden' );
		$( '#investment-synthesis-pictos-' + userID ).removeClass( 'hidden' );
		$( '#to-hide-after-loading-success-' + userID ).hide();
	}

	$( '#ajax-loader-' + userID ).after( sBuffer );

	$( '#vote-intentions-' + userID ).removeClass( 'hidden' );
	
	$( '#item-body-projects' ).height( 'auto' );
	
	// Masquage de ce qui n'est plus utile
	$( '#ajax-loader-img-' + userID ).hide();
	
	self.toggleRois();

	$( '.tooltip button' ).click( function() {
		$( this ).siblings().toggle();
	} );
};

/**
 * Affiche ou masque les détails de paiement
 */
UserAccountDashboard.prototype.toggleRois = function(){
	var self = this;
	
	$( '.button-view-royalties-list' ).click( function() {
		var sIdList = $( this ).data( 'list' );
		$( this ).slideUp( 100 );
		$( '#royalties-list-' + sIdList ).slideDown( 300 );
	} );
	
	$( '.button-hide-royalties-list' ).click( function() {
		var sIdList = $( this ).data( 'list' );
		$( '#button-royalties-list-' + sIdList ).slideDown( 100 );
		$( '#royalties-list-' + sIdList ).slideUp( 300 );
	} );
};

/**
 * Affiche le formulaire de dispense
 */
UserAccountDashboard.prototype.initTaxExemption = function(){
	$( '#display-tax-exemption-form' ).click( function() {
		if ( $( '#tax-exemption-form' ).is(':visible')) {
			$( '#tax-exemption-form' ).slideUp( 300 );
		} else {
			$( '#tax-exemption-form' ).slideDown( 300 );
		}		
		$( '#upload-tax-exemption-form' ).slideUp( 300 );
	} );
	
	$( '#tax-exemption-form button.half.left' ).click( function() {
		$( '#tax-exemption-form' ).slideUp( 300 );
	} );

	
	$( '#display-upload-tax-exemption-form' ).click( function() {		
		if ( $( '#upload-tax-exemption-form' ).is(':visible')) {
			$( '#upload-tax-exemption-form' ).slideUp( 300 );
		} else {
			$( '#upload-tax-exemption-form' ).slideDown( 300 );
		}
		$( '#tax-exemption-form' ).slideUp( 300 );
	} );
	
	$( '#upload-tax-exemption-form button.half.left' ).click( function() {
		$( '#upload-tax-exemption-form' ).slideUp( 300 );
	} );
};

/**
 * Gestion de l'affichage de l'affichage des notifications SMS
 */
UserAccountDashboard.prototype.initPhoneNotification = function(){
	if ( $( '#-phone-notification' ).is( ':checked' ) ) {
		$( '.phone-number-hidden' ).show();
	} else {
		$( '.phone-number-hidden' ).hide();
	}

	$( '#-phone-notification' ).parent().click( function() {
		if ( $( '#-phone-notification' ).is( ':checked' ) ) {
			$( '.phone-number-hidden' ).slideDown( 300 );
		} else {
			$( '.phone-number-hidden' ).slideUp( 300 );
		}
	} );
};

$(function(){
	jQuery(document).ready( function($) {
		new UserAccountDashboard();
	} );
});