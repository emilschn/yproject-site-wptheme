<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Sitemap() );

class WDG_Page_Controler_Sitemap extends WDG_Page_Controler {
	private $send_in_blue_templates_count;
	private $send_in_blue_templates_index;

	public function __construct() {
		$this->send_in_blue_templates_count = 0;

		// Procédure particulière pour les templates sib
		// Ca demande du temps : on va le faire en Ajax
		$input_force_init_sendinblue_templates = filter_input( INPUT_GET, 'force_init_sendinblue_templates' );
		if ( !empty( $input_force_init_sendinblue_templates ) && $input_force_init_sendinblue_templates == '1' ) {
			$this->init_send_in_blue_templates();
		} else {
			$input_queue = filter_input( INPUT_GET, 'queue' );
			$input_rss = filter_input( INPUT_GET, 'rss' );
			$input_make_finished_xml = filter_input( INPUT_GET, 'input_make_finished_xml' );
			$input_force_summary_call = filter_input( INPUT_GET, 'force_summary_call' );
			$input_force_daily_call = filter_input( INPUT_GET, 'force_daily_call' );
			$input_force_clean_sms_lists = filter_input( INPUT_GET, 'clean_sms_lists' );
			$input_force_daily_notifications = filter_input( INPUT_GET, 'force_daily_notifications' );
			$input_init_quarterly_subscriptions = filter_input( INPUT_GET, 'init_quarterly_subscriptions' );

			if ( !empty( $input_queue ) && $input_queue == '1' ) {
				$nb_done = WDGQueue::execute_next( 10 );
				exit( $nb_done . ' queued actions executed.' );
			} else if ( !empty( $input_rss ) && $input_rss == '1' ) {
				$input_campaign = filter_input( INPUT_GET, 'campaign' );
				if ( !empty( $input_campaign ) ) {
					WDGCronActions::make_campaign_xml( $input_campaign );
				} else {
					WDGCronActions::make_projects_rss();
				}
			} else if ( !empty( $input_make_finished_xml ) && $input_make_finished_xml == '1' ) {
				WDGCronActions::make_projects_rss( FALSE );
			} else if ( !empty( $input_force_summary_call ) && $input_force_summary_call == '1' ) {
				$this->summary_call();
			} else if ( !empty( $input_force_daily_call ) && $input_force_daily_call == '1' ) {
				$this->daily_call();
			} else if ( !empty( $input_force_clean_sms_lists ) && $input_force_clean_sms_lists == '1' ) {
				$this->clean_sms_lists();
			} else if ( !empty( $input_force_daily_notifications ) && $input_force_daily_notifications == '1' ) {
				WDGCronActions::send_notifications();
			} else if ( !empty( $input_init_quarterly_subscriptions ) && $input_init_quarterly_subscriptions == '1' ) {
				WDGCronActions::init_quarterly_subscriptions();
			} else {
				$this->hourly_call();
			}
			exit();
		}
	}

	private function hourly_call() {
		WDG_Cache_Plugin::initialize_most_recent_projects();
		WDG_Cache_Plugin::initialize_home_projects();
		$this->rebuild_cache();
	}

	private function daily_call() {
		$this->rebuild_sitemap();
		WDG_Cache_Plugin::initialize_home_stats();
	}

	private function clean_sms_lists() {
		// Tous les jours, suppression de listes de SMS qui s'accumulent
		WDGWPRESTLib::call_get_wdg( 'sms/clean' );
	}

