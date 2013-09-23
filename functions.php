<?php
if ( !function_exists( 'bp_dtheme_enqueue_styles' ) ) :
    function bp_dtheme_enqueue_styles() {}
endif;

//Sécurité
remove_action("wp_head", "wp_generator");
add_filter('login_errors',create_function('$a', "return null;"));

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

function _catch_empty_user( $username, $pwd ) {
    if (empty($username)||empty($pwd)) {
	$page_connexion = get_page_by_path('connexion');
	wp_redirect(get_permalink($page_connexion->ID) . '?login=failed');
	exit();
    }
}
add_action( 'wp_authenticate', '_catch_empty_user', 1, 2 );

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

/**
 * Les fonctions gèrent l'affichage de la partie centrale de la page d'accueil (participer à un projet, proposer un projet)
 * @param type $atts
 * @param type $content
 * @return type
 */
function yproject_participate_project_shortcode($atts, $content) {
    return '<div class="home_half_size">' . $content . '</div>';
}
add_shortcode('yproject_participate_project', 'yproject_participate_project_shortcode');

function yproject_post_project_shortcode($atts, $content) {
    return '<div class="home_half_size">' . $content . '</div><div style="clear:both"></div>';
}
add_shortcode('yproject_post_project', 'yproject_post_project_shortcode');

function yproject_intro_home_shortcode($atts, $content) {
    return '<div class="home_intro">' . $content . '</div>';
}
add_shortcode('yproject_intro_home', 'yproject_intro_home_shortcode');

function yproject_home_discover_shortcode($atts, $content) {
    return '<div class="home_discover_half_size">' . $content . '</div>';
}
add_shortcode('yproject_home_discover', 'yproject_home_discover_shortcode');


//Permet à tous les utilisateurs inscrits d'insérer des images
function change_subscriber_cap() {
    $role = get_role( 'subscriber' );
    $role->add_cap( 'upload_files' );
}
add_action('init', 'change_subscriber_cap');
?>