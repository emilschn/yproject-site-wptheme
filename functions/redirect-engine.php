<?php
/**
 * Cette lib se charge de gérer les redirections, en fonction des langues
 */
class WDG_Redirect_Engine {
	private static $locale_id;

	/**
	 * Surcharge la redirection standard de WP
	 */
	public static function override_redirect($page_name) {
		wp_redirect( self::override_get_page_url( $page_name ) );
	}

	/**
	 * Surcharge la recherche de la bonne URL
	 */
	public static function override_get_page_url($page_name) {
		global $force_language_to_translate_to;
		
		// Si on est en français (et qu'on n'a pas forcé une autre langue), on fait une simple redirection d'url
		if ( WDG_Languages_Helpers::is_french_displayed() && empty( $force_language_to_translate_to ) ) {
			return home_url( '/' .$page_name. '/' );

		// Sinon, on va chercher la page sur la langue correspondante
		} else {
			if ( !empty( $force_language_to_translate_to ) ) {
				WDG_Languages_Helpers::switch_to_temp_language( $force_language_to_translate_to );
			}
			$translated_post_url = self::get_current_locale_page_url( $page_name );
			if ( !empty( $force_language_to_translate_to ) ) {
				WDG_Languages_Helpers::switch_back_to_display_language();
			}

			return $translated_post_url;
		}
	}

	/**
	 * Récupère le page_name en fonction de la langue à partir du page_name français
	 */
	public static function override_get_page_name($page_name) {
		if ( WDG_Languages_Helpers::is_french_displayed() ) {
			return $page_name;
		} else {
			return self::get_current_locale_page_name( $page_name );
		}
	}

	/**
	 * Retourne la page demandée dans la bonne langue
	 */
	private static function get_current_locale_page_object($page_name) {
		// Récupération de la langue en cours
		$locale_id = WDG_Languages_Helpers::get_current_locale_id();
		global $force_language_to_translate_to;
		if ( !empty( $force_language_to_translate_to ) ) {
			$locale_id = $force_language_to_translate_to;
		}
		// Récupération de l'objet page initial (en français)
		$page_object_init = get_page_by_path( $page_name );
		// Récupération de l'id d'objet page traduit
		$page_object_translated_id = apply_filters( 'wpml_object_id', $page_object_init->ID, 'page', FALSE, $locale_id );
		// Récupération de l'objet page traduit
		if ( !empty( $page_object_translated_id ) ) {
			$page_object_translated = get_post( $page_object_translated_id );

			return $page_object_translated;
		} else {
			ypcf_function_log( 'get_current_locale_page_object', $page_name . ' not found' );

			return FALSE;
		}
	}

	/**
	 * Retourne le page_name demandé en fonction de la bonne langue
	 */
	private static function get_current_locale_page_name($page_name) {
		$page_object_translated = self::get_current_locale_page_object( $page_name );
		// Renvoi du page_name
		if ( !empty( $page_object_translated ) ) {
			return $page_object_translated->post_name;
		} else {
			return $page_name;
		}
	}

	/**
	 * Retourne l'url de la page demandée dans la bonne langue
	 */
	private static function get_current_locale_page_url($page_name) {
		$page_object_translated = self::get_current_locale_page_object( $page_name );
		// Renvoi de l'url
		if ( !empty( $page_object_translated ) ) {
			return get_permalink( $page_object_translated->ID );
		} else {
			return home_url( '/' .$page_name. '/' );
		}
	}
}