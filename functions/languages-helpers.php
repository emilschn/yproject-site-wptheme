<?php
/**
 * Cette lib contient les fonctions relatives aux langues
 */
class WDG_Languages_Helpers {
	private static $locale_id;
	private static $current_locale_display;

	/**
	 * Décharge et recharge les langues
	 */
	public static function reload_languages($locale = '') {
		if ( $locale == '' ) {
			$locale = self::get_current_locale_id();
		}
		unload_textdomain( 'yproject' );
		self::load_languages($locale);
	}

	/**
     * Décharge et recharge les langues
     */
	public static function load_languages($locale = '') {
		if ( $locale == '' ) {
			$locale = self::get_current_locale_id();
		}
		$path = get_template_directory();
		$mofile = $locale . '.mo';
		$buffer = load_textdomain( 'yproject', $path . '/languages/' . $mofile );
	}

	/**
	 * Retourne l'id de la langue en cours (fr, en, ...)
	 */
	public static function get_current_locale_id() {
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
	 * Retourne TRUE si la langue en cours est le français
	 */
	public static function is_french_displayed() {
		$locale_id = self::get_current_locale_id();

		return ( $locale_id == 'fr' );
	}

	/**
	 * Fonction qui passe en français temporairement
	 * Doit s'utiliser conjointement avec la fonction switch_back_to_display_language
	 */
	public static function switch_to_french_temp() {
		global $sitepress;
		// Enregistre en statique la langue en cours
		self::$current_locale_display = ICL_LANGUAGE_CODE;
		// La langue française de référence
		$new_lang = 'fr';
		// WPML passe en français
		$sitepress->switch_lang($new_lang);
	}

	/**
	 * Fonction qui retourne à la langue en cours
	 * Doit s'utiliser obligatoirement après la fonction switch_to_french_temp
	 */
	public static function switch_back_to_display_language() {
		global $sitepress;
		// WPML repasse à la langue d'affichage
		$sitepress->switch_lang( self::$current_locale_display );
	}
}