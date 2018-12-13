<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_MeanPayment() );

class WDG_Page_Controler_MeanPayment extends WDG_Page_Controler {
	public static $display_card_alert_amount = 500;
	
	/**
	 * @var ATCF_Campaign
	 */
	private $current_campaign;
	/**
	 * @var WDGOrganization
	 */
	private $current_campaign_organization;
	/**
	 * @var WDGUserInvestments
	 */
	private $current_user_investments;
	/**
	 * @var WDGInvestment
	 */
	private $current_investment;
	
	private $current_step;
	private $current_view;
	private $current_meanofpayment;
	
	private $can_use_wallet;
	private $can_use_card_and_wallet;
	
	private $display_error;
	
	public function __construct() {
		parent::__construct();
		
		define( 'SKIP_BASIC_HTML', TRUE );
		
		$this->init_current_campaign();
		WDGRoutes::redirect_invest_if_not_logged_in();
		WDGRoutes::redirect_invest_if_project_not_investable();
		WDGRoutes::redirect_invest_if_investment_session_not_initialized();
		
		$this->init_current_user_investments();
		$this->init_current_investment();
		$this->init_current_step();
		$this->init_current_mean_of_payment();
	}
	
/******************************************************************************/
// CURRENT CAMPAIGN
/******************************************************************************/
	private function init_current_campaign() {
		$this->current_campaign = atcf_get_current_campaign();
	}
	
	public function get_current_campaign() {
		return $this->current_campaign;
	}
	
	public function get_campaign_organization_name() {
		if ( !isset( $this->current_campaign_organization ) ) {
			$campaign_organization = $this->current_campaign->get_organization();
			$this->current_campaign_organization = new WDGOrganization( $campaign_organization->wpref, $campaign_organization );
		}
		return $this->current_campaign_organization->get_name();
	}
	
/******************************************************************************/
// CURRENT USER
/******************************************************************************/
	private function init_current_user_investments() {
		$WDGUser_current = WDGUser::current();
		$this->current_user_investments = new WDGUserInvestments( $WDGUser_current );
	}
	
	public function get_current_user_investments() {
		return $this->current_user_investments;
	}
	
/******************************************************************************/
// CURRENT INVESTMENT
/******************************************************************************/
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
	
	private function init_current_investment() {
		$this->current_investment = WDGInvestment::current();
	}
	
	public function get_current_investment() {
		return $this->current_investment;
	}
	
	public function is_preinvestment() {
		return ( $this->current_campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote );
	}
	
/******************************************************************************/
// CURRENT STEP
/******************************************************************************/
	private function init_current_step() {
		$this->current_step = 4;
	}
	public function get_current_step() {
		return $this->current_step;
	}
	
/******************************************************************************/
// DISPLAY
/******************************************************************************/
	public function is_list_displayed() {
		return ( $this->get_current_mean_of_payment() == '' );
	}
	
	public function get_current_view() {
		return $this->current_view;
	}
	
	public function get_display_error() {
		return $this->display_error;
	}
	
/******************************************************************************/
// ACCEPTED MEAN OF PAYMENTS
/******************************************************************************/
	public function get_payment_url( $param ) {
		return home_url( '/moyen-de-paiement/' ) . '?campaign_id=' .$this->current_campaign->ID. '&meanofpayment=' .$param;
	}
	
	public function get_lemonway_amount() {
		$WDGUser_current = WDGUser::current();
		return $WDGUser_current->get_lemonway_wallet_amount();
	}
	
	public function get_remaining_amount() {
		$current_investment = WDGInvestment::current();
		return $current_investment->get_session_amount() - $this->get_lemonway_amount();
	}
	
	public function is_user_lemonway_registered() {
		$WDGUser_current = WDGUser::current();
		return $WDGUser_current->is_lemonway_registered();
	}
	
