<?php
$wordpress_events = WDG_WordPress_Events::instance();

/**
 * Cette classe se charge d'auto-déterminer les fichiers à charger en préparation et en contenu
 * Cette classe doit être un singleton auto-instancié.
 */
class WDG_WordPress_Events {
	private static $instance;
	/**
	 * @return WDG_Templates_Engine
	 */
	public static function instance() {
		if ( !isset( self::$instance ) ) {
			self::$instance = new WDG_WordPress_Events();
		}

		return self::$instance;
	}

	public function __construct() {
		// Chargement de page
		add_action( 'init', 'WDG_WordPress_Events::init' );
		add_action( 'after_setup_theme', 'WDG_WordPress_Events::after_setup_theme', 15 );
		add_action( 'send_headers', 'WDG_WordPress_Events::send_headers' );
		add_action( 'wp_logout', 'WDG_WordPress_Events::wp_logout' );
		add_action( 'user_register', 'WDG_WordPress_Events::user_register', 10, 1 );
		// Sécurité
		remove_action( 'wp_head', 'wp_generator' );
		add_filter( 'login_errors', create_function( '$a', "return null;" ) );
		// Requetes sur page chargée
		add_action( 'pre_get_posts', 'WDG_WordPress_Events::pre_get_posts' );
		add_action( 'wp_enqueue_scripts', 'WDG_WordPress_Events::wp_enqueue_scripts' );
		//add_action( 'wp_enqueue_scripts', 'WDG_WordPress_Events::login_or_register' );
		WDG_WordPress_Events::login_or_register();
		add_action( 'wp_insert_comment', array('NotificationsEmails', 'new_comment'), 99, 2 );
		// Composants
		add_action( 'widgets_init', 'WDG_WordPress_Events::widgets_init' );
		// Suppression de l'action qui passe les paiements en attente en abandonnés au bout d'une semaine
		remove_action( 'edd_weekly_scheduled_events', 'edd_mark_abandoned_orders' );
		// Suppression de la notification envoyée quand on modifie l'adresse d'un utilisateur
		add_filter( 'send_email_change_email', '__return_false' );
		// Suppression de la notification envoyée quand on modifie le mot de passe d'un utilisateur
		add_filter('send_password_change_email', '__return_false');
		/* Disable Admin Password Change Notification */
		remove_action('after_password_reset', 'wp_password_change_notification');
		// Suppression d'actions d'easy digital downloads lancées dans template_redirect
		remove_action( 'template_redirect', 'edd_disable_woo_ssl_on_checkout', 9 );
		remove_action( 'template_redirect', 'edd_disable_404_redirected_redirect', 9 );
		remove_action( 'template_redirect', 'edd_delayed_get_actions' );
		remove_action( 'template_redirect', 'edd_delayed_post_actions' );
		remove_action( 'template_redirect', 'edd_listen_for_failed_payments' );
		remove_action( 'template_redirect', 'edd_enforced_ssl_redirect_handler' );
		remove_action( 'template_redirect', 'edd_enforced_ssl_asset_handler' );
		remove_action( 'template_redirect', 'edd_paypal_process_pdt_on_return' );
		remove_action( 'template_redirect', 'edd_recovery_user_mismatch' );
		remove_action( 'template_redirect', 'edd_disable_jetpack_og_on_checkout' );
		remove_action( 'template_redirect', 'edd_display_email_template_preview' );
		remove_action( 'template_redirect', 'edd_block_attachments' );
		remove_action( 'template_redirect', 'edd_refresh_permalinks_on_bad_404' );
		remove_action( 'template_redirect', 'edd_process_cart_endpoints', 100 );
		// Désactivation du XMLRPC
		add_filter( 'xmlrpc_enabled', '__return_false' );
		remove_action( 'wp_head', 'rsd_link' );
		// Limitation de l'accès à l'API REST
		add_filter('rest_authentication_errors', 'WDG_WordPress_Events::secure_api');
	}

