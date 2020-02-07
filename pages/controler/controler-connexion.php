<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Connection() );

class WDG_Page_Controler_Connection extends WDG_Page_Controler {
	
	private $login_error_reason;
	private $display_alert_project;
	private $login_init;
	
	public function __construct() {
		parent::__construct();
		
		if ( is_user_logged_in() ) {
			ypcf_debug_log( 'WDG_Page_Controler_Connection::is_user_logged_in' );
			wp_redirect( WDGUser::get_login_redirect_page( '#' ) );
			exit();
		}
		
		//Cas particulier cause cache :
		// Si on se connecte par facebook sur la page d'accueil ou la liste des projets, 
		// => la redirection directe ne fonctionne pas (rechargement de la page en cache)
		// Solution temporaire : dans ces cas spécifiques, on redirige vers Mon compte
		if ( WDGFormUsers::login_facebook() ) {
			$referer_url = wp_get_referer();
			if ( $referer_url == home_url( '/' ) || $referer_url == home_url( '/les-projets/' ) ) {
				ypcf_debug_log( 'WDG_Page_Controler_Connection::login_facebook > mon-compte' );
				wp_redirect( home_url( '/mon-compte/#' ) );
			} else {
				ypcf_debug_log( 'WDG_Page_Controler_Connection::login_facebook > #' );
				wp_redirect( WDGUser::get_login_redirect_page( '#' ) );
			}
			exit();
		}
		
		ypcf_session_start();
		$_SESSION[ 'login-fb-referer' ] = WDGUser::get_login_redirect_page();
		$input_redirect_invest = filter_input( INPUT_GET, 'redirect-invest' );
		if ( !empty( $input_redirect_invest ) ) {
			$invest_url = home_url( '/investir/?campaign_id=' .$input_redirect_invest. '&amp;invest_start=1' );
			$_SESSION[ 'login-fb-referer' ] = $invest_url;
		}

		$WDG_Vue_Components = WDG_Vue_Components::instance();
		$WDG_Vue_Components->enqueue_component( WDG_Vue_Components::$component_signin_signup );
		
		$input_source = filter_input( INPUT_GET, 'source' );
		$this->display_alert_project = ( $input_source == 'project' );
	}
	
	public function get_display_alert_project() {
		return $this->display_alert_project;
	}
	
}