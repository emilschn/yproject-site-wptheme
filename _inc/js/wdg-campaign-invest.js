jQuery(document).ready( function($) {
	WDGInvestPageFunctions.initUI();
});

var WDGInvestPageFunctions = (function($) {
	return {
		forceInvestSubmit: false,
		initUI:function() {
			// Changement de montant
			if ( $( 'form input#amount' ).length > 0 ) {
				WDGInvestPageFunctions.checkInvestInput();
				$( 'form input#amount' ).on( 'keyup change', function () {
					WDGInvestPageFunctions.checkInvestInput();
				} );
			}

			// Si erreur le formulaire se met à jour en prenant en compte les précédentes modifications
			var userType = $( 'form input#user-type-select' ).val();
			WDGInvestPageFunctions.userTypeSelect( userType );

			var idOrga = $( 'form select#select-orga-id' ).val();
			if ( idOrga ) {
				WDGInvestPageFunctions.idOrgaSelect( idOrga );
			}

			// Changement de type d'investisseur
			if ( $( 'form input#user-type-user' ).length > 0 ) {
				$( 'form input#user-type-user, form input#user-type-orga' ).click( function() {
					WDGInvestPageFunctions.userTypeSelect( $( this ).val() );
				} );
				// Changement choix d'organisation
				$( 'form select#select-orga-id' ).change( function() {
					WDGInvestPageFunctions.idOrgaSelect( $( this ).val() );
				} );
			}
			
			if ( $( '.mean-payment-button' ).length > 0 ) {
				$( '.mean-payment-button' ).click( function( e ) {
					e.preventDefault();
					$( '.mean-payment' ).hide();
					$( this ).show();
					$( '#change-mean-payment' ).show();
					var sMeanPayment = 'mean-payment-';
					$( '#meanofpayment' ).val( $( this ).attr( 'id' ).substr( sMeanPayment.length ) );
					if ( $( this ).attr( 'id' ).indexOf( 'card' ) > -1 && $( '#two-contracts-preview' ).length > 0 ) {
						if ( $( this ).attr( 'id' ).indexOf( 'wallet' ) > -1 ) {
							if ( $( '#two-contracts-preview-with-wallet' ).length > 0 ) {
								$( '#two-contracts-preview-with-wallet' ).show();
								$( '#two-contracts-preview-with-wallet .contract-preview-with-tabs .contract-preview-tabs div:first' ).addClass( 'selected' );
								$( '#two-contracts-preview-with-wallet .contract-preview-with-tabs .contract-preview-content > div:first' ).show();
							} else {
								$( '#contract-preview' ).show();
							}
						} else {
							$( '#two-contracts-preview' ).show();
							$( '#two-contracts-preview .contract-preview-with-tabs .contract-preview-tabs div:first' ).addClass( 'selected' );
							$( '#two-contracts-preview .contract-preview-with-tabs .contract-preview-content > div:first' ).show();
						}
					} else {
						$( '#contract-preview' ).show();
					}
					$( '#contract-buttons' ).show();
				} );
				
				$( '#change-mean-payment' ).click( function( e ) {
					e.preventDefault();
					$( '.mean-payment' ).show();
					$( this ).hide();
					$( '#contract-preview' ).hide();
					$( '.two-contracts-preview' ).hide();
					$( '#contract-buttons' ).hide();
					$( 'div.two-contracts-preview .contract-preview-with-tabs .contract-preview-tabs div' ).removeClass( 'selected' );
					$( 'div.two-contracts-preview .contract-preview-with-tabs .contract-preview-content > div' ).hide();
				} );
				
				$( 'div.two-contracts-preview .contract-preview-with-tabs .contract-preview-tabs div' ).click( function() {
					if ( !$( this ).hasClass( 'selected' ) ) {
						$( 'div.two-contracts-preview .contract-preview-with-tabs .contract-preview-content > div' ).each( function() {
							if ( $( this ).is( ':visible' ) ) {
								$( this ).hide();
							} else {
								$( this ).show();
							}
						} );
						$( 'div.two-contracts-preview .contract-preview-with-tabs .contract-preview-tabs div' ).each( function() {
							if ( $( this ).hasClass( 'selected' ) ) {
								$( this ).removeClass( 'selected' );
							} else {
								$( this ).addClass( 'selected' );
							}
						} );
					}
				} );
			}
			
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
			
			if ( $( 'body.template-declarer-chiffre-daffaires .mean-payment' ).length > 0 ) {
				$( 'body.template-declarer-chiffre-daffaires .mean-payment' ).click( function() {
					$( 'body.template-declarer-chiffre-daffaires .mean-payment' ).removeClass( 'selected' );
					$( this ).addClass( 'selected' );
					$( 'body.template-declarer-chiffre-daffaires form button.half.right.red').removeClass( 'hidden' );
					$( 'input#input-meanofpayment' ).attr( 'value', $( this ).data( 'meanofpayment' ) );
				} );
			}
		},

		userTypeSelect: function( userType ) {
			if ( userType == 'user' ) {
				$( 'form #fieldgroup-orga-info' ).hide();
				$( 'form #fieldgroup-orga-info-new' ).hide();
				$( 'form #fieldgroup-user-type-orga' ).hide();
				$( 'form #fieldgroup-user-info' ).slideDown( 200 );
				$( 'form #fieldgroup-to-display' ).slideDown( 200 );
				$( '#user-type-user' ).prop("checked", true);
			} else if ( userType == 'orga' ) {
				$( 'form #fieldgroup-user-type-orga' ).slideDown( 200 );
				$( 'form #fieldgroup-user-info' ).hide();
				$( 'form #fieldgroup-to-display' ).hide();
				$( '#user-type-orga' ).prop("checked", true);
			}

		},

		idOrgaSelect: function( idOrga ) {
			if ( idOrga == '' ) {
				$( 'form #fieldgroup-user-info' ).hide();
				$( 'form #fieldgroup-orga-info' ).hide();
				$( 'form #fieldgroup-to-display' ).hide();
				$( 'form #fieldgroup-orga-info-new' ).hide();
			} else {
				$( 'form #fieldgroup-user-info' ).slideDown( 200 );
				$( 'form #fieldgroup-orga-info' ).slideDown( 200 );
				$( 'form #fieldgroup-to-display' ).slideDown( 200 );
				if ( idOrga == 'new-orga' ) {
					$( 'form #fieldgroup-orga-info-new' ).slideDown( 200 );
				} else {
					$( 'form #fieldgroup-orga-info-new' ).hide();
				}
				WDGInvestPageFunctions.updateOrgaFields( idOrga );
			}
		},
		
		checkInvestInput: function() {
			$( '.invest_error' ).hide();
			$( '.invest_success' ).hide();

			var bValidInput = true;
			$( 'form input#amount' ).val( ( $( 'form input#amount' ).val() ).replace( /,/g, "." ) );
            
			if ( $( 'form input#amount' ).val() == '' ) {
			    bValidInput = false;
				
			} else if ( !$.isNumeric( $( 'form input#amount' ).val() ) ) {
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
				if ( parseInt( $( 'form input#amount' ).val() ) > $( '#input_invest_user_max_value' ).val() ) {
					$( '#invest_error_max' ).text( $( '#input_invest_user_max_reason' ).val() );
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
				var ratioOfPercentRound = Math.round( ratioOfPercent * 100000 ) / 100000;
				ratioOfPercentRoundStr = ratioOfPercentRound.toString().replace('.', ',');
				
				var currentAmount = Number( $( 'span#amount-reached' ).data( 'current-amount' ) );
				var amountReached = inputVal + currentAmount;
				$( 'span#amount-reached' ).text( amountReached.toLocaleString( 'fr-FR' ) );
				
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
			$( 'form #fieldgroup-orga-info #org_website' ).val( $( 'form #org_init_website_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_legalform' ).val( $( 'form #org_init_legalform_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_idnumber' ).val( $( 'form #org_init_idnumber_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_rcs' ).val( $( 'form #org_init_rcs_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_capital' ).val( $( 'form #org_init_capital_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_address_number' ).val( $( 'form #org_init_address_number_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #select-org_address_number_comp' ).val( $( 'form #org_init_address_number_comp_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_address' ).val( $( 'form #org_init_address_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_postal_code' ).val( $( 'form #org_init_postal_code_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_city' ).val( $( 'form #org_init_city_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #select-org_nationality' ).val( $( 'form #org_init_nationality_' + idOrga ).val() );
			$( 'form #fieldgroup-orga-info #org_nationality' ).change();
		}
		
	};
})(jQuery);