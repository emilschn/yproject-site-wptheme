<?php
YPUIHelpers::register_filters();
class YPUIHelpers {
	public static function register_filters() {
		add_filter( 'tiny_mce_before_init', 'YPUIHelpers::tiny_mce_before_init' );
		add_filter( 'mce_buttons_2', 'YPUIHelpers::mce_buttons_2' );
		add_filter( 'mce_buttons', 'YPUIHelpers::mce_buttons', 5 );
		add_filter( 'oembed_result', 'YPUIHelpers::oembed_result', 1, true );
		//Suppression de code supplémentaire généré par edd
		remove_filter( 'the_content', 'edd_microdata_wrapper', 10 );
	}
	
	/**
	 * @deprecated Use UIHelpers::format_number
	 * Affiche les nombres de manière standardisée
	 * @param int $number
	 * @return string
	 */
	public static function display_number( $number, $money = FALSE ) {
		if ( empty( $number ) ) {
			return '0';
		}
		
		//Remplace les points par des virgules
		$buffer = UIHelpers::format_number( $number );
		
		return $buffer;
	}
	
	/**
	 * Ajoute des chiffres à gauche pour compléter les centaines et milliers, etc.
	 * @param int $number
	 * @param int $nb_str
	 * @return string
	 */
	public static function complete_number_str( $number, $nb_str = 3 ) {
		$buffer = $number;
		
		$n_test = 10;
		for ( $i = 2; $i <= $nb_str; $i++ ) {
			if ( $number < $n_test ) {
				$buffer = '0' . $buffer;
			}
			$n_test *= 10;
		}
		
		return $buffer;
	}
	
	
//***********************
// Modification TINYMCE
//***********************
	public static function tiny_mce_before_init($init) {
		$default_colours = '"000000", "Black",
							"993300", "Burnt orange",
							"333300", "Dark olive",
							"003300", "Dark green",
							"003366", "Dark azure",
							"000080", "Navy Blue",
							"333399", "Indigo",
							"333333", "Very dark gray",
							"800000", "Maroon",
							"FF6600", "Orange",
							"808000", "Olive",
							"008000", "Green",
							"008080", "Teal",
							"0000FF", "Blue",
							"666699", "Grayish blue",
							"808080", "Gray",
							"FF0000", "Red",
							"FF9900", "Amber",
							"99CC00", "Yellow green",
							"339966", "Sea green",
							"33CCCC", "Turquoise",
							"3366FF", "Royal blue",
							"800080", "Purple",
							"999999", "Medium gray",
							"FF00FF", "Magenta",
							"FFCC00", "Gold",
							"FFFF00", "Yellow",
							"00FF00", "Lime",
							"00FFFF", "Aqua",
							"00CCFF", "Sky blue",
							"993366", "Red violet",
							"FFFFFF", "White",
							"FF99CC", "Pink",
							"FFCC99", "Peach",
							"FFFF99", "Light yellow",
							"CCFFCC", "Pale green",
							"CCFFFF", "Pale cyan",
							"99CCFF", "Light sky blue",
							"CC99FF", "Plum"';

		// Ajout couleur WDG
		$custom_colours =  '"EA4F51", "WE DO GOOD"';

		// build colour grid default+custom colors
		$init['textcolor_map'] = '['.$default_colours.','.$custom_colours.']';

		// enable 6th row for custom colours in grid
		$init['textcolor_rows'] = 6;

		return $init;
	}

	public static function mce_buttons_2( $buttons ) {
	   array_unshift($buttons, 'fontsizeselect');
	   return $buttons;
	}

	public static function mce_buttons( $buttons_array ){
		if ( !in_array( 'alignjustify', $buttons_array ) && in_array( 'alignright', $buttons_array ) ){
			$key = array_search( 'alignright', $buttons_array );
			$inserted = array( 'alignjustify' );
			array_splice( $buttons_array, $key + 1, 0, $inserted );
		}
		if ( !in_array( 'underline', $buttons_array ) && in_array( 'italic', $buttons_array ) ){
			$key = array_search( 'italic', $buttons_array );
			$inserted = array( 'underline' );
			array_splice( $buttons_array, $key + 1, 0, $inserted );
		}

		return $buttons_array;
	}
	
//***********************
// FIN - Modification TINYMCE
//***********************

	/**
	 * Ajoute rel=0 à la fin de l'url de la vidéo
	 * @param type $embed
	 * @return type
	 */
	public static function oembed_result( $embed ) {
		if (strstr($embed,'http://www.youtube.com/embed/') || strstr($embed,'https://www.youtube.com/embed/')) {
			$embed = str_replace( 'frameborder="0"', 'style="border: none"', $embed );
			$embed = str_replace( 'allowfullscreen', '', $embed );
			$embed = str_replace( 'feature=oembed', 'feature=oembed&rel=0&wmode=transparent', $embed );
		}
		return $embed;
	}
}


// A trier :

//Interdit l'accès à l'admin pour les utilisateurs qui ne sont pas admins
function yproject_admin_init() {
	global $pagenow;
	if ($pagenow != 'media-new.php' && $pagenow != 'async-upload.php' && $pagenow != 'media-upload.php' && $pagenow != 'media.php' && !current_user_can('level_5') ) {
		wp_redirect( site_url() );
		exit;
	}
}
//add_action( 'admin_init', 'yproject_admin_init' );


//Permet de n'afficher que les images uploadées par l'utilisateur en cours
function yproject_my_files_only( $wp_query ) {
	global $current_user, $pagenow;
	if( !is_a( $current_user, 'WP_User') ) return;
	if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' ) return;
	if( !current_user_can('level_5') ) $wp_query->set('author', $current_user->ID );
	return;
}
add_filter('parse_query', 'yproject_my_files_only' );


//******************************************************************************
// TODO : A placer uniquement pour les campagnes
function yproject_campaign_open_comments( $open, $post_id ) {
	if (empty($post_id)) {
		global $campaign;
	} else {
		$post_campaign = get_post($post_id);
		$campaign = new ATCF_Campaign( $post_campaign );
	}
	if (!empty($campaign)) {
		if ($campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote 
			|| $campaign->campaign_status() == ATCF_Campaign::$campaign_status_collecte) {
			$open = TRUE;
		}
	}
	return $open;
}
add_filter( 'comments_open', 'yproject_campaign_open_comments', 11, 2 );

function yproject_page_template( $template ) {
	global $post;
	$campaign = atcf_get_campaign( $post );
	if (!empty($campaign->ID) && is_object( $campaign ) && ($campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing) && !$campaign->current_user_can_edit()) {
		header("Status: 404 Not Found");
		global $wp_query;
		$wp_query->set_404();
		status_header(404);
		nocache_headers();
		$new_template = locate_template( array( '404.php' ) );
		return $new_template;
	}
	return $template;
}
add_filter( 'template_include', 'yproject_page_template', 99 );
