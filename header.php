<?php 
    global $facebook_infos;
    global $twitter_infos;
    
    /* Récupération des infos Facebook */
    require_once("_external/facebook/facebook.php");
    $facebook = new Facebook(array(
	'appId'  => '370169356424813',
	'secret' => 'e1f7ee659011a09c8765cf379719104f',
    ));
    $fb_infos = $facebook->api('http://graph.facebook.com/381460615282040'); 
    $facebook_infos = $fb_infos['likes'];
    /* Récupération des infos Twitter *
    require_once("_external/twitter/TwitterAPIExchange.php");
    $apiUrl = "https://api.twitter.com/1.1/users/show.json";
    $requestMethod = 'GET';
    $getField = '?screen_name=yproject_co';
    $settings = array(
        'oauth_access_token' => "YOUR_OAUTH_ACCESS_TOKEN",
        'oauth_access_token_secret' => "YOUR_OAUTH_ACCESS_TOKEN_SECRET",
        'consumer_key' => "YOUR_CONSUMER_KEY",
        'consumer_secret' => "YOUR_CONSUMER_SECRET"
    );

    $twitter = new TwitterAPIExchange($settings);
    $response = $twitter->setGetfield($getField)
			->buildOauth($apiUrl, $requestMethod)
			->performRequest();
    echo $response;
    $followers = json_decode($response);
    $twitter_infos = $followers->followers_count;*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<?php if ( current_theme_supports( 'bp-default-responsive' ) ) : ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" /><?php endif; ?>
		<title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?></title>
		
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<?php do_action( 'bp_head' ); ?>
		<?php wp_head(); ?>
		
		<script type="text/javascript" src="<?php if (WP_DEBUG) echo 'http://localhost/taffe/wp-yproject-site/wp-content/themes/yproject'; else echo get_stylesheet_directory_uri(); ?>/_inc/js/common.js"></script>
	</head>

	<body <?php body_class(); ?> id="bp-default">

		<?php do_action( 'bp_before_header' ); ?>

		<nav id="navigation" role="navigation">
		    <div class="center">
			<ul id="nav">
			    <?php /* Logo Accueil */ ?>
			    <li class="page_item"><a href="<?php echo home_url(); ?>"><img src="" width="32" height="16" /></a></li>
			    <?php /* Menu Découvrir les projets */ $page_discover = get_page_by_path('projects'); ?>
			    <li class="page_item">
				<a href="<?php echo get_permalink($page_discover->ID); ?>"><?php echo __('Decouvrir les projets', 'yproject'); ?></a>
				<?php /*<ul>
				    <li class="page_item_out"><a href="#">CAT1</a></li>
				    <li class="page_item_out"><a href="#">CAT2</a></li>
				</ul> */ ?>
			    </li>
			    <?php /* Menu Proposer un projet */ $page_start = get_page_by_path('proposer-un-projet'); ?>
			    <li class="page_item"><a href="<?php echo get_permalink($page_start->ID); ?>"><?php echo __('Proposer un projet', 'yproject'); ?></a></li>
			    <?php /* Menu Comment ça marche ? */ $page_how = get_page_by_path('descriptif'); ?>
			    <li class="page_item"><a href="<?php echo get_permalink($page_how->ID); ?>"><?php echo __('Comment ca marche ?', 'yproject'); ?></a></li>
			    <?php /* Menu Communauté */ $page_community = get_page_by_path('community'); ?>
			    <li class="page_item_out">
				<a href="<?php echo get_permalink($page_community->ID); ?>"><?php echo __('Communaute', 'yproject'); ?></a>
				<ul>
				    <?php /* Sous-Menu Communauté */ $page_community_activity = get_page_by_path('activity'); $page_community_who = get_page_by_path('qui-sommes-nous'); $page_community_blog = get_page_by_path('blog'); ?>
				    <li class="page_item_out"><a href="<?php echo get_permalink($page_community_activity->ID); ?>"><?php echo __('Fil dactivite', 'yproject'); ?></a></li>
				    <li class="page_item_out"><a href="<?php echo get_permalink($page_community_who->ID); ?>"><?php echo __('Qui sommes-nous ?', 'yproject'); ?></a></li>
				    <li class="page_item_out"><a href="<?php echo get_permalink($page_community_blog->ID); ?>"><?php echo __('Blog', 'yproject'); ?></a></li>
				</ul>
			    </li>
			    <?php /* Logo FB / TW */ ?>
			    <li class="page_item_out mobile_hidden" id="menu_item_facebook"><a href="https://www.facebook.com/pages/Y-Project/381460615282040" target="_blank" title="Notre page Facebook"><img src="" width="16" height="16" /></a></li>
			    <li class="page_item_out mobile_hidden" id="menu_item_twitter"><a href="https://twitter.com/yproject_co" target="_blank" title="Notre compte Twitter"><img src="" width="16" height="16" /></a></li>
			    
			    <?php if (is_user_logged_in()) : ?>
			    <?php /* Menu Mon compte */ ?>
			    <li class="page_item_out page_item_inverted">
				<a class="page_item_inverted" href="<?php echo bp_loggedin_user_domain(); ?>"><?php _e('Mon compte', 'yproject'); ?></a>
				<ul>
				    <?php /* Sous-Menu Mon compte */ $page_community_activity = get_page_by_path('activity'); $page_community_who = get_page_by_path('qui-sommes-nous'); $page_community_blog = get_page_by_path('blog'); ?>
				    <li class="page_item_out"><a href="<?php echo wp_logout_url( wp_guess_url() ); ?>"><?php _e('Se deconnecter', 'yproject'); ?></a></li>
				</ul>
			    </li>
			    <?php else : ?>
			    <?php /* Menu Connexion */ $page_connexion = get_page_by_path('connexion'); ?>
			    <li id="menu_item_connection" class="page_item_out page_item_inverted"><a class="page_item_inverted" href="<?php echo get_permalink($page_connexion->ID); ?>"><?php _e('Connexion', 'yproject'); ?></a></li>
			    <?php endif; ?>
			    
			    <li style="clear:both" class="only_on_mobile"></li>
			</ul>
		    </div>
		    <?php
			if (is_user_logged_in() && !ypcf_check_user_can_invest(false)) {
			    $page_update_account = get_page_by_path('modifier-mon-compte'); 
		    ?>
			<div id="finish_subscribe"><a href="<?php echo get_permalink($page_update_account->ID); ?>"><?php _e('Terminer mon inscription', 'yproject'); ?></a></div>
		    <?php
			}
		    ?>
		</nav>
		<div id="fb_infos">
		    <?php echo $facebook_infos; ?>
		</div>
		<div id="twitter_infos">
		    <?php echo $twitter_infos; ?>
		</div>
		<div id="submenu_item_connection">
		    <?php /* Sous-Menu Connexion */ $page_connexion_register = get_page_by_path('register'); ?>
		    <ul>
			<li class="page_item_out">
			    <form name="login-form" id="sidebar-login-form" class="standard-form" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" method="post">
				<label><?php _e('Identifiant', 'yproject'); ?></label>
				<input type="text" name="log" id="sidebar-user-login" class="input" value="<?php if ( isset( $user_login) ) echo esc_attr(stripslashes($user_login)); ?>" />
				<br />

				<label><?php _e('Mot de passe', 'yproject'); ?></label>
				<input type="password" name="pwd" id="sidebar-user-pass" class="input" value="" />
				<br />

				<p class="forgetmenot">
				    <input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" /> <label><?php _e('Se souvenir de moi', 'yproject'); ?></label>
				    <input type="submit" name="wp-submit" id="sidebar-wp-submit" value="<?php _e('Connexion', 'yproject'); ?>" />
				</p>
				
				<input type="hidden" name="testcookie" value="1" />
			    </form>
			    
			    <div><?php dynamic_sidebar( 'sidebar-1' ); ?></div>
			    <br />
			    <div><a href="<?php echo get_permalink($page_connexion_register->ID); ?>"><?php _e('Sinscrire', 'yproject'); ?></a></div>
			</li>
		    </ul>
		</div>

		<?php do_action( 'bp_header' ); ?>
		<?php do_action( 'bp_after_header'     ); ?>
		<?php do_action( 'bp_before_container' ); ?>

		<div></div>
	    
		<div id="container">
