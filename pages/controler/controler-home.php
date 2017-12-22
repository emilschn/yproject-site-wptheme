<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Home() );

class WDG_Page_Controler_Home extends WDG_Page_Controler {
	
	private $slider;
	
	private static $nb_query_campaign_funded = 40;
	private static $projects_html_key = 'home-projects';
	private static $projects_html_duration = 180; // 3 minutes de cache
	private static $projects_html_version = 2;
	private static $projects_nb_to_show = 3;
	private $projects_html;
	private $projects_list;
	
	private static $stats_html_key = 'home-projects-stats';
	private static $stats_html_duration = 86400; // 24 heures de cache
	private static $stats_html_version = 2;
	private $stats_html;
	private $stats_list;
	
	public function __construct() {
		parent::__construct();
		if ( ATCF_CrowdFunding::get_platform_context() == 'wedogood' ) {
			date_default_timezone_set("Europe/London");
			define( 'SKIP_BASIC_HTML', TRUE );
			$this->make_slider();
			$this->init_projects();
			$this->prepare_stats();
		}
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
			'slider-prix-fintech.jpg' => "En 2017,<br />WE DO GOOD<br />est le coup de coeur<br />Fintech de l'année",
			'slider-twiza.jpg' => "255 personnes ont investi<br />101 060 € pour propulser<br />les impacts positifs<br />de Twiza",
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
	
/******************************************************************************/
// PROJECT STATS
/******************************************************************************/
	private function prepare_stats() {
		$this->stats_html = $this->get_db_cached_elements( WDG_Page_Controler_Home::$stats_html_key, WDG_Page_Controler_Home::$stats_html_version );
		if ( empty( $this->stats_html ) ) {
			$project_list_funded = ATCF_Campaign::get_list_funded( WDG_Page_Controler_Home::$nb_query_campaign_funded, '', true );
			$count_amount = 0;
			$people_list = array();
			$count_projects = 0;
			$count_roi = 0;
			foreach ( $project_list_funded as $project_post ) {
				$count_projects++;
				$campaign = atcf_get_campaign( $project_post->ID );
				$backers_id_list = $campaign->backers_id_list();
				$people_list = array_merge( $people_list, $backers_id_list );
				$count_amount += $campaign->current_amount( false );
				$declaration_list = $campaign->get_roi_declarations();
				foreach ( $declaration_list as $declaration ) {
					$count_roi += $declaration[ 'total_roi_with_adjustment' ];
				}
			}
			$people_list_unique = array_unique( $people_list );
			$count_people = count( $people_list_unique );
			$count_roi = floor( $count_roi );
			$this->stats_list = array(
				'count_amount'	=> $count_amount,
				'count_people'	=> $count_people,
				'nb_projects'	=> count($project_list_funded),
				'count_roi'		=> $count_roi
			);
		}
	}
	
	public function get_stats_html() {
		return $this->stats_html;
	}
	
	public function set_stats_html( $html ) {
		$this->stats_html = $html;
		$this->set_db_cached_elements( WDG_Page_Controler_Home::$stats_html_key, $html, WDG_Page_Controler_Home::$stats_html_duration, WDG_Page_Controler_Home::$stats_html_version );
	}
	
	public function get_stats_list() {
		return $this->stats_list;
	}
	
}