	/**
	 * Fonctions lancées automatiquement lors de l'action "init" de WordPress
	 */
	public static function init() {
		// Sécurité : enlever les "magic quotes"
		$_POST      = array_map( 'stripslashes_deep', $_POST );
		$_GET       = array_map( 'stripslashes_deep', $_GET );
		$_COOKIE    = array_map( 'stripslashes_deep', $_COOKIE );
		$_REQUEST   = array_map( 'stripslashes_deep', $_REQUEST );

		// Donne la possibilité de mettre des tags aux pages
		register_taxonomy_for_object_type('post_tag', 'page');

		// Sécurisation utilisateur
		show_admin_bar( false );
		if ( is_user_logged_in() ) {
			//Redéfinit le style de tinymce
			global $editor_styles;
			$editor_styles = (array) $editor_styles;
			$stylesheet    = 'editor-style.css';
			$stylesheet    = (array) $stylesheet;
			$editor_styles = array_merge( $editor_styles, $stylesheet );

			//Redéfinit le role utilisateur pour permettre l'upload de fichier
			$role_subscriber = get_role("subscriber");
			$role_subscriber->add_cap( 'level_0' );
			$role_subscriber->remove_cap( 'level_1' );
			$role_subscriber->add_cap( 'read' );
			$role_subscriber->add_cap( 'upload_files' );
			$role_subscriber->remove_cap( 'publish_pages' );
			$role_subscriber->remove_cap( 'edit_pages' );
			$role_subscriber->remove_cap( 'edit_private_pages' );
			$role_subscriber->add_cap( 'edit_published_pages' );
			$role_subscriber->add_cap( 'edit_others_pages' );
			$role_subscriber->remove_cap( 'publish_posts' );
			$role_subscriber->remove_cap( 'edit_post' );
			$role_subscriber->remove_cap( 'edit_posts' );
			$role_subscriber->remove_cap( 'edit_private_posts' );
			$role_subscriber->add_cap( 'edit_published_posts' );
			$role_subscriber->add_cap( 'edit_others_posts' );
		}

		if ( get_user_option('rich_editing') == 'true' ) {
			add_filter( 'mce_external_plugins', 'WDG_WordPress_Events::add_plugin' );
			add_filter( 'mce_buttons', 'WDG_WordPress_Events::register_button' );
		}

		add_filter('tiny_mce_before_init', 'WDG_WordPress_Events::color_text_editor');
		add_filter( 'tiny_mce_before_init', 'WDG_WordPress_Events::display_toolbar' );
	}

	/**
	 * Ajout du bouton aux boutons existant
	 */
	public static function register_button($buttons) {
		array_push( $buttons, "|", "video" );

		return $buttons;
	}

	/**
	 * Ajout du plugin video aux plugins existant
	 */
	public static function add_plugin($plugin_array) {
		$plugin_array['video'] = '/wp-content/themes/yproject/_inc/js/tinymce/video-plugin.js';

		return $plugin_array;
	}

	/**
	 * Choix des couleurs de la palette de l'éditeur de texte
	 */
	public static function color_text_editor($init) {
		$default_colours = '
		"EA4F51", "WDG Rouge",
		"00879B", "WDG Bleu",
		"EBCE67", "WDG Jaune",
		"5EB82C", "WDG Vert",
		"F8CACA", "WDG Rose",
		"B3DAE1", "WDG Bleu clair",
		"F9F0D1", "WDG Jaune clair",
		"CEE9C0", "WDG Vert clair",

		"333333", "WDG Noir",
		"C2C2C2", "WDG Gris",
		"EBEBEB", "WDG Gris clair",
		"FFFFFF", "WDG Blanc",
		"FFFFFF", "Blanc",
		"FFFFFF", "Blanc",
		"FFFFFF", "Blanc",
		"FFFFFF", "Blanc",

		"A9EAFE", "Azurin",
		"FF5E4D", "Rouge capucine",
		"F7FF3C", "Jaune citron",
		"B0F2B6", "Vert eau",
		"FEBFD2", "Rose dragée",
		"FFE4C4", "Beige",
		"CECECE", "Gris perle",
		"FFFFFF", "Blanc",

		"77B5FE", "Bleu ciel",
		"FF0000", "Rouge vif",
		"E7F00D", "Jaune",
		"16B84E", "Vert menthe",
		"FD6C9E", "Rose",
		"BA9B61", "Claro",
		"9E9E9E", "Gris souris",
		"FFFFFF", "Blanc",

		"318CE7", "Bleu France",
		"DE2916", "Rouge tomate",
		"DFAF2C", "Ocre jaune",
		"3A9D23", "Vert gazon",
		"D473D4", "Mauve",	
		"87591A", "Marron",
		"606060", "Gris",
		"FFFFFF", "Blanc",

		"0131B4", "Bleu saphir",
		"BC2001", "Rouge écrevisse",
		"ED7F10", "Orange",
		"096A09", "Vert bouteille",
		"800080", "Magenta foncé",
		"5B3C11", "Brun",
		"000000", "Noir",
		"FFFFFF", "Blanc",

		"0F056B", "Bleu nuit",
		"6D071A", "Bordeaux",
		"CC5500", "Orange foncé",
		"00561B", "Vert impérial",
		"660099", "Violet",
		"463F32", "Taupe",
		"FFFFFF", "Blanc",

		';

		$init['textcolor_map'] = '['.$default_colours.']';

		return $init;
	}

