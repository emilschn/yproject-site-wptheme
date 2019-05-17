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
		
		<link href="https://plus.google.com/+WedogoodCo" rel="publisher" />
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="<?php echo $page_controler->get_page_description(); ?>" />
		<meta name="google-site-verification" content="GKtZACFMpEC-1TO9ox4c85RJgfWRm7gNv4c0QrNKYgM" />
		
		<!--[if lt IE 9]>
		    <script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/html5shiv.js"></script>
		<![endif]-->
		<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/common.min.css?d=<?php echo ASSETS_VERSION; ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?d=<?php echo ASSETS_VERSION; ?>" type="text/css" media="screen" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		
		<?php if ( !is_user_logged_in() && $post->post_name == 'inscription' ): ?>
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
				<a href="<?php echo home_url( '/les-projets/' ); ?>" class="lines"><?php _e( "Les projets", 'yproject' ); ?></a>
				<a href="<?php echo home_url( '/financement/' ); ?>" class="lines"><?php _e( "Financer mon projet", 'yproject' ); ?></a>
				<a href="<?php echo home_url( '/investissement/' ); ?>" class="lines"><?php _e( "Investir en royalties", 'yproject' ); ?></a>
				<a href="<?php echo home_url( '/a-propos/vision/' ); ?>" class="lines"><?php _e( "Vision", 'yproject' ); ?></a>
                                
				<a href="#" id="btn-search"><img class="search inactive" src="<?php echo $stylesheet_directory_uri; ?>/images/navbar/recherche-icon.png" alt="SEARCH" /></a>
				<?php if (is_user_logged_in()): ?>
				<a href="#" class="btn-user connected <?php if ( $page_controler->get_show_user_needs_authentication() ): ?>needs-authentication<?php endif; ?>"><?php UIHelpers::print_user_avatar($WDGUser_current->wp_user->ID, 'icon'); ?></a>				
				<?php elseif ( $page_controler->get_display_link_account() ): ?>
				<a href="<?php echo home_url( 'mon-compte' ); ?>" class="btn-user not-connected"><img src="<?php echo $stylesheet_directory_uri; ?>/images/navbar/profil-icon-noir.png" alt="USER" /></a>
				<?php else: ?>
				<a href="#" class="btn-user not-connected inactive"><img src="<?php echo $stylesheet_directory_uri; ?>/images/navbar/profil-icon-noir.png" alt="USER" /></a>
				<?php endif; ?>
				<a href="#" id="btn-burger" class="only-inf997"><img src="<?php echo $stylesheet_directory_uri; ?>/images/navbar/menu-burger.png" alt="MENU" /></a>
				
				
				<?php /* Affichage quand clic sur Rechercher */ ?>
				<div id="submenu-search" class="submenu-style hidden">
					<div class="only-inf997">
						<a href="<?php echo home_url( '/les-projets/' ); ?>"><?php _e( "Les projets", 'yproject' ); ?></a>
						<a href="<?php echo home_url( '/financement/' ); ?>"><?php _e( "Financer mon projet", 'yproject' ); ?></a>
						<a href="<?php echo home_url( '/investissement/' ); ?>"><?php _e( "Investir en royalties", 'yproject' ); ?></a>
						<a href="<?php echo home_url( '/a-propos/vision/' ); ?>"><?php _e( "Vision", 'yproject' ); ?></a>
					</div>
					
					<input type="text" id="submenu-search-input" placeholder="<?php _e("Rechercher un projet", 'yproject'); ?>" />
					<ul class="submenu-list">
					</ul>
				</div>

				<?php if (is_user_logged_in()): ?>
				<div id="submenu-user" class="connected submenu-style hidden">
					<?php /* Au clic picto Compte, afficher menu utilisateur */ ?>
					<?php global $current_user; get_currentuserinfo();
					$user_name_str = ($current_user->user_firstname != '') ? $current_user->user_firstname : $current_user->user_login;
					$page_dashboard = home_url( '/tableau-de-bord/' );
					?>
					<span id="submenu-user-hello"><?php _e("Bonjour", 'yproject'); ?> <?php echo $user_name_str; ?> !</span>
					<ul class="submenu-list">
						<?php
						$is_project_needing_authentication = FALSE;
						$project_list_dom = '';
						foreach ($project_list as $project_id) { 
							if ( !empty( $project_id ) ) {
								$project_campaign = new ATCF_Campaign( $project_id );
								if ( isset( $project_campaign ) && $project_campaign->get_name() != '' ) {
									$campaign_organization = $project_campaign->get_organization();
									$campaign_organization = new WDGOrganization( $campaign_organization->wpref );
									$project_list_dom .= '<li><a href="' .$page_dashboard. '?campaign_id=' .$project_id. '"';
									if ( !$campaign_organization->is_registered_lemonway_wallet() ) {
										$is_project_needing_authentication = TRUE;
										$project_list_dom .= ' class="needs-authentication"';
									}
									$project_list_dom .= '>' .$project_campaign->get_name(). '</a></li>';
								}
							}
						}
						?>
							
						<li><a href="<?php echo home_url( '/mon-compte/' ); ?>" <?php if ( $page_controler->get_show_user_needs_authentication() && !$is_project_needing_authentication ): ?>class="needs-authentication"<?php endif; ?>><?php _e("Mon compte", 'yproject'); ?></a></li>
						<?php echo $project_list_dom; ?>
					</ul>
					
					<div id="button-logout" class="box_connection_buttons red">
						<a href="<?php echo wp_logout_url(); echo '&page_id='.get_the_ID() ?>"><span><?php _e("Me d&eacute;connecter", 'yproject'); ?></span></a>
					</div>
				</div>
				
				
				<?php else: ?>
				<div id="submenu-user" class="not-connected submenu-style hidden">
					<?php /* Au clic picto Compte, afficher menu connexion */ ?>
					<div class="only-inf997">
						<a href="<?php echo home_url( '/connexion/' ); ?>" class="box_connection_buttons button red"><span><?php _e( "Connexion", 'yproject' ); ?></span></a>
					</div>
					
					<form method="post" action="<?php echo home_url( "/connexion/" ); ?>" name="login-form" class="sidebar-login-form model-form hidden-inf997">
						<br>
						<span id="title-connection"><?php _e('Connexion', 'yproject'); ?></span>
						<input class="input_connection" id="identifiant" type="text" name="log" placeholder="<?php _e('E-mail ou identifiant', 'yproject'); ?>" value="" />
						<br>

						<input class="input_connection" id="password" type="password" name="pwd" placeholder="Mot de passe" value="" />
						<div class="submit-center" style="display: none;">             
							<input type="submit" name="wp-submit" class="input_submit button red" id="connect" value="OK"/>
							<input type="hidden" class="redirect-page" name="redirect-page" value="<?php echo WDGUser::get_login_redirect_page(); ?>" />
							<input type="hidden" name="login-form" value="1" />
						</div>   

						<div>
							<?php $page_forgotten = get_page_by_path('mot-de-passe-oublie'); ?>
							<a href="<?php echo get_permalink($page_forgotten->ID); ?>" class="forgotten"><?php _e('(Mot de passe oubli&eacute;)', 'yproject');?></a>
						</div>

						<input id="rememberme" type="checkbox" name="rememberme" value="forever" />
						<label><?php _e('Se souvenir de moi', 'yproject'); ?></label>
						<br><br>
					</form>
					
					<hr class="login-separator">

					<div class="box_connection_buttons blue">
						<a href="#" class="social_connect_login_facebook" data-redirect="<?php echo WDGUser::get_login_redirect_page(); ?>"><span><?php _e('Se connecter avec Facebook', 'yproject'); ?></span></a>
					</div>
					<div class="social_connect_login_facebook_loading align-center hidden">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading" />
					</div>
					
					<hr class="login-separator">

					<div>
						<a href="<?php echo home_url( '/inscription/' ); ?>" class="box_connection_buttons button red"><span><?php _e( "Cr&eacute;er un compte", 'yproject' ); ?></span></a>
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

		<?php if ( is_user_logged_in() && (!isset($_SESSION['has_displayed_connected_lightbox']) || ($_SESSION['has_displayed_connected_lightbox'] != $current_user->ID)) ): ?>
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
		<?php if ( $_SESSION['subscribe_newsletter_sendinblue'] == true ): ?>
			<div class="timeout-lightbox wdg-lightbox">
				<div class="wdg-lightbox-click-catcher"></div>
				<div class="wdg-lightbox-padder">
					<p class="wdg-lightbox-msg-info"><?php _e("Votre inscription a bien &eacute;t&eacute; prise en compte !", 'yproject'); ?></p>
				</div>
			</div>
		<?php endif; ?>
		<?php $_SESSION['subscribe_newsletter_sendinblue'] = false; ?>
		<?php endif; ?>
		
		<?php if ( $page_controler->get_show_user_pending_investment() ): ?>
			<?php locate_template( array( 'common/lightbox/pending-investment-lightbox.php' ), true ); ?>
		<?php endif; ?>
		
		<?php if ( $page_controler->get_show_user_pending_preinvestment() ): ?>
			<?php locate_template( array( 'common/lightbox/pending-preinvestment-lightbox.php' ), true ); ?>
		<?php endif; ?>
		
		<?php if ( $page_controler->get_show_user_details_confirmation() ): ?>
			<?php locate_template( array( 'common/lightbox/user-details-lightbox.php' ), true ); ?>
		<?php endif; ?>
		
		<div id="container"> 
