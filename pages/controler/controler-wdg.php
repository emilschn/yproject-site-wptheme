<?php
class WDG_Page_Controler_WDG extends WDG_Page_Controler {
	
	public function __construct() {
		parent::__construct();
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		wp_deregister_script( 'wp-embed.min.js' );
		wp_deregister_script( 'contact-form-7' );
		wp_deregister_style( 'contact-form-7' );
	}

}