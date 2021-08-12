<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_contrat_abonnement() );

class WDG_Page_Controler_contrat_abonnement extends WDG_Page_Controler {
   
	private $campaign;
	private $subscription;

    public function __construct() {
        parent::__construct();
		$id_subscription = filter_input(INPUT_GET,'id_subscription');
		$this->subscription = new WDGSUBSCRIPTION($id_subscription);
		$id_project = $this->subscription->id_project;
		$this->campaign = new ATCF_Campaign(false, $id_project);
		// var_dump($this->subscription); die();
    }


    /******************************************************************************/
	// RECAP ABONNEMENT
	/******************************************************************************/
    public function get_contract_warning() {

		if ($this->subscription->amount_type == 'part_royalties')
		echo ('la totalité de vos roaylties ');
		else{
			echo ($this->subscription->amount);
		}
		$this->subscription->start_date = new DateTime();
		echo $this->subscription->start_date->format('N/d/Y ');
		// var_dump($this->campaign->data); die();
		// echo $this->campaign;
		echo ('porte-monnaie ');
		echo ('trimestriel');
	}


    /******************************************************************************/
	// AFFICHAGE DU CONTRAT
	/******************************************************************************/
    public function get_current_investment_contract_preview() {
		$current_user = wp_get_current_user();
		$part_value = $this->campaign->part_value();
		$amount = $this->subscription->amount;
		$amount_part = ( $amount === FALSE ) ? 0 : $amount / $part_value;

		$invest_data = array(
			"amount_part"					=> $amount_part,
			"amount"						=> $amount,
			"total_parts_company"			=> $this->campaign->total_parts(),
			"total_minimum_parts_company"	=> $this->campaign->total_minimum_parts(),
			"ip"							=> filter_input( INPUT_SERVER, 'REMOTE_ADDR' )
		);

		$organization = false;
		if ( $_SESSION[ 'redirect_current_user_type' ] != 'user' ) {
			$organization = new WDGOrganization( $_SESSION[ 'redirect_current_user_type' ] );
		}

		return fillPDFHTMLDefaultContent( $current_user, $this->campaign, $invest_data, $organization, true );
	}

    /******************************************************************************/
	// Gestion des erreurs
	/******************************************************************************/
    public function get_form_errors() {
		return $this->form->getPostErrors();
	}

    /******************************************************************************/
	// VALIDATION DU CONTRAT
	/******************************************************************************/
    public function get_form_action() {
		return WDG_Redirect_Engine::override_get_page_url( 'mon-compte' ). '#subscription';
	}
}