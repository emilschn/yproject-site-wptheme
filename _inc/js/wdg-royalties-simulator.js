function WDGRoyaltiesSimulator() {
	this.init();
}

/**
 * Initialise les différents champs
 */
WDGRoyaltiesSimulator.prototype.init = function(){
	var self = this;
	$( '#royalties-simulator button' ).click( function( e ) {
		e.preventDefault();
		self.processResults();
	} );
};

/**
 * Exécute les calculs et présente les résultats
 */
WDGRoyaltiesSimulator.prototype.processResults = function(){
	var buffer = true;
	$( '#royalties-simulator .form-error-general' ).hide();
	$( '#royalties-simulator #royalties_advice' ).hide();
	$( '#royalties-simulator #royalties_advice .royalties_advice_value' ).text( '' );
	
	var nTotalTurnover = 0;
	for ( var i = 1; i <= 5; i++ ) {
		var nTurnoverItem = this.filterInput( '#royalties-simulator #year-' + i );
		if ( nTurnoverItem === false ) {
			buffer = false;
		} else {
			nTotalTurnover += nTurnoverItem;
		}
	}
	
	var nGoal = this.filterInput( '#royalties-simulator #goal' );
	if ( nGoal === false ) {
		buffer = false;
	}
	
	if ( buffer ) {
		var nRoyalties = nGoal / nTotalTurnover * 100 * 2;
		var nRoundRoyalties = Math.round( nRoyalties * 10000 ) / 10000;
		$( '#royalties-simulator #royalties_advice .royalties_advice_value' ).text( nRoundRoyalties );
		$( '#royalties-simulator #royalties_advice' ).show();
		
		
	} else {
		$( '#royalties-simulator .form-error-general' ).show();
		
	}
};

/**
 * Filtre les valeurs saisies pour vérifier
 */
WDGRoyaltiesSimulator.prototype.filterInput = function( sInputId ){
	var buffer = true;
	
	$( sInputId ).attr( 'style', 'border: 0px;' );
		
	var sInput =  $( sInputId ).val();
	sInput = sInput.split( ' ' ).join( '' );
	sInput = sInput.split( ',' ).join( '.' );
	var nTurnoverItem = Number( sInput );
	if ( isNaN( nTurnoverItem ) || nTurnoverItem < 0 ) {
		$( sInputId ).attr( 'style', 'border: 1px solid red;' );
		buffer = false;
	} else {
		buffer = nTurnoverItem;
	}
	
	return buffer;
};

var wdgRoyaltiesSimulator;
jQuery(document).ready( function($) {
    wdgRoyaltiesSimulator = new WDGRoyaltiesSimulator();
} );