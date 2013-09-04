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

/**
 * permet de se loger avec son mail
 * @param type $username
 * @return type
 */
function yproject_email_login_authenticate( $user, $username, $password ) {
    if ( is_a( $user, 'WP_User' ) ) return $user;

    if ( !empty( $username ) ) {
	$username = str_replace( '&', '&amp;', stripslashes( $username ) );
	$user = get_user_by( 'email', $username );
	if ( isset( $user, $user->user_login, $user->user_status ) && 0 == (int) $user->user_status )
	    $username = $user->user_login;
    }

    return wp_authenticate_username_password( null, $username, $password );
}
remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );
add_filter( 'authenticate', 'yproject_email_login_authenticate', 20, 3 );
?>