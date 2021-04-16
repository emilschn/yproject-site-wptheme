<?php
	global $WDG_cache_plugin, $stylesheet_directory_uri, $is_campaign_page, $campaign, $post, $current_user, $sitepress;
	if ($WDG_cache_plugin == null) {
		$WDG_cache_plugin = new WDG_Cache_Plugin();
	}
	$page_controler = WDG_Templates_Engine::instance()->get_controler();
	wp_reset_query();

	$analytics_data = $page_controler->get_analytics_data();
	$campaign_google_tag_manager_id = FALSE;
	if ( $is_campaign_page && !empty( $campaign ) ) {
		$campaign_google_tag_manager_id = $campaign->google_tag_manager_id();
	}
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

		<?php /* Google Tag Manager */ ?>
		<?php
			/* WDG :
			si c'est déjà défini par le biais des controler (= si l'utilisateur est identifié),
			on peut déjà envoyer la donnée ; sinon ce sera envoyé plus tard
			*/
		?>
		<script>
			function wdg_gtm_call() {
				(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
				})(window,document,'script','dataLayer','GTM-KFV5RN5');
			}
			dataLayer = [];

			<?php if ( !empty( $analytics_data[ 'payment' ] ) ): ?>
				dataLayer.push({
					'event': 'purchase',
					'event_category': 'Ecommerce',
					'event_action': 'Transaction',
					// ID de la transaction - Format : String
					'event_label': '<?php echo $analytics_data[ 'payment' ][ 'event_label' ]; ?>',
					// Montant total de l'investissement - Format : Numérique
					'value': <?php echo $analytics_data[ 'payment' ][ 'value' ]; ?>,
					'currency': 'EUR',
					'ecommerce': {
						'purchase': {
							'actionField': {
								// ID de la transaction - Format : String
								'id': '<?php echo $analytics_data[ 'payment' ][ 'event_label' ]; ?>',
								// Montant total de l'investissement, incluant la TVA - Format : Numérique
								'revenue': <?php echo $analytics_data[ 'payment' ][ 'value' ]; ?>
							},
							'products': [{
								// Titre du projet - Format : String
								'name': '<?php echo $analytics_data[ 'payment' ][ 'product_name' ]; ?>',
								// ID du projet - Format : String
								'id': '<?php echo $analytics_data[ 'payment' ][ 'product_id' ]; ?>',
								// Montant de l'investissement - Format : Numérique
								'price': <?php echo $analytics_data[ 'payment' ][ 'value' ]; ?>,
								// Nom de la société qui porte le projet - Format : String
								'brand': '<?php echo $analytics_data[ 'payment' ][ 'product_brand' ]; ?>',
								// Catégorie du projet - Format : String
								'category': '<?php echo $analytics_data[ 'payment' ][ 'product_category' ]; ?>',
								// Aide pour Analytics
								'quantity': 1
							}]
						}
					}
				});
			<?php endif; ?>
		</script>

		<?php /* Campagne */ ?>
		<?php if ( !empty( $campaign_google_tag_manager_id ) ): ?>
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','campaignDataLayer','<?php echo $campaign_google_tag_manager_id; ?>');</script>
		<?php endif; ?>
		<?php /* End Google Tag Manager */ ?>
		
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
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>" charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="<?php echo $page_controler->get_page_description(); ?>" />
		<meta name="google-site-verification" content="GKtZACFMpEC-1TO9ox4c85RJgfWRm7gNv4c0QrNKYgM" />
		
		<!--[if lt IE 9]>
		    <script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/html5shiv.js"></script>
		<![endif]-->
		<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/common.min.css?d=<?php echo ASSETS_VERSION; ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?d=<?php echo ASSETS_VERSION; ?>" type="text/css" media="screen" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		
		<?php if ( !is_user_logged_in() && ( $post->post_name == 'inscription' || $post->post_name == 'registration')): ?>
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
		<?php if ( $campaign->is_hidden() ):?>
			<meta name="robots" content="noindex">
		<?php endif; ?>

		<?php else: ?>
		<meta property="og:description" content="<?php echo $page_controler->get_page_description(); ?>" />
		
		<?php endif; ?>
		<meta property="og:image" content="<?php echo $imageFacebook ?>" />
		<meta property="og:image:secure_url" content="<?php echo $imageFacebook ?>" />
		<meta property="og:image:type" content="image/png" />
		<meta property="fb:app_id" content="<?php echo YP_FB_APP_ID; ?>" />
	</head>

	<body <?php body_class(get_locale()); ?>>
		<?php /* Google Tag Manager (noscript) */ ?>
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KFV5RN5"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		
		<?php if ( !empty( $campaign_google_tag_manager_id ) ): ?>
			<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $campaign_google_tag_manager_id; ?>"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<?php endif; ?>
		<?php /* End Google Tag Manager (noscript) */ ?>
		
		<?php if ( $page_controler->get_header_nav_visible() ): ?>
		<nav id="main">
			<div id="menu">
				<a href="<?php echo home_url(); ?>"><img id="logo_wdg" src="<?php echo $stylesheet_directory_uri; ?>/images/navbar/logo-wdg.png" alt="WE DO GOOD" width="178" height="33" /></a>
				<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'les-projets' ); ?>" class="lines"><?php _e( 'menu.THE_PROJECTS', 'yproject' ); ?></a>
				<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'epargne-positive' ); ?>" class="lines"><?php _e( 'menu.POSITIVE_SAVINGS', 'yproject' ); ?></a>
				<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'financement' ); ?>" class="lines"><?php _e( 'menu.FUND_PROJECT', 'yproject' ); ?></a>
				<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'investissement' ); ?>" class="lines"><?php _e( 'menu.INVEST', 'yproject' ); ?></a>
				<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'a-propos/vision' ); ?>" class="lines"><?php _e( 'menu.VISION', 'yproject' ); ?></a>

				<a href="#" id="btn-search"><img class="search inactive" src="<?php echo $stylesheet_directory_uri; ?>/images/navbar/recherche-icon.png" alt="SEARCH" /></a>
				
				<?php if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ): ?>
					<?php $active_languages = apply_filters( 'wpml_active_languages', NULL ); ?>
					<a href="#" id="btn-switch-lang">
					<?php foreach ( $active_languages as $language_key => $language_item ): if ( $language_item[ 'active' ] ): ?>
						<?php echo $language_item[ 'code' ]; ?>
					<?php endif; endforeach; ?>
					</a>
				<?php endif; ?>
				
				<a href="#" class="btn-user not-connected inactive"><?php _e( 'common.CONNECTION', 'yproject' ); ?></a>
				<a href="#" id="btn-burger" class="only-inf997"><img src="<?php echo $stylesheet_directory_uri; ?>/images/navbar/menu-burger.png" alt="MENU" /></a>
				
				<?php if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ): ?>
				<?php
				$url_suffix = '';
				if ( isset( $_GET[ 'campaign_id' ] ) ) {
					$url_suffix = '?campaign_id=' . $_GET[ 'campaign_id' ];
				}
				?>
				<div id="submenu-switch-lang" class="submenu-style hidden">
					<ul class="submenu-list">
					<?php foreach ( $active_languages as $language_key => $language_item ): ?>
						<li <?php if ( $language_item[ 'active' ] ) {
					echo 'class="active"';
				} ?>><a href="<?php echo $language_item[ 'url' ] . $url_suffix; ?>" data-key="<?php echo $language_key; ?>"><?php echo $language_item[ 'native_name' ]; ?></a></li>
					<?php endforeach; ?>
					</ul>
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading" class="hidden">
				</div>
				<?php endif; ?>
				
				<?php /* Affichage quand clic sur Rechercher */ ?>
				<div id="submenu-search" class="submenu-style hidden">
					<div class="only-inf997">
						<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'mon-compte' ); ?>"><?php _e( 'common.MY_ACCOUNT', 'yproject' ); ?></a>
						<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'les-projets' ); ?>"><?php _e( 'menu.THE_PROJECTS', 'yproject' ); ?></a>
						<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'epargne-positive' ); ?>"><?php _e( 'menu.POSITIVE_SAVINGS', 'yproject' ); ?></a>
						<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'financement' ); ?>"><?php _e( 'menu.FUND_PROJECT', 'yproject' ); ?></a>
						<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'investissement' ); ?>"><?php _e( 'menu.INVEST', 'yproject' ); ?></a>
						<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'a-propos/vision' ); ?>"><?php _e( 'menu.VISION', 'yproject' ); ?></a>
						<?php if ( is_user_logged_in() ): ?>
							<br>
							<a href="<?php echo wp_logout_url(); ?>"><?php _e( 'menu.LOGOUT', 'yproject' ); ?></a>
						<?php endif; ?>
					</div>
					
					<input type="text" id="submenu-search-input" placeholder="<?php _e( 'menu.SEARCH_PROJECT', 'yproject' ); ?>" />
					<ul class="submenu-list">
							<div class="empty-list-info hidden">
								<?php _e( "Vous ne trouvez pas le projet que vous cherchez ? Il est peut-&ecirc;tre priv&eacute;.", 'yproject' ); ?><br>
								<a href="https://support.wedogood.co/lev%C3%A9es-de-fonds-priv%C3%A9es" target="_blank"><?php _e( "En savoir plus.", 'yproject' ); ?></a>
							</div>
					</ul>
				</div>
				
				<div id="submenu-user" class="not-connected submenu-style hidden">
					<div class="menu-loading-init align-center">
						<br>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading">
						<br>
						<br>
					</div>
					
					<div class="menu-connection-forms hidden">
						<?php /* Au clic picto Compte, afficher menu connexion */ ?>
						<div class="only-inf997">
							<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'connexion' ); ?>" class="box_connection_buttons button red"><span><?php _e( 'common.CONNECTION', 'yproject' ); ?></span></a>
						</div>

						<form method="post" action="<?php echo WDG_Redirect_Engine::override_get_page_url( "connexion" ); ?>" name="login-form" class="sidebar-login-form model-form hidden-inf997">
							<br>
							<span id="title-connection"><?php _e( 'common.CONNECTION', 'yproject' ); ?></span>
							<input class="input_connection" id="identifiant" type="text" name="log" placeholder="<?php _e( 'login.EMAIL_OR_LOGIN', 'yproject' ); ?>" value="" />
							<br>

							<input class="input_connection" id="password" type="password" name="pwd" placeholder="<?php _e( 'common.PASSWORD', 'yproject' ); ?>" value="" />
							<div class="submit-center" style="display: none;">
								<input type="submit" name="wp-submit" class="input_submit button red" id="connect" value="OK"/>
								<input type="hidden" class="redirect-page" name="redirect-page" value="<?php echo WDGUser::get_login_redirect_page(); ?>" />
								<input type="hidden" name="login-form" value="1" />
							</div>   

							<div>
								<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'mot-de-passe-oublie' ); ?>" class="forgotten">(<?php _e( 'login.FORGOTTEN_PASSWORD', 'yproject' );?>)</a>
							</div>

							<br>
							<label class="checkbox-parent">
								<input id="rememberme" type="checkbox" name="rememberme" value="forever" />
								<?php _e( 'login.REMEMBER_ME', 'yproject' ); ?>
							</label>
							<br><br>
						</form>

						<hr class="login-separator">

						<div class="box_connection_buttons blue">
							<a href="#" class="social_connect_login_facebook" data-redirect="<?php echo WDGUser::get_login_redirect_page(); ?>"><span><?php _e( 'login.LOG_IN_FACEBOOK', 'yproject' ); ?></span></a>
						</div>
						<div class="social_connect_login_facebook_loading align-center hidden">
							<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading" />
						</div>

						<hr class="login-separator">

						<div>
							<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'inscription' ); ?>" class="box_connection_buttons button red"><span><?php _e( 'login.CREATE_ACCOUNT', 'yproject' ); ?></span></a>
						</div>
					</div>
					
					<div class="menu-connected hidden">
						<span id="submenu-user-hello"><span><?php _e( 'account.HELLO', 'yproject' ); ?></span> <span class="hello-user-name"></span> !</span>
						<ul class="submenu-list">
							<li><a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'mon-compte' ); ?>"><?php _e( 'common.MY_ACCOUNT', 'yproject' ); ?></a></li>
						</ul>
						<div id="button-logout" class="box_connection_buttons red">
							<a href="" class="button red"><?php _e( 'menu.LOGOUT', 'yproject' ); ?></a>
						</div>
					</div>

				</div>
				
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
				<span><?php _e( 'terms.UPDATE', 'yproject' ); ?></span>
				<div class="validate-terms-excerpt">
					<?php echo wpautop( stripslashes( WDGConfigTexts::get_config_text_by_name( WDGConfigTexts::$type_term_extracts, 'terms_general_excerpt' ) ) ); ?>
				</div>
				<form method="POST">
					<input type="hidden" name="action" value="validate-terms" />
					<label for="validate-terms-check-header"><input type="checkbox" id="validate-terms-check-header" name="validate-terms-check" /> <?php _e( 'terms.ACCEPT', 'yproject' ); ?></label><br />
					<div style="text-align: center;"><input type="submit" value="<?php _e( 'common.VALIDATE', 'yproject' ); ?>" class="button" /></div>
				</form> 
			</div>
		</div>
		<?php endif; ?>

		<?php if ( is_user_logged_in() && (!isset($_SESSION['has_displayed_connected_lightbox']) || ($_SESSION['has_displayed_connected_lightbox'] != $current_user->ID)) ): ?>
			<?php $_SESSION['has_displayed_connected_lightbox'] = $current_user->ID; ?>
			<div class="timeout-lightbox wdg-lightbox">
				<div class="wdg-lightbox-click-catcher"></div>
				<?php
				$user_name_str = $current_user->user_firstname;
				if ($user_name_str == '') {
					$user_name_str = $current_user->user_login;
				}
				?>
				<div class="wdg-lightbox-padder">
					<?php _e( 'account.HELLO', 'yproject' ); ?> <?php echo $user_name_str; ?>
					<?php if ( ATCF_CrowdFunding::get_platform_context() == "wedogood" ): ?>
					<?php _e( 'menu.WELCOME_TO_WEDOGOOD', 'yproject' ); ?>
					<?php endif; ?>
				</div>
			</div>
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
