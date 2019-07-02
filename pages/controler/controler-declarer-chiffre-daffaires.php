<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_DeclarationInput() );

class WDG_Page_Controler_DeclarationInput extends WDG_Page_Controler {
	/**
	 * @var ATCF_Campaign
	 */
	private $current_campaign;
	/**
	 * @var WDGROIDeclaration
	 */
	private $current_declaration;
	private $current_step;
	private $form;
	
	private $can_access;
	private $summary_data;
	private $display_payment_error;
	
	public function __construct() {
		parent::__construct();
		
		define( 'SKIP_BASIC_HTML', TRUE );
		
		$this->can_access = TRUE;
		$this->display_payment_error = FALSE;
		$this->init_current_campaign();
		$this->init_current_declaration();
		if ( !$this->can_access ) {
			wp_redirect( home_url() );
			exit();
		}
		
		$this->init_current_step();
		$this->init_form();
	}
	
	/**
	 * Surcharge de WDG_Page_Controler	
	*/
	public function init_show_user_pending_investment() {
		$this->show_user_pending_investment = false;
	}
	/**
	 * Surcharge de WDG_Page_Controler	
	*/
	public function init_show_user_pending_preinvestment() {
		$this->show_user_pending_preinvestment = false;
	}
	
/******************************************************************************/
// CURRENT CAMPAIGN
/******************************************************************************/
	private function init_current_campaign() {
		$campaign_id = filter_input( INPUT_GET, 'campaign_id' );
		if ( empty( $campaign_id ) ) {
			$this->can_access = FALSE; 
		} else {
			$this->current_campaign = new ATCF_Campaign( $campaign_id );
			$this->can_access = $this->current_campaign->current_user_can_edit();
		}
	}
	
	public function get_current_campaign() {
		return $this->current_campaign;
	}
	
	public function get_current_campaign_organization_wallet_id() {
		$campaign_organization_item = $this->current_campaign->get_organization();
		$WDGOrganization = new WDGOrganization( $campaign_organization_item->wpref, $campaign_organization_item );
		return $WDGOrganization->get_lemonway_id();
	}
	
/******************************************************************************/
// CURRENT DECLARATION
/******************************************************************************/
	private function init_current_declaration() {
		$declaration_id = filter_input( INPUT_GET, 'declaration_id' );
		if ( empty( $declaration_id ) ) {
			$this->can_access = FALSE; 
		} else {
			$this->current_declaration = new WDGROIDeclaration( $declaration_id );
		}
	}
	
	public function get_current_declaration_royalties_amount() {
		return $this->current_declaration->get_amount_royalties();
	}
	
	public function get_current_declaration_amount() {
		return $this->current_declaration->get_amount_with_commission();
	}
	
/******************************************************************************/
// CURRENT STEP
/******************************************************************************/
	/**
	 * WDGROIDeclaration::$status_declaration,
	 * WDGROIDeclaration::$status_payment,
	 * WDGROIDeclaration::$status_waiting_transfer,
	 * WDGROIDeclaration::$status_transfer,
	 * WDGROIDeclaration::$status_finished
	 * WDGROIDeclaration::$status_failed
	 */
	private function init_current_step() {
		$this->current_step = $this->current_declaration->get_status();
	}
	
	public function get_current_step() {
		return $this->current_step;
	}
	
/******************************************************************************/
// SUMMARY DATA
/******************************************************************************/
	private function init_summary_data() {
		$this->summary_data = array();
		// prévisionnel
		$this->summary_data[ 'amount_estimated' ] = $this->current_declaration->get_estimated_turnover();
		// mois et enregistrements
		$this->summary_data[ 'turnover_by_month' ] = array();
		$months = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
		$nb_fields = $this->current_campaign->get_turnover_per_declaration();
		$date_due = new DateTime( $this->current_declaration->date_due );
		$date_due->sub( new DateInterval( 'P' .$nb_fields. 'M' ) );
		for ( $i = 0; $i < $nb_fields; $i++ ) {
			$label = ucfirst( __( $months[ $date_due->format( 'm' ) - 1 ] ) ). ' ' . $date_due->format( 'Y' );
			$this->summary_data[ 'turnover_by_month' ][ $label ] = $this->current_declaration->get_turnover()[ $i ];
			$date_due->add( new DateInterval( 'P1M' ) );
		}
		// total
		$this->summary_data[ 'turnover_total' ] = $this->current_declaration->get_turnover_total();
		
		// ajustement
		$adjustment_value = $this->current_declaration->get_adjustment_value();
		if ( $adjustment_value > 0 ) {
			// TODO avec refonte
			$this->summary_data[ 'amount_adjustment' ] = $this->current_declaration->get_adjustment_value();
		}
		
		// commission
		$commission = $this->current_declaration->get_commission_to_pay();
		if ( $commission > 0 ) {
			// frais
			$this->summary_data[ 'commission_without_tax' ] = $this->current_declaration->get_commission_to_pay_without_tax();
			// tva
			$this->summary_data[ 'commission_tax' ] = $this->current_declaration->get_commission_tax();
			// commission_percent_without_tax
			$this->summary_data[ 'commission_percent_without_tax' ] = $this->current_campaign->get_costs_to_organization() / 1.2;
			// minimum_commission_without_tax
			$this->summary_data[ 'minimum_commission_without_tax' ] = $this->current_campaign->get_minimum_costs_to_organization() / 1.2;
			// total royalties
			$this->summary_data[ 'amount_royalties_with_adjustment' ] = $this->current_declaration->get_amount_with_adjustment();
		}
		
		// On affiche le montant des royalties que si il diffère du montant total (autrement dit : on ne l'affiche pas pour les vieux projets
		if ( $adjustment_value > 0 || $commission > 0 ) {
			// royalties sur CA déclaré
			$this->summary_data[ 'amount_royalties' ] = $this->current_declaration->get_amount_royalties();
			$this->summary_data[ 'percent_royalties' ] = $this->current_campaign->roi_percent();
		}
		
		// total à payer
		$this->summary_data[ 'amount_to_pay' ] = $this->current_declaration->get_amount_with_commission();
		
		// message
		$this->summary_data[ 'message' ] = $this->current_declaration->get_message();
	}
	
