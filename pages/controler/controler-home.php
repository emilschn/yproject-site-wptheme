<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Home() );

class WDG_Page_Controler_Home extends WDG_Page_Controler {
	
	private $projects_list;
	private $stats_list;
	
	public function __construct() {
		parent::__construct();
		if ( ATCF_CrowdFunding::get_platform_context() == 'wedogood' ) {
			date_default_timezone_set("Europe/London");
			define( 'SKIP_BASIC_HTML', TRUE );
			$this->init_projects();
			$this->prepare_stats();
		}
	}
	
/******************************************************************************/
// PROJECT LIST
/******************************************************************************/
	private function init_projects() {
		$db_cacher = WDG_Cache_Plugin::current();
		$projects = $db_cacher->get_cache( WDG_Cache_Plugin::$projects_key, WDG_Cache_Plugin::$projects_version );

		$this->projects_list = json_decode($projects, true);
	}
	
	public function get_projects_list() {
		return $this->projects_list;
	}
	
/******************************************************************************/
// PROJECT STATS
/******************************************************************************/
	private function prepare_stats() {
		$db_cacher = WDG_Cache_Plugin::current();

		$stats = $db_cacher->get_cache( WDG_Cache_Plugin::$stats_key, WDG_Cache_Plugin::$stats_version );

		$stats_array = json_decode($stats, true);
		$this->stats_list = array(
				'count_amount'	=> $stats_array['count_amount'],
				'count_people'	=> $stats_array['count_people'],
				'nb_projects'	=> $stats_array['nb_projects'],
				'count_roi'		=> $stats_array['count_roi']
		);
	}

	public function get_stats_list() {
		return $this->stats_list;
	}
	
}