	private function summary_call() {
		$yesterday_date = new DateTime();
		$yesterday_date->sub( new DateInterval( 'P1D' ) );
		$args = [
			'date_query' => [
				[
					'year'  => $yesterday_date->format( 'Y' ),
					'month' => $yesterday_date->format( 'm' ),
					'day'   => $yesterday_date->format( 'd' ),
				],
			]
		];
		$query = new WP_User_Query( $args );
		$users = $query->get_results();
		NotificationsSlack::send_update_summary_user_subscribed( $users );

		$today_date = new DateTime();
		// On n'envoie que le lundi, mardi et vendredi
		// Donc si on est mercredi, jeudi, samedi ou dimanche, on n'envoie pas les notifications de projet
		if ( $today_date->format( 'N' ) == 3 || $today_date->format( 'N' ) == 4 || $today_date->format( 'N' ) == 6 || $today_date->format( 'N' ) == 7 ) {
			return;
		}
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
			$item[ 'nb_not_validated_preinvestment' ] = $vote_results[ 'count_not_validate_preinvestments' ];
			$item[ 'value_not_validated_preinvestment' ] = $vote_results[ 'amount_not_validate_preinvestments' ];
			array_push( $params[ 'vote' ], $item );
		}

		$params[ 'funding' ] = array();
		$project_list_funding_current = ATCF_Campaign::get_list_funding( -1, '', FALSE );
		foreach ( $project_list_funding_current as $project_post ) {
			$campaign = new ATCF_Campaign( $project_post->ID );
			$investment_results = WDGCampaignInvestments::get_list( $project_post->ID );
			$item = array();
			$item[ 'name' ] = $campaign->get_name();
			$item[ 'min_goal' ] = $campaign->minimum_goal();
			$item[ 'time_remaining' ] = $campaign->time_remaining_str();
			$item[ 'nb_invest' ] = $campaign->backers_count();
			$item[ 'value_invest' ] = $campaign->current_amount( false );
			$item[ 'nb_not_validated' ] = $investment_results[ 'count_not_validate_investments' ];
			$item[ 'value_not_validated' ] = $investment_results[ 'amount_not_validate_investments' ];
			array_push( $params[ 'funding' ], $item );
		}
		$project_list_funding_notime = ATCF_Campaign::get_list_funding( -1, '', FALSE, FALSE );
		foreach ( $project_list_funding_notime as $project_post ) {
			$campaign = new ATCF_Campaign( $project_post->ID );
			$investment_results = WDGCampaignInvestments::get_list( $project_post->ID );
			$item = array();
			$item[ 'name' ] = $campaign->get_name();
			$item[ 'min_goal' ] = $campaign->minimum_goal();
			$item[ 'time_remaining' ] = $campaign->time_remaining_str();
			$item[ 'nb_invest' ] = $campaign->backers_count();
			$item[ 'value_invest' ] = $campaign->current_amount( false );
			$item[ 'nb_not_validated' ] = $investment_results[ 'count_not_validate_investments' ];
			$item[ 'value_not_validated' ] = $investment_results[ 'amount_not_validate_investments' ];
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
			$item[ 'value_not_validated' ] = $investment_results[ 'amount_not_validate_investments' ];
			array_push( $params[ 'hidden' ], $item );
		}
		$project_list_hidden_notime = ATCF_Campaign::get_list_current_hidden( ATCF_Campaign::$campaign_status_collecte, FALSE );
		foreach ( $project_list_hidden_notime as $project_post ) {
			$campaign = new ATCF_Campaign( $project_post->ID );
			$investment_results = WDGCampaignInvestments::get_list( $project_post->ID );
			$item = array();
			$item[ 'name' ] = $campaign->get_name();
			$item[ 'min_goal' ] = $campaign->minimum_goal();
			$item[ 'time_remaining' ] = $campaign->time_remaining_str();
			$item[ 'nb_invest' ] = $campaign->backers_count();
			$item[ 'value_invest' ] = $campaign->current_amount( false );
			$item[ 'nb_not_validated' ] = $investment_results[ 'count_not_validate_investments' ];
			$item[ 'value_not_validated' ] = $investment_results[ 'amount_not_validate_investments' ];
			array_push( $params[ 'hidden' ], $item );
		}