	public function get_summary_data() {
		return $this->summary_data;
	}
	
	public function is_card_shortcut_displayed() {
		$declaration_amount_to_pay = $this->current_declaration->get_amount_with_commission();
		return ( $declaration_amount_to_pay < 500 );
	}
	
	public function has_commission() {
		return ( $this->current_declaration->get_commission_to_pay() > 0 );
	}
	
/******************************************************************************/
// CURRENT FORM
/******************************************************************************/
	private function init_form() {
		ypcf_debug_log( 'WDG_Page_Controler_DeclarationInput::init_form' );
		// Récupération d'un éventuel post de formulaire
		$action_posted = filter_input( INPUT_POST, 'action' );
		
		if ( $this->current_step == WDGROIDeclaration::$status_declaration ) {
			$core = ATCF_CrowdFunding::instance();
			$core->include_form( 'declaration-input' );
			$this->form = new WDG_Form_Declaration_Input( $this->current_campaign->ID, $this->current_declaration->id );
			if ( $action_posted == WDG_Form_Declaration_Input::$name ) {
				$result_form = $this->form->postForm();
				if ( $result_form ) {
					// La déclaration a été validée, le statut a changé, il faut recharger
					$this->current_declaration = new WDGROIDeclaration( $this->current_declaration->id );
					$this->current_step = $this->current_declaration->get_status();
				}
			}
		}
		
		if ( $this->current_step == WDGROIDeclaration::$status_payment ) {
			$this->init_summary_data();
			$has_tried_payment = FALSE;
			switch( $action_posted ) {
				case 'gobacktodeclaration':
					$this->current_declaration->status = WDGROIDeclaration::$status_declaration;
					$this->current_step = $this->current_declaration->get_status();
					$core = ATCF_CrowdFunding::instance();
					$core->include_form( 'declaration-input' );
					$this->form = new WDG_Form_Declaration_Input( $this->current_campaign->ID, $this->current_declaration->id );
					break;
				
				case 'changepayment':
					$this->current_step = WDGROIDeclaration::$status_payment . '2';
					break;
				
				case 'gotopayment':
					$this->current_step = WDGROIDeclaration::$status_payment . '2';
					$has_tried_payment = TRUE;
					if ( $this->is_card_shortcut_displayed() ) {
						// Démarrer paiement par carte
						$this->display_payment_error = !$this->proceed_payment_card();
						
					} else {
						// Procéder au prélèvement
						$this->display_payment_error = !$this->proceed_payment_mandate();
					}
					break;
					
				case 'proceedpayment':
					$this->current_step = WDGROIDeclaration::$status_payment . '2';
					$has_tried_payment = TRUE;
					// Démarrer paiement par carte
					$input_meanofpayment = filter_input( INPUT_POST, 'meanofpayment' );
					switch ( $input_meanofpayment ) {
						case 'card':
							$this->display_payment_error = !$this->proceed_payment_card();
							break;
						case 'mandate':
							$this->display_payment_error = !$this->proceed_payment_mandate();
							break;
						case 'wire':
							$this->proceed_payment_wire();
							break;
					}
					break;
				
				default:
					$input_cardreturn = filter_input( INPUT_GET, 'cardreturn' );
					$input_response_wkToken = filter_input( INPUT_GET, 'response_wkToken' );
					if ( $input_cardreturn == '1' && !empty( $input_response_wkToken ) ) {
						$return_lemonway_card = WDGFormProjects::return_lemonway_card();
						if ( $return_lemonway_card == TRUE ) {
							$has_tried_payment = TRUE;
							
						} elseif ( $return_lemonway_card !== FALSE ) {
							$has_tried_payment = TRUE;
							$this->display_payment_error = TRUE;
						}
					}
					break;
			}
			
			if ( $has_tried_payment && !$this->display_payment_error ) {
				$this->current_step = $this->current_declaration->get_status();
			}
		}
		
		ypcf_debug_log( 'WDG_Page_Controler_DeclarationInput::init_form > ' .$this->current_step );
	}
	
