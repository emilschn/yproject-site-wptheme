<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Home() );

class WDG_Page_Controler_Home extends WDG_Page_Controler {
	
	private static $projects_html_key = 'home-projects';
	private static $projects_html_duration = 180; // 3 minutes de cache
	private static $projects_html_version = 3;
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
			$this->init_projects();
			$this->prepare_stats();
		}
	}
	
/******************************************************************************/
// PROJECT LIST
/******************************************************************************/
	private static $funded_campaign_top_list = array( 'naoden', 'listo', 'twiza' );
	private function init_projects() {
		$this->projects_html = $this->get_db_cached_elements( WDG_Page_Controler_Home::$projects_html_key, WDG_Page_Controler_Home::$projects_html_version );
		if ( empty( $this->projects_html ) ) {
			$this->projects_list = array();
			$campaignlist_funding = ATCF_Campaign::get_list_funding( 10 );
			$campaignlist_funding_sorted = $this->sort_project_list( $campaignlist_funding );
			$count_campaignlist = count( $campaignlist_funding_sorted );
			foreach ( $campaignlist_funding_sorted as $campaign ) { array_push( $this->projects_list, $campaign->ID ); }
			
			if ( $count_campaignlist < WDG_Page_Controler_Home::$projects_nb_to_show ) {
				$campaignlist_vote = ATCF_Campaign::get_list_vote( 10 );
				$campaignlist_vote_sorted = $this->sort_project_list( $campaignlist_vote );
				$count_campaignlist += count( $campaignlist_vote_sorted );
				foreach ( $campaignlist_vote_sorted as $campaign ) { array_push( $this->projects_list, $campaign->ID ); }
			}
			
			$i = $count_campaignlist - 1;
			while ( $i > WDG_Page_Controler_Home::$projects_nb_to_show - 1 ){
				array_splice( $this->projects_list, $i, 1 );
				$i--;
			}

			if ( $count_campaignlist < WDG_Page_Controler_Home::$projects_nb_to_show ) {
				for ( $i = 0; $i < WDG_Page_Controler_Home::$projects_nb_to_show - $count_campaignlist; $i++ ) {
					global $wpdb;
					$result = $wpdb->get_results( 
						"SELECT ID FROM ".$wpdb->posts." WHERE ".$wpdb->posts.".post_type = 'download' AND ".$wpdb->posts.".post_name = '".WDG_Page_Controler_Home::$funded_campaign_top_list[ $i ]."'",
						OBJECT
					);
					if ( count( $result ) > 0 ) {
						array_push( $this->projects_list, $result[0]->ID );
					} else {
						array_push( $this->projects_list, 1 );
					}
				}
			}
		}
	}
	
	private function sort_project_list( $campaign_list ) {
		// On commence par mélanger toute la liste pour être sûr d'avoir de l'aléatoire
		shuffle( $campaign_list );
		
		// On parcourt tous les projets
		$count_campaigns = count( $campaign_list );
		for ( $i = $count_campaigns - 1; $i >= 0; $i-- ) {
			$campaign_post = $campaign_list[ $i ];
			$campaign = new ATCF_Campaign( $campaign_post->ID );
			$campaign_categories_str = $campaign->get_categories_str();
			
			// On supprime ceux qui ne sont pas des projets d'entreprise
			if ( strpos( $campaign_categories_str, 'entreprises' ) === FALSE ) {
				array_splice( $campaign_list, $i, 1 );
			}
			// On met au début ceux qui sont "positifs"
			if ( strpos( $campaign_categories_str, 'environnemental' ) !== FALSE || strpos( $campaign_categories_str, 'social' ) !== FALSE ) {
				$campaign_element = $campaign_list[ $i ];
				array_splice( $campaign_list, $i, 1 );
				array_unshift( $campaign_list, $campaign_element );
			}
		}
		
		return $campaign_list;
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