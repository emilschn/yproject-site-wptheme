<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Authentication() );

class WDG_Page_Controler_Authentication extends WDG_Page_Controler {
	public function __construct() {
		parent::__construct();

		define( 'SKIP_BASIC_HTML', TRUE );

		// on récupère le composant Vue
		$WDG_Vue_Components = WDG_Vue_Components::instance();
		$WDG_Vue_Components->enqueue_component( WDG_Vue_Components::$component_account_authentication );
	}
}