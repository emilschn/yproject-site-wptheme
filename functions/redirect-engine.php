<?php
/**
 * Cette lib se charge de gérer les redirections, en fonction des langues
 */
class WDG_Redirect_Engine {

	private static $locale_id;

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
			return home_url( '/' .$page_name. '/' );

		// Sinon, on va chercher la page sur la langue correspondante
		} else {
			$translated_post_url = self::get_current_locale_page_url( $page_name );
			return $translated_post_url;
		}
	}

	/**
	 * Récupère le page_name en fonction de la langue à partir du page_name français
	 */
	public static function override_get_page_name( $page_name ) {
		if ( self::is_french_displayed() ) {
			return $page_name;
		} else {
			return self::get_current_locale_page_name( $page_name );
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
		if ( empty( self::$locale_id ) ) {
			// Si on est avec WPML, on se sert de la liste des langues pour récupérer la langue active
			if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
				$active_languages = apply_filters( 'wpml_active_languages', NULL );
				foreach ( $active_languages as $language_key => $language_item ) {
					if ( $language_item[ 'active' ] ) {
						$locale = $language_item[ 'code' ];
						break;
					}
				}
				
			// Sinon on récupère l'id de locale interne à WP
			} else {
				global $locale;
			}

			// Stocke en variable statique pour limiter ce calcul
			self::$locale_id = substr( $locale, 0, 2 );
		}

		return self::$locale_id;
	}

	/**
	 * Retourne la page demandée dans la bonne langue
	 */
	private static function get_current_locale_page_object( $page_name ) {
		// Récupération de la langue en cours
		$locale_id = self::get_current_locale_id();
		// Récupération de l'objet page initial (en français)
		$page_object_init = get_page_by_path( $page_name );
		// Récupération de l'id d'objet page traduit
		$page_object_translated_id = apply_filters( 'wpml_object_id', $page_object_init->ID, 'page', FALSE, $locale_id );
		// Récupération de l'objet page traduit
		$page_object_translated = get_post( $page_object_translated_id );
		return $page_object_translated;
	}

	/**
	 * Retourne le page_name demandé en fonction de la bonne langue
	 */
	private static function get_current_locale_page_name( $page_name ) {
		$page_object_translated = self::get_current_locale_page_object( $page_name );
		// Renvoi du page_name
		return $page_object_translated->post_name;
	}

	/**
	 * Retourne l'url de la page demandée dans la bonne langue
	 */
	private static function get_current_locale_page_url( $page_name ) {
		$page_object_translated = self::get_current_locale_page_object( $page_name );
		// Renvoi de l'url
		return get_permalink( $page_object_translated->ID );
	}

}