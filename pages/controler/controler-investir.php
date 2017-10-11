<?php
global $page_controler;
$page_controler = new WDG_Page_Controler_Invest();

class WDG_Page_Controler_Invest extends WDG_Page_Controler {
	/**
	 * @var ATCF_Campaign
	 */
	private $current_campaign;
	/**
	 * @var WDGInvestment
	 */
	private $current_investment;
	
	private $current_step;
	private $form;
	
	public function __construct() {
		parent::__construct();
		
		$core = ATCF_CrowdFunding::instance();
		$core->include_form( 'invest-input' );
		$core->include_form( 'invest-user-details' );
		
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
	
/******************************************************************************/
// CURRENT INVESTMENT
/******************************************************************************/
	private function init_current_investment() {
		$this->current_investment = WDGInvestment::current();
	}
	
	public function get_current_investment() {
		return $this->current_investment;
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
// CURRENT STEP
/******************************************************************************/
	private function init_form() {
		$action_posted = filter_input( INPUT_POST, 'action' );
		
		$this->form = new WDG_Form_Invest_Input( $this->current_campaign );
		
		switch ( $action_posted ) {
			case WDG_Form_Invest_Input::$name:
				if ( $this->form->postForm() ) {
					$this->current_step = 2;
				}
				break;
		}
		
		switch ( $this->current_step ) {
			case 2:
				$WDGCurrent_User = WDGUser::current();
				$this->form = new WDG_Form_Invest_User_Details( $this->current_campaign, $WDGCurrent_User->wp_user->ID );
				break;
		}
		
	}
	public function get_form() {
		return $this->form;
	}
	public function get_form_errors() {
		return $this->form->getPostErrors();
	}
	
}