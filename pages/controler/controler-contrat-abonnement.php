<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_contrat_abonnement() );

class WDG_Page_Controler_contrat_abonnement extends WDG_Page_Controler {
   
    public function __construct() {
        parent::__construct();
		$id_subscription = filter_input(INPUT_GET,'id_subscription');
		$subscription = new WDGSUBSCRIPTION($id_subscription);
		
    }


    /******************************************************************************/
	// NOM DE LA CAMPAGNE
	/******************************************************************************/
	public function get_current_campaign() {
		return $this->current_campaign;
	}

    
    /******************************************************************************/
	// AFFICHAGE DU CONTRAT
	/******************************************************************************/
    public function get_current_investment_contract_preview() {
	
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