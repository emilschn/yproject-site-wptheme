<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_InvestShare() );

class WDG_Page_Controler_InvestShare extends WDG_Page_Controler {
	/**
	 * @var ATCF_Campaign
	 */
	private $current_campaign;
	
	private $current_step;
	private $current_investment;
	
	private $can_display_form;
	private $form;
	
	public function __construct() {
		parent::__construct();
		
		define( 'SKIP_BASIC_HTML', TRUE );
		
		$this->init_current_campaign();
		$this->init_current_step();
		$this->init_current_investment();
		$this->init_form_polls();
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
		$this->current_step = 5;
	}
	public function get_current_step() {
		return $this->current_step;
	}
	
/******************************************************************************/
// CURRENT STEP
/******************************************************************************/
	private function init_form_polls() {
		$this->can_display_form = FALSE;
		if ( is_user_logged_in() ) {
			$WDGCurrent_User = WDGUser::current();
			$poll_answers = WDGWPREST_Entity_PollAnswer::get_list( $WDGCurrent_User->get_api_id(), $this->current_campaign->get_api_id() );
			if ( empty( $poll_answers ) ) {
				$this->can_display_form = TRUE;
				$core = ATCF_CrowdFunding::instance();
				$core->include_form( 'invest-poll' );
				$this->form = new WDG_Form_Invest_Poll( $this->current_campaign->ID, $WDGCurrent_User->wp_user->ID );
				if ( $this->form->isPosted() && $this->form->postForm( $this->current_investment->get_session_amount() ) ) {
					$this->can_display_form = FALSE;
				}
			}
		}
	}
	
	public function can_display_form() {
		return $this->can_display_form;
	}
	public function get_form() {
		return $this->form;
	}
	
	public function get_form_errors() {
		return $this->form->getPostErrors();
	}
	
	public function get_form_action() {
		$url = home_url( '/paiement-partager/' ). '?campaign_id=' .$this->current_campaign->ID;
		return $url;
	}
	
}