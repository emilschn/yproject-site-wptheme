<?php 
    global $facebook_infos;
    global $twitter_infos;
    
    /* Récupération des infos Facebook */
    require_once("_external/facebook/facebook.php");
    $facebook = new Facebook(array(
	'appId'  => YP_FB_APP_ID,
	'secret' => YP_FB_SECRET,
    ));
    $fb_infos = $facebook->api(YP_FB_URL); 
    if ($fb_infos) $facebook_infos = $fb_infos['likes'];
    /* Récupération des infos Twitter */
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
    if ($followers) $twitter_infos = $followers->followers_count;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<?php if ( current_theme_supports( 'bp-default-responsive' ) ) : ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" /><?php endif; ?>
		
		<meta name="description" content="Plateforme d'investissement participatif &agrave; impact positif" />
		<title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?></title>
		
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<?php do_action( 'bp_head' ); ?>
		<?php wp_head(); ?>
		
		<script type="text/javascript" src="<?php if (WP_DEBUG) echo 'http://localhost/taffe/wp-yproject-site/wp-content/themes/yproject'; else echo get_stylesheet_directory_uri(); ?>/_inc/js/common.js"></script>
		
		<!--[if lt IE 9]>
		    <script type="text/javascript" src="<?php if (WP_DEBUG) echo 'http://localhost/taffe/wp-yproject-site/wp-content/themes/yproject'; else echo get_stylesheet_directory_uri(); ?>/_inc/js/html5shiv.js"></script>
		<![endif]-->
		
	</head>

	<body <?php body_class(); ?> id="bp-default">

		<?php do_action( 'bp_before_header' ); ?>

		<nav id="navigation" role="navigation">
		    <div class="center">
			<ul id="nav">
			    <?php /* Logo Accueil */ ?>
			    <li class="page_item_out page_item_logo"><a href="<?php echo home_url(); ?>" style="padding-left: 0px; padding-right: 0px;"><img border="0" src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png" width="160" height="100" /></a></li>
			    <?php /* Menu Découvrir les projets */ $page_discover = get_page_by_path('reveler-un-projet'); ?>
			    <li class="page_item"><span class="page_item_border"><a href="<?php echo get_permalink($page_discover->ID); ?>"><?php echo /*__('Decouvrir les projets', 'yproject');*/"R&eacute;v&eacute;ler un projet" ?></a></span></li>
			    <?php /* Menu Proposer un projet */ $page_start = get_page_by_path('proposer-un-projet'); ?>
			    <li class="page_item"><span class="page_item_border"><a href="<?php echo get_permalink($page_start->ID); ?>"><?php echo __('Proposer un projet', 'yproject'); ?></a></span></li>
			    <?php /* Menu Comment ça marche ? */ $page_how = get_page_by_path('descriptif'); ?>
			    <li class="page_item"><span class="page_item_border"><a href="<?php echo get_permalink($page_how->ID); ?>"><?php echo __('Comment ca marche ?', 'yproject'); ?></a></span></li>
			    <?php /* Menu Communauté */ $page_community = get_page_by_path('communaute'); ?>
			    <li class="page_item">
				<span class="page_item_border"><a href="<?php echo get_permalink($page_community->ID); ?>"><?php echo __('Communaute', 'yproject'); ?></a></span>
				<?php /*<ul>
				    $page_community_activity = get_page_by_path('activity'); $page_community_who = get_page_by_path('qui-sommes-nous'); $page_community_blog = get_page_by_path('blog'); ?>
				    <li class="page_item_out"><a href="<?php echo get_permalink($page_community_activity->ID); ?>"><?php echo __('Fil dactivite', 'yproject'); ?></a></li>
				    <li class="page_item_out"><a href="<?php echo get_permalink($page_community_who->ID); ?>"><?php echo __('Qui sommes-nous ?', 'yproject'); ?></a></li>
				    <li class="page_item_out"><a href="<?php echo get_permalink($page_community_blog->ID); ?>"><?php echo __('Blog', 'yproject'); ?></a></li>
				</ul> */?>
			    </li>
			    <?php /* Logo FB / TW */ ?>
			    <li class="page_item_out mobile_hidden" id="menu_item_facebook"><a href="https://www.facebook.com/wedogood.co" target="_blank" title="Notre page Facebook"><img border="0" src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook.png" width="20" height="20" /></a></li>
			    <li class="page_item_out mobile_hidden" id="menu_item_twitter"><a href="https://twitter.com/wedogood_co" target="_blank" title="Notre compte Twitter"><img border="0" src="<?php echo get_stylesheet_directory_uri(); ?>/images/twitter.png" width="20" height="20" /></a></li>
			    
			    <?php if (is_user_logged_in()) : ?>
			    <?php /* Menu Mon compte */ ?>
			    <li class="page_item_out page_item_inverted">
				<a class="page_item_inverted" href="<?php echo bp_loggedin_user_domain(); ?>"><?php _e('Mon compte', 'yproject'); ?></a>
				<ul>
				    <li class="page_item_out"><a href="<?php echo wp_logout_url( wp_guess_url() ); ?>"><?php _e('Se deconnecter', 'yproject'); ?></a></li>
				</ul>
			    </li>
			    <?php else : ?>
			    <?php /* Menu Connexion */ $page_connexion = get_page_by_path('connexion'); ?>
			    <li id="menu_item_connection" class="page_item_out page_item_inverted"><a class="page_item_inverted" href="<?php echo get_permalink($page_connexion->ID); ?>"><?php _e('Connexion', 'yproject'); ?></a></li>
			    <?php endif; ?>
			    
			    <?php /* <li style="clear:both" class="only_on_mobile"></li> */ ?>
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
			    <div id="submenu_item_connection_register"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blc_connexion.jpg" width="25" height="25" />&nbsp;<a href="<?php echo get_permalink($page_connexion_register->ID); ?>">Cr&eacute;er un compte</a></div>
			    <hr />
			    <div class="social_connect_login_facebook"><a href="javascript:void(0);" class="social_connect_login_facebook"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook_connexion.jpg" width="25" height="25"/><span>&nbsp;Se connecter avec Facebook</span></a></div>
			 
   <div class="hidden"><?php dynamic_sidebar( 'sidebar-1' ); ?></div>
			    <hr /> <?php $page_forgotten = get_page_by_path('mot-de-passe-oublie'); ?>
			   
			    <div id="submenu_item_connection_login"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_noir_connexion.jpg" width="25" height="25" />&nbsp;Connexion</div>
			    <form name="login-form" id="sidebar-login-form" class="standard-form" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" method="post">
				<input type="text" name="log" id="sidebar-user-login" class="input" placeholder="Identifiant ou e-mail" />
				<br />



				<input type="password" name="pwd" id="sidebar-user-pass" class="input" placeholder="<?php _e('Mot de passe', 'yproject'); ?>" />
				<input type="submit" name="wp-submit" id="sidebar-wp-submit" value="OK" />
				<br />

(<a style="color: #333333; text-align: right; font-size: 10px; font-style: italic;" href="<?php echo get_permalink($page_forgotten->ID); ?>">Mot de passe oubli&eacute;</a>)
<br><br>

				<label><input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" />&nbsp;<?php _e('Se souvenir de moi', 'yproject'); ?></label> 
				
				<input type="hidden" name="testcookie" value="1" />
			    </form>
			</li>
		    </ul>
		</div>

		<?php do_action( 'bp_header' ); ?>
		<?php do_action( 'bp_after_header'     ); ?>
		<?php do_action( 'bp_before_container' ); ?>

		<div></div>
	    
		<div id="container">
