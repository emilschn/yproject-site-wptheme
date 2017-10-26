<?php
global $page_controler;
$page_controler = new WDG_Page_Controler_Invest();

class WDG_Page_Controler_Invest extends WDG_Page_Controler {
	/**
	 * @var ATCF_Campaign
	 */
	private $current_campaign;
	/**
	 * @var WDGOrganization
	 */
	private $current_campaign_organization;
	/**
	 * @var WDGInvestment
	 */
	private $current_investment;
	
	private $current_step;
	private $form;
	
	private $can_use_wallet;
	private $can_use_card_and_wallet;
	
	public function __construct() {
		parent::__construct();
		
		$core = ATCF_CrowdFunding::instance();
		$core->include_form( 'invest-input' );
		$core->include_form( 'invest-user-details' );
		$core->include_form( 'invest-contract' );
		
		date_default_timezone_set( "Europe/London" );
		define( 'SKIP_BASIC_HTML', TRUE );
		
		$this->init_current_campaign();
		$this->init_current_investment();
		$this->init_current_step();
		$this->init_form();
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
	
	public function get_campaign_min_part() {
		$buffer = ceil( $this->get_campaign_min_amount() / $this->current_campaign->part_value() );
		return $buffer;
	}
	
	public function get_campaign_max_amount() {
		return ypcf_get_max_value_to_invest();
	}
	
	public function get_campaign_min_amount() {
		return ypcf_get_min_value_to_invest();
	}
	
	public function get_campaign_name() {
		return $this->current_campaign->data->post_title;
	}
	
	public function get_campaign_organization_name() {
		if ( !isset( $this->current_campaign_organization ) ) {
			$campaign_organization = $this->current_campaign->get_organization();
			$this->current_campaign_organization = new WDGOrganization( $campaign_organization->wpref );
		}
		return $this->current_campaign_organization->get_name();
	}
	
	public function get_campaign_funding_duration() {
		return $this->current_campaign->funding_duration();
	}
	
	public function get_campaign_maximum_profit() {
		return $this->current_campaign->maximum_profit();
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
	
	public function get_current_investment_contract_preview() {
		$current_user = wp_get_current_user();
		$campaign = $this->current_campaign;
		$part_value = ypcf_get_part_value();
        $amount_part = ( $_SESSION[ 'redirect_current_amount' ] === FALSE ) ? 0 : $_SESSION[ 'redirect_current_amount' ] / $part_value;
		$invest_data = array(
			"amount_part"					=> $amount_part,
			"amount"						=> $_SESSION[ 'redirect_current_amount' ],
			"total_parts_company"			=> $campaign->total_parts(),
			"total_minimum_parts_company"	=> $campaign->total_minimum_parts(),
			"ip"							=> filter_input( INPUT_SERVER, 'REMOTE_ADDR' )
		);
		
		$organization = false;
		if ( $_SESSION[ 'redirect_current_user_type' ] != 'user' ) {
			$organization = new WDGOrganization( $_SESSION[ 'redirect_current_user_type' ] );
		}
		return fillPDFHTMLDefaultContent( $current_user, $campaign, $invest_data, $organization );
	}
	
/******************************************************************************/
// CURRENT STEP
/******************************************************************************/
	private function init_current_step() {
		$this->current_step = 1;
	}
	public function get_current_step() {
		return $this->current_step;
	}
	
/******************************************************************************/
// CURRENT FORM
/******************************************************************************/
	private function init_form() {
		$current_investment = WDGInvestment::current();
		
		// Récupération d'un éventuel post de formulaire
		$action_posted = filter_input( INPUT_POST, 'action' );
		if ( $action_posted != WDG_Form_Invest_Input::$name && !$current_investment->is_session_correct() ) {
			$action_posted = FALSE;
			$this->current_step = 1;
		}
		$reload_form = FALSE;
		
		switch ( $action_posted ) {
			// Analyse formulaire saisie montant
			case WDG_Form_Invest_Input::$name:
				$this->form = new WDG_Form_Invest_Input( $this->current_campaign );
				if ( $this->form->postForm() ) {
					$this->current_step = 2;
					$reload_form = TRUE;
				}
				break;
			
			// Analyse formulaire saisie infos
			case WDG_Form_Invest_User_Details::$name:
				$input_nav = filter_input( INPUT_POST, 'nav' );
				if ( $input_nav == 'previous' ) {
					$this->current_step = 1;
					$reload_form = TRUE;
					
				} else {
					$this->current_step = 2;
					$WDGCurrent_User = WDGUser::current();
					$this->form = new WDG_Form_Invest_User_Details( $this->current_campaign, $WDGCurrent_User->wp_user->ID );
					if ( $this->form->postForm() ) {
						$this->current_step = 3;
						$reload_form = TRUE;
					}
				}
				break;
				
			// Analyse formulaire validation contrat
			case WDG_Form_Invest_Contract::$name:
				$input_nav = filter_input( INPUT_POST, 'nav' );
				if ( $input_nav == 'previous' ) {
					$this->current_step = 2;
					$reload_form = TRUE;
					
				} else {
					$this->current_step = 3;
					$WDGCurrent_User = WDGUser::current();
					$this->form = new WDG_Form_Invest_Contract( $this->current_campaign, $WDGCurrent_User->wp_user->ID );
					if ( $this->form->postForm() ) {
						$this->current_step = 4;
						$reload_form = TRUE;
					}
				}
				break;
				
			// Chargement formulaire saisie montant, si rien en cours
			default:
				$this->form = new WDG_Form_Invest_Input( $this->current_campaign );
				break;
		}
		
		// Chargement du formulaire à afficher
		if ( $reload_form ) {
			$WDGCurrent_User = WDGUser::current();
			switch ( $this->current_step ) {
				case 1:
					$this->form = new WDG_Form_Invest_Input( $this->current_campaign, $WDGCurrent_User->wp_user->ID );
					break;
				case 2:
					$this->form = new WDG_Form_Invest_User_Details( $this->current_campaign, $WDGCurrent_User->wp_user->ID );
					break;
				case 3:
					$this->form = new WDG_Form_Invest_Contract( $this->current_campaign, $WDGCurrent_User->wp_user->ID );
					break;
				case 4:
					break;
			}
		}
		
	}
	
	public function get_form() {
		return $this->form;
	}
	
	public function get_form_errors() {
		return $this->form->getPostErrors();
	}
	
	public function get_form_action() {
		$url = home_url( '/investir' );
		$url .= '?campaign_id=' . $this->current_campaign->ID;
		return $url;
	}
	
/******************************************************************************/
// ACCEPTED MEAN OF PAYMENTS
/******************************************************************************/
	public function get_payment_url( $param ) {
		return home_url( '/moyen-de-paiement' ) . '?campaign_id=' .$this->current_campaign->ID. '&meanofpayment=' .$param;
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
	
	public function init_can_use_wallet() {
		if ( !isset( $this->can_use_wallet ) ) {
			$this->can_use_wallet = FALSE;
			$this->can_use_card_and_wallet = FALSE;
			$current_investment = WDGInvestment::current();
			if ( !$current_investment->has_token() && ATCF_CrowdFunding::get_platform_context() == "wedogood" ) {
				if ( $current_investment->get_session_user_type() == 'user' ) {
					$WDGUser_current = WDGUser::current();
					$can_use_wallet = $WDGUser_current->can_pay_with_wallet( $current_investment->get_session_amount(), $this->current_campaign );
					$can_use_card_and_wallet = $WDGUser_current->can_pay_with_card_and_wallet( $current_investment->get_session_amount(), $this->current_campaign );
				} else {
					$invest_type = $current_investment->get_session_user_type();
					$organization = new WDGOrganization($invest_type);
					$can_use_wallet = $organization->can_pay_with_wallet( $current_investment->get_session_amount(), $this->current_campaign );
					$can_use_card_and_wallet = $organization->can_pay_with_card_and_wallet( $current_investment->get_session_amount(), $this->current_campaign );
				}
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
	
	public function can_use_wire() {
		$current_investment = WDGInvestment::current();
		return ( $this->current_campaign->can_use_wire( $current_investment->get_session_amount() ) );
	}
	
	public function display_inactive_wire() {
		$current_investment = WDGInvestment::current();
		$buffer = $this->current_campaign->can_use_wire_remaining_time()
				&& !$this->current_campaign->can_use_wire_amount( $current_investment->get_session_amount() )
				&& !$current_investment->has_token();
		return $buffer;
	}
	
	public function can_use_check() {
		$current_investment = WDGInvestment::current();
		$buffer = $this->current_campaign->can_use_check( $current_investment->get_session_amount() )
				&& !$current_investment->has_token();
		return $buffer;
	}
	
	public function display_inactive_check() {
		$current_investment = WDGInvestment::current();
		return !$current_investment->has_token();
	}
	
}