	public static function display_toolbar($init) {
		$init['toolbar'] = true;

		return $init;
	}

	/**
	 * Définition du domaine pour les traductions
	 */
	public static function after_setup_theme() {
		// Chargement des langues
		load_theme_textdomain( 'yproject', get_template_directory_uri() .'/languages' );
		load_child_theme_textdomain( 'yproject', get_stylesheet_directory() . '/languages' );
		// Activer les blocs larges et plein écran
		add_theme_support( 'align-wide' );

		/** palette de couleurs Gutenberg **/
		add_theme_support('editor-color-palette', [
				[
					'name'  => esc_html__( 'Rouge', 'wpdc' ),
					'slug'  => 'rouge',
					'color' => '#EA4F51',
				],
				[
					'name'  => esc_html__( 'Bleu', 'wpdc' ),
					'slug'  => 'bleu',
					'color' => '#00879B',
				],
				[
					'name'  => esc_html__( 'Jaune', 'wpdc' ),
					'slug'  => 'jaune',
					'color' => '#EBCE67',
				],
				[
					'name'  => esc_html__( 'Vert', 'wpdc' ),
					'slug'  => 'vert',
					'color' => '#5EB82C',
				],
				[
					'name'  => esc_html__( 'Rose', 'wpdc' ),
					'slug'  => 'rose',
					'color' => '#F8CACA',
				],
				[
					'name'  => esc_html__( 'Bleu clair', 'wpdc' ),
					'slug'  => 'bleu-clair',
					'color' => '#B3DAE1',
				],
				[
					'name'  => esc_html__( 'Jaune clair', 'wpdc' ),
					'slug'  => 'jaune-clair',
					'color' => '#F9F0D1',
				],
				[
					'name'  => esc_html__( 'Vert clair', 'wpdc' ),
					'slug'  => 'vert-clair',
					'color' => '#CEE9C0',
				],
				[
					'name'  => esc_html__( 'Noir', 'wpdc' ),
					'slug'  => 'noir',
					'color' => '#333333',
				],
				[
					'name'  => esc_html__( 'Gris', 'wpdc' ),
					'slug'  => 'gris',
					'color' => '#C2C2C2',
				],
				[
					'name'  => esc_html__( 'Gris clair', 'wpdc' ),
					'slug'  => 'gris-clair',
					'color' => '#EBEBEB',
				],
				[
					'name'  => esc_html__( 'Blanc', 'wpdc' ),
					'slug'  => 'blanc',
					'color' => '#ffffff',
				],
			]);

		add_theme_support( 'editor-styles' );
		add_editor_style( 'editor-style.css' );
	}

	/**
	 * S'assure que les tags sont inclus dans les requêtes
	 */
	public static function pre_get_posts($wp_query) {
		if ( $wp_query->get('tag') ) {
			$wp_query->set( 'post_type', 'any' );
		}
	}

	/**
	 * Gestion cache côté serveur (configuration Varnish)
	 */
	public static function send_headers() {
		if ( !headers_sent() ) {
			header('X-UA-Compatible: IE=edge');
			header('Cache-Control: public, s-maxage=120');
			header('Pragma: public');
		}
		if ( !session_id() ) {
			session_cache_limiter('');
			session_start();
		}
	}

