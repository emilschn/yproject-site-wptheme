<?php
global $page_controler;

class WDG_Page_Controler_ProjectList extends WDG_Page_Controler {
	
	private $slider;
	
	private static $stats_html_key = 'projectlist-projects-stats';
	private static $stats_html_duration = 60*60*24; // 24 heures de cache
	private static $stats_html_version = 2;
	private $stats_html;
	private $stats_list;
	
	private static $filters_html_key = 'projectlist-filters';
	private static $filters_html_duration = 60*60*24; // 24 heures de cache
	private static $filters_html_version = 1;
	private $filters_html;
	private $filters_list;
	
	private static $currentprojects_html_key = 'projectlist-projects-current';
	private static $currentprojects_html_duration = 60*60*2; // 24 heures de cache
	private static $currentprojects_html_version = 1;
	private $currentprojects_html;
	private $currentprojects_list;
	
	private static $fundedprojects_html_key = 'projectlist-projects-funded';
	private static $fundedprojects_html_duration = 60*60*2; // 24 heures de cache
	private static $fundedprojects_html_version = 1;
	private $fundedprojects_html;
	private $fundedprojects_list;
	
	public function __construct() {
		parent::__construct();
		date_default_timezone_set("Europe/London");
		define( 'SKIP_BASIC_HTML', TRUE );
		$this->prepare_slider();
		$this->prepare_stats();
		$this->prepare_filters();
		$this->prepare_currentprojects();
		$this->prepare_fundedprojects();
	}
	
/******************************************************************************/
// SLIDER
/******************************************************************************/
	private function prepare_slider() {
		$list_projects = ATCF_Campaign::get_list_most_recent( 3 );
		$this->slider = array();
		foreach ( $list_projects as $project_id ) {
			$campaign = atcf_get_campaign( $project_id );
			$img = $campaign->get_home_picture_src();
			array_push( $this->slider, array(
					'img'	=> $img,
					'title'	=> $campaign->data->post_title,
					'link'	=> get_permalink( $project_id )
				)
			);
		}
	}
	
	public function get_slider() {
		return $this->slider;
	}
	
/******************************************************************************/
// PROJECT STATS
/******************************************************************************/
	private function prepare_stats() {
		$this->stats_html = $this->get_db_cached_elements( WDG_Page_Controler_ProjectList::$stats_html_key, WDG_Page_Controler_ProjectList::$stats_html_version );
		if ( empty( $this->stats_html ) ) {
			$project_list_funded = ATCF_Campaign::get_list_funded( 30 );
			$count_amount = 0;
			$people_list = array();
			$count_projects = 0;
			foreach ( $project_list_funded as $project_post ) {
				$count_projects++;
				$campaign = atcf_get_campaign( $project_post->ID );
				$backers_id_list = $campaign->backers_id_list();
				$people_list = array_merge( $people_list, $backers_id_list );
				$count_amount += $campaign->current_amount( false );
			}
			$people_list_unique = array_unique( $people_list );
			$count_people = count( $people_list_unique );
			$this->stats_list = array(
				'count_amount'	=> $count_amount,
				'count_people'	=> $count_people,
				'nb_projects'	=> count($project_list_funded),
			);
		}
	}
	
	public function get_stats_html() {
		return $this->stats_html;
	}
	
	public function set_stats_html( $html ) {
		$this->stats_html = $html;
		$this->set_db_cached_elements( WDG_Page_Controler_ProjectList::$stats_html_key, $html, WDG_Page_Controler_ProjectList::$stats_html_duration, WDG_Page_Controler_ProjectList::$stats_html_version );
	}
	
