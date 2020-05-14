<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_ProspectSetup() );

class WDG_Page_Controler_ProspectSetup extends WDG_Page_Controler {

	private $guid;
	
	public function __construct() {
		parent::__construct();
		
		define( 'SKIP_BASIC_HTML', TRUE );
		
		// on récupère le composant Vue
		$WDG_Vue_Components = WDG_Vue_Components::instance();
		$WDG_Vue_Components->enqueue_component( WDG_Vue_Components::$component_prospect_setup );

		$this->guid = filter_input( INPUT_GET, 'guid' );
	}

	public function has_init_guid() {
		return ( !empty( $this->guid ) );
	}

	public function get_init_guid() {
		return $this->guid;
	}
	
}