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
	/**
	 * @var WDG_Form_User_Identity_Docs
	 */
	private $form_user_identity_docs;
	private $form_user_identity_docs_feedback;
	
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
		
		$this->init_current_investment();
		$this->init_maximum_investable_amount();
		$this->init_identitydocs_form();
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
		$WDGCurrent_User = WDGUser::current();
		$WDGCurrent_User_Investments = new WDGUserInvestments( $WDGCurrent_User );
		$this->maximum_investable_amount = $WDGCurrent_User_Investments->get_maximum_investable_amount_without_alert();
	}
	
	public function get_maximum_investable_amount() {
		return $this->maximum_investable_amount;
	}
	
	public function get_remaining_amount_to_invest() {
		return ( $this->current_investment->get_session_amount() - $this->get_maximum_investable_amount() );
	}
	
	public function needs_two_contracts() {
		$amount_part = $this->current_investment->get_session_amount();
		return ( $amount_part > $this->maximum_investable_amount );
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
// FORM
/******************************************************************************/
	private function init_identitydocs_form() {
		if ( $this->needs_two_contracts() ) {
			$WDGCurrent_User = WDGUser::current();
			$is_orga = $this->get_current_investment()->get_session_user_type() != 'user';
			$this->form_user_identity_docs = new WDG_Form_User_Identity_Docs( $is_orga ? $this->get_current_investment()->get_session_user_type() : $WDGCurrent_User->get_wpref(), $is_orga );
			$action_posted = filter_input( INPUT_POST, 'action' );
			if ( $action_posted == WDG_Form_User_Identity_Docs::$name ) {
				$this->form_user_identity_docs_feedback = $this->form_user_identity_docs->postForm();
				if ( empty( $this->form_user_identity_docs_feedback[ 'errors' ] ) ) {
					wp_redirect( $this->get_success_next_link() );
				}
			}
		}
	}
	
	public function get_identitydocs_form() {
		return $this->form_user_identity_docs;
	}
	
	public function get_identitydocs_form_feedback() {
		return $this->form_user_identity_docs_feedback;
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
		if ( $WDGUser_current->has_phone_number_correct() ) {
			$buffer = $WDGUser_current->get_phone_number();
		}
		return $buffer;
	}
	
	public function get_pending_next_link() {
		$buffer = '';
		if ( $this->current_investment->has_token() ) {
			$buffer = $this->current_investment->get_redirection( 'error', 'investpending' );
		} elseif ( $this->current_investment->needs_signature() ) {
			$buffer = home_url( '/paiement-signature' ). '?campaign_id=' .$this->current_campaign->ID;
		} else {
			$buffer = home_url( '/paiement-partager/' ). '?campaign_id=' .$this->current_campaign->ID;
		}
		return $buffer;
	}
	
	public function get_success_next_link() {
		$buffer = '';
		if ( $this->current_investment->has_token() ) {
			$buffer = $this->current_investment->get_redirection( 'success', $this->current_investment->get_token() );
		} elseif ( $this->current_investment->needs_signature() ) {
			$buffer = home_url( '/paiement-signature' ). '?campaign_id=' .$this->current_campaign->ID;
		} else {
			$buffer = home_url( '/paiement-partager/' ). '?campaign_id=' .$this->current_campaign->ID;
		}
		return $buffer;
	}
	
	public function get_restart_link() {
		return home_url( '/investir/' ). '?campaign_id=' .$this->current_campaign->ID. '&invest_start=1';
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
			$buffer = home_url( '/investir/' ). '?campaign_id=' .$this->current_campaign->ID. '&invest_start=1';
		}
		return $buffer;
	}
	
}