<?php
class WDG_Page_Controler {
	
	private $db_cache_manager;
	
	public function __construct() {
		date_default_timezone_set("Europe/Paris");
		$this->db_cache_manager = new WDG_Cache_Plugin();
	}
	
	public function get_db_cached_elements( $key, $version ) {
		return $this->db_cache_manager->get_cache( $key, $version );
	}
	
	public function set_db_cached_elements( $key, $value, $duration, $version ) {
		$this->db_cache_manager->set_cache( $key, $value, $duration, $version );
	}
	
}