<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_InvestSignature() );

class WDG_Page_Controler_InvestSignature extends WDG_Page_Controler {
	/**
	 * @var ATCF_Campaign
	 */
	private $current_campaign;
	/**
	 * @var WDGInvestment
	 */
	private $current_investment;
	private $current_step;
	private $current_signature_link;
	private $current_success_next_link;
	
	public function __construct() {
		parent::__construct();
		
		define( 'SKIP_BASIC_HTML', TRUE );
		
		$this->init_current_campaign();
		WDGRoutes::redirect_invest_if_not_logged_in();
		$this->init_current_investment();
		$this->init_current_step();
		$this->init_signature_link();
		$this->init_success_next_link();
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
		$this->current_step = 4;
		$this->current_investment->create_signature();
	}
	public function get_current_step() {
		return $this->current_step;
	}
	
/******************************************************************************/
// LINKS
/******************************************************************************/
	private function init_signature_link() {
		$this->current_signature_link = $this->current_investment->get_signature_url();
	}
	public function get_signature_link() {
		return $this->current_signature_link;
	}
	
	public function init_success_next_link() {
		$this->current_success_next_link = '';
		if ( $this->current_investment->has_token() ) {
			$this->current_success_next_link = $this->current_investment->get_redirection( 'success', $this->current_investment->get_token() );
		} else {
			$this->current_success_next_link = home_url( '/paiement-partager' ). '?campaign_id=' .$this->current_campaign->ID;
		}
	}
	public function get_success_next_link() {
		return $this->current_success_next_link;
	}
	
}