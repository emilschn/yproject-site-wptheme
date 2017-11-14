<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_PreinvestmentFinish() );

class WDG_Page_Controler_PreinvestmentFinish extends WDG_Page_Controler {
	/**
	 * @var ATCF_Campaign
	 */
	private $current_campaign;
	/**
	 *
	 * @var WDGInvestment
	 */
	private $current_investment;
	private $current_step;
	private $form;
	
	public function __construct() {
		parent::__construct();
		
		$core = ATCF_CrowdFunding::instance();
		$core->include_form( 'invest-contract' );
		
		define( 'SKIP_BASIC_HTML', TRUE );
		
		$this->init_current_investment();
		$this->init_current_campaign();
		$this->check_current_preinvestment();
		$this->init_current_step();
		$this->init_form();
	}
	
/******************************************************************************/
// CURRENT INVESTMENT
/******************************************************************************/
	private function init_current_investment() {
		$investment_id = filter_input( INPUT_GET, 'investment_id' );
		if ( !empty( investment_id ) ) {
			$this->current_investment = new WDGInvestment( $investment_id );
		}
	}
	
	/**
	 * Vérifications de sécurité pour être sûr qu'on a le droit de toucher à cet investissement
	 */
	private function check_current_preinvestment() {
		$buffer = TRUE;
		
		// Forcément un utilisateur connecté
		if ( !is_user_logged_in() ) {
			$buffer = FALSE;
		}
		// Seul l'utilisateur qui correspond à cet investissement peut y toucher
		$current_user = wp_get_current_user();
		$saved_user_id = $this->current_investment->get_saved_user_id();
		if ( $saved_user_id != $current_user->ID ) {
			$buffer = FALSE;
		}
		// Il ne peut le modifier que si le statut correspond à un préinvestissement à valider
		if ( $this->current_investment->get_contract_status() != WDGInvestment::$contract_status_preinvestment_validated ) {
			$buffer = FALSE;
		}
		// Il ne peut y toucher que si la campagne est en collecte
		if (  $this->current_campaign->campaign_status() != ATCF_Campaign::$campaign_status_collecte ) {
			$buffer = FALSE;
		}
		
		if ( !$buffer ) {
			wp_redirect( home_url() );
		}
	}
	
	public function get_current_investment() {
		return $this->current_investment;
	}
	
	public function get_current_investment_contract_preview() {
		$current_user = wp_get_current_user();
		$campaign = $this->current_investment->get_saved_campaign();
        $amount_part = $this->current_investment->get_saved_amount();
		$invest_data = array(
			"amount_part"					=> $amount_part,
			"amount"						=> $this->current_investment->get_saved_amount(),
			"total_parts_company"			=> $campaign->total_parts(),
			"total_minimum_parts_company"	=> $campaign->total_minimum_parts(),
			"ip"							=> filter_input( INPUT_SERVER, 'REMOTE_ADDR' )
		);
		
		$user_id = $this->current_investment->get_saved_user_id();
					
		$organization = false;
		if ( WDGOrganization::is_user_organization( $user_id ) ) {
			$organization = new WDGOrganization( $user_id );
		}
		return fillPDFHTMLDefaultContent( $current_user, $campaign, $invest_data, $organization );
	}
	
	public function get_contract_warning() {
		WDG_PDF_Generator::add_shortcodes();
		$edd_settings = get_option( 'edd_settings' );
		return wpautop( $edd_settings[ 'investment_terms' ] );
	}
	
/******************************************************************************/
// CURRENT CAMPAIGN
/******************************************************************************/
	private function init_current_campaign() {
		$this->current_campaign = $this->current_investment->get_saved_campaign();
	}
	
	public function get_current_campaign() {
		return $this->current_campaign;
	}
	
/******************************************************************************/
// CURRENT STEP
/******************************************************************************/
	public static $step_validation = 'validation';
	public static $step_cancel = 'cancel';
	public static $step_confirm_cancel = 'confirm_cancel';
	private function init_current_step() {
		$validate_contract = filter_input( INPUT_GET, 'validate' );
		$cancel_contract = filter_input( INPUT_GET, 'cancel' );
		$confirm_cancel_contract = filter_input( INPUT_GET, 'confirm_cancel' );
		
		$this->current_step = ( !empty( $validate_contract ) ) ? WDG_Page_Controler_PreinvestmentFinish::$step_validation : '';
		if ( empty( $this->current_step ) ) {
			$this->current_step = ( !empty( $cancel_contract ) ) ? WDG_Page_Controler_PreinvestmentFinish::$step_cancel : '';
		}
		if ( empty( $this->current_step ) ) {
			$this->current_step = ( !empty( $confirm_cancel_contract ) ) ? WDG_Page_Controler_PreinvestmentFinish::$step_confirm_cancel : '';
		}
	}
	
	public function get_current_step() {
		return $this->current_step;
	}
	
/******************************************************************************/
// CURRENT FORM
/******************************************************************************/
	private function init_form() {
		// Récupération d'un éventuel post de formulaire
		$action_posted = filter_input( INPUT_POST, 'action' );
		$load_form = TRUE;

		// Analyse formulaire validation contrat
		if ( $action_posted == WDG_Form_Invest_Contract::$name ) {
			$input_nav = filter_input( INPUT_POST, 'nav' );
			if ( $input_nav == 'previous' ) {
				$url = home_url( '/terminer-preinvestissement' );
				$url .= '?cancel=1&investment_id=' . $this->current_investment->get_id();
				wp_redirect( $url );

			} else {
				$WDGCurrent_User = WDGUser::current();
				$this->form = new WDG_Form_Invest_Contract( $this->current_campaign, $WDGCurrent_User->wp_user->ID );
				if ( $this->form->postForm() ) {
					$this->current_investment->set_contract_status( WDGInvestment::$contract_status_investment_validated );
					ypcf_get_updated_payment_status( $this->current_investment->get_id() );
					wp_redirect( home_url( '/paiement-partager' ) . '?campaign_id=' . $this->current_campaign->ID );
				}
			}
			
		// Action d'annulation du paiement
		} elseif ( $this->current_step == WDG_Page_Controler_PreinvestmentFinish::$step_confirm_cancel ) {
			$this->current_investment->set_contract_status( WDGInvestment::$contract_status_investment_refused );
			$this->current_investment->refund();
			$current_user = wp_get_current_user();
			NotificationsEmails::preinvestment_canceled( $current_user, $this->current_investment->get_saved_campaign() );
		}
		
		// Chargement du formulaire à afficher
		if ( $load_form ) {
			$WDGCurrent_User = WDGUser::current();
			$this->form = new WDG_Form_Invest_Contract( $this->current_campaign, $WDGCurrent_User->wp_user->ID );
		}
		
	}
	
	public function get_form() {
		return $this->form;
	}
	
	public function get_form_errors() {
		return $this->form->getPostErrors();
	}
	
	public function get_form_action() {
		$url = home_url( '/terminer-preinvestissement' );
		$url .= '?investment_id=' . $this->current_investment->get_id();
		return $url;
	}
	
}