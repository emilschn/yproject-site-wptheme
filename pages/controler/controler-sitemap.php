<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Sitemap() );

class WDG_Page_Controler_Sitemap extends WDG_Page_Controler {
	
	public function __construct() {
		parent::__construct();
		$this->hourly_call();
		$input_force_daily_call = filter_input( INPUT_GET, 'force_daily_call' );
		if ( $this->is_daily_call_time() || $input_force_daily_call == '1' ) {
			$this->daily_call();
		}
		
	}
	
	private function hourly_call() {
		$this->rebuild_cache();
		$this->initialize_home_projects();
		$this->initialize_most_recent_projects();
	}
	
	private function daily_call() {
		$this->rebuild_sitemap();
		$this->initialize_home_stats();
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
			// 0.8
			'/financement/entreprises/'				=> '0.8',
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
//			'/financement/entreprises/B2C/'					=> '0.6',
			'/financement/entreprises/start-up/love-money/'	=> '0.6',
			'/financement/label-croissance-verte/'			=> '0.6',
			'/investissement/comparatif-capital-pret-royalties/'		=> '0.6',
//			'/investissement/impact-investing/evaluation-impacts/'		=> '0.6',
			'/solutions/'									=> '0.6',
//			'/solutions/accelerateur/'						=> '0.6',
//			'/solutions/entreprises/'						=> '0.6',
//			'/solutions/fonds-investissement/'				=> '0.6',
			'/solutions/incubateur/'						=> '0.6',
			// 0.5
			// PROJETS
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
			// 0.1
			'/cgu/'								=> '0.1',
			'/placement-royalties/'				=> '0.1',
			'/a-propos/'						=> '0.1',
			'/confidentialite/'					=> '0.1',
			'/love-money/'						=> '0.1',
			'/mentions-legales/'				=> '0.1',
			'/reclamations/'					=> '0.1',
			
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
	
	// Calcul les stats et les met en cache 
	private function initialize_home_stats() {
		$db_cacher = WDG_Cache_Plugin::current();

		$count_amount = 0;
		$people_list = array();
		$count_projects = 0;
		$count_roi = 0;

		$project_list_funded = ATCF_Campaign::get_list_funded( WDG_Cache_Plugin::$nb_query_campaign_funded, '', true, false );
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
		$stats_list = array(
			'count_amount'	=> $count_amount,
			'count_people'	=> $count_people,
			'nb_projects'	=> count($project_list_funded),
			'count_roi'		=> $count_roi
		);
		$stats_content = json_encode($stats_list);

	    $db_cacher->set_cache( WDG_Cache_Plugin::$stats_key, $stats_content, WDG_Cache_Plugin::$stats_duration, WDG_Cache_Plugin::$stats_version );
	}

	// Recherche les 3 projects les plus récent et les met en cache 
	private function initialize_most_recent_projects() {
		$db_cacher = WDG_Cache_Plugin::current();
		$list_projects = ATCF_Campaign::get_list_most_recent( 3 );
		$slider = array();

		foreach ( $list_projects as $project_id ) {
			$campaign = atcf_get_campaign( $project_id );
			$img = $campaign->get_home_picture_src( TRUE, 'large' );
			array_push( $slider, array(
					'img'	=> $img,
					'title'	=> $campaign->data->post_title,
					'link'	=> get_permalink( $project_id )
				)
			);
		}		
		$slider_content = json_encode($slider);

	    $db_cacher->set_cache( WDG_Cache_Plugin::$slider_key, $slider_content, WDG_Cache_Plugin::$slider_duration, WDG_Cache_Plugin::$slider_version );
	}

	//Recherche projets en cours de financement à impact positif et les met en cache
	private static $funded_campaign_top_list = array( 'naoden', 'listo', 'twiza' );
	private function initialize_home_projects(){
		$db_cacher = WDG_Cache_Plugin::current();
		$projects_list = array();

		$campaignlist_funding = ATCF_Campaign::get_list_funding( 10 );
		$campaignlist_funding_sorted = $this->sort_project_list( $campaignlist_funding );
		$count_campaignlist = count( $campaignlist_funding_sorted );
		foreach ( $campaignlist_funding_sorted as $campaign ) { array_push( $projects_list, $campaign->ID ); }
		
		if ( $count_campaignlist < WDG_Cache_Plugin::$projects_nb_to_show ) {
			$campaignlist_vote = ATCF_Campaign::get_list_vote( 10 );
			$campaignlist_vote_sorted = $this->sort_project_list( $campaignlist_vote );
			$count_campaignlist += count( $campaignlist_vote_sorted );
			foreach ( $campaignlist_vote_sorted as $campaign ) { array_push( $projects_list, $campaign->ID ); }
		}
		
		$i = $count_campaignlist - 1;
		while ( $i > WDG_Cache_Plugin::$projects_nb_to_show - 1 ){
			array_splice( $projects_list, $i, 1 );
			$i--;
		}
		if ( $count_campaignlist < WDG_Cache_Plugin::$projects_nb_to_show ) {
			for ( $i = 0; $i < WDG_Cache_Plugin::$projects_nb_to_show - $count_campaignlist; $i++ ) {
				global $wpdb;
				$result = $wpdb->get_results( 
					"SELECT ID FROM ".$wpdb->posts." WHERE ".$wpdb->posts.".post_type = 'download' AND ".$wpdb->posts.".post_name = '".WDG_Page_Controler_Sitemap::$funded_campaign_top_list[ $i ]."'", OBJECT
                );
				if ( count( $result ) > 0 ) {
					array_push( $projects_list, $result[0]->ID );
				} else {
					array_push( $projects_list, 1 );
				}
			}
		}
		$projects_content = json_encode($projects_list);

		$db_cacher->set_cache( WDG_Cache_Plugin::$projects_key, $projects_content, WDG_Cache_Plugin::$projects_duration, WDG_Cache_Plugin::$projects_version );
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

}