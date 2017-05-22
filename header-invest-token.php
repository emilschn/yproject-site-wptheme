<?php 
	global $WDG_cache_plugin, $stylesheet_directory_uri, $is_campaign_page, $campaign, $post, $current_user;
	if ($WDG_cache_plugin == null) {
		$WDG_cache_plugin = new WDG_Cache_Plugin();
	}
	$stylesheet_directory_uri = get_stylesheet_directory_uri();
	date_default_timezone_set("Europe/Paris");
	ypcf_session_start();
	$title_str = UIHelpers::current_page_title();
	
	$project_list = array();
	if (is_user_logged_in()) {
		$WDGUser_current = WDGUser::current();
		$cache_project_list = $WDG_cache_plugin->get_cache('WDGUser::get_projects_by_id('.$WDGUser_current->wp_user->ID.', TRUE)', 1);
		if ($cache_project_list !== FALSE) { $project_list = json_decode($cache_project_list); }
		else {
			$project_list = WDGUser::get_projects_by_id($WDGUser_current->wp_user->ID, TRUE);
			$WDG_cache_plugin->set_cache('WDGUser::get_projects_by_id('.$WDGUser_current->wp_user->ID.', TRUE)', json_encode($project_list), 60*10, 1); //MAJ 10min
		}
	}
	
	$projects_searchable = array();
	$cache_projects_searchable = $WDG_cache_plugin->get_cache('ATCF_Campaign::list_projects_searchable', 1);
	if ($cache_projects_searchable !== FALSE) { $projects_searchable = json_decode($cache_projects_searchable); }
	else {
		$projects_searchable = ATCF_Campaign::list_projects_searchable();
		$projects_searchable_encoded = json_encode($projects_searchable);
		$WDG_cache_plugin->set_cache('ATCF_Campaign::list_projects_searchable', $projects_searchable_encoded, 60*60*3, 1); //MAJ 3h
	}
	
	
	wp_reset_query();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head> 
		<link href="<?php echo $stylesheet_directory_uri; ?>/images/favicon.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
		<!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="<?php echo $stylesheet_directory_uri; ?>/images/favicon.ico"/><![endif]-->
		<title><?php if ($title_str) { echo $title_str; } else { wp_title( '|', true, 'right' ); bloginfo( 'name' ); } ?></title>
		
		<link rel="alternate" href="<?php echo get_permalink($campaign->ID); ?>?lang=fr_FR" hreflang="fr" />
		<?php if ($is_campaign_page): 
			$lang_list = $campaign->get_lang_list();
			if (!empty($lang_list)):
				foreach ($lang_list as $lang): $short_lang_str = substr($lang, 0, 2); ?>
		<link rel="alternate" href="<?php echo get_permalink($campaign->ID); ?>?lang=<?php echo $lang; ?>" hreflang="<?php echo $short_lang_str; ?>" />
				<?php endforeach;
			endif;
		endif; ?>
		
		<!-- meta keywords -->
		<?php if (is_single() || is_page() ) : if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>	
		<?php csv_tags(); ?>
		<?php endwhile; endif; elseif(is_home()) : ?>	
		<?php endif; ?>
		
		<?php
		//*******************
		//CACHE HEAD
		$cache_head = $WDG_cache_plugin->get_cache('html-head', 2);
		if ($cache_head !== FALSE) { echo $cache_head; }
		else {
			ob_start();
		?>
		<link href="https://plus.google.com/+WedogoodCo" rel="publisher" />
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="Plateforme d'investissement participatif a impact positif" />
		
		<!--[if lt IE 9]>
		    <script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/html5shiv.js"></script>
		<![endif]-->
		
		<?php $version = '20170510'; ?>
		<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/common.css?d=<?php echo $version; ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/components.css?d=<?php echo $version; ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/responsive-inf997.css?d=<?php echo $version; ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/responsive.css?d=<?php echo $version; ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/responsive-medium.css?d=<?php echo $version; ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?d=<?php echo $version; ?>" type="text/css" media="screen" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php
			$cache_head = ob_get_contents();
			$WDG_cache_plugin->set_cache('html-head', $cache_head, 60*60*24, 1);
			ob_end_clean();
			echo $cache_head;
		}
		//FIN CACHE HEAD
		//*******************
		?>
		<?php if (!is_user_logged_in()): ?>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<?php endif; ?>

		<?php wp_head(); ?>

		<!-- Meta spécifiques à Facebook -->
		<?php /*<meta property="og:title" content="WEDOGOOD<?php if ($is_campaign_page === true) { echo " : ".$campaign->data->post_title; } ?>" />
		<meta property="og:description" content="<?php 
                    if ($is_campaign_page === true) {
			    echo ($campaign->subtitle() != '') ? $campaign->subtitle() : $campaign->data->post_title;
                    } else {
			    echo "Plateforme d'investissement participatif à impact positif";
                    } ?>" />*/ ?>
		<?php
		$imageFacebook = (isset($campaign) && $is_campaign_page === true) ? $campaign->get_home_picture_src() : $stylesheet_directory_uri .'/images/common/wedogood-logo-rouge.png';
		$url = (isset($campaign) && $is_campaign_page === true) ? get_page_link($post) : "";
		?>
		<?php if (isset($campaign) && $is_campaign_page === true): ?>
		<meta property="og:url" content="<?php echo $url; ?>" />
		<meta property="og:title" content="<?php echo $post->post_title; ?>" />
		<meta property="og:description" content="<?php echo $campaign->summary(); ?>" />
		<meta property="fb:app_id" content="<?php echo YP_FB_APP_ID; ?>" />
		<?php endif; ?>
		<meta property="og:image" content="<?php echo $imageFacebook ?>" />
		<meta property="og:image:secure_url" content="<?php echo $imageFacebook ?>" />
		<meta property="og:image:type" content="image/png" />
	</head>

	<body <?php body_class(get_locale()); ?>>
		<div id="container"> 