	public function get_form() {
		return $this->form;
	}
	
	public function get_form_errors() {
		return $this->form->getPostErrors();
	}
	
	public function get_form_action() {
		$url = home_url( '/declarer-chiffre-daffaires/' );
		$url .= '?campaign_id=' .$this->current_campaign->ID. '&declaration_id=' .$this->current_declaration->id;
		return $url;
	}
	
	public function get_dashboard_url() {
		$url = home_url( '/tableau-de-bord/' );
		$url .= '?campaign_id=' .$this->current_campaign->ID;
		return $url;
	}
	
	public function can_display_payment_error() {
		return $this->display_payment_error;
	}
	
	public function has_sign_mandate() {
		$campaign_organization_item = $this->current_campaign->get_organization();
		$WDGOrganization = new WDGOrganization( $campaign_organization_item->wpref, $campaign_organization_item );
		return $WDGOrganization->has_signed_mandate();
	}
	
/******************************************************************************/
// PAYMENT
/******************************************************************************/
	public function proceed_payment_card() {
		$campaign_organization_item = $this->current_campaign->get_organization();
		$WDGOrganization = new WDGOrganization( $campaign_organization_item->wpref, $campaign_organization_item );
		
		$return_url = $this->get_form_action() . '&cardreturn=1';
		$wk_token = LemonwayLib::make_token( '', $this->current_declaration->id );
		$this->current_declaration->payment_token = $wk_token;
		$this->current_declaration->save();
		$WDGOrganization->register_lemonway( TRUE );
		$return = LemonwayLib::ask_payment_webkit( $WDGOrganization->get_lemonway_id(), $this->current_declaration->get_amount_with_commission(), $this->current_declaration->get_commission_to_pay(), $wk_token, $return_url, $return_url, $return_url );
		if ( !empty( $return->MONEYINWEB->TOKEN ) ) {
			wp_redirect( YP_LW_WEBKIT_URL . '?moneyInToken=' . $return->MONEYINWEB->TOKEN );
			exit();
		}
		return FALSE;
	}
	
	public function proceed_payment_mandate() {
		$buffer = FALSE;
		
		$campaign_organization_item = $this->current_campaign->get_organization();
		$WDGOrganization = new WDGOrganization( $campaign_organization_item->wpref, $campaign_organization_item );
		if ( $WDGOrganization->has_signed_mandate() ) {

			$wallet_id = $WDGOrganization->get_lemonway_id();
			$saved_mandates_list = $WDGOrganization->get_lemonway_mandates();
			if ( !empty( $saved_mandates_list ) ) {
				$last_mandate = end( $saved_mandates_list );
			}
			$mandate_id = $last_mandate['ID'];

			if ( !empty( $wallet_id ) && !empty( $mandate_id ) ) {
				$result = LemonwayLib::ask_payment_with_mandate( $wallet_id, $this->current_declaration->get_amount_with_commission(), $mandate_id, $this->current_declaration->get_commission_to_pay() );
				$lw_return = ($result->TRANS->HPAY->ID) ? "success" : $result->TRANS->HPAY->MSG;

				if ( $lw_return == 'success' ) {
					$buffer = TRUE;
					
					// Enregistrement de l'objet Lemon Way
					$withdrawal_post = array(
						'post_author'   => $campaign_organization_item->wpref,
						'post_title'    => $this->current_declaration->get_amount_with_commission() . ' - ' . $this->current_declaration->get_commission_to_pay(),
						'post_content'  => print_r( $result, TRUE ),
						'post_status'   => 'publish',
						'post_type'		=> 'mandate_payment'
					);
					wp_insert_post( $withdrawal_post );

					// Enregistrement de la déclaration
					$date_now = new DateTime();
					$this->current_declaration->date_paid = $date_now->format( 'Y-m-d' );
					$this->current_declaration->mean_payment = WDGROIDeclaration::$mean_payment_mandate;
					$this->current_declaration->status = WDGROIDeclaration::$status_transfer;
					$this->current_declaration->save();
				}
			}

		}
		
		return $buffer;
	}
	
	public function proceed_payment_wire() {
		$this->current_declaration->status = WDGROIDeclaration::$status_waiting_transfer;
		$this->current_declaration->save();
	}
	
}