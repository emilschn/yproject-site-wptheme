jQuery(document).ready( function($) {
	WDGInvestPageFunctions.initUI();
});

var WDGInvestPageFunctions = (function($) {
	return {
		forceInvestSubmit: false,
		initUI:function() {
			// Changement de montant
			$( 'form input#amount' ).on( 'keyup change', function () {
				WDGInvestPageFunctions.checkInvestInput();
			} );
			// Changement de type d'investisseur
			$( 'form input#user-type-user, form input#user-type-orga' ).click( function() {
				if ( $( this ).val() == 'user' ) {
					$( 'form #fieldgroup-orga-info' ).hide();
					$( 'form #fieldgroup-orga-info-new' ).hide();
					$( 'form #fieldgroup-user-type-orga' ).hide();
					$( 'form #fieldgroup-to-display' ).slideDown( 200 );
					
				} else if ( $( this ).val() == 'orga' ) {
					$( 'form #fieldgroup-user-type-orga' ).slideDown( 200 );
					$( 'form #fieldgroup-to-display' ).hide();
				}
			} );
			// Changement choix d'organisation
			$( 'form select#select-orga-id' ).change( function() {
				if ( $(this).val() === '' ) {
					$( 'form #fieldgroup-orga-info' ).hide();
					$( 'form #fieldgroup-to-display' ).hide();
					$( 'form #fieldgroup-orga-info-new' ).hide();
				} else {
					$( 'form #fieldgroup-orga-info' ).slideDown( 200 );
					$( 'form #fieldgroup-to-display' ).slideDown( 200 );
					if ( $(this).val() === 'new-orga' ) {
						$( 'form #fieldgroup-orga-info-new' ).slideDown( 200 );
					} else {
						$( 'form #fieldgroup-orga-info-new' ).hide();
					}
					WDGInvestPageFunctions.updateOrgaFields( $(this).val() );
				}
			} );
		},
		
		checkInvestInput: function() {
			$( '.invest_error' ).hide();
			$( '.invest_success' ).hide();

			var bValidInput = true;
			$( 'form input#amount' ).val( ( $( 'form input#amount' ).val() ).replace( /,/g, "." ) );
                        
			if ( !$.isNumeric( $( 'form input#amount' ).val() ) ) {
			    $( '#invest_error_general' ).show();
			    bValidInput = false;
				
			} else {
			    if ( $( 'form input#amount' ).val() != Math.floor( $( 'form input#amount' ).val() ) ) {
					$( '#invest_error_integer' ).show();
					bValidInput = false;
			    }
			    if ( parseInt( $( 'form input#amount' ).val() ) < $( '#input_invest_min_value' ).val() ) {
					$( '#invest_error_min' ).show();
					bValidInput = false;
			    }
			    if ( parseInt( $( 'form input#amount' ).val() ) > $( '#input_invest_max_value' ).val() ) {
					$( '#invest_error_max' ).show();
					bValidInput = false;
			    }
			    var nAmountInterval = $( '#input_invest_max_value' ).val() - parseInt( $( 'form input#amount' ).val()); 		
				if ( nAmountInterval < $( '#input_invest_min_value' ).val() && nAmountInterval > 0 ) {
					$( '#invest_error_interval' ).show(); 		
					bValidInput = false; 		
			    }
			}
			
			var ratioOfPercentRoundStr = 0;
			if (bValidInput) {
				var inputVal = Number( $( 'form input#amount' ).val() );
				if ( isNaN( inputVal ) || inputVal < 0) inputVal = 0;
				var percentProject = Number( $( 'input#roi_percent_project' ).val() );
				var goalProject = Number( $( 'input#roi_goal_project' ).val() );
				var ratioOfGoal = inputVal / goalProject;
				var ratioOfPercent = ratioOfGoal * percentProject;
				var ratioOfPercentRound = Math.round( ratioOfPercent * 10000 ) / 10000;
				ratioOfPercentRoundStr = ratioOfPercentRound.toString().replace( '.', ',' );
				
				$( 'form button' ).slideDown( 200 );
				
			} else {
				$( 'form button' ).slideUp( 200 );
			}
			
			$( 'span#royalties-percent' ).text( ratioOfPercentRoundStr );

			return bValidInput;
		},
		
		updateOrgaFields: function( idOrga ) {
			$( 'form #fieldgroup-orga-info #org_name' ).val( $( 'form #org_init_name_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_email' ).val( $( 'form #org_init_email_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_legalform' ).val( $( 'form #org_init_legalform_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_idnumber' ).val( $( 'form #org_init_idnumber_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_rcs' ).val( $( 'form #org_init_rcs_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_capital' ).val( $( 'form #org_init_capital_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_address' ).val( $( 'form #org_init_address_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_postal_code' ).val( $( 'form #org_init_postal_code_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_city' ).val( $( 'form #org_init_city_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #select-org_nationality' ).val( $( 'form #org_init_nationality_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_nationality' ).change();
		}
		
	};
})(jQuery);