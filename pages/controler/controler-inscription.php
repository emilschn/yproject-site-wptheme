<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Register() );

class WDG_Page_Controler_Register extends WDG_Page_Controler {
	
	public function __construct() {
		parent::__construct();
		
		if ( is_user_logged_in() ) {
			ypcf_debug_log( 'WDG_Page_Controler_Register::is_user_logged_in' );
			wp_redirect( WDGUser::get_login_redirect_page( '#' ) );
			exit();
		}
	}
		
	
}