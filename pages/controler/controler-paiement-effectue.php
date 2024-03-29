<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_PaymentDone() );

class WDG_Page_Controler_PaymentDone extends WDG_Page_Controler {
	/**
	 * @var ATCF_Campaign
	 */
	private $current_campaign;
	/**
	 * @var WDGInvestment
	 */
	private $current_investment;
	private $maximum_investable_amount;
	
	private $current_step;
	private $current_meanofpayment;
	private $current_view;
	
	public function __construct() {
		parent::__construct();
		
		define( 'SKIP_BASIC_HTML', TRUE );
		
		$core = ATCF_CrowdFunding::instance();
		$core->include_form( 'user-identitydocs' );
		
		$this->init_current_campaign();
		WDGRoutes::redirect_invest_if_not_logged_in();
		WDGRoutes::redirect_invest_if_project_not_investable();
		
		$this->init_mean_of_payment();
		$this->init_current_investment();
		$this->init_maximum_investable_amount();
		$this->init_current_step();
		$this->init_payment_result();
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
	
	private function init_mean_of_payment() {
		$this->current_meanofpayment = filter_input( INPUT_GET, 'meanofpayment' );
		if ( empty( $this->current_meanofpayment ) ) {
			$this->current_meanofpayment = WDGInvestment::$meanofpayment_card;
		}
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
	
	private function init_maximum_investable_amount() {
		$amount_wallet = 0;
		if ( $this->current_investment->get_session_user_type() != 'user' ) {
			$WDGInvestorEntity = new WDGOrganization( $this->current_investment->get_session_user_type() );
			if ( $this->current_meanofpayment == WDGInvestment::$meanofpayment_cardwallet ) {
				$amount_wallet = $WDGInvestorEntity->get_available_rois_amount();
			}

		} else {
			$WDGInvestorEntity = WDGUser::current();
			if ( $this->current_meanofpayment == WDGInvestment::$meanofpayment_cardwallet ) {
				$amount_wallet = $WDGInvestorEntity->get_lemonway_wallet_amount();
			}
		}
		$WDGCurrent_User_Investments = new WDGUserInvestments( $WDGInvestorEntity );
		$this->maximum_investable_amount = $WDGCurrent_User_Investments->get_maximum_investable_amount_without_alert() + $amount_wallet;
	}
	
	public function get_maximum_investable_amount() {
		return $this->maximum_investable_amount;
	}
	
	public function get_remaining_amount_to_invest() {
		return ( $this->current_investment->get_session_amount() - $this->get_maximum_investable_amount() );
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
// CURRENT MEAN OF PAYMENT RETURN
/******************************************************************************/
	private function init_payment_result() {
		$payment_return = filter_input( INPUT_GET, 'payment_return' );
		if ( empty( $payment_return ) ) {
			$payment_return = $this->current_investment->payment_return( $this->current_meanofpayment );
		}
		if ( empty( $payment_return ) ) {
			$payment_return = 'error-contact';
		}
		if (
				$payment_return == 'publish'
				|| 
				(
					$payment_return == 'pending'
					&&
					( $this->current_meanofpayment == WDGInvestment::$meanofpayment_wire || $this->current_meanofpayment == WDGInvestment::$meanofpayment_check )
				)
			) {
			$this->page_analytics_data[ 'payment' ] = array();
			// ID de la transaction
			$this->page_analytics_data[ 'payment' ][ 'event_label' ] = $this->current_investment->get_id();
			// Montant total de l'investissement
			$this->page_analytics_data[ 'payment' ][ 'value' ] = $this->current_investment->get_session_amount();
			// Titre du projet
			$this->page_analytics_data[ 'payment' ][ 'product_name' ] = $this->get_current_campaign()->get_name();
			// ID du projet
			$this->page_analytics_data[ 'payment' ][ 'product_id' ] = $this->get_current_campaign()->ID;
			// Nom de la société qui porte le projet
			$this->page_analytics_data[ 'payment' ][ 'product_brand' ] = $this->get_campaign_organization_name();
			// Catégorie du projet
			$this->page_analytics_data[ 'payment' ][ 'product_category' ] = 'Entreprises';
			if ( $this->get_current_campaign()->is_positive_savings() ) {
				$this->page_analytics_data[ 'payment' ][ 'product_category' ] = 'Epargne positive';
			}
		}
		$this->current_view = $this->current_meanofpayment . '-' . $payment_return;
		ypcf_debug_log( 'paiement-effectue > init_payment_result --- $this->current_view :: ' .$this->current_view );
	}
	
	public function get_current_view() {
		return $this->current_view;
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
	
	public function get_pending_next_link() {
		$buffer = '';
		if ( $this->current_investment->has_token() ) {
			$buffer = $this->current_investment->get_redirection( 'error', 'investpending' );
		} else {
			$page_url = WDG_Redirect_Engine::override_get_page_url( 'paiement-partager' );
			$buffer = $page_url. '?campaign_id=' .$this->current_campaign->ID;
		}
		return $buffer;
	}
	
	public function get_success_next_link() {
		$buffer = '';
		if ( $this->current_investment->has_token() ) {
			$buffer = $this->current_investment->get_redirection( 'success', $this->current_investment->get_token() );
		} else {
			$page_url = WDG_Redirect_Engine::override_get_page_url( 'paiement-partager' );
			$buffer = $page_url. '?campaign_id=' .$this->current_campaign->ID;
		}
		return $buffer;
	}
	
	public function get_restart_link() {
		$page_url = WDG_Redirect_Engine::override_get_page_url( 'investir' );
		return $page_url. '?campaign_id=' .$this->current_campaign->ID. '&invest_start=1';
	}
	
	public function get_error_link() {
		$buffer = '';
		if ( $this->current_investment->has_token() ) {
			$buffer = $this->current_investment->get_redirection( 'error', 'investerror', $this->current_investment->get_error_code() );
		}
		return $buffer;
	}
	
	public function get_error_restart_link() {
		$buffer = '';
		$error_item = $this->current_investment->get_error_item();
		if ( isset( $error_item ) && $error_item->ask_restart() ) {
			$page_url = WDG_Redirect_Engine::override_get_page_url( 'moyen-de-paiement' );
			$buffer = $page_url. '?campaign_id=' .$this->current_campaign->ID;
		}
		return $buffer;
	}
	
}