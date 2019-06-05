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