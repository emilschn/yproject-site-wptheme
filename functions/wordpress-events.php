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
		if ( ! isset ( self::$instance ) ) {
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
		add_action( 'wp_insert_comment', array('NotificationsEmails', 'new_comment'), 99 ,2 );
		// Composants
		add_action( 'widgets_init', 'WDG_WordPress_Events::widgets_init' );
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
		// Vérifie si nécessaire de lancer les tâches quotidiennes
		WDGCronActions::init_actions();
		// Vérifie si le formulaire de connexion ou d'inscription a été posté
		WDGFormUsers::login();
		WDGFormUsers::register();
		// Vérifie si le fomulaire d'inscription à la NL a été posté
		WDGPostActions::subscribe_newsletter_sendinblue();
		
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
		
	}
	
	/**
	 * Définition du domaine pour les traductions
	 */
	public static function after_setup_theme() {
		load_child_theme_textdomain( 'yproject', get_stylesheet_directory() . '/languages' );
	}

	/**
	 * S'assure que les tags sont inclus dans les requêtes
	 */
	public static function pre_get_posts( $wp_query ) {
		if ( $wp_query->get('tag') ) $wp_query->set( 'post_type', 'any' );
	}

	/**
	 * Gestion cache côté serveur (configuration Varnish)
	 */
	public static function send_headers() {
		header('X-UA-Compatible: IE=edge');
		session_cache_limiter('');
		header('Cache-Control: public, s-maxage=120');
		header('Pragma: public');
		if( !session_id() ) {
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
	 * Inscription à la NL lors de l'inscription
	 */
	public static function user_register( $user_id ) {
		$user = get_userdata( $user_id );
		WDGPostActions::subscribe_newsletter_sendinblue( $user->user_email );
	}
	
	/**
	 * Charge les styles et scripts nécessaires pour la page
	 */
	public static function wp_enqueue_scripts() {
		global $can_modify, $is_campaign, $is_campaign_page, $post;
		$campaign = atcf_get_current_campaign();
		$can_modify = ($is_campaign) && ($campaign->current_user_can_edit());
		$is_dashboard_page = ($post->post_name == 'gestion-financiere' || $post->post_name == 'tableau-de-bord');
		$is_admin_page = ($post->post_name == 'liste-des-paiements');
		$current_version = '20170920';

		if ( !is_admin() ) {
			wp_deregister_script('jquery');
			wp_register_script('jquery', (dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery.min.js'), false);
			wp_enqueue_script('jquery');
		}
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('wdg-project-dashboard-i18n-fr', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/i18n/datepicker-fr.js', array('jquery', 'jquery-ui-datepicker'), $current_version);

		wp_enqueue_style('jquery-ui-wdg',dirname( get_bloginfo('stylesheet_url')).'/_inc/css/jquery-ui-wdg.css', null, false, 'all');

		wp_deregister_style( 'font-awesome' );
		wp_register_style('font-awesome', (dirname( get_bloginfo('stylesheet_url')).'/_inc/css/font-awesome.min.css'));
		wp_enqueue_style('font-awesome');

		wp_enqueue_script( 'wdg-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/common.js', array('jquery'), $current_version);
		if (is_home() || is_front_page() || $post->post_name == 'les-projets') { 
			wp_enqueue_script('wdg-slider', dirname(get_bloginfo('stylesheet_url')).'/_inc/js/slideshow.js', array('jquery'), $current_version);            
		}       
		if ($is_campaign) { wp_enqueue_script( 'wdg-project-invest', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-campaign-invest.js', array('jquery'), $current_version); }

		//Fichiers du tableau de bord (CSS, Fonctions Ajax et scripts de Datatable)
		if ($is_dashboard_page && $can_modify) {
			wp_enqueue_script( 'wdg-project-dashboard', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-project-dashboard.js', array('jquery'), $current_version);
			wp_enqueue_style( 'dashboard-css', dirname( get_bloginfo('stylesheet_url')).'/_inc/css/dashboard.css', null, $current_version, 'all');


			wp_enqueue_script( 'datatable-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/dataTables/jquery.dataTables.min.js', array('jquery', 'wdg-script'), true, true);
			wp_enqueue_style('datatable-css', dirname( get_bloginfo('stylesheet_url')).'/_inc/css/dataTables/jquery.dataTables.min.css', null, false, 'all');

			wp_enqueue_script( 'datatable-colreorder-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/dataTables/dataTables.colReorder.min.js', array('datatable-script'), true, true);
			wp_enqueue_style('datatable-colreorder-css', dirname( get_bloginfo('stylesheet_url')).'/_inc/css/dataTables/colReorder.dataTables.min.css', null, false, 'all');

			wp_enqueue_script( 'datatable-select-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/dataTables/dataTables.select.min.js', array('datatable-script'), true, true);
			wp_enqueue_style('datatable-select-css', dirname( get_bloginfo('stylesheet_url')).'/_inc/css/dataTables/select.dataTables.min.css', null, false, 'all');

			wp_enqueue_script( 'datatable-buttons-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/dataTables/dataTables.buttons.min.js', array('datatable-script'), true, true);
			wp_enqueue_script( 'datatable-buttons-colvis-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/dataTables/buttons.colVis.min.js', array('datatable-script'), true, true);
			wp_enqueue_script( 'datatable-buttons-html5-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/dataTables/buttons.html5.min.js', array('datatable-script'), true, true);
			wp_enqueue_script( 'datatable-buttons-print-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/dataTables/buttons.print.min.js', array('datatable-script'), true, true);
			wp_enqueue_script( 'datatable-jszip-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/dataTables/jszip.min.js', array('datatable-script'), true, true);
			wp_enqueue_style('datatable-buttons-css', dirname( get_bloginfo('stylesheet_url')).'/_inc/css/dataTables/buttons.dataTables.min.css', null, false, 'all');

		}
		if ($is_admin_page) { wp_enqueue_script( 'wdg-admin-dashboard', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-admin-dashboard.js', array('jquery'), $current_version); }
		wp_enqueue_script( 'jquery-form', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery.form.js', array('jquery'));
		wp_enqueue_script( 'chart-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/chart.new.js', array('wdg-script'), true, true);
		wp_enqueue_script( 'sharer-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/sharer.min.js', array(), true, true);
	//	wp_enqueue_script( 'wdg-ux-helper', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-ux-helper.js', array('wdg-script'));

		if ($is_campaign_page) {
			if ($is_campaign && !$is_dashboard_page) {
				wp_enqueue_style( 'campaign-css', dirname( get_bloginfo('stylesheet_url')).'/_inc/css/campaign.min.css', null, $current_version, 'all');
			}
			wp_enqueue_script( 'wdg-campaign', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-campaign.js', array('jquery', 'jquery-ui-dialog'), $current_version);
			if ($is_campaign_page && $can_modify) { wp_enqueue_script( 'wdg-project-editor', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-project-editor.js', array('jquery', 'jquery-ui-dialog'), $current_version); }
		} else {
			if ($is_campaign_page && $can_modify) { wp_enqueue_script( 'wdg-project-editor', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-project-editor-v2.js', array('jquery'), $current_version); }
		}
		
		if ( $post->post_name == 'mon-compte' ) {
			wp_enqueue_style( 'dashboard-investor-css', dirname( get_bloginfo( 'stylesheet_url' ) ).'/_inc/css/dashboard-investor.css', null, $current_version, 'all');
			wp_enqueue_script( 'wdg-user-account', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-user-account.js', array('jquery', 'jquery-ui-dialog'), $current_version);
		}

		wp_enqueue_script('qtip', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery.qtip.min.js', array('jquery'));
		wp_enqueue_style('qtip', dirname( get_bloginfo('stylesheet_url')).'/_inc/css/jquery.qtip.min.css', null, false, 'all');

		wp_localize_script( 'wdg-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
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
}