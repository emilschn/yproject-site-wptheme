<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_User_Capacity() );

class WDG_Page_Controler_User_Capacity extends WDG_Page_Controler {
	public function __construct() {
		parent::__construct();

		define( 'SKIP_BASIC_HTML', TRUE );

		parent::init_redirect_url_by_language();

		// on récupère le composant Vue
		$WDG_Vue_Components = WDG_Vue_Components::instance();
		$WDG_Vue_Components->enqueue_component( WDG_Vue_Components::$component_user_investment_capacity );
	}
	
	/**
	 * Surcharge de la fonction parente qui ne semble pas fonctionner ?
	 */
	public function get_init_locale() {
		// Si on est avec WPML, on se sert de la liste des langues pour récupérer la langue active
		if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
			$active_languages = apply_filters( 'wpml_active_languages', NULL );
			foreach ( $active_languages as $language_key => $language_item ) {
				if ( $language_item[ 'active' ] ) {
					return $language_item[ 'code' ];
				}
			}
		}
		return 'fr';
	}
}