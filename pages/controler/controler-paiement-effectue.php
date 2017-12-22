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
	
	private $current_step;
	private $current_meanofpayment;
	private $current_view;
	
	public function __construct() {
		parent::__construct();
		
		define( 'SKIP_BASIC_HTML', TRUE );
		
		$this->init_current_campaign();
		WDGRoutes::redirect_invest_if_not_logged_in();
		WDGRoutes::redirect_invest_if_project_not_investable();
		
		$this->init_current_investment();
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
			$this->current_campaign_organization = new WDGOrganization( $campaign_organization->wpref );
		}
		return $this->current_campaign_organization->get_name();
	}
	
/******************************************************************************/
// CURRENT INVESTMENT
/******************************************************************************/
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
// CURRENT MEAN OF PAYMENT RETURN
/******************************************************************************/
	private function init_payment_result() {
		$this->current_meanofpayment = filter_input( INPUT_GET, 'meanofpayment' );
		if ( empty( $this->current_meanofpayment ) ) {
			$this->current_meanofpayment = WDGInvestment::$meanofpayment_card;
		}
		$payment_return = $this->current_investment->payment_return( $this->current_meanofpayment );
		if ( empty( $payment_return ) ) {
			$payment_return = 'error-contact';
		}
		$this->current_view = $this->current_meanofpayment . '-' . $payment_return;
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
			$buffer = home_url( '/paiement-partager' ). '?campaign_id=' .$this->current_campaign->ID;
		}
		return $buffer;
	}
	
	public function get_success_next_link() {
		$buffer = '';
		if ( $this->current_investment->has_token() ) {
			$buffer = $this->current_investment->get_redirection( 'success', $this->current_investment->get_token() );
		} else {
			$buffer = home_url( '/paiement-partager' ). '?campaign_id=' .$this->current_campaign->ID;
		}
		return $buffer;
	}
	
	public function get_restart_link() {
		return home_url( '/investir' ). '?campaign_id=' .$this->current_campaign->ID. '&invest_start=1';
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
			$buffer = home_url( '/investir' ). '?campaign_id=' .$this->current_campaign->ID. '&invest_start=1';
		}
		return $buffer;
	}
	
}