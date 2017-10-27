<?php
global $page_controler;
$page_controler = new WDG_Page_Controler_InvestShare();

class WDG_Page_Controler_InvestShare extends WDG_Page_Controler {
	/**
	 * @var ATCF_Campaign
	 */
	private $current_campaign;
	
	private $current_step;
	private $current_investment;
	
	public function __construct() {
		parent::__construct();
		
		define( 'SKIP_BASIC_HTML', TRUE );
		
		$this->init_current_campaign();
		$this->init_current_step();
		$this->init_current_investment();
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
	
	public function get_campaign_link() {
		return get_permalink( $this->current_campaign->ID );
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
		$this->current_step = 5;
	}
	public function get_current_step() {
		return $this->current_step;
	}
	
}