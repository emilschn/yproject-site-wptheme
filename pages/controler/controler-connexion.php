<?php
global $page_controler;
$page_controler = new WDG_Page_Controler_Connection();

class WDG_Page_Controler_Connection extends WDG_Page_Controler {
	
	public function __construct() {
		parent::__construct();
		
		if ( WDGFormUsers::login_facebook() || is_user_logged_in() ) {
			wp_redirect( WDGUser::get_login_redirect_page() . '#' );
			exit();
		}
	}
	
}