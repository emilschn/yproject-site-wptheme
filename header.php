<?php 
	global $WDG_cache_plugin, $stylesheet_directory_uri, $is_campaign_page, $campaign, $post, $current_user;
	if ($WDG_cache_plugin == null) {
		$WDG_cache_plugin = new WDG_Cache_Plugin();
	}
	$page_controler = WDG_Templates_Engine::instance()->get_controler();
	
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
	
	if ( $page_controler->get_header_nav_visible() ){
		$projects_searchable = array();
		$cache_projects_searchable = $WDG_cache_plugin->get_cache('ATCF_Campaign::list_projects_searchable', 2);
		if ($cache_projects_searchable !== FALSE) { $projects_searchable = json_decode($cache_projects_searchable); }
		else {
			$projects_searchable = ATCF_Campaign::list_projects_searchable();
			$projects_searchable_encoded = json_encode($projects_searchable);
			$WDG_cache_plugin->set_cache('ATCF_Campaign::list_projects_searchable', $projects_searchable_encoded, 60*60*3, 2); //MAJ 3h
		}
	}
	
	
	wp_reset_query();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head>
		<?php if ( ATCF_CrowdFunding::get_platform_context() == 'wedogood' ): ?>
		<link href="<?php echo $stylesheet_directory_uri; ?>/images/favicon.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
		<!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="<?php echo $stylesheet_directory_uri; ?>/images/favicon.ico"/><![endif]-->
		<?php else: ?>
		<link href="<?php echo $stylesheet_directory_uri; ?>/images/favicon/chart.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
		<!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="<?php echo $stylesheet_directory_uri; ?>/images/favicon/chart.ico"/><![endif]-->
		<?php endif; ?>
		<title><?php echo $page_controler->get_page_title(); ?></title>
		
		<?php if ($is_campaign_page): ?>
		<link rel="alternate" href="<?php echo get_permalink( $campaign->ID ); ?>?lang=fr_FR" hreflang="fr" />
			<?php
			$lang_list = $campaign->get_lang_list();
			if (!empty($lang_list)):
				foreach ($lang_list as $lang): $short_lang_str = substr($lang, 0, 2); ?>
		<link rel="alternate" href="<?php echo get_permalink( $campaign->ID ); ?>?lang=<?php echo $lang; ?>" hreflang="<?php echo $short_lang_str; ?>" />
				<?php endforeach;
			endif; ?>
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
		<meta name="description" content="<?php echo $page_controler->get_page_description(); ?>" />
		
		<!--[if lt IE 9]>
		    <script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/html5shiv.js"></script>
		<![endif]-->
		<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/common.min.css?d=<?php echo ASSETS_VERSION; ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?d=<?php echo ASSETS_VERSION; ?>" type="text/css" media="screen" />
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
		<?php
		$imageFacebook = (isset($campaign) && $is_campaign_page === true) ? $campaign->get_home_picture_src() : $stylesheet_directory_uri .'/images/common/wedogood-logo-rouge.png';
		$url = (isset($campaign) && $is_campaign_page === true) ? get_page_link($post) : "";
		?>
		<?php if (isset($campaign) && $is_campaign_page === true): ?>
		<meta property="og:url" content="<?php echo $url; ?>" />
		<meta property="og:title" content="<?php echo $post->post_title; ?>" />
		<meta property="og:description" content="<?php echo str_replace( array( '<br>', '<br />' ), '', $campaign->summary() ); ?>" />
		
		<?php else: ?>
		<meta property="og:description" content="Première plateforme de financement participatif en royalties (royalty crowdfunding). Entrepreneurs : levez des fonds sans diluer votre capital !" />
		
		<?php endif; ?>
		<meta property="og:image" content="<?php echo $imageFacebook ?>" />
		<meta property="og:image:secure_url" content="<?php echo $imageFacebook ?>" />
		<meta property="og:image:type" content="image/png" />
		<meta property="fb:app_id" content="<?php echo YP_FB_APP_ID; ?>" />
	</head>

	<body <?php body_class(get_locale()); ?>>
		
		<?php if ( $page_controler->get_header_nav_visible() ): ?>
		<nav id="main">
			<div id="menu">
				<a href="<?php echo home_url(); ?>"><img id="logo_wdg" src="<?php echo $stylesheet_directory_uri; ?>/images/navbar/logo-wdg.png" alt="WE DO GOOD" width="178" height="33" /></a>
				<a href="<?php echo home_url( '/les-projets' ); ?>" class="lines"><?php _e( "Les projets", 'yproject' ); ?></a>
				<a href="<?php echo home_url( '/financement' ); ?>" class="lines"><?php _e( "Financer mon projet", 'yproject' ); ?></a>
				<a href="<?php echo home_url( '/investissement' ); ?>" class="lines"><?php _e( "Investir en royalties", 'yproject' ); ?></a>
				<a href="<?php echo home_url( '/vision' ); ?>" class="lines"><?php _e( "Vision", 'yproject' ); ?></a>
                                
				<a href="#" id="btn-search"><img class="search inactive" src="<?php echo $stylesheet_directory_uri; ?>/images/navbar/recherche-icon.png" alt="SEARCH" /></a>
				<?php if (is_user_logged_in()): ?>
				<a href="#" class="btn-user connected"><?php UIHelpers::print_user_avatar($WDGUser_current->wp_user->ID, 'icon'); ?></a>				
				<?php else: ?>
				<a href="#" class="btn-user not-connected inactive"><img src="<?php echo $stylesheet_directory_uri; ?>/images/navbar/profil-icon-noir.png" alt="USER" /></a>
				<?php endif; ?>
				<a href="#" id="btn-burger" class="only-inf997"><img src="<?php echo $stylesheet_directory_uri; ?>/images/navbar/menu-burger.png" alt="MENU" /></a>
				
				
				<?php /* Affichage quand clic sur Rechercher */ ?>
				<div id="submenu-search" class="submenu-style hidden">
					<div class="only-inf997">
						<a href="<?php echo home_url( '/les-projets' ); ?>"><?php _e( "Les projets", 'yproject' ); ?></a>
						<a href="<?php echo home_url( '/financement' ); ?>"><?php _e( "Financer mon projet", 'yproject' ); ?></a>
						<a href="<?php echo home_url( '/investissement' ); ?>"><?php _e( "Investir en royalties", 'yproject' ); ?></a>
						<a href="<?php echo home_url( '/vision' ); ?>"><?php _e( "Vision", 'yproject' ); ?></a>
					</div>
					
					<input type="text" id="submenu-search-input" placeholder="<?php _e("Rechercher un projet", 'yproject'); ?>" />
					<ul class="submenu-list">
						<?php foreach ($projects_searchable as $project_post): ?>
						<li class="hidden"><a href="<?php echo get_permalink( $project_post->ID ); ?>"><?php echo $project_post->post_title; ?><span class="hidden"><?php echo $project_post->post_name; ?></span></a></li>
						<?php endforeach; ?>
					</ul>
				</div>

				<?php if (is_user_logged_in()): ?>
				<div id="submenu-user" class="connected submenu-style hidden">
					<?php /* Au clic picto Compte, afficher menu utilisateur */ ?>
					<?php global $current_user; get_currentuserinfo();
					$user_name_str = ($current_user->user_firstname != '') ? $current_user->user_firstname : $current_user->user_login;
					$page_dashboard = home_url( '/tableau-de-bord' );
					?>
					<span id="submenu-user-hello"><?php _e("Bonjour", 'yproject'); ?> <?php echo $user_name_str; ?> !</span>
					<ul class="submenu-list">
						<li><a href="<?php echo home_url( '/mon-compte' ); ?>"><?php _e("Mon compte", 'yproject'); ?></a></li>
						
						<?php foreach ($project_list as $project_id): if (!empty($project_id)): $post_campaign = get_post($project_id); if (isset($post_campaign)): ?>
							<li><a href="<?php echo $page_dashboard . '?campaign_id=' .$project_id; ?>"><?php echo $post_campaign->post_title; ?></a></li>
						<?php endif; endif; endforeach; ?>
					</ul>
					
					<div id="button-logout" class="box_connection_buttons red">
						<a href="<?php echo wp_logout_url(); echo '&page_id='.get_the_ID() ?>"><span><?php _e("Me d&eacute;connecter", 'yproject'); ?></span></a>
					</div>
				</div>
				
				
				<?php else: ?>
				<div id="submenu-user" class="not-connected submenu-style hidden">
					<?php /* Au clic picto Compte, afficher menu connexion */ ?>
					<div class="box_connection_buttons red">
						<a href="#register" class="wdg-button-lightbox-open" data-lightbox="register"><span><?php _e('Cr&eacute;er un compte', 'yproject'); ?></span></a>
					</div>

					<div class="box_connection_buttons blue">
						<a href="#" class="social_connect_login_facebook"><span><?php _e('Se connecter avec Facebook', 'yproject'); ?></span></a>
					</div>
					<div class="social_connect_login_facebook_loading align-center hidden">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading" />
					</div>

					<hr style="-moz-border-bottom-colors: none; -moz-border-left-colors: none; -moz-border-right-colors: none; -moz-border-top-colors: none; border-color: -moz-use-text-color; border-image: none; border-right: 0 none; border-style: solid none none; border-width: 2px 0 0; color: #000000; margin: 5% 5%;"/>

					<form method="post" action="<?php echo home_url( "/connexion" ); ?>" name="login-form" class="sidebar-login-form model-form">
						<span id="title-connection"><?php _e('Connexion', 'yproject'); ?></span>
						<input class="input_connection" id="identifiant" type="text" name="log" placeholder="<?php _e('Identifiant ou e-mail', 'yproject'); ?>" value="" />
						<br />

						<input class="input_connection" id="password" type="password" name="pwd" placeholder="Mot de passe" value="" />
						<div class="submit-center" style="display: none;">             
							<input type="submit" name="wp-submit" class="input_submit button red" id="connect" value="OK"/>
							<input type="hidden" class="redirect-page" name="redirect-page" value="<?php echo WDGUser::get_login_redirect_page(); ?>" />
							<input type="hidden" name="login-form" value="1" />
						</div>   

						<div>
							<?php $page_forgotten = get_page_by_path('mot-de-passe-oublie'); ?>
							<a href="<?php echo get_permalink($page_forgotten->ID); ?>"><?php _e('(Mot de passe oubli&eacute;)', 'yproject');?></a>
						</div>

						<input id="rememberme" type="checkbox" name="rememberme" value="forever" />
						<label><?php _e('Se souvenir de moi', 'yproject'); ?></label>
						<br />
						<br />
					</form>
				</div>
				<?php endif; ?>
				
			</div>
		</nav>
		<?php endif; ?>
            
                
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
				<form method="POST">
					<input type="hidden" name="action" value="validate-terms" />
					<label for="validate-terms-check-header"><input type="checkbox" id="validate-terms-check-header" name="validate-terms-check" /> J&apos;accepte les conditions g&eacute;n&eacute;rales d&apos;utilisation</label><br />
					<div style="text-align: center;"><input type="submit" value="Valider" class="button" /></div>
				</form> 
			</div>
		</div>
		<?php endif; ?>

		<?php if (!is_user_logged_in()): ?>
			<?php echo do_shortcode('[yproject_register_lightbox]'); ?>
			<?php if ( !isset( $post->post_name ) || $post->post_name != 'connexion' ): ?>
			<?php echo do_shortcode('[yproject_connexion_lightbox]'); ?>
			<?php endif; ?>
		
		<?php elseif (!isset($_SESSION['has_displayed_connected_lightbox']) || ($_SESSION['has_displayed_connected_lightbox'] != $current_user->ID)): ?>
			<?php $_SESSION['has_displayed_connected_lightbox'] = $current_user->ID; ?>
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
					<?php _e( "Bonjour", 'yproject' ); ?> <?php echo $user_name_str; ?>
					<?php if ( ATCF_CrowdFunding::get_platform_context() == "wedogood" ): ?>
					<?php _e( "et bienvenue sur WE DO GOOD !", 'yproject' ); ?>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		
		<?php if ( ATCF_CrowdFunding::get_platform_context() == 'wedogood' ): ?>
		<?php if($_SESSION['subscribe_newsletter_sendinblue'] == true): ?>
			<div class="timeout-lightbox wdg-lightbox">
				<div class="wdg-lightbox-click-catcher"></div>
				<div class="wdg-lightbox-padder">
					<p class="wdg-lightbox-msg-info"><?php _e("Votre inscription a bien été prise en compte!", 'yproject'); ?></p>
				</div>
			</div>
		<?php endif; ?>
		<?php $_SESSION['subscribe_newsletter_sendinblue'] = false; ?>
		<?php endif; ?>
		
		<?php if ( $page_controler->get_show_user_details_confirmation() ): ?>
			<?php locate_template( array( 'common/lightbox/user-details-lightbox.php' ), true ); ?>
		<?php endif; ?>
		
		<?php if ( $page_controler->get_show_user_pending_preinvestment() ): ?>
			<?php locate_template( array( 'common/lightbox/pending-preinvestment-lightbox.php' ), true ); ?>
		<?php endif; ?>
		
		<div id="container"> 
