<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Launch_Project() ); 

class WDG_Page_Controler_Launch_Project extends WDG_Page_Controler {
	
	public function __construct() {
		parent::__construct();        
        
	}

}