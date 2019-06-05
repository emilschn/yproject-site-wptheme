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
	
	public function __construct() {
		parent::__construct();
		ypcf_debug_log( 'WDG_Page_Controler_DeclarationInput::__construct' );
		
		define( 'SKIP_BASIC_HTML', TRUE );
		
		$this->can_access = TRUE;
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
		}
		
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
	
}