	/**
	 * On ne peut utiliser la carte que si on n'a pas dépassé les valeurs limites sur une période imposées par LW
	 * @return boolean
	 */
	public function can_use_card() {
		$buffer = TRUE;
		
		if ( $this->current_user_investments->can_invest_nb() != TRUE ) {
			$buffer = FALSE;
		}
		
		return $buffer;
	}
	
	public function init_can_use_wallet() {
		if ( !isset( $this->can_use_wallet ) ) {
			$this->can_use_wallet = FALSE;
			$this->can_use_card_and_wallet = FALSE;
			if ( !$this->current_investment->has_token() && ATCF_CrowdFunding::get_platform_context() == "wedogood" ) {
				if ( $this->current_investment->get_session_user_type() == 'user' ) {
					$WDGUser_current = WDGUser::current();
					$this->can_use_wallet = $WDGUser_current->can_pay_with_wallet( $this->current_investment->get_session_amount(), $this->current_campaign );
					$this->can_use_card_and_wallet = $WDGUser_current->can_pay_with_card_and_wallet( $this->current_investment->get_session_amount(), $this->current_campaign );
				}/* else {
					$invest_type = $this->current_investment->get_session_user_type();
					$organization = new WDGOrganization($invest_type);
					$this->can_use_wallet = $organization->can_pay_with_wallet( $this->current_investment->get_session_amount(), $this->current_campaign );
					$this->can_use_card_and_wallet = $organization->can_pay_with_card_and_wallet( $this->current_investment->get_session_amount(), $this->current_campaign );
				}*/
			}
		}
	}
	
	public function can_use_wallet() {
		$this->init_can_use_wallet();
		return $this->can_use_wallet;
	}
	
	public function can_use_card_and_wallet() {
		$this->init_can_use_wallet();
		return $this->can_use_card_and_wallet;
	}
	
	public function display_card_amount_alert() {
		return ( $this->current_investment->get_session_amount() >= WDG_Page_Controler_MeanPayment::$display_card_alert_amount );
	}
	
	public function can_use_wire() {
		return ( $this->current_campaign->can_use_wire( $this->current_investment->get_session_amount() / $this->current_campaign->part_value() ) );
	}
	
	public function display_inactive_wire() {
		$buffer = $this->current_campaign->can_use_wire_remaining_time()
				&& !$this->current_campaign->can_use_wire_amount( $this->current_investment->get_session_amount() / $this->current_campaign->part_value() )
				&& !$this->current_investment->has_token();
		return $buffer;
	}
	
	public function can_use_check() {
		$buffer = $this->current_campaign->can_use_check( $this->current_investment->get_session_amount() / $this->current_campaign->part_value() )
				&& !$this->current_investment->has_token();
		return $buffer;
	}
	
	public function display_inactive_check() {
		return $this->current_campaign->can_use_check_option() && !$this->current_investment->has_token();
	}
	
/******************************************************************************/
// CURRENT MEAN OF PAYMENT
/******************************************************************************/
	private function init_current_mean_of_payment() {
		$this->current_meanofpayment = filter_input( INPUT_GET, 'meanofpayment' );
		
		switch ( $this->current_meanofpayment ) {
			case WDGInvestment::$meanofpayment_wallet:
				$return = $this->current_investment->try_payment( WDGInvestment::$meanofpayment_wallet );
				if ( empty( $return ) ) {
					$this->current_view = 'wallet-error';
				} else {
					$this->current_view = 'wallet-success';
				}
				break;
				
			case WDGInvestment::$meanofpayment_cardwallet:
			case WDGInvestment::$meanofpayment_card:
				$return = $this->current_investment->try_payment( $this->current_meanofpayment );
				if ( empty( $return ) ) {
					$this->display_error = __( "Il y a eu une erreur de connexion &agrave; notre prestataire de paiement Lemon Way.", 'yproject' );
					$investment_error = $this->current_investment->get_error();
					if ( !empty( $investment_error ) ) {
						$this->display_error .= '<br>';
						$this->display_error .= __( "Merci de nous transmettre les informations ci-dessous via le chat en ligne en bas &agrave; droite de l'&eacute;cran, ou par e-mail &agrave; l'adresse investir@wedogood.co.", 'yproject' );
						$this->display_error .= '<br><br>';
						$this->display_error .= $investment_error[0];
						$this->display_error .= '<br><br>';
					}
				}
				break;
			
			case WDGInvestment::$meanofpayment_wire:
				$this->current_view = 'wire';
				break;
			
			case WDGInvestment::$meanofpayment_check:
				$check_return = $this->get_check_return();
				if ( !empty( $check_return ) ) {
					// Stock avant destruction
					$this->current_investment->get_session_amount();
					$this->current_investment->get_session_user_type();
					WDGInvestment::unset_session();
					$this->current_view = 'check-return';
				} else {
					$this->current_investment->set_status( WDGInvestment::$status_waiting_check );
					$this->current_view = 'check-form';
				}
				break;
		}
		
	}
	
