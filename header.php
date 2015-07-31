<?php 
	global $WDG_cache_plugin, $stylesheet_directory_uri, $is_campaign_page, $campaign, $post, $current_user;
	$stylesheet_directory_uri = get_stylesheet_directory_uri();
	date_default_timezone_set("Europe/Paris");
	ypcf_session_start();
	UIHelpers::init_social_infos();
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head>
		<?php $title_str = UIHelpers::current_page_title();
		if ($title_str) : ?>
		<title><?php echo $title_str; ?></title>
		<?php else : ?>
		<title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?></title>
		<?php endif; ?>
		
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
		<?php if ( current_theme_supports( 'bp-default-responsive' ) ) : ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" /><?php endif; ?>
		<meta name="description" content="Plateforme d'investissement participatif a impact positif" />
		
		<!--[if lt IE 9]>
		    <script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/html5shiv.js"></script>
		<![endif]-->
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?d=20150710" type="text/css" media="screen" />
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
		<meta property="og:title" content="WEDOGOOD<?php 
                    if ($is_campaign_page === true) {
			    echo " : ".$campaign->data->post_title; 
                    } 
                    ?>" />
		<meta property="og:description" content="<?php 
                    if($is_campaign_page === true){
			    echo $campaign->subtitle()." ".$campaign->summary();
                    } else {
			    echo "Plateforme d'investissement participatif a impact positif";
                    }
                    ?>" />
                <?php 
                    if($is_campaign_page === true){
			$imageFacebook = $campaign->get_home_picture_src();
                    } else {
			$imageFacebook = $stylesheet_directory_uri .'/images/logo_entier.jpg';	
                    }
		?> 
		<meta property="og:image" content="<?php echo $imageFacebook ?>" />
		<meta property="og:image:secure_url" content="<?php echo $imageFacebook ?>" />
		<meta property="og:image:type" content="image/jpeg" />
	</head>

	<body <?php body_class(); ?> id="bp-default"> 
		<?php
		global $post;
		$menu_pages = array(
			'les-projets' => 'Les projets',
			'financement' => 'Financer son projet',
			'descriptif' => 'Comment ca marche ?',
			'blog' => 'Actualit&eacute;s'
		);
		?>

		<nav id="navigation" role="navigation">
			<div class="center">
				<ul id="nav">
				    
					<?php
					//*******************
					//CACHE MENU
					$cache_menu = $WDG_cache_plugin->get_cache('menu-items', 2);
					if ($cache_menu !== FALSE) { echo $cache_menu; }
					else {
						ob_start();
					?>
				    
					<li class="page_item only_on_mobile">
						<span class="page_item_border"><a href="#" id="mobile-menu"><img src="<?php echo $stylesheet_directory_uri; ?>/images/menu-smartphone.png" alt="bouton menu mobile" /></a></span>
					</li>
				    
					<?php /* Logo Accueil */ ?>
					<li class="page_item_out page_item_logo tablet_hidden"><a href="<?php echo home_url(); ?>" style="padding-left: 0px; padding-right: 14px;">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/logo.png" width="160" height="100" alt="logo" class="mobile_hidden" />
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/logo_court.png" width="160" height="51" alt="logo court" class="only_on_mobile" style="display: none;" />
					</a></li>
				    
					<li class="page_item only_on_tablet" style="display: none;"><span><a href="<?php echo home_url(); ?>">WEDOGOOD.co</a></span></li>
				    
					<?php 
					foreach ($menu_pages as $menu_page_key => $menu_page_label): ?>
						<?php $menu_page_object = get_page_by_path($menu_page_key); ?>
						<li class="page_item mobile_hidden"><span class="page_item_border"><a href="<?php echo get_permalink($menu_page_object->ID); ?>"><?php _e($menu_page_label, 'yproject'); ?></a></span></li>
					<?php endforeach; ?>
				    
					<?php /* Logo FB / TW */ ?>
					<li class="page_item_out mobile_hidden" id="menu_item_facebook"><a href="https://www.facebook.com/wedogood.co" target="_blank" title="Notre page Facebook"><img src="<?php echo $stylesheet_directory_uri; ?>/images/facebook.png" width="20" height="20" alt="logo facebook" /></a></li>
					<li class="page_item_out mobile_hidden" id="menu_item_twitter"><a href="https://twitter.com/wedogood_co" target="_blank" title="Notre compte Twitter"><img src="<?php echo $stylesheet_directory_uri; ?>/images/twitter.png" width="20" height="20" alt="logo twitter" /></a></li>
					<?php
						$cache_menu = ob_get_contents();
						$WDG_cache_plugin->set_cache('menu-items', $cache_menu, 60*60*24, 1);
						ob_end_clean();
						echo $cache_menu;
					}
					//FIN CACHE MENU
					//*******************
					?>
					
					
					<?php
					if (is_user_logged_in()) : 
						// Menu Mon compte
						$page_update_account = get_page_by_path('modifier-mon-compte'); 
						?>
						<li class="page_item_out page_item_inverted mobile_hidden">
						<a class="page_item_inverted" href="<?php echo bp_loggedin_user_domain(); ?>"><?php _e('Mon compte', 'yproject'); ?></a>
						<ul>
							<li class="page_item_out upper"><a href="<?php echo get_permalink($page_update_account->ID); ?>"><?php _e('Param&egrave;tres', 'yproject'); ?></a></li>
							<?php 
							$project_list = LibUsers::get_projects_by_id(bp_loggedin_user_id(), TRUE); 
							foreach ($project_list as $project_id) {
							    if (!empty($project_id)) {
							    $post_campaign = get_post($project_id);
							?>
							<li class="page_item_out"><a href="<?php echo get_permalink($project_id); ?>"><?php echo $post_campaign->post_title; ?></a></li>
							<?php } } ?>
							<li class="page_item_out last upper"><a href="<?php echo wp_logout_url();echo '&page_id='.get_the_ID() ?>"><?php _e('Se deconnecter', 'yproject'); ?></a></li>
						</ul>
						</li>
						
					<?php else : ?>
						<?php /* Menu Connexion */ $page_connexion = get_page_by_path('connexion'); ?>
						<li id="menu_item_connection" class="page_item_out page_item_inverted mobile_hidden"><a class="page_item_inverted" href="<?php echo get_permalink($page_connexion->ID); ?>"><?php _e('Connexion', 'yproject'); ?></a></li>
					<?php endif; ?>
				</ul>
			</div>
		</nav>
            
		<div id="submenu_item_connection">
                    <?php /* Sous-Menu Connexion */ $page_connexion_register = get_page_by_path('register'); ?>
                    <ul>
                        <li class="page_item_out">
                                <div id="submenu_item_connection_register" style="background-color: #3E3E40;"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blc_connexion.jpg" width="25" height="25" alt="Triangle blanc" />&nbsp;<a href="<?php echo get_permalink($page_connexion_register->ID); ?>">Cr&eacute;er un compte</a></div>
                                <hr />
                                <div class="social_connect_login_facebook"><a href="javascript:void(0);" class="social_connect_login_facebook"><img src="<?php echo $stylesheet_directory_uri; ?>/images/facebook_connexion.jpg" width="25" height="25" alt="connexion facebook" /><span>&nbsp;Se connecter avec Facebook</span></a></div>
                                <div class="hidden"><?php dynamic_sidebar( 'sidebar-1' ); ?></div>
                                <hr /> 

                                <div id="submenu_item_connection_login"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_noir_connexion.jpg" width="25" height="25" alt="triangle noir" />&nbsp;Connexion</div>
                                <form method="post" action="" name="login-form" id="sidebar-login-form" class="standard-form" >
                                    <input type="text" name="log" id="sidebar-user-login" class="input" placeholder="Identifiant ou e-mail" />
                                    <br />

                                    <input type="password" name="pwd" id="sidebar-user-pass" class="input" placeholder="<?php _e('Mot de passe', 'yproject'); ?>" />
                                    <input type="submit" name="wp-submit" id="sidebar-wp-submit" value="OK" />

                                    <input type="hidden" name="redirect-page" value="<?php echo YPUsersLib::get_login_redirect_page(); ?>" />
                                    <input type="hidden" name="redirect-error" value="<?php $page_connexion = get_page_by_path("connexion"); echo get_permalink($page_connexion->ID); ?>?login=failed" />
				    <input type="hidden" name="login-form" value="1" />
                                    <br />
                                    <?php $page_forgotten = get_page_by_path('mot-de-passe-oublie'); ?>
                                    <a style="color: #333333; text-align: right; font-size: 10px; font-style: italic;" href="<?php echo get_permalink($page_forgotten->ID); ?>">(Mot de passe oubli&eacute;)</a>
                                    <br /><br />
                                    <label><input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" />&nbsp;<?php _e('Se souvenir de moi', 'yproject'); ?></label>
                                </form>
                        </li>
                    </ul>                   
		</div>
	    
		<div id="submenu-mobile">
                    <ul>    
                        <?php foreach ($menu_pages as $menu_page_key => $menu_page_label): ?>
                                <?php $menu_page_object = get_page_by_path($menu_page_key); ?>
                                <li class="page_item"><a href="<?php echo get_permalink($menu_page_object->ID); ?>"><?php _e($menu_page_label, 'yproject'); ?></a></li>
                        <?php endforeach; ?>

                        <?php if (is_user_logged_in()) : ?>
                                <?php /* Menu Mon compte */ ?>
                                <?php $page_update_account = get_page_by_path('modifier-mon-compte'); ?>
                                <li class="page_item"><a href="<?php echo bp_loggedin_user_domain(); ?>"><?php _e('Mon compte', 'yproject'); ?></a></li>
                                <li class="page_item"><a href="<?php echo get_permalink($page_update_account->ID); ?>"><?php _e('Param&egrave;tres', 'yproject'); ?></a></li>
                                <li class="page_item"><a href="<?php echo wp_logout_url();echo '&page_id='.get_the_ID() ?>"><?php _e('Se deconnecter', 'yproject'); ?></a></li>
                        <?php else : ?>
                                <?php /* Menu Connexion */ ?>
                                <?php $page_connexion = get_page_by_path('connexion'); ?>
                                <li class="page_item"><a href="<?php echo get_permalink($page_connexion->ID); ?>"><?php _e('Connexion', 'yproject'); ?></a></li>
                        <?php endif; ?>
                    </ul>             
		</div>
             
		<?php 
		LibUsers::check_validate_general_terms();
		if (LibUsers::must_show_general_terms_block()): 
			global $edd_options;
		?>
		<div id="validate-terms" class="wdg-lightbox">
			<div class="wdg-lightbox-padder">
				<span>Mise &agrave; jour des conditions g&eacute;n&eacute;rales d&apos;utilisation</span>
				<div class="validate-terms-excerpt">
					<?php echo wpautop( stripslashes( $edd_options[LibUsers::$edd_general_terms_excerpt])); ?>
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
            
                <?php 
                /*if (is_user_logged_in()){
                    $userId = get_current_user_id(); 
                    $check = yproject_check_is_warning_meta_init($userId);
                    if($check){ ?>
                        <?php 
                            ob_start();
                                locate_template('common/warning-lightbox.php',true);
                                $content = ob_get_contents();
                            ob_end_clean();
                        ?>
                        <div class="wdg-lightbox">
                            <div class="wdg-lightbox-padder">
                                <div class="validate-terms-excerpt">
                                     <?php echo $content; ?>
				</div>
                                    
                            </div>
                        </div>
                    <?php }
                }*/
                ?>
                <div id="container"> 
