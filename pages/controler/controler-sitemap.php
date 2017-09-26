<?php
global $page_controler;
$page_controler = new WDG_Page_Controler_Sitemap();

class WDG_Page_Controler_Sitemap extends WDG_Page_Controler {
	
	public function __construct() {
		parent::__construct();
		$this->hourly_call();
		if ( $this->is_daily_call_time() ) {
			$this->daily_call();
		}
	}
	
	private function hourly_call() {
		$this->rebuild_cache();
	}
	
	private function daily_call() {
		$this->rebuild_sitemap();
		WDGCronActions::make_projects_rss();
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
	
		$postsForSitemap = get_posts( array(
			'numberposts'	=> -1,
			'orderby'		=> 'modified',
			'post_type'		=> array( 'page', 'download' ),
			'order'			=> 'DESC'
		));

		$ordre_prio = array(
			"1.0" => array("type" => "page", "id" => array("accueil")),
			"0.9" => array("type" => "page", "id" => array("les-projets", "financement", "investissement")),
			"0.8" => array("type" => "page", "id" => array("offre","vision")),
			"0.7" => array("type" => "page", "id" => array("blog")),
			"0.6" => array("type" => "page", "id" => array()),
			"0.5" => array("type" => "page", "id" => array()),
			"0.4" => array("type" => "page", "id" => array()),
			"0.3" => array("type" => "page", "id" => array("espace-presse","press-book","partenaires")),
			"0.2" => array("type" => "page", "id" => array("lequipe","contact","stats","recrutement")),
			"0.1" => array("type" => "page", "id" => array("cgu","confidentialite","mentions-legales","reclamations")),
		);
		
		$sitemap = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
		$sitemap .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

		foreach($postsForSitemap as $post) {		
			setup_postdata($post);		
			$postdate = explode(" ", $post->post_modified);
			$lien = get_permalink($post->ID);

			switch ($post->post_type) {
				case "page":
					$priority = "";
					foreach ($ordre_prio as $obj_key =>$prio){
						foreach($prio['id'] as $prio1){
							if (($prio1)==($post->post_name)){
								$priority = $obj_key;}
						}
					}

					if ( !empty( $priority ) ){
						$sitemap .= "<url>".
							"<loc>". $lien ."</loc>".
							"<lastmod>". $postdate[0] ."</lastmod>".
							"<changefreq>weekly</changefreq>".
							"<priority>". $priority ."</priority>".
						"</url>\n";
					}
					break;

				case "download":
					$campaign = atcf_get_campaign($post->ID);

					if ( $campaign->campaign_status() !== ATCF_Campaign::$campaign_status_preparing 
							&& $campaign->campaign_status() !== ATCF_Campaign::$campaign_status_validated
							&& $campaign->campaign_status() !== ATCF_Campaign::$campaign_status_archive ) {
						
						$priority = "0.6";
						$sitemap .= "<url>".
							"<loc>". $lien ."</loc>".
							"<lastmod>". $postdate[0] ."</lastmod>".
							"<changefreq>weekly</changefreq>".
							"<priority>". $priority ."</priority>".
						"</url>\n";
						
					}
				break;
			}
		}
		$sitemap .= '</urlset>';

		$fp = fopen( dirname ( __FILE__ ) . '/../../../../../sitemap.xml', 'w' );
		fwrite($fp, $sitemap);
		fclose($fp);
		
	}
	
}