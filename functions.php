<?php
if ( !function_exists( 'bp_dtheme_enqueue_styles' ) ) :
    function bp_dtheme_enqueue_styles() {}
endif;

/**
 * Declare textdomain for this yproject child theme.
 */
function yproject_setup() {
	load_child_theme_textdomain( 'yproject', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'yproject_setup' );
?>