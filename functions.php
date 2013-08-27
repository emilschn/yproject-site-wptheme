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

/**
 * Redirige les erreurs de login
 * @param type $username
 */
function yproject_front_end_login_fail($username){
    $page_connexion = get_page_by_path('connexion');
    wp_redirect(get_permalink($page_connexion->ID) . '?login=failed');
    exit;
}
add_action('wp_login_failed', 'yproject_front_end_login_fail'); 
?>