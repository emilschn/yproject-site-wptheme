<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Sitemap() );

class WDG_Page_Controler_Sitemap extends WDG_Page_Controler {
	
	public function __construct() {
		$input_queue = filter_input( INPUT_GET, 'queue' );
		
		if ( !empty( $input_queue ) && $input_queue == '1' ) {
			$nb_done = WDGQueue::execute_next( 10 );
			exit( $nb_done . ' queued actions executed.' );
			
		} else {
			$this->hourly_call();
			$input_force_daily_call = filter_input( INPUT_GET, 'force_daily_call' );
			$input_force_summary_call = filter_input( INPUT_GET, 'force_summary_call' );
			if ( $this->is_daily_call_time() || $input_force_daily_call == '1' ) {
				$this->daily_call();
			} else if ( $this->is_summary_call_time() || $input_force_summary_call == '1' ) {
				$this->summary_call();
			}
			
		}
		exit();
	}
	
	private function hourly_call() {
		WDG_Cache_Plugin::initialize_most_recent_projects();
		WDG_Cache_Plugin::initialize_home_projects();
		$this->rebuild_cache();
	}
	
	private function daily_call() {
		$this->rebuild_sitemap();
		WDG_Cache_Plugin::initialize_home_stats();
		$input_make_finished_xml = filter_input( INPUT_GET, 'input_make_finished_xml' );
		if ( empty( $input_make_finished_xml ) ) {
			WDGCronActions::make_projects_rss();
		} else {
			WDGCronActions::make_projects_rss( FALSE );
		}
		WDGCronActions::send_notifications();
	}
	
	private function is_daily_call_time() {
		$buffer = FALSE;
		$date_now = new DateTime();
		$last_daily_call = get_option( 'last_daily_call' );
		$saved_date = new DateTime( $last_daily_call );
		if ( $last_daily_call == FALSE || $saved_date->diff($date_now)->days >= 1 ) {
			update_option( 'last_daily_call', $date_now->format( 'Y-m-d H:i:s' ) );
			$buffer = TRUE;
		}
		return $buffer;
	}
	
	private function summary_call() {
		$params = array();
		$params[ 'vote' ] = array();
		$project_list_vote = ATCF_Campaign::get_list_vote();
		foreach ( $project_list_vote as $project_post ) {
			$campaign = new ATCF_Campaign( $project_post->ID );
			$vote_results = WDGCampaignVotes::get_results( $project_post->ID );
			$item = array();
			$item[ 'name' ] = $campaign->get_name();
			$item[ 'min_goal' ] = $campaign->minimum_goal();
			$item[ 'time_remaining' ] = $campaign->time_remaining_str();
			$item[ 'nb_votes' ] = $campaign->nb_voters();
			$item[ 'value_intent' ] = $vote_results[ 'sum_invest_ready' ];
			$item[ 'nb_preinvestment' ] = $vote_results[ 'count_preinvestments' ];
			$item[ 'value_preinvestment' ] = $vote_results[ 'amount_preinvestments' ];
			array_push( $params[ 'vote' ], $item );
		}
		
		$params[ 'funding' ] = array();
		$project_list_funding = ATCF_Campaign::get_list_funding();
		foreach ( $project_list_funding as $project_post ) {
			$campaign = new ATCF_Campaign( $project_post->ID );
			$investment_results = WDGCampaignInvestments::get_list( $project_post->ID );
			$item = array();
			$item[ 'name' ] = $campaign->get_name();
			$item[ 'min_goal' ] = $campaign->minimum_goal();
			$item[ 'time_remaining' ] = $campaign->time_remaining_str();
			$item[ 'nb_invest' ] = $campaign->backers_count();
			$item[ 'value_invest' ] = $campaign->current_amount( false );
			$item[ 'nb_not_validated' ] = $investment_results[ 'count_not_validate_investments' ];
			array_push( $params[ 'funding' ], $item );
		}
		
		
		$params[ 'hidden' ] = array();
		$project_list_hidden = ATCF_Campaign::get_list_current_hidden( ATCF_Campaign::$campaign_status_collecte );
		foreach ( $project_list_hidden as $project_post ) {
			$campaign = new ATCF_Campaign( $project_post->ID );
			$investment_results = WDGCampaignInvestments::get_list( $project_post->ID );
			$item = array();
			$item[ 'name' ] = $campaign->get_name();
			$item[ 'min_goal' ] = $campaign->minimum_goal();
			$item[ 'time_remaining' ] = $campaign->time_remaining_str();
			$item[ 'nb_invest' ] = $campaign->backers_count();
			$item[ 'value_invest' ] = $campaign->current_amount( false );
			$item[ 'nb_not_validated' ] = $investment_results[ 'count_not_validate_investments' ];
			array_push( $params[ 'hidden' ], $item );
		}
		
		NotificationsSlack::send_update_summary_current_projects( $params );
	}
	