	/**
	 * Redirection lors de la déconnexion
	 */
	public static function wp_logout() {
		$page_id = filter_input( INPUT_GET, 'page_id' );
		if ( !empty( $page_id ) ) {
			wp_redirect( get_permalink( $page_id ) );
		} else {
			wp_redirect( home_url() );
		}
		exit;
	}

	/**
	 * Enregistrement de la langue d'affichage lors de la création d'un compte
	 */
	public static function user_register($user_id) {
		$wdg_user = new WDGUser( $user_id );
		$wdg_user->set_language( WDG_Languages_Helpers::get_current_locale_id() );
		$wdg_user->update_api();
	}

	/**
	 * Charge les styles et scripts nécessaires pour la page
	 */
	public static function wp_enqueue_scripts() {
		global $can_modify, $is_campaign, $is_campaign_page, $post, $locale;

		$post_name = $post->post_name;
		if ( $locale != 'fr' && $locale != 'fr_FR' ) {
			$post_in_french_id = apply_filters( 'wpml_object_id', $post->ID, 'page', FALSE, 'fr' );
			$post_in_french = get_post( $post_in_french_id );
			$post_name = $post_in_french->post_name;
		}

		$campaign = atcf_get_current_campaign();
		$can_modify = ($is_campaign) && ($campaign->current_user_can_edit());
		$is_dashboard_page = ($post_name == 'tableau-de-bord');
		$is_admin_page = ($post_name == 'liste-des-paiements');
		$is_user_account = ($post_name == 'mon-compte');

		// Modification version jquery chargée
		include_once 'assets-version.php';
		if ( !is_admin() ) {
			wp_deregister_script('jquery');
			wp_register_script('jquery', (dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery.min.js'), false);
			wp_enqueue_script('jquery');
		}

		// Script principal WDG
		wp_enqueue_script( 'wdg-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/common.min.js', array('jquery'), ASSETS_VERSION);

		// Suppression script et style de easy digital downloads
		wp_deregister_style( 'edd-styles' );
		wp_deregister_script( 'edd-ajax' );

		// Styles utiles dans les pages complexes (avec selection de date, popups, formulaires spécifiques etc.)
		$pages_simple = array( 'connexion', 'inscription', 'les-projets' );
		if ( !in_array( $post_name, $pages_simple ) && !is_home() && !is_front_page() ) {
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('wdg-project-dashboard-i18n-fr', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/i18n/datepicker-fr.js', array('jquery', 'jquery-ui-datepicker'), ASSETS_VERSION);

			wp_enqueue_style('jquery-ui-wdg', dirname( get_bloginfo('stylesheet_url')).'/_inc/css/jquery-ui-wdg.css', null, false, 'all');

			wp_deregister_style( 'font-awesome' );
			wp_register_style('font-awesome', (dirname( get_bloginfo('stylesheet_url')).'/_inc/css/font-awesome.min.css'));
			wp_enqueue_style('font-awesome');

			wp_enqueue_script( 'jquery-form', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery.form.js', array('jquery'));
		}

		if ( in_array( $post_name, $pages_simple ) || is_home() || is_front_page() || $is_user_account ) {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			wp_deregister_script( 'wp-embed' );
			wp_deregister_script( 'wp-embed.min.js' );
			wp_deregister_script( 'contact-form-7' );
			wp_deregister_style(' contact-form-7' );
			wp_deregister_style( 'font-awesome' );
			wp_deregister_script( 'jquery-form' );
		}

		// Chargement de la lib de graphs (uniquement en liaison avec les projets)
		if ( ( $is_campaign || $is_campaign_page ) && !$is_dashboard_page ) {
			wp_enqueue_script( 'chart-script', 'https://cdn.jsdelivr.net/npm/chart.js', array('wdg-script'), '1' );
		}
		if ( $is_dashboard_page ) {
			wp_enqueue_script('qtip', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery.qtip.min.js', array('jquery'));
			wp_enqueue_style('qtip', dirname( get_bloginfo('stylesheet_url')).'/_inc/css/jquery.qtip.min.css', null, false, 'all');
		}

		// Styles et scripts liés aux projets
		if ($is_campaign_page) {
			if ($is_campaign && !$is_dashboard_page) {
				wp_enqueue_style( 'campaign-css', dirname( get_bloginfo('stylesheet_url')).'/_inc/css/campaign.min.css', null, ASSETS_VERSION, 'all');
			}
			wp_enqueue_script( 'wdg-campaign', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-campaign.js', array('jquery', 'jquery-ui-dialog'), ASSETS_VERSION);
			if ( $is_campaign_page && $can_modify && !is_archive() ) {
				wp_enqueue_script( 'wdg-project-editor', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-project-editor.js', array('jquery', 'jquery-ui-dialog'), ASSETS_VERSION );
			}
		}

		// Styles et scripts liés aux pages d'investissements
		$pages_investment = array( 'investir', 'moyen-de-paiement', 'paiement-effectue', 'paiement-partager', 'terminer-preinvestissement', 'declarer-chiffre-daffaires', 'contrat-abonnement' );
		if ( in_array( $post_name, $pages_investment ) ) {
			wp_enqueue_style( 'invest-css', dirname( get_bloginfo( 'stylesheet_url' ) ).'/_inc/css/invest.min.css', null, ASSETS_VERSION, 'all' );
			wp_enqueue_script( 'wdg-project-invest', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-campaign-invest.js', array('jquery'), ASSETS_VERSION );
		}

		// Script lié aux pages d'admin
		if ($is_admin_page) {
			wp_enqueue_script( 'wdg-admin-dashboard', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-admin-dashboard.js', array('jquery'), ASSETS_VERSION);
		}

		if ( $post_name == 'equipe' ) {
			wp_enqueue_script( 'wdg-project-invest', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/ee-team.js', array('jquery'), ASSETS_VERSION );
		}

		// Ajout variable JS avec l'url de la page utilisée pour les requêtes Ajax
		wp_localize_script( 'wdg-script', 'ajax_object', array(
			'ajax_url'			=> admin_url( 'admin-ajax.php' ),
			'custom_ajax_url'	=> home_url( '/wp-content/plugins/appthemer-crowdfunding/includes/control/requests/ajax-entry-point.php' )
		));
	}

	public static function login_or_register() {
		// Vérifie si le formulaire de connexion ou d'inscription a été posté
		WDGFormUsers::login();
		WDGFormUsers::register();
	}

	public static function widgets_init() {
		// Area 1, located in the sidebar. Empty by default.
		register_sidebar( array(
			'name'          => 'Sidebar',
			'id'            => 'sidebar-1',
			'description'   => __( 'The sidebar widget area', 'buddypress' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>'
		) );

		// Area 2, located in the footer. Empty by default.
		register_sidebar( array(
			'name' => __( 'First Footer Widget Area', 'buddypress' ),
			'id' => 'first-footer-widget-area',
			'description' => __( 'The first footer widget area', 'buddypress' ),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>',
		) );

		// Area 3, located in the footer. Empty by default.
		register_sidebar( array(
			'name' => __( 'Second Footer Widget Area', 'buddypress' ),
			'id' => 'second-footer-widget-area',
			'description' => __( 'The second footer widget area', 'buddypress' ),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>',
		) );

		// Area 4, located in the footer. Empty by default.
		register_sidebar( array(
			'name' => __( 'Third Footer Widget Area', 'buddypress' ),
			'id' => 'third-footer-widget-area',
			'description' => __( 'The third footer widget area', 'buddypress' ),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>',
		) );

		// Area 5, located in the footer. Empty by default.
		register_sidebar( array(
			'name' => __( 'Fourth Footer Widget Area', 'buddypress' ),
			'id' => 'fourth-footer-widget-area',
			'description' => __( 'The fourth footer widget area', 'buddypress' ),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widgettitle">',
			'after_title' => '</h3>',
		) );
	}

	public function secure_api($result) {
		if ( !empty( $result ) ) {
			return $result;
		}
		if ( !is_user_logged_in() ) {
			return new WP_Error( 'rest_not_logged_in', 'You are not currently logged in.', array( 'status' => 401 ) );
		}

		return $result;
	}
}