	public function get_current_mean_of_payment() {
		return $this->current_meanofpayment;
	}
	
	public function has_contract_errors() {
		global $contract_errors;
		$buffer = FALSE;
		if ( isset( $contract_errors ) && $contract_errors != '' ) {
			ypcf_debug_log( "has_contract_errors --- ERROR :: contract :: " .$contract_errors );
			$buffer = TRUE;
		}
		return $buffer;
	}
	
	public function get_check_return() {
		return filter_input( INPUT_GET, 'check-return' );
	}
	
	public function get_current_user_email() {
		$WDGUser_current = WDGUser::current();
		return $WDGUser_current->get_email();
	}
	
	public function get_current_user_phone() {
		$buffer = FALSE;
		$WDGUser_current = WDGUser::current();
		if ( ypcf_check_user_phone_format( $WDGUser_current->get_phone_number() ) ) {
			$buffer = $WDGUser_current->get_phone_number();
		}
		return $buffer;
	}
	
	public function get_investor_lemonway_id() {
		$WDGUser_current = WDGUser::current();
		$WDGUser_current->register_lemonway();
		$lemonway_id = $WDGUser_current->get_lemonway_id();
		if ( $this->current_investment->get_session_user_type() != 'user' ) {
			$organization = new WDGOrganization( $this->current_investment->get_session_user_type() );
			$organization->register_lemonway();
			$lemonway_id = $organization->get_lemonway_id();
		}
		return $lemonway_id;
	}
	
	public function is_investor_lemonway_registered() {
		$buffer = FALSE;
		if ( $this->current_investment->get_session_user_type() != 'user' ) {
			$organization = new WDGOrganization( $this->current_investment->get_session_user_type() );
			$buffer = $organization->is_registered_lemonway_wallet();
			
		} else {
			$WDGUser_current = WDGUser::current();
			$buffer = $WDGUser_current->is_lemonway_registered();
		}
		return $buffer;
	}
	
	public function get_user_document_list_by_type( $type ) {
		$WDGUser_current = WDGUser::current();
		return WDGKYCFile::get_list_by_owner_id( $WDGUser_current->wp_user->ID, WDGKYCFile::$owner_user, $type );
	}
	
	public function get_organization_document_list_by_type( $type ) {
		return WDGKYCFile::get_list_by_owner_id( $this->current_investment->get_session_user_type(), WDGKYCFile::$owner_organization, $type );
	}
	
	public function get_wire_next_link() {
		$buffer = home_url( '/paiement-effectue/' ). '?campaign_id=' .$this->current_campaign->ID. '&meanofpayment=wire';
		return $buffer;
	}
	
	public function get_success_next_link() {
		$buffer = '';
		if ( $this->current_investment->has_token() ) {
			$buffer = $this->current_investment->get_redirection( 'success', $this->current_investment->get_token() );
		} else {
			$buffer = home_url( '/paiement-partager/' ). '?campaign_id=' .$this->current_campaign->ID;
		}
		return $buffer;
	}
	
	public function get_restart_link() {
		return home_url( '/investir/' ). '?campaign_id=' .$this->current_campaign->ID. '&invest_start=1';
	}
	
}