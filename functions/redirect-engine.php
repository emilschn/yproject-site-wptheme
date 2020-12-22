<?php
/**
 * Cette lib se charge de gérer les redirections, en fonction des langues
 */
class WDG_Redirect_Engine {

	/**
	 * Surcharge la redirection standard de WP
	 */
	public static function override_redirect( $page_name ) {
		wp_redirect( self::override_get_page_url( $page_name ) );
	}

	/**
	 * Surcharge la recherche de la bonne URL
	 */
	public static function override_get_page_url( $page_name ) {
		// Si on est en français, on fait une simple redirection d'url
		if ( self::is_french_displayed() ) {
			return home_url( '/' .$url. '/' );

		// Sinon, on va chercher la page sur la langue correspondante
		} else {
			$translated_post_url = self::get_current_locale_page_url( $page_name );
			return $translated_post_url;
		}
	}

	/**
	 * Retourne TRUE si la langue en cours est le français
	 */
	private static function is_french_displayed() {
		$locale_id = self::get_current_locale_id();
		return ( $locale_id == 'fr' );
	}

	/**
	 * Retourne l'id de la langue en cours (fr, en, ...)
	 */
	private static function get_current_locale_id() {
		global $locale;
		return substr( $locale, 0, 2 );
	}

	/**
	 * Retourne la page demandée dans la bonne langue
	 */
	private static function get_current_locale_page_url( $page_name ) {
		// Récupération de la langue en cours
		$locale_id = self::get_current_locale_id();
		// Récupération de l'objet page initial (en français)
		$page_object_init = get_page_by_path( $page_name );
		// Récupération de l'id d'objet page traduit
		$page_object_translated_id = apply_filters( 'wpml_object_id', $page_object_init->ID, 'page', FALSE, $locale_id );
		// Récupération de l'objet page traduit
		$page_object_translated = get_post( $page_object_translated_id );
		// Renvoi de l'url
		return get_permalink( $page_object_translated->ID );
	}

}