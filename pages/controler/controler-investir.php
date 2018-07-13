<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Invest() );

class WDG_Page_Controler_Invest extends WDG_Page_Controler {
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
	 * @var WDGInvestment
	 */
	private $current_investment;
	
	private $amount_first_contract;
	private $amount_second_contract;
	private $can_use_wallet;
	private $can_use_card_and_wallet;
	
	private $current_step;
	private $form;
	
	public function __construct() {
		parent::__construct();
		
		$core = ATCF_CrowdFunding::instance();
		$core->include_form( 'invest-input' );
		$core->include_form( 'invest-user-details' );
		$core->include_form( 'invest-contract' );
		
		date_default_timezone_set( "Europe/London" );
		define( 'SKIP_BASIC_HTML', TRUE );
		
		$this->init_current_campaign();
		WDGRoutes::redirect_invest_if_not_logged_in();
		WDGRoutes::redirect_invest_if_project_not_investable();
		WDGRoutes::redirect_invest_if_investment_not_initialized();
		
		$this->init_current_investment();
		$this->init_current_step();
		$this->init_form();
		$this->init_can_use_wallet();
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
			$this->current_campaign_organization = new WDGOrganization( $campaign_organization->wpref, $campaign_organization );
		}
		return $this->current_campaign_organization->get_name();
	}
	
	public function get_campaign_investors_number() {
		$current_number = $this->current_campaign->backers_count();
		return $current_number + 1;
	}
	
	public function get_campaign_current_amount() {
		return $this->current_campaign->current_amount( FALSE );
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
	
	public function needs_two_contracts() {
		if ( $_SESSION[ 'redirect_current_user_type' ] != 'user' ) {
			$WDGInvestorEntity = new WDGOrganization( $_SESSION[ 'redirect_current_user_type' ] );
		} else {
			$WDGInvestorEntity = WDGUser::current();
		}
		$amount_part = $_SESSION[ 'redirect_current_amount_part' ];
		$WDGCurrent_User_Investments = new WDGUserInvestments( $WDGInvestorEntity );
		return ( $amount_part > $WDGCurrent_User_Investments->get_maximum_investable_amount_without_alert() );
	}
	
	public function get_current_investment_contract_preview( $first_contract = TRUE ) {
		$current_user = wp_get_current_user();
		$campaign = $this->current_campaign;
		$part_value = $campaign->part_value();
		$amount = $_SESSION[ 'redirect_current_amount_part' ];
		if ( $this->needs_two_contracts() ) {
			if ( $first_contract ) {
				$amount = $this->get_first_contract_amount();
			} else {
				$amount = $this->get_second_contract_amount();
			}
		}
        $amount_part = ( $amount === FALSE ) ? 0 : $amount / $part_value;
		
		$invest_data = array(
			"amount_part"					=> $amount_part,
			"amount"						=> $amount,
			"total_parts_company"			=> $campaign->total_parts(),
			"total_minimum_parts_company"	=> $campaign->total_minimum_parts(),
			"ip"							=> filter_input( INPUT_SERVER, 'REMOTE_ADDR' )
		);
		
		$organization = false;
		if ( $_SESSION[ 'redirect_current_user_type' ] != 'user' ) {
			$organization = new WDGOrganization( $_SESSION[ 'redirect_current_user_type' ] );
		}
		return fillPDFHTMLDefaultContent( $current_user, $campaign, $invest_data, $organization, true );
	}
	
	public function get_first_contract_amount( $with_wallet = FALSE ) {
		if ( !isset( $this->amount_first_contract ) ) {
			$amount_wallet = 0;
			if ( $_SESSION[ 'redirect_current_user_type' ] != 'user' ) {
				$WDGInvestorEntity = new WDGOrganization( $_SESSION[ 'redirect_current_user_type' ] );
				if ( $with_wallet ) {
					$amount_wallet = $WDGInvestorEntity->get_available_rois_amount();
				}
				
			} else {
				$WDGInvestorEntity = WDGUser::current();
				if ( $with_wallet ) {
					$amount_wallet = $WDGInvestorEntity->get_lemonway_wallet_amount();
				}
			}
			$WDGCurrent_User_Investments = new WDGUserInvestments( $WDGInvestorEntity );
			$this->amount_first_contract = $WDGCurrent_User_Investments->get_maximum_investable_amount_without_alert() + $amount_wallet;
		}
		return $this->amount_first_contract;
	}
	
	public function get_second_contract_amount( $with_wallet = FALSE ) {
		if ( !isset( $this->amount_second_contract ) ) {
			$amount = $_SESSION[ 'redirect_current_amount_part' ];
			$this->amount_second_contract = $amount - $this->get_first_contract_amount( $with_wallet );
		}
		return $this->amount_second_contract;
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
	
	/**
	 * Détermine si la fenêtre d'avertissement est visible :
	 * - sur la première page du processus
	 * - quand on y vient pour la première fois
	 * - si on est en collecte
	 * @return boolean
	 */
	public function is_warning_visible() {
		$invest_start = filter_input( INPUT_GET, 'invest_start' );
		return ( $this->current_step == 1 && $invest_start == '1' && $this->current_campaign->campaign_status() == ATCF_Campaign::$campaign_status_collecte );
	}
	public function get_warning_content() {
		$edd_settings = get_option( 'edd_settings' );
		return wpautop( $edd_settings[ 'investment_generalities' ] );
	}
	
	public function get_contract_warning() {
		WDG_PDF_Generator::add_shortcodes();
		$edd_settings = get_option( 'edd_settings' );
		return wpautop( $edd_settings[ 'investment_terms' ] );
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
			ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 1 >> $action_posted != WDG_Form_Invest_Input::$name && !$current_investment->is_session_correct()' );
		}
		$reload_form = FALSE;
		
		switch ( $action_posted ) {
			// Analyse formulaire saisie montant
			case WDG_Form_Invest_Input::$name:
				$this->form = new WDG_Form_Invest_Input( $this->current_campaign );
				if ( $this->form->postForm() ) {
					$this->current_step = 2;
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 2 >> WDG_Form_Invest_Input::$name POSTED' );
					$reload_form = TRUE;
				}
				break;
			
			// Analyse formulaire saisie infos
			case WDG_Form_Invest_User_Details::$name:
				$input_nav = filter_input( INPUT_POST, 'nav' );
				if ( $input_nav == 'previous' ) {
					$this->current_step = 1;
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 1 >> WDG_Form_Invest_User_Details::$name PREVIOUS' );
					$reload_form = TRUE;
					
				} else {
					$this->current_step = 2;
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 2 >> WDG_Form_Invest_User_Details::$name POSTED' );
					$WDGCurrent_User = WDGUser::current();
					$this->form = new WDG_Form_Invest_User_Details( $this->current_campaign, $WDGCurrent_User->wp_user->ID );
					if ( $this->form->postForm() ) {
						$this->current_step = 3;
						ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 3 >> GOTO WDG_Form_Invest_Contract' );
						$reload_form = TRUE;
					}
				}
				break;
				
			// Analyse formulaire validation contrat
			case WDG_Form_Invest_Contract::$name:
				$input_nav = filter_input( INPUT_POST, 'nav' );
				if ( $input_nav == 'previous' ) {
					$this->current_step = 2;
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 2 >> WDG_Form_Invest_Contract::$name PREVIOUS' );
					$reload_form = TRUE;
					
				} else {
					$this->current_step = 3;
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 3 >> WDG_Form_Invest_Contract::$name POSTED' );
					$WDGCurrent_User = WDGUser::current();
					$this->form = new WDG_Form_Invest_Contract( $this->current_campaign, $WDGCurrent_User->wp_user->ID );
					if ( $this->form->postForm() ) {
						ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> GOTO moyen-de-paiement' );
						wp_redirect( home_url( '/moyen-de-paiement/' ) . '?campaign_id=' . $this->current_campaign->ID. '&meanofpayment=' .$this->form->getMeanOfPayment() );
					}
				}
				break;
				
			// Chargement formulaire saisie montant, si rien en cours
			default:
				$init_invest = FALSE;
				if ( $this->current_campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote ) {
					$WDGCurrent_User = WDGUser::current();
					$init_invest = $WDGCurrent_User->get_amount_voted_on_campaign( $this->current_campaign->ID );
				} else {
					$init_invest = filter_input( INPUT_GET, 'init_invest' );
				}
				ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> START >> WDG_Form_Invest_Input::$name' );
				$this->form = new WDG_Form_Invest_Input( $this->current_campaign, $init_invest );
				break;
		}
		
		// Chargement du formulaire à afficher
		if ( $reload_form ) {
			$WDGCurrent_User = WDGUser::current();
			switch ( $this->current_step ) {
				case 1:
					$amount_voted_on_campaign = FALSE;
					if ( $this->current_campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote ) {
						$WDGCurrent_User = WDGUser::current();
						$amount_voted_on_campaign = $WDGCurrent_User->get_amount_voted_on_campaign( $this->current_campaign->ID );
					}
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> LOAD WDG_Form_Invest_Input' );
					$this->form = new WDG_Form_Invest_Input( $this->current_campaign, $amount_voted_on_campaign );
					break;
				case 2:
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> LOAD WDG_Form_Invest_User_Details' );
					$this->form = new WDG_Form_Invest_User_Details( $this->current_campaign, $WDGCurrent_User->wp_user->ID );
					break;
				case 3:
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> LOAD WDG_Form_Invest_Contract' );
					$this->form = new WDG_Form_Invest_Contract( $this->current_campaign, $WDGCurrent_User->wp_user->ID );
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
		$url = home_url( '/investir/' );
		$url .= '?campaign_id=' . $this->current_campaign->ID;
		return $url;
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
		
		if ( $_SESSION[ 'redirect_current_user_type' ] != 'user' ) {
			$WDGInvestorEntity = new WDGOrganization( $_SESSION[ 'redirect_current_user_type' ] );
		} else {
			$WDGInvestorEntity = WDGUser::current();
		}
		$WDGCurrent_User_Investments = new WDGUserInvestments( $WDGInvestorEntity );
		if ( $WDGCurrent_User_Investments->can_invest_nb() != TRUE ) {
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
		return ( $this->current_investment->get_session_amount() >= WDG_Page_Controler_Invest::$display_card_alert_amount );
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
		return !$this->current_investment->has_token();
	}
	
}