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
		$this->form = new WDG_Form_Invest_Input( $this->current_campaign );
	}
	public function get_form() {
		return $this->form;
	}
	
}