	private function is_summary_call_time() {
		$buffer = FALSE;
		$date_now = new DateTime();
		$last_summary_call = get_option( 'last_summary_call' );
		$saved_date = new DateTime( $last_summary_call );
		if ( $last_summary_call == FALSE || $saved_date->diff($date_now)->h >= 12 ) {
			update_option( 'last_summary_call', $date_now->format( 'Y-m-d H:i:s' ) );
			$buffer = TRUE;
		}
		return $buffer;
	}
	
	private function rebuild_cache() {
		
		$WDG_File_Cacher = new WDG_File_Cacher();
		do_action('wdg_delete_cache', array(
			'home-projects',
			'projectlist-projects-current',
			'projectlist-projects-funded'
		));
		$WDG_File_Cacher->rebuild_cache();

	}
	
	private function rebuild_sitemap() {
	
		$priority_by_url = array(
			// 0.9
			'/financement/'			=> '0.9',
			'/investissement/'		=> '0.9',
			'/epargne-positive/'	=> '0.9',
			// 0.8
			'/financement/entreprises/'				=> '0.8',
			'/financement/royalties/levee-de-fonds-privee/'	=> '0.8',
//			'/financement/solutions/'				=> '0.8',
			'/investissement/comparatif-risque/'	=> '0.8',
			'/investissement/start-up/'				=> '0.8',
			// 0.7
			'/financement/automatise/'						=> '0.7',
//			'/financement/entreprises/B2B/'					=> '0.7',
			'/financement/entreprises/cooperatives/'		=> '0.7',
//			'/financement/entreprises/PME/'					=> '0.7',
//			'/financement/entreprises/start-up/'			=> '0.7',
			'/financement/entreprises/start-up/amorcage/'	=> '0.7',
			'/financement/ethique/'							=> '0.7',
			'/financement/flexible/'						=> '0.7',
			'/financement/fonds-propres/'					=> '0.7',
			'/financement/non-dilutif/'						=> '0.7',
			'/financement/offres/love-money/'				=> '0.7',
			'/financement/rapide/'							=> '0.7',
			'/financement/royalties/'						=> '0.7',
			'/financement/royalty-crowdfunding/'			=> '0.7',
//			'/financement/solutions/innovation/'			=> '0.7',
//			'/financement/solutions/investissements/'		=> '0.7',
//			'/financement/solutions/tresorerie-bfr/'		=> '0.7',
			'/financement/investissement/impact-investing/'	=> '0.7',
//			'/les-projets/'									=> '0.7',
//			'/solutions/expert-comptable/'					=> '0.7',
			'/solutions/start-up/'							=> '0.7',
			// 0.6
			'/guide/'										=> '0.6',
			'/a-propos/statistiques/'						=> '0.6',
			'/a-propos/vision/'								=> '0.6',
			'/financement/conditions/'						=> '0.6',
//			'/financement/entreprises/B2C/'					=> '0.6',
			'/financement/entreprises/start-up/love-money/'	=> '0.6',
			'/financement/label-croissance-verte/'			=> '0.6',
			'/financement/simulateur-taux-de-royalties/'	=> '0.6',
			'/investissement/comparatif-capital-pret-royalties/'		=> '0.6',
			'/investissement/impact-investing/evaluation-des-impacts/'	=> '0.6',
			'/solutions/'									=> '0.6',
//			'/solutions/accelerateur/'						=> '0.6',
//			'/solutions/entreprises/'						=> '0.6',
//			'/solutions/fonds-investissement/'				=> '0.6',
			'/solutions/incubateur/'						=> '0.6',
			'/solutions/incubateurs-accelerateurs/'			=> '0.6',
			// 0.5
			// PROJETS
			'/a-propos/statistiques/rapport-activite-2018/'			=> '0.5',
//			'/financement/offres/'									=> '0.5',
//			'/financement/royalty-crowdfunding/accompagnement/'		=> '0.5',
//			'/investissement/cooperatives/'							=> '0.5',
			'/investissement/fiscalite-royalties/'					=> '0.5',
			// 0.4
			'/financement/offres/amorcage-crowdfunding/'			=> '0.4',
			'/financement/offres/crowdfunding-accompagnement/'		=> '0.4',
			'/financement/offres/crowdfunding-self-service/'		=> '0.4',
			// 0.3
			'/a-propos/espace-presse/'			=> '0.3',
			'/a-propos/partenaires/'			=> '0.3',
//			'/investir/actifs/'					=> '0.3',
			'/press-book/'						=> '0.3',
			// 0.2
			'/a-propos/contact/'				=> '0.2',
			'/a-propos/equipe/'					=> '0.2',
			'/a-propos/recrutement/'			=> '0.2',
			'/a-propos/statistiques/rapport-activite-2017/'			=> '0.2',
			// 0.1
			'/placement-royalties/'				=> '0.1',
			'/a-propos/'						=> '0.1',
			'/a-propos/cgu/'					=> '0.1',
			'/a-propos/cgu/conditions-particulieres/'				=> '0.1',
			'/a-propos/cgu/confidentialite/'	=> '0.1',
			'/a-propos/cgu/mentions-legales/'	=> '0.1',
			'/a-propos/cgu/reclamations/'		=> '0.1',
			'/love-money/'						=> '0.1',
			
		);
		
		$current_date = new DateTime();
		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
		$sitemap .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
		
		// Accueil : '1.0'
		$sitemap .= "<url>".
			"<loc>". home_url() ."</loc>".
			"<lastmod>". $current_date->format( 'Y-m-d' ) ."</lastmod>".
			"<changefreq>hourly</changefreq>".
			"<priority>1.0</priority>".
		"</url>\n";
		// les-projets : '0.7'
		$sitemap .= "<url>".
			"<loc>". home_url( '/les-projets/' ) ."</loc>".
			"<lastmod>". $current_date->format( 'Y-m-d' ) ."</lastmod>".
			"<changefreq>hourly</changefreq>".
			"<priority>0.7</priority>".
		"</url>\n";
		
		// Ajout accueil et les-projets d'abord
		foreach ( $priority_by_url as $uri => $priority ) {
			$page_by_uri = get_page_by_path( $uri );
			if ( $page_by_uri ) {
				$page_modified_exploded = explode( ' ', $page_by_uri->post_modified );
				if ( count( $page_modified_exploded ) > 0 ) {
					$sitemap .= "<url>".
						"<loc>". home_url( $uri ) ."</loc>".
						"<lastmod>". $page_modified_exploded[0] ."</lastmod>".
						"<changefreq>weekly</changefreq>".
						"<priority>". $priority ."</priority>".
					"</url>\n";
				}
			}
		}

		// Ajout des projets en vote
		$campaignlist_vote = ATCF_Campaign::get_list_vote();
		foreach ( $campaignlist_vote as $campaign_post ) {
			$campaign_id = $campaign_post->ID;
			$sitemap .= "<url>".
				"<loc>". get_permalink( $campaign_id ) ."</loc>".
				"<lastmod>". $current_date->format( 'Y-m-d' ) ."</lastmod>".
				"<changefreq>daily</changefreq>".
				"<priority>0.5</priority>".
			"</url>\n";
		}
		
		$campaignlist_funding = ATCF_Campaign::get_list_funding();
		foreach ( $campaignlist_funding as $campaign_post ) {
			$campaign_id = $campaign_post->ID;
			$sitemap .= "<url>".
				"<loc>". get_permalink( $campaign_id ) ."</loc>".
				"<lastmod>". $current_date->format( 'Y-m-d' ) ."</lastmod>".
				"<changefreq>hourly</changefreq>".
				"<priority>0.5</priority>".
			"</url>\n";
		}
		
		$campaignlist_funded = ATCF_Campaign::get_list_funded( 50 );
		foreach ( $campaignlist_funded as $campaign_post ) {
			$campaign_id = $campaign_post->ID;
			$page_modified_exploded = explode( ' ', $campaign_post->post_modified );
			$sitemap .= "<url>".
				"<loc>". get_permalink( $campaign_id ) ."</loc>".
				"<lastmod>". $page_modified_exploded[0] ."</lastmod>".
				"<changefreq>monthly</changefreq>".
				"<priority>0.5</priority>".
			"</url>\n";
		}
		
		$sitemap .= '</urlset>';

		$fp = fopen( dirname ( __FILE__ ) . '/../../../../../sitemap.xml', 'w' );
		fwrite($fp, $sitemap);
		fclose($fp);
		
	}
}