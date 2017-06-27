<?php
/**
 * Cette classe se charge d'auto-déterminer les fichiers à charger en préparation et en contenu
 * Cette classe doit être un singleton auto-instancié.
 */
class WDG_Templates_Engine {
	
	private static $instance;
	/**
	 * @return WDG_Templates_Engine
	 */
	public static function instance() {
		if ( ! isset ( self::$instance ) ) {
			self::$instance = new WDG_Templates_Engine();
		}
		return self::$instance;
	}
	
	public function __construct() {
		add_action( 'template_include', 'WDG_Templates_Engine::load_controler', 100, 2 );
		add_action( 'single_template', 'WDG_Templates_Engine::load_controler', 100, 2 );
		add_filter( 'body_class', 'WDG_Templates_Engine::body_class' );
		add_filter( 'the_content', 'WDG_Templates_Engine::override_content' );
	}
	
	public static $current_page_name;
	public static function get_page_name() {
		if ( ! isset ( self::$current_page_name ) ) {
			wp_reset_query();
			if ( is_home() or is_front_page() ) {
				self::$current_page_name = 'home';
				
			} elseif ( is_single() ) {
				self::$current_page_name = 'projet';

			} else {
				global $post;
				self::$current_page_name = $post->post_name;

			}
		}
		return self::$current_page_name;
	}
	
	
/*******************************************************************************
 * GESTION DES CONTROLERS
 ******************************************************************************/
	private static $controler_path = 'pages/controler/';
	
	/**
	 * Détermine le nom du controler à charger si il y en a
	 * @return string or boolean
	 */
	public function get_controler_name() {
		$page_name = WDG_Templates_Engine::get_page_name();
		if ( locate_template( WDG_Templates_Engine::$controler_path. 'controler-' .$page_name. '.php' ) ) {
			return $page_name;
		}
		return FALSE;
	}
	
	/**
	 * Appelé à chaque chargement de page :
	 * - charge de toute façon le controler général
	 * - charge le controler spécifique si existant
	 */
	public static function load_controler( $template ) {
		locate_template( WDG_Templates_Engine::$controler_path. 'controler.php', TRUE );
		
		$wdg_templates_engine = WDG_Templates_Engine::instance();
		$controler_name = $wdg_templates_engine->get_controler_name();
		if ( $controler_name ) {
			locate_template( WDG_Templates_Engine::$controler_path. 'controler-' .$controler_name. '.php', TRUE );
			
		} else {
			global $page_controler;
			$page_controler = new WDG_Page_Controler();
			
		}

		return $template;
	}
	
	/**
	 * Retourne l'objet controler
	 * Sans doute mieux à faire que de gérer une variable globale
	 * @return WDG_Page_Controler
	 */
	public function get_controler() {
		global $page_controler;
		return $page_controler;
	}
	
/******************************************************************************/
// CLASSE CSS DANS LE BODY
/******************************************************************************/
	public static function body_class( $classes ) {
		$page_name = WDG_Templates_Engine::get_page_name();
		array_push( $classes, 'template-' . $page_name );
		return $classes;
	}
	
/*******************************************************************************
 * GESTION DES VUES
 ******************************************************************************/
	private static $view_path = 'pages/view/';
	
	/**
	 * Détermine le nom de la vue à charger si il y en a
	 * @return string or boolean
	 */
	public function get_view_name() {
		$page_name = WDG_Templates_Engine::get_page_name();
		if ( ATCF_CrowdFunding::get_platform_context() != 'wedogood') {
			$page_name .= '-' . ATCF_CrowdFunding::get_platform_context();
		}
		if ( locate_template( WDG_Templates_Engine::$view_path. 'view-' .$page_name. '.php' ) ) {
			return $page_name;
		}
		return FALSE;
	}
	
	/**
	 * Appelé à chaque chargement de page :
	 * - si une existe sur une page, remplace le content
	 */
	public static function override_content( $content ) {
		$wdg_templates_engine = WDG_Templates_Engine::instance();
		$view = $wdg_templates_engine->get_view_name();
		if ( $view ) {
			ob_start();
			locate_template( WDG_Templates_Engine::$view_path. 'view-' .$view. '.php', TRUE );
			$content = ob_get_contents();
			ob_clean();
		}
		return $content;
	}
	
}

global $wdg_templates_engine;
$wdg_templates_engine = WDG_Templates_Engine::instance();