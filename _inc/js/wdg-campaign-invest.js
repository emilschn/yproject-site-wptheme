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
			
			
		
			if ( $( '.investment-form' ).length > 0 ) {
				$( 'div :submit' ).click( function( e ) {
					$(this).find(".button-text").hide();
					$(this).find(".button-loading").show();
					if ($(this).hasClass("disabled")) {
						e.preventDefault();
					}
					$(this).addClass("disabled");
				} );
				
				$( '.investment-button' ).click( function( e ) {
					$(this).find(".button-text").hide();
					$(this).find(".button-loading").show();
					if ($(this).hasClass("disabled")) {
						e.preventDefault();
					}
					$(this).addClass("disabled");
				} );
			}	

			if ( $( '.grenade-festif-overlay' ).length > 0 ) {
				function toDataUrl(url, callback) {
					var xhr = new XMLHttpRequest();
					xhr.onload = function() {
					  var reader = new FileReader();
					  reader.onloadend = function() {
						callback(reader.result);
					  }
					  reader.readAsDataURL(xhr.response);
					};
					xhr.open('GET', url);
					xhr.responseType = 'blob';
					xhr.send();
				  }
				  
				  // Initially load the GIF once, get base64 data
				  toDataUrl('../../wp-content/themes/yproject/images/gif-festif-grenade.gif',
				  function(img_base64) {
					// Set to none, then base64-encoded URL to restart the gif
					var $div = $( '.grenade-festif-overlay' );
					$div.css({backgroundImage: "none"});
					$div.css({backgroundImage: "url("+img_base64.replace("image/gif","image/gif;rnd="+Math.random())+")"});
				  });
			}				

			if ( $( '.mean-payment-button' ).length > 0 ) {
				$( '.mean-payment-button' ).click( function( e ) {
					e.preventDefault();
					$( '.mean-payment' ).removeClass( 'selected' );
					$( this ).addClass( 'selected' );
					$( '#form-navigation button' ).show();
					var sMeanPaymentStr = 'mean-payment-';
					var sMeanOfPayment = $( this ).attr( 'id' ).substr( sMeanPaymentStr.length );

					// Si on change de moyen de paiement
					if ( sMeanOfPayment != $( '#input-meanofpayment' ).val() ) {
						$( '#input-meanofpayment' ).val( sMeanOfPayment );
						$( '#input-meanofpayment-card-type' ).val( '' );
						$( '#input-meanofpayment-card-save' ).val( '' );

						$( '.save-card-zone input' ).attr( 'checked', false );
						$( '.save-card-zone' ).slideUp( 200 );
						$( '.card-options-list' ).slideUp( 200 );
						$( '.registered-card-preview' ).slideUp( 200 );

						if ( $( '.card-options-list' ).length > 0 ) {
							$( '.expand-on-card-choice' ).slideUp( 200 );
							if ( sMeanOfPayment == 'card' || sMeanOfPayment == 'cardwallet' ) {
								$( '#deploy-on-card-choice-' + sMeanOfPayment ).slideDown( 200 );
								var idDefaultCardType = $( '#deploy-on-card-choice-' + sMeanOfPayment ).data( 'default-card-type' );
								$( '#input-meanofpayment-card-type' ).val( idDefaultCardType );
								$( '#card-option-' + sMeanOfPayment + '-' + idDefaultCardType ).attr( 'checked', true );
							}

						} else {
							if ( sMeanOfPayment == 'card' || sMeanOfPayment == 'cardwallet' ) {
								$( '#save-card-zone-' + sMeanOfPayment ).slideDown( 200 );
							}

						}
					}
				} );
			}
			
			if ( $( 'body.template-declarer-chiffre-daffaires .mean-payment' ).length > 0 ) {
				$( 'body.template-declarer-chiffre-daffaires .mean-payment' ).click( function() {
					$( 'body.template-declarer-chiffre-daffaires .mean-payment' ).removeClass( 'selected' );
					$( this ).addClass( 'selected' );
					$( 'body.template-declarer-chiffre-daffaires form button.half.right.red').removeClass( 'hidden' );
					var sMeanOfPayment = $( this ).data( 'meanofpayment' );

					// Si on change de moyen de paiement
					if ( sMeanOfPayment != $( '#input-meanofpayment' ).val() ) {
						$( '#input-meanofpayment' ).val( sMeanOfPayment );
						$( '#input-meanofpayment-card-type' ).val( '' );
						$( '#input-meanofpayment-card-save' ).val( '' );

						$( '.save-card-zone input' ).attr( 'checked', false );
						$( '.save-card-zone' ).slideUp( 200 );
						$( '.card-options-list' ).slideUp( 200 );
						$( '.registered-card-preview' ).slideUp( 200 );

						if ( $( '.card-options-list' ).length > 0 ) {
							$( '.expand-on-card-choice' ).slideUp( 200 );
							if ( sMeanOfPayment == 'card' ) {
								$( '#deploy-on-card-choice-' + sMeanOfPayment ).slideDown( 200 );
								var idDefaultCardType = $( '#deploy-on-card-choice-' + sMeanOfPayment ).data( 'default-card-type' );
								$( '#input-meanofpayment-card-type' ).val( idDefaultCardType );
								$( '#card-option-' + sMeanOfPayment + '-' + idDefaultCardType ).attr( 'checked', true );
							}

						} else {
							if ( sMeanOfPayment == 'card' ) {
								$( '#save-card-zone-' + sMeanOfPayment ).slideDown( 200 );
							}

						}
					}
				} );
			}

			$( '.card-options-list div.field' ).click( function( e ) {
				e.stopImmediatePropagation();
			} );

			$( '.edit-card' ).click( function() {
				var sMeanOfPayment = $( this ).data( 'type' );
				$( '#deploy-on-card-choice-' + sMeanOfPayment ).slideUp( 200 );
				$( '#card-options-list-' + sMeanOfPayment ).slideDown( 200 );
			} );

			$( '.card-options-list .field-container' ).click( function() {
				var childrenInput = $( this ).find( 'input' );
				var nChildren = childrenInput.length;
				for ( var i = 0; i < nChildren; i++ ) {
					var childInput = childrenInput[ i ];
					if ( $( childInput ).is( ':checked' ) ) {
						$( '#input-meanofpayment-card-type' ).val( $( childInput ).val() );
					}
				}
				var sMeanOfPayment = $( '#input-meanofpayment' ).val();
				if ( $( '#input-meanofpayment-card-type' ).val() == 'other' ) {
					$( '#save-card-zone-' + sMeanOfPayment ).slideDown( 200 );
				} else {
					$( '#input-meanofpayment-card-save' ).val( '' );
					$( '.save-card-zone input' ).attr( 'checked', false );
					$( '#save-card-zone-' + sMeanOfPayment ).slideUp( 200 );
				}
			} );

			$( '.save-card-zone' ).click( function() {
				if ( $( this ).find( 'input' ).is( ':checked' ) ) {
					$( '#input-meanofpayment-card-save' ).val( '1' );
				} else {
					$( '#input-meanofpayment-card-save' ).val( '' );
				}
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

			// Options de notification par téléphone pour l'envoi de document
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
			var sAmount = ( $( 'form input#amount' ).val() ).replace( /,/g, "." ).split( ' ' ).join( '' );
            
			if ( sAmount== '' ) {
			    bValidInput = false;
				
			} else if ( !$.isNumeric( sAmount ) ) {
			    $( '#invest_error_general' ).show();
			    bValidInput = false;
				
			} else {
			    if ( sAmount != Math.floor( sAmount ) ) {
					$( '#invest_error_integer' ).show();
					bValidInput = false;
			    }
			    if ( parseInt( sAmount ) < $( '#input_invest_min_value' ).val() ) {
					$( '#invest_error_min' ).show();
					bValidInput = false;
			    }
			    if ( parseInt( sAmount ) > $( '#input_invest_max_value' ).val() ) {
					$( '#invest_error_max' ).show();
					bValidInput = false;
			    }
				if ( parseInt( sAmount ) > $( '#input_invest_user_max_value' ).val() ) {
					$( '#invest_error_max' ).text( $( '#input_invest_user_max_reason' ).val() );
					$( '#invest_error_max' ).show();
					bValidInput = false;
				}
			    var nAmountInterval = $( '#input_invest_max_value' ).val() - parseInt( sAmount ); 		
				if ( nAmountInterval < $( '#input_invest_min_value' ).val() && nAmountInterval > 0 ) {
					$( '#invest_error_interval' ).show(); 		
					bValidInput = false; 		
			    }
			}
			
			var ratioOfPercentRoundStr = 0;
			if (bValidInput) {
				var inputVal = Number( sAmount );
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
				
				// différencier calculateur EP 
				if ( $( 'input#is_positive_savings' ).val() == "true" ){
					var assetPrice = $( 'input#asset_price' ).val();
					var commonGoodsTurnoverPercent = $( 'input#common_goods_turnover_percent' ).val();
					var assetSingular = $( 'input#asset_singular' ).val();
					var assetPlural = $( 'input#asset_plural' ).val();
					var nbAssets = Math.ceil(inputVal / assetPrice);
					$("span.nb_assets").text(nbAssets);
					if (nbAssets > 1){
						$("span.name_assets").text(' '+assetPlural);
					} else {
						$("span.name_assets").text(' '+assetSingular);
					}
					if (nbAssets != 0){
						var ratioOfPercentRoundStr = Math.round( inputVal / assetPrice / nbAssets * percentProject * commonGoodsTurnoverPercent * 1000) /100000;
					} else {
						var ratioOfPercentRoundStr = 0;
					}
					$("span.roi_percent_user").text(ratioOfPercentRoundStr);
				} else {
					var ratioOfPercentRound = Math.round(ratioOfPercent * 100000) / 100000;
					var ratioOfPercentRoundStr = ratioOfPercentRound.toString().replace('.', ',');
					$("span.roi_percent_user").text(ratioOfPercentRoundStr);
				}
				
				$( 'form button' ).slideDown( 200 );
			} else {
				$( 'form button' ).slideUp( 200 );
			}

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