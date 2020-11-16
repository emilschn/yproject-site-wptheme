<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_ProjectList() );

class WDG_Page_Controler_ProjectList extends WDG_Page_Controler {
	
	private $slider_list = array();
	private $stats_list;
	
	private static $filters_html_key = 'projectlist-filters';
	private static $filters_html_duration = 86400; // 24 heures de cache
	private static $filters_html_version = 2;
	private $filters_html;
	private $filters_list;
	
	private static $currentprojects_html_key = 'projectlist-projects-current';
	private static $currentprojects_html_duration = 3600; // 1 heure de cache
	private static $currentprojects_html_version = 2;
	private $currentprojects_html;
	private $currentprojects_list;
	
	private static $positive_savings_projects_html_key = 'projectlist-projects-positive-savings';
	private static $positive_savings_projects_html_duration = 7200; // 2 heures de cache
	private static $positive_savings_projects_html_version = 2;
	private $positive_savings_projects_html;
	private $positive_savings_projects_list;
	
	private static $fundedprojects_html_key = 'projectlist-projects-funded';
	private static $fundedprojects_html_duration = 7200; // 2 heures de cache
	private static $fundedprojects_html_version = 2;
	private $fundedprojects_html;
	private $fundedprojects_list;
	
	public function __construct() {
		parent::__construct();
		date_default_timezone_set("Europe/London");
		define( 'SKIP_BASIC_HTML', TRUE );
		wp_enqueue_script( 'wdg-slideshow', dirname( get_bloginfo( 'stylesheet_url' ) ).'/_inc/js/slideshow.js', array( 'jquery' ), ASSETS_VERSION );
		$this->prepare_slider();
		$this->prepare_stats();
		$this->prepare_filters();
		$this->prepare_currentprojects();
		$this->prepare_positive_savings_projects();
		$this->prepare_fundedprojects();
	}
	
/******************************************************************************/
// SLIDER
/******************************************************************************/
	private function prepare_slider() {
		$db_cacher = WDG_Cache_Plugin::current();
		$slider = $db_cacher->get_cache( WDG_Cache_Plugin::$slider_key, WDG_Cache_Plugin::$slider_version );

		if (!$slider) {
			$this->slider_list = WDG_Cache_Plugin::initialize_most_recent_projects();
		} else {
			$slider_array = json_decode( $slider, true );
			for ( $i = 0 ; $i < 3 ; $i++ ) {
				$img = $slider_array[$i]['img'];
				$title = $slider_array[$i]['title'];
				$link = $slider_array[$i]['link'];
			
				array_push( $this->slider_list, array(
						'img'	=> $img,
						'title'	=> $title,
						'link'	=> $link
					)
				);
			}	
		}	
	}
	
	public function get_slider() {
		return $this->slider_list;
	}
	
/******************************************************************************/
// PROJECT STATS
/******************************************************************************/
	private function prepare_stats() {
		$db_cacher = WDG_Cache_Plugin::current();
		$stats = $db_cacher->get_cache( WDG_Cache_Plugin::$stats_key, WDG_Cache_Plugin::$stats_version );

		if (!$stats) {
			$this->stats_list = WDG_Cache_Plugin::initialize_home_stats();
		} else {
			$stats_array = json_decode($stats, true);
			$this->stats_list = array(
				'count_amount'				=> $stats_array['count_amount'],
				'count_people'				=> $stats_array['count_people'],
				'royaltying_projects'		=> $stats_array['royaltying_projects']
			);
		}
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
				'vote'		=> __( 'project.status.VOTE', 'yproject' ),
				'collecte'	=> __( 'project.status.FUNDING', 'yproject' ),
				'funded'	=> __( 'project.status.FUNDED', 'yproject' )	
			);

			$terms_activity = get_terms('download_category', array( 'slug' => 'types', 'hide_empty' => false ) );
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
				'funding'			=> ATCF_Campaign::get_list_funding( -1, '', TRUE ),
				'funding_after'		=> ATCF_Campaign::get_list_funding( -1, '', TRUE, FALSE ),
				'vote'				=> ATCF_Campaign::get_list_vote( -1, '', TRUE )
			);
		}
		if ( empty( $this->currentprojects_list ) ) {
			$this->currentprojects_list = array(
				'funding_after'		=> ATCF_Campaign::get_list_funding( -1, '', TRUE, FALSE )
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
// POSITIVE SAVINGS PROJECTS
/******************************************************************************/
	private function prepare_positive_savings_projects() {
		$this->positive_savings_projects_html = $this->get_db_cached_elements( WDG_Page_Controler_ProjectList::$positive_savings_projects_html_key, WDG_Page_Controler_ProjectList::$positive_savings_projects_html_version );
		if ( empty( $this->positive_savings_projects_html ) ) {
			$this->positive_savings_projects_list = ATCF_Campaign::get_list_positive_savings( 0 );
		}
	}
	
	public function get_positive_savings_projects_html() {
		return $this->positive_savings_projects_html;
	}
	
	public function set_positive_savings_projects_html( $html ) {
		$this->positive_savings_projects_html = $html;
		$this->set_db_cached_elements( WDG_Page_Controler_ProjectList::$positive_savings_projects_html_key, $html, WDG_Page_Controler_ProjectList::$positive_savings_projects_html_duration, WDG_Page_Controler_ProjectList::$positive_savings_projects_html_version );
	}
	
	public function get_positive_savings_projects_list() {
		return $this->positive_savings_projects_list;
	}
	
/******************************************************************************/
// FUNDED PROJECTS
/******************************************************************************/
	private function prepare_fundedprojects() {
		$nb_key = ceil( WDG_Cache_Plugin::$nb_query_campaign_funded / 5 );
		$this->fundedprojects_html = $this->get_db_cached_elements( WDG_Page_Controler_ProjectList::$fundedprojects_html_key, WDG_Page_Controler_ProjectList::$fundedprojects_html_version );
		if ( empty( $this->fundedprojects_html ) ) {
			$this->fundedprojects_html = '';
			$this->fundedprojects_list = ATCF_Campaign::get_list_funded( WDG_Cache_Plugin::$nb_query_campaign_funded );
			
		} else {
			$this->fundedprojects_html = '';
			for ( $i = 1; $i <= $nb_key; $i++ ) {
				$this->fundedprojects_html .= $this->get_db_cached_elements( WDG_Page_Controler_ProjectList::$fundedprojects_html_key. '_' .$i, WDG_Page_Controler_ProjectList::$fundedprojects_html_version );
			}
		}
	}
	
	public function get_fundedprojects_html() {
		return $this->fundedprojects_html;
	}
	
	public function set_fundedprojects_html( $html, $index ) {
		$this->fundedprojects_html .= $html;
		$this->set_db_cached_elements( WDG_Page_Controler_ProjectList::$fundedprojects_html_key, '1', WDG_Page_Controler_ProjectList::$fundedprojects_html_duration, WDG_Page_Controler_ProjectList::$fundedprojects_html_version );
		$this->set_db_cached_elements( WDG_Page_Controler_ProjectList::$fundedprojects_html_key. '_' .$index, $html, WDG_Page_Controler_ProjectList::$fundedprojects_html_duration, WDG_Page_Controler_ProjectList::$fundedprojects_html_version );
	}
	
	public function get_fundedprojects_list() {
		return $this->fundedprojects_list;
	}
	
}