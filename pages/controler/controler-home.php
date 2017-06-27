<?php
global $page_controler;
$page_controler = new WDG_Page_Controler_Home();

class WDG_Page_Controler_Home extends WDG_Page_Controler {
	
	private $slider;
	
	private static $projects_html_key = 'home-projects';
	private static $projects_html_duration = 180; // 3 minutes de cache
	private static $projects_html_version = 2;
	private static $projects_nb_to_show = 3;
	private $projects_html;
	private $projects_list;
	
	public function __construct() {
		parent::__construct();
		date_default_timezone_set("Europe/London");
		define( 'SKIP_BASIC_HTML', TRUE );
		$this->make_slider();
		$this->init_projects();
	}
	
/******************************************************************************/
// SLIDER
/******************************************************************************/
	public function get_slider() {
		return $this->slider;
	}
	
	private function make_slider() {
		$this->slider = array(
			'slider-01.jpg' => "Nous activons<br />une finance à impact positif<br />en développant<br />les levées de fonds en royalties",
			'slider-prixFintech.jpg' => "En 2017,<br />WE DO GOOD<br />est le coup de coeur<br />Fintech de l'année",
			'slider-03.jpg' => "142 personnes ont investi<br />90 000 € pour propulser<br />les impacts positifs<br />de Naoden",
		);
	}
	
/******************************************************************************/
// PROJECT LIST
/******************************************************************************/
	private function init_projects() {
		$this->projects_html = $this->get_db_cached_elements( WDG_Page_Controler_Home::$projects_html_key, WDG_Page_Controler_Home::$projects_html_version );
		if ( empty( $this->projects_html ) ) {
			$this->projects_list = ATCF_Campaign::get_list_most_recent( WDG_Page_Controler_Home::$projects_nb_to_show );
		}
	}
	
	public function get_projects_html() {
		return $this->projects_html;
	}
	
	public function set_projects_html( $html ) {
		$this->projects_html = $html;
		$this->set_db_cached_elements( WDG_Page_Controler_Home::$projects_html_key, $html, WDG_Page_Controler_Home::$projects_html_duration, WDG_Page_Controler_Home::$projects_html_version );
	}
	
	public function get_projects_list() {
		return $this->projects_list;
	}
	
}