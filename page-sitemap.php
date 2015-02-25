<?php
	$postsForSitemap = get_posts(array(
		'numberposts' => -1,
		'orderby' => 'modified',
		'post_type'  => array('post','page', 'download'),
		'order'    => 'DESC'
	));
	$sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
	$sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
  
	$ordre_prio = array(
		"1.0" => array("type" => "page", "id" => array("accueil")),
		"0.9" => array("type" => "page", "id" => array("les-projets", "proposer-un-projet")),
		"0.8" => array("type" => "page", "id" => array("descriptif","creer-un-projet")),
		"0.7" => array("type" => "page", "id" => array("faq-2","financement","cooperatives","blog")),
		"0.6" => array("type" => "page", "id" => array()),
		"0.5" => array("type" => "page", "id" => array()),
		"0.4" => array("type" => "page", "id" => array()),
		"0.3" => array("type" => "page", "id" => array("espace-presse","partenaires","makesense","les-projets-que-nous-aimons")),
		"0.2" => array("type" => "page", "id" => array("lequipe","contact")),
		"0.1" => array("type" => "page", "id" => array("cgu","confidentialite","mentions-legales")),
	);
	
	foreach($postsForSitemap as $post) {		
		setup_postdata($post);		
		$postdate = explode(" ", $post->post_modified);
		$lien = get_permalink($post->ID);
		
		$priority = "0.1";
		foreach ($ordre_prio as $obj_key =>$prio){
			foreach($prio['id'] as $prio1){
				if (($prio1)==($post->post_name)){
					$priority = $obj_key;}
			}
		}
		
		switch ($post->post_type) {
			case "page":
				$sitemap .= '<url>'.
				  '<loc>'. $lien .'</loc>'.
				  '<lastmod>'. $postdate[0] .'</lastmod>'.
				  '<changefreq>weekly</changefreq>'.
				  '<priority>'. $priority .'</priority>'.
				'</url>';
				break;
			    
			case "post":
				if (in_category('wedogood',$post->ID)){
					$sitemap .= '<url>'.
					  '<loc>'. $lien .'</loc>'.
					  '<lastmod>'. $postdate[0] .'</lastmod>'.
					  '<changefreq>weekly</changefreq>'.
					  '<priority>'. $priority ="0.6" .'</priority>'.
					'</url>';
				} else {
					$sitemap .= '<url>'.
					  '<loc>'. $lien .'</loc>'.
					  '<lastmod>'. $postdate[0] .'</lastmod>'.
					  '<changefreq>weekly</changefreq>'.
					  '<priority>'. $priority ="0.4" .'</priority>'.
					'</url>';
				}
			break;
			
			case "download":
				$campaign = atcf_get_campaign($post->ID);
			    
				if ($campaign->campaign_status() !== "preparing") {
					$campaign_id_param = '?campaign_id='.$post->ID;

					$category_slug = $post->ID . '-blog-' . $post->post_name;
					$category_obj = get_category_by_slug($category_slug);
					if (!empty($category_obj)) {
						$category_link = get_category_link($category_obj->cat_ID);
						$posts_in_category = get_posts(array('category'=>$category_obj->cat_ID));
					} else {
						$category_link = '';
					}
					$news_link = esc_url($category_link);  //page d'actu du projet

					$stats_page = get_page_by_path('statistiques');
					$stats_link = get_permalink($stats_page->ID).$campaign_id_param; //page statisqtique du projet


					$sitemap .= '<url>'.
					  '<loc>'. $lien .'</loc>'.
					  '<lastmod>'. $postdate[0] .'</lastmod>'.
					  '<changefreq>weekly</changefreq>'.
					  '<priority>'. $priority ="0.5" .'</priority>'.
					'</url>';
					$sitemap .= '<url>'.
					  '<loc>'. $news_link .'</loc>'.
					  '<lastmod>'. $postdate[0] .'</lastmod>'.
					  '<changefreq>weekly</changefreq>'.
					  '<priority>'. $priority ="0.4"  .'</priority>'.
					'</url>';
					$sitemap .= '<url>'.
					  '<loc>'. $stats_link .'</loc>'.
					  '<lastmod>'. $postdate[0] .'</lastmod>'.
					  '<changefreq>weekly</changefreq>'.
					  '<priority>'. $priority ="0.2".'</priority>'.
					'</url>';
				}
			break;
		}
    }
    $sitemap .= '</urlset>';

    $fp = fopen(ABSPATH . "sitemap.xml", 'w');
    fwrite($fp, $sitemap);
    fclose($fp);
?>