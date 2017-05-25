<?php
global $page_controler;

class WDG_Page_Controler_Home extends WDG_Page_Controler {
	
	private $slider;
	
	public function __construct() {
		parent::__construct();
		date_default_timezone_set("Europe/Paris");
		$this->make_slider();
	}
	
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
	
}

$page_controler = new WDG_Page_Controler_Home();