		NotificationsSlack::send_update_summary_current_projects( $params );
	}

	private function rebuild_cache() {
		$params = array(
			'home-projects',
			'projectlist-projects-current',
			'projectlist-projects-funded'
		);

		$WDG_Cache_Plugin = WDG_Cache_Plugin::current();
		$WDG_Cache_Plugin->delete_cache( $params );

		$WDG_File_Cacher = WDG_File_Cacher::current();
		$WDG_File_Cacher->delete( 'home' );
		$WDG_File_Cacher->delete( 'les-projets' );
		$WDG_File_Cacher->rebuild_cache();
	}

	private function rebuild_sitemap() {
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

		// On va chercher toutes les pages priorisées
		$query_options = array(
			'numberposts' => -1,
			'post_type' => 'page',
			'meta_query' => array(
				array( 'key' =>'sitemap_priority', 'compare' => 'EXISTS' )
			),
			'orderby' => 'meta_value'
		);
		$posts = get_posts( $query_options );

		foreach ($posts as $post) {
			$page_modified_exploded = explode( ' ', $post->post_modified );
			if ( count( $page_modified_exploded ) > 0 ) {
				$uri = get_page_uri( $post->ID );
				// on va chercher la priorité dans le champ personnalisé
				$priority = get_post_meta($post->ID, 'sitemap_priority', TRUE ); 
				$sitemap .= "<url>".
					"<loc>". home_url( $uri ) ."</loc>".
					"<lastmod>". $page_modified_exploded[0] ."</lastmod>".
					"<changefreq>weekly</changefreq>".
					"<priority>". $priority ."</priority>".
				"</url>\n";
			}
		}

		// Ajout des projets en évaluation
		$campaignlist_vote = ATCF_Campaign::get_list_vote();
		foreach ( $campaignlist_vote as $campaign_post ) {
			$campaign_id = $campaign_post->ID;
			$sitemap .= "<url>".
				"<loc>". get_permalink( $campaign_id ) ."</loc>".
				"<lastmod>". $current_date->format( 'Y-m-d' ) ."</lastmod>".
				"<changefreq>daily</changefreq>".
				"<priority>0.2</priority>".
			"</url>\n";
		}

		// Ajout des projets en cours de financement
		$campaignlist_funding = ATCF_Campaign::get_list_funding();
		foreach ( $campaignlist_funding as $campaign_post ) {
			$campaign_id = $campaign_post->ID;
			$sitemap .= "<url>".
				"<loc>". get_permalink( $campaign_id ) ."</loc>".
				"<lastmod>". $current_date->format( 'Y-m-d' ) ."</lastmod>".
				"<changefreq>hourly</changefreq>".
				"<priority>0.2</priority>".
			"</url>\n";
		}

		// Ajout de 40 projets (publics) financés
		$campaignlist_funded = ATCF_Campaign::get_list_funded( 40 );
		foreach ( $campaignlist_funded as $campaign_post ) {
			$campaign_id = $campaign_post->ID;
			$page_modified_exploded = explode( ' ', $campaign_post->post_modified );
			$sitemap .= "<url>".
				"<loc>". get_permalink( $campaign_id ) ."</loc>".
				"<lastmod>". $page_modified_exploded[0] ."</lastmod>".
				"<changefreq>monthly</changefreq>".
				"<priority>0.1</priority>".
			"</url>\n";
		}

		$sitemap .= '</urlset>';

		$fp = fopen( dirname( __FILE__ ) . '/../../../../../sitemap.xml', 'w' );
		fwrite($fp, $sitemap);
		fclose($fp);
	}

	/**
	 * Gestion initialisation des templates SendInBlue
	 */
	private function init_send_in_blue_templates() {
		$this->send_in_blue_templates_index = 0;
		$this->send_in_blue_templates_count = count( NotificationsAPI::$description_str_by_template_id );
	}

	public function has_send_in_blue_templates_to_init() {
		return ( $this->send_in_blue_templates_count > 0 );
	}

	public function get_send_in_blue_templates_count() {
		return $this->send_in_blue_templates_count;
	}
}