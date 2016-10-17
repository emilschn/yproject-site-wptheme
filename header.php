<?php 
	global $WDG_cache_plugin, $stylesheet_directory_uri, $is_campaign_page, $campaign, $post, $current_user;
	$stylesheet_directory_uri = get_stylesheet_directory_uri();
	date_default_timezone_set("Europe/Paris");
	ypcf_session_start();
	$title_str = UIHelpers::current_page_title();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head>              
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
		<?php $version = '20161012'; ?>
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

		<?php do_action( 'bp_head' ); ?>
		<?php wp_head(); ?>

		<!-- Meta spécifiques à Facebook -->
		<?php /*<meta property="og:title" content="WEDOGOOD<?php if ($is_campaign_page === true) { echo " : ".$campaign->data->post_title; } ?>" />
		<meta property="og:description" content="<?php 
                    if ($is_campaign_page === true) {
			    echo ($campaign->subtitle() != '') ? $campaign->subtitle() : $campaign->data->post_title;
                    } else {
			    echo "Plateforme d'investissement participatif à impact positif";
                    } ?>" />*/ ?>
                <?php $imageFacebook = (isset($campaign) && $is_campaign_page === true) ? $campaign->get_home_picture_src() : $stylesheet_directory_uri .'/images/logo_entier.jpg'; ?> 
		<?php /* <meta property="og:image" content="<?php echo $imageFacebook ?>" /> */ ?>
		<meta property="og:image:secure_url" content="<?php echo $imageFacebook ?>" />
		<meta property="og:image:type" content="image/jpeg" />
	</head>

	<body <?php body_class(get_locale()); ?>> 
		<nav id="main">
			<div class="center-lg">
                            <a href="<?php echo home_url(); ?>"><img id="logo_wdg" src="<?php echo $stylesheet_directory_uri; ?>/images/navbar/logo-wdg.png" alt="logo we do good"/></a>
				<a href="<?php echo home_url( '/vision' ); ?>" class="hidden-inf997 lines"><?php _e( "Vision", 'yproject' ); ?></a>
				<a href="<?php echo home_url( '/financement' ); ?>" class="hidden-inf997 lines"><?php _e( "Financer son projet", 'yproject' ); ?></a>
				<a href="<?php echo home_url( '/investissement' ); ?>" class="hidden-inf997 lines"><?php _e( "Investir en royalties", 'yproject' ); ?></a>
				<a href="<?php echo home_url( '/les-projets' ); ?>" class="hidden-inf997 lines"><?php _e( "Les projets", 'yproject' ); ?></a>
                                
                                <a href="#box_search"><img class="search inactive" src="<?php echo $stylesheet_directory_uri; ?>/images/navbar/recherche-icon.png"/></a>
                                <?php if (is_user_logged_in()): ?>
                                <a href="page-modifier-mon-compte.php" id="avatar" class="hidden-inf997"><img src="<?php //echo WDGUser::get_avatar()?>"/></a> <!-- photo du user à récupérer ici, début de fonction créée dans user.php  -->
				<?php else: ?>
				<a href="#box_connection" class="profil_button inactive"><img id="profil_logo" class="hidden-inf997" src="<?php echo $stylesheet_directory_uri; ?>/images/navbar/profil-icon-noir.png"/></a>
				<?php endif; ?>
				<a href="#" class="only-inf997">Burg</a>
			</div>
		</nav>
                
                <!-- Sur click mon recherche, afficher champ de recherche -->
                <div id="box_search" class="box-style" style="display: inline-block;">
                    
                </div>
            
            
                <!-- Sur click mon compte, afficher menu connexion -->
                <div id="box_connection" class="box-style" style="display: none">
                    <?php if (WDGUser::has_login_errors()): ?>
                    <div class="errors">
                            <?php echo WDGUser::display_login_errors(); ?>
                    </div>
                    <?php endif; ?>

                    <div id="connexion_facebook_container">                     
                        <div id="submenu_item_connection_register" class="box_connection_buttons red">
                            <a href="#register" class="wdg-button-lightbox-open" data-lightbox="register"><span><?php _e('Cr&eacute;er un compte', 'yproject'); ?></span></a>
                        </div>                       
                    </div>
                                       
                    <div id="connexion_facebook_container" class="box_connection_buttons blue">
                        <a href="javascript:void(0);" class="social_connect_login_facebook"><span><?php _e('Se connecter avec Facebook', 'yproject'); ?></span></a>
                    </div>
                                        
                    <div class="hidden"><?php dynamic_sidebar('sidebar-1'); ?></div>

                    <hr style="-moz-border-bottom-colors: none; -moz-border-left-colors: none; -moz-border-right-colors: none; -moz-border-top-colors: none; border-color: -moz-use-text-color; border-image: none; border-right: 0 none; border-style: solid none none; border-width: 2px 0 0; color: #000000; margin: 5% 5%;"/>
                                      
                    <form method="post" action="" name="login-form" id="sidebar-login-form" class="model-form">
                        <h2 style="margin: 0% 5%;"><?php _e('connexion', 'yproject'); ?></h2>
                        <input class="input_connection" id="identifiant" type="text" name="log" placeholder="Identifiant ou e-mail" value="" />
                        <br />

                        <input class="input_connection" id="password" type="password" name="pwd" placeholder="Mot de passe" value="" />
                        <div id="submit-center" style="display: none;">             
                            <input type="submit"  name="wp-submit" class="input_submit" id="connect" value="OK"/>
                            <input type="hidden" id="redirect-page" name="redirect-page" value="<?php echo WDGUser::get_login_redirect_page(); ?>" />
                            <input type="hidden" name="login-form" value="1" />
                        </div>   
                        
                        <div id="sidebar-login-form-lightbox">
                            <?php $page_forgotten = get_page_by_path('mot-de-passe-oublie'); ?>
                            <a href="<?php echo get_permalink($page_forgotten->ID); ?>" style="margin: 0% 5%;"><?php _e('(Mot de passe oubli&eacute)', 'yproject');?></a>
                        </div>

                        <input id="rememberme" type="checkbox" name="rememberme" value="forever" style="margin: 2% 0% 0% 5%; cursor: pointer;" />
                        <label><?php _e('Se souvenir de moi', 'yproject'); ?></label>
                        <br />

                                           
                        <br />
                    </form>   
                    
                    
                </div>
                
		<?php 
		WDGUser::check_validate_general_terms();
		if (WDGUser::must_show_general_terms_block()): 
			global $edd_options;
		?>
		<div id="validate-terms" class="wdg-lightbox">
			<div class="wdg-lightbox-padder">
				<span>Mise &agrave; jour des conditions g&eacute;n&eacute;rales d&apos;utilisation</span>
				<div class="validate-terms-excerpt">
					<?php echo wpautop( stripslashes( $edd_options[WDGUser::$edd_general_terms_excerpt])); ?>
				</div>
				<form action="" method="POST">
					<input type="hidden" name="action" value="validate-terms" />
					<label for="validate-terms-check"><input type="checkbox" name="validate-terms-check" /> J&apos;accepte les conditions g&eacute;n&eacute;rales d&apos;utilisation</label><br />
					<div style="text-align: center;"><input type="submit" value="Valider" class="button" /></div>
				</form> 
			</div>
		</div>
		<?php endif; ?>
           
		<?php 
		if (is_user_logged_in() && (!isset($_SESSION['has_displayed_connected_lightbox']) || ($_SESSION['has_displayed_connected_lightbox'] != $current_user->ID))): 
			$_SESSION['has_displayed_connected_lightbox'] = $current_user->ID; 
		?>
		<div class="timeout-lightbox wdg-lightbox">
			<div class="wdg-lightbox-click-catcher"></div>
			<?php 
			get_currentuserinfo();
			$user_name_str = $current_user->user_firstname;
			if ($user_name_str == '') {
				$user_name_str = $current_user->user_login;
			}
			?>
			<div class="wdg-lightbox-padder">
				Bonjour <?php echo $user_name_str; ?>, bienvenue sur WE DO GOOD !
			</div>
		</div>
		<?php endif; ?>
		
		<div id="container"> 
