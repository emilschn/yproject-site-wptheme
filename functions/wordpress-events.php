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
		add_action( 'init', 'WDG_WordPress_Events::wordpress_init_event' );
	}
	
	/**
	 * Fonctions lancées automatiquement lors de l'action "init" de WordPress
	 */
	public static function wordpress_init_event() {
		// Donne la possibilité de mettre des tags aux pages
		register_taxonomy_for_object_type('post_tag', 'page');
		// Vérifie si nécessaire de lancer les tâches quotidiennes
		WDGCronActions::init_actions();
		// Vérifie si le formulaire de connexion ou d'inscription a été posté
		WDGFormUsers::login();
		WDGFormUsers::register();
		// Vérifie si le fomulaire d'inscription à la NL a été posté
		WDGPostActions::subscribe_newsletter_sendinblue();
	}
	
}