function UserAccountDashboard() {
	this.initWithHash();
	this.initMenu();
	this.initProjectList();
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
			'action' : 'print_user_projects'
		}
		
	}).done(function( result ){
		// Une fois les projets obtenus
		$( '#ajax-loader' ).after(result);
		$( '#item-body-projects' ).height( 'auto' );
		$( '#ajax-loader-img' ).hide();
		
		// On cache tous les paiements effectués et on affiche Détails des paiements
		self.togglePayments();
		$( '.history-projects' ).each(function(){
			$(this).hide();
		});
		
		// On applique cette fonction une première fois afin d'afficher les projets investis
		self.filterProjects();
		$( '#filter-projects :checkbox' ).change(function() {// On met un listener sur les checkbox
			self.filterProjects();
		});
		
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

		$(this).find('.user-subscribe-news input').each(function(){
			$(this).click(function(){
				checkbox = $(this);

				$(this).prop('disabled',true);
				if(this.checked){
					value = 1;
				} else {
					value = 0;
				};
				campaign_id = $(this).closest(".history-projects").data("value");

				$.ajax({
					'type' : "POST",
					'url' : ajax_object.ajax_url,
					'context' : checkbox,
					'data': {
						'action':'update_subscription_mail',
						'subscribe' : value,
						'id_campaign' : campaign_id
					},
				}).done(function(){
					$(this).prop('disabled',false);
				});
			});
		});
	});
};
		
UserAccountDashboard.prototype.filterProjects = function(){
	
	var o = new Object();
	var tab = [ "jycrois", "invested", "voted" ];
	
	// On regarde quelles sont les checkbox cochées
	$( '#filter-projects input' ).each(function(){
		// Si elle est cochée, on met un "1"
		o[ this.value ] = ( this.checked ) ? 1 : 0;
		
		// On affiche les projets selon les checkbox cochées
		$( '.history-projects' ).each(function(){
			var show_project = false;
			for ( var i = 0; i <= tab.length; i++ ){
				// Exemple : L'utilisateur croit au projet  -> data-jycrois=1 (dans le HTML) et J'y crois est coché
				if( $(this).attr( 'data-' + tab[i] ) === '1' && o[ tab[ i ] ] === 1 ){
					show_project = true;
				}
			}
			
			if ( show_project ) {
				$(this).show();
				
			} else {
				$(this).hide();
			}
		});
	});

};

$(function(){
    new UserAccountDashboard();
    
});