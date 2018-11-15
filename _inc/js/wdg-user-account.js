function UserAccountDashboard() {
	this.initWithHash();
	this.initMenu();
}

/**
 * Initialise l'affichage avec le # de l'url
 */
UserAccountDashboard.prototype.initWithHash = function() {

	var sCurrentTab = window.location.hash.substring(1);
	if ( sCurrentTab !== '' ) {
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
	
};

/**
 * Change d'onglet
 */
UserAccountDashboard.prototype.switchTab = function( sType, clickedElement ) {
	
	$( 'ul.nav-menu li' ).removeClass( 'selected' );
	$( 'div#item-body > div.item-body-tab' ).hide();
	
	$( 'ul.nav-menu li#menu-item-' + sType ).addClass( 'selected' );
	$( 'div#item-body > div#item-body-' + sType ).show();
	
	if ( sType == 'investments' ) {
		this.initProjectList();
	}
	
};

/**
 * Récupère tous les projets ou un utilisateur est impliqué
 */
UserAccountDashboard.prototype.initProjectList = function() {
	
	var self = this;
	var userID = $('main').data('userid');
	
	$.ajax({
		'type' : "POST",
		'url' : ajax_object.ajax_url,
		'data': {
			'user_id': userID,
			'action' : 'display_user_investments'
		}
		
	// Une fois les projets obtenus
	}).done(function( result ){
		
		// Affichage par campagne
		var sBuffer = '';
		var aInvestmentCampaigns = JSON.parse( result );
		for ( var nCampaignID in aInvestmentCampaigns ) {
			var oCampaignItem = aInvestmentCampaigns[ nCampaignID ];
			if ( oCampaignItem[ 'name' ] !== undefined && oCampaignItem[ 'name' ] !== null ) {
				var sCampaignBuffer = '<h3 class="has-margin-top">Mes investissements sur ' + oCampaignItem[ 'name' ] + '</h3>';
				var aCampaignInvestments = oCampaignItem[ 'items' ];
				for ( var nIndex in aCampaignInvestments ) {
					var oInvestmentItem = aCampaignInvestments[ nIndex ];
					sCampaignBuffer += '<div class="investment-item">';
					
					sCampaignBuffer += '<div class="amount-date">';
					sCampaignBuffer += '<strong>' + oInvestmentItem[ 'amount' ] + ' €</strong><br>';
					sCampaignBuffer += oInvestmentItem[ 'date' ];
					sCampaignBuffer += '</div>';
					
					var sStatusStr = 'Valid&eacute;';
					if ( oInvestmentItem[ 'status' ] === 'pending' ) {
						sStatusStr = 'En attente';
					}
					if ( oCampaignItem[ 'status' ] === 'archive' ) {
						sStatusStr = 'Rembours&eacute;';
					}
					sCampaignBuffer += '<div class="single-line ' +oInvestmentItem[ 'status' ]+ ' campaign-' +oCampaignItem[ 'status' ]+ '">';
					sCampaignBuffer += sStatusStr;
					sCampaignBuffer += '</div>';
					
					sCampaignBuffer += '<div class="align-center">';
					sCampaignBuffer += 'Investissement sur ' + oCampaignItem[ 'funding_duration' ] + ' ans<br>';
					sCampaignBuffer += 'à compter du ' + oCampaignItem[ 'start_date' ];
					sCampaignBuffer += '</div>';
					
					sCampaignBuffer += '<div class="align-center">';
					sCampaignBuffer += 'Royalties reçues :<br><strong>' + oInvestmentItem[ 'roi_amount' ] + ' €</strong>';
					sCampaignBuffer += '</div>';
					
					sCampaignBuffer += '<div class="align-center">';
					sCampaignBuffer += 'Retour sur investissement :<br><strong>' + oInvestmentItem[ 'roi_return' ] + ' %</strong>';
					sCampaignBuffer += '</div>';
					
					sCampaignBuffer += '<div class="clear"></div>';
					
					sCampaignBuffer += '</div>';
				}
				
				// Pour les mettre dans l'ordre inverse
				sBuffer = sCampaignBuffer + sBuffer;
			}
		}
		$( '#ajax-loader' ).after( sBuffer );
		$( '#item-body-projects' ).height( 'auto' );
		
		// Masquage de ce qui n'est plus utile
		$( '#ajax-loader-img' ).hide();
		$( '.to-hide-after-loading' ).hide();
		
	});
};

/**
 * Affiche ou masque les détails de paiement
 */
UserAccountDashboard.prototype.togglePayments = function(){
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
						$(this).find('.user-history-payments-list').toggle(400);
					}
					else{
						$(this).find('.user-history-payments-list').hide(400);
					}
				});
			});
		});
	});
};

$(function(){
    new UserAccountDashboard();
    
});