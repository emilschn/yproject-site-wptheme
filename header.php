<?php 
    global $WDG_cache_plugin;
    global $stylesheet_directory_uri;
    $stylesheet_directory_uri = get_stylesheet_directory_uri();
    /* Récupération des infos Facebook */
    global $facebook_infos;
    require_once("_external/facebook/facebook.php");
    $cache_result = $WDG_cache_plugin->get_cache('facebook-count');
    if (false === $cache_result) {
	    
	    $facebook = new Facebook(array(
		    'appId'  => YP_FB_APP_ID,
		    'secret' => YP_FB_SECRET,
	    ));
	    $fb_infos = $facebook->api(YP_FB_URL); 
	    if ($fb_infos) $cache_result= $fb_infos['likes'];
	    
	    $WDG_cache_plugin->set_cache('facebook-count',$cache_result,60*60*24);
    }
    $facebook_infos = $cache_result;
	
    /* Récupération des infos Twitter */
    global $twitter_infos;
    $cache_result = $WDG_cache_plugin->get_cache('twitter-count');
    if (false === $cache_result) {
	    require_once("_external/twitter/TwitterAPIExchange.php");
	    $apiUrl = "https://api.twitter.com/1.1/users/show.json";
	    $requestMethod = 'GET';
	    $getField = '?screen_name=wedogood_co';
	    $settings = array(
		    'oauth_access_token' => YP_TW_oauth_access_token,
		    'oauth_access_token_secret' => YP_TW_oauth_access_token_secret,
		    'consumer_key' => YP_TW_consumer_key,
		    'consumer_secret' => YP_TW_consumer_secret
	    );

	    $twitter = new TwitterAPIExchange($settings);
	    $response = $twitter->setGetfield($getField)
			    ->buildOauth($apiUrl, $requestMethod)
			    ->performRequest();
	    $followers = json_decode($response);
	    if ($followers && isset($followers->followers_count)) $cache_result = $followers->followers_count;
	    $WDG_cache_plugin->set_cache('twitter-count',$cache_result,60*60*24);
    }
    $twitter_infos = $cache_result;

    function getWDGTitle() {
	    global $post;
	    $buffer = '';
	    if ( is_category() ) {
		    global $cat;
		    $this_category = get_category($cat);
		    $this_category_name = $this_category->name;
		    $name_exploded = explode('cat', $this_category_name);
		    $campaign_post = get_post($name_exploded[1]);
		    $buffer = 'Actualit&eacute;s du projet ' . (is_object($campaign_post) ? $campaign_post->post_title : '') . ' | ' . get_bloginfo( 'name' );
	    } else if (isset($post)) {
		    $page_name = get_post($post)->post_name;
		    if ($page_name == 'forum' && isset($_GET['campaign_id'])) {
			    $campaign_post = get_post($_GET['campaign_id']);
			    $buffer = 'Commentaires du projet ' . $campaign_post->post_title . ' | ' . get_bloginfo( 'name' );
		    }
	    }
	    return $buffer;
    }
    date_default_timezone_set("Europe/Paris");
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head>
		<?php $title_str = getWDGTitle();
		if ($title_str) : ?>
		<title><?php echo $title_str; ?></title>
		<?php else : ?>
		<title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?></title>
		<?php endif; ?>
		<?php
			$cache_result = $WDG_cache_plugin->get_cache('header-content');
			// START CACHE HEADER CONTENT
 			if (false === $cache_result) {
				ob_start();
		?>
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<?php if ( current_theme_supports( 'bp-default-responsive' ) ) : ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" /><?php endif; ?>
		<meta name="description" content="Plateforme d'investissement participatif &agrave; impact positif" />
		<meta property="og:title" content="WEDOGOOD" />
		<meta property="og:image" content="<?php echo $stylesheet_directory_uri; ?>/images/logo_entier.jpg" />
		<meta property="og:image:secure_url" content="<?php echo $stylesheet_directory_uri; ?>/images/logo_entier.jpg" />
		<meta property="og:image:type" content="image/jpeg" />
		<!--[if lt IE 9]>
		    <script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/html5shiv.js"></script>
		<![endif]-->
		
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?ver=1.1.001" type="text/css" media="screen" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<?php do_action( 'bp_head' ); ?>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?> id="bp-default">

		<?php do_action( 'bp_before_header' ); ?>

		<nav id="navigation" role="navigation">
		    <div class="center">
				<ul id="nav">
					<?php /* Logo Accueil */ ?>
					<li class="page_item_out page_item_logo"><a href="<?php echo home_url(); ?>" style="padding-left: 0px; padding-right: 14px;"><img src="<?php echo $stylesheet_directory_uri; ?>/images/logo.png" width="160" height="100" alt="logo" /></a></li>
					<?php /* Menu Proposer un projet */ $page_start = get_page_by_path('proposer-un-projet'); ?>
					<li class="page_item"><span class="page_item_border"><a href="<?php echo get_permalink($page_start->ID); ?>"><?php echo __('Proposer un projet', 'yproject'); ?></a></span></li>
					<?php /* Menu Comment ça marche ? */ $page_how = get_page_by_path('descriptif'); ?>
					<li class="page_item"><span class="page_item_border"><a href="<?php echo get_permalink($page_how->ID); ?>"><?php echo __('Comment ca marche ?', 'yproject'); ?></a></span></li>
					<?php /* Menu Communauté */ $page_community = get_page_by_path('communaute'); ?>
					<li class="page_item"><span class="page_item_border"><a href="<?php echo get_permalink($page_community->ID); ?>"><?php echo __('Communaute', 'yproject'); ?></a></span></li>
					<?php /* Logo FB / TW */ ?>
					<li class="page_item_out mobile_hidden" id="menu_item_facebook"><a href="https://www.facebook.com/wedogood.co" target="_blank" title="Notre page Facebook"><img src="<?php echo $stylesheet_directory_uri; ?>/images/facebook.png" width="20" height="20" alt="facebook" /></a></li>
					<li class="page_item_out mobile_hidden" id="menu_item_twitter"><a href="https://twitter.com/wedogood_co" target="_blank" title="Notre compte Twitter"><img src="<?php echo $stylesheet_directory_uri; ?>/images/twitter.png" width="20" height="20" alt="facebook" /></a></li>
					<?php
						$cache_result = ob_get_contents();
						$WDG_cache_plugin->set_cache('header-content',$cache_result,60*60*24);
						ob_end_clean();
					}
					// END CACHE HEADER CONTENT
					echo $cache_result;
					
					if (is_user_logged_in()) : ?>
						<?php /* Menu Mon compte */ ?>
						<li class="page_item_out page_item_inverted">
						<a class="page_item_inverted" href="<?php echo bp_loggedin_user_domain(); ?>"><?php _e('Mon compte', 'yproject'); ?></a>
						<ul>
							<?php
							if (is_user_logged_in() && !ypcf_check_user_can_invest(false)) {
								$page_update_account = get_page_by_path('modifier-mon-compte'); 
							?>
							<li style="border-bottom: 1px solid #FFF;" class="page_item_out"><a href="<?php echo get_permalink($page_update_account->ID); ?>"><?php _e('Terminer mon inscription', 'yproject'); ?></a></li>
							<?php
							}
							?>
							<li class="page_item_out"><a href="<?php echo wp_logout_url();echo '&page_id='.get_the_ID() ?>"><?php _e('Se deconnecter', 'yproject'); ?></a></li>
						</ul>
						</li>
						
					<?php else : ?>
						<?php /* Menu Connexion */ $page_connexion = get_page_by_path('connexion'); ?>
						<li id="menu_item_connection" class="page_item_out page_item_inverted"><a class="page_item_inverted" href="<?php echo get_permalink($page_connexion->ID);?>"><?php _e('Connexion', 'yproject'); ?></a></li>
					<?php endif; ?>
				</ul>
		    </div>
		</nav>
		
		<div id="submenu_item_connection">
		    <?php /* Sous-Menu Connexion */ $page_connexion_register = get_page_by_path('register'); ?>
		    <ul>
				<li class="page_item_out">
					<div id="submenu_item_connection_register"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blc_connexion.jpg" width="25" height="25" alt="Triangle blanc" />&nbsp;<a href="<?php echo get_permalink($page_connexion_register->ID); ?>">Cr&eacute;er un compte</a></div>
					<hr />
					<div class="social_connect_login_facebook"><a href="javascript:void(0);" class="social_connect_login_facebook"><img src="<?php echo $stylesheet_directory_uri; ?>/images/facebook_connexion.jpg" width="25" height="25" alt="Connexion Facebook" /><span>&nbsp;Se connecter avec Facebook</span></a></div>
					<div class="hidden"><?php dynamic_sidebar( 'sidebar-1' ); ?></div>
					<hr /> 
				   
					<div id="submenu_item_connection_login"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_noir_connexion.jpg" width="25" height="25" alt="Triangle noir" />&nbsp;Connexion</div>
					<form name="login-form" id="sidebar-login-form" class="standard-form" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" method="post">
					<input type="text" name="log" id="sidebar-user-login" class="input" placeholder="Identifiant ou e-mail" />
					<br />

					<input type="password" name="pwd" id="sidebar-user-pass" class="input" placeholder="<?php _e('Mot de passe', 'yproject'); ?>" />
					<input type="submit" name="wp-submit" id="sidebar-wp-submit" value="OK" />
					<input type="hidden" name="redirect-page" id="redirect-page" value="<?php echo get_the_ID();?>" />
					<br />
					<?php $page_forgotten = get_page_by_path('mot-de-passe-oublie'); ?>
					(<a style="color: #333333; text-align: right; font-size: 10px; font-style: italic;" href="<?php echo get_permalink($page_forgotten->ID); ?>">Mot de passe oubli&eacute;</a>)
					<br /><br />

					<label><input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" />&nbsp;<?php _e('Se souvenir de moi', 'yproject'); ?></label>
					</form>
				</li>
		    </ul>
		</div>

		<?php do_action( 'bp_header' ); ?>
		<?php do_action( 'bp_after_header'     ); ?>
		<?php do_action( 'bp_before_container' ); ?>

		<div></div>
	    
		<div id="container">
