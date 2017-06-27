<?php
class WDG_Page_Controler {
	
	private $db_cache_manager;
	private $page_title;
	
	public function __construct() {
		ypcf_session_start();
		date_default_timezone_set("Europe/Paris");
		global $stylesheet_directory_uri;
		$stylesheet_directory_uri = get_stylesheet_directory_uri();
		$this->db_cache_manager = new WDG_Cache_Plugin();
		$this->init_page_title();
	}
	
	public function get_db_cached_elements( $key, $version ) {
		return $this->db_cache_manager->get_cache( $key, $version );
	}
	
	public function set_db_cached_elements( $key, $value, $duration, $version ) {
		$this->db_cache_manager->set_cache( $key, $value, $duration, $version );
	}
	
	/**
	 * Retourne le titre de la page
	 * @return string
	 */
	public function get_page_title() {
		return $this->page_title;
	}
	
	private function init_page_title() {
		if ( is_category() ) {
			global $cat;
			$this_category = get_category($cat);
			$this_category_name = $this_category->name;
			$name_exploded = explode('cat', $this_category_name);
			$campaign_post = get_post($name_exploded[1]);
			$this->page_title = 'Actualit&eacute;s du projet ' . (is_object($campaign_post) ? $campaign_post->post_title : '') . ' | ' . get_bloginfo( 'name' );
			
		} else {
			$this->page_title = wp_title( '|', false, 'right' ) . get_bloginfo( 'name' );
		}
	}
	
}