	public function get_stats_list() {
		return $this->stats_list;
	}
	
/******************************************************************************/
// FILTERS
/******************************************************************************/
	private function prepare_filters() {
		$this->filters_html = $this->get_db_cached_elements( WDG_Page_Controler_ProjectList::$filters_html_key, WDG_Page_Controler_ProjectList::$filters_html_version );
		if ( empty( $this->filters_html ) ) {
			$this->filters_list = array();

			$terms_category = get_terms( 'download_category', array( 'slug' => 'categories', 'hide_empty' => false ) );
			$term_category_id = $terms_category[0]->term_id;
			$this->filters_list[ 'impacts' ] = get_terms( 'download_category', array(
				'child_of' => $term_category_id,
				'hierarchical' => false,
				'hide_empty' => false
			) );

			$this->filters_list[ 'regions' ] = atcf_get_regions();

			$this->filters_list[ 'status' ] = array(
				'vote'		=> __( "En vote", 'yproject' ),
				'collecte'	=> __( "En financement", 'yproject' ),
				'funded'	=> __( "Financ&eacute;", 'yproject' )	
			);

			$terms_activity = get_terms('download_category', array( 'slug' => 'activities', 'hide_empty' => false ) );
			$term_activity_id = $terms_activity[0]->term_id;
			$this->filters_list[ 'activities' ] = get_terms( 'download_category', array(
				'child_of' => $term_activity_id,
				'hierarchical' => false,
				'hide_empty' => false
			) );
		}
	}
	
	public function get_filters_html() {
		return $this->filters_html;
	}
	
	public function set_filters_html( $html ) {
		$this->filters_html = $html;
		$this->set_db_cached_elements( WDG_Page_Controler_ProjectList::$filters_html_key, $html, WDG_Page_Controler_ProjectList::$filters_html_duration, WDG_Page_Controler_ProjectList::$filters_html_version );
	}
	
	public function get_filters_list() {
		return $this->filters_list;
	}
	
/******************************************************************************/
// CURRENT PROJECTS
/******************************************************************************/
	private function prepare_currentprojects() {
		$this->currentprojects_html = $this->get_db_cached_elements( WDG_Page_Controler_ProjectList::$currentprojects_html_key, WDG_Page_Controler_ProjectList::$currentprojects_html_version );
		if ( empty( $this->currentprojects_html ) ) {
			$this->currentprojects_list = array(
				'funding'	=> ATCF_Campaign::get_list_funding( ),
				'vote'		=> ATCF_Campaign::get_list_vote( )
			);
		}
	}
	
	public function get_currentprojects_html() {
		return $this->currentprojects_html;
	}
	
	public function set_currentprojects_html( $html ) {
		$this->currentprojects_html = $html;
		$this->set_db_cached_elements( WDG_Page_Controler_ProjectList::$currentprojects_html_key, $html, WDG_Page_Controler_ProjectList::$currentprojects_html_duration, WDG_Page_Controler_ProjectList::$currentprojects_html_version );
	}
	
	public function get_currentprojects_list() {
		return $this->currentprojects_list;
	}
	
/******************************************************************************/
// FUNDED PROJECTS
/******************************************************************************/
	private function prepare_fundedprojects() {
		$this->fundedprojects_html = $this->get_db_cached_elements( WDG_Page_Controler_ProjectList::$fundedprojects_html_key, WDG_Page_Controler_ProjectList::$fundedprojects_html_version );
		if ( empty( $this->fundedprojects_html ) ) {
			$this->fundedprojects_list = ATCF_Campaign::get_list_funded( 30 );
		}
	}
	
	public function get_fundedprojects_html() {
		return $this->fundedprojects_html;
	}
	
	public function set_fundedprojects_html( $html ) {
		$this->fundedprojects_html = $html;
		$this->set_db_cached_elements( WDG_Page_Controler_ProjectList::$fundedprojects_html_key, $html, WDG_Page_Controler_ProjectList::$fundedprojects_html_duration, WDG_Page_Controler_ProjectList::$fundedprojects_html_version );
	}
	
	public function get_fundedprojects_list() {
		return $this->fundedprojects_list;
	}
	
}

$page_controler = new WDG_Page_Controler_ProjectList();