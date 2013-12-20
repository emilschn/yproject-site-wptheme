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
	remove_action( 'bp_member_header_actions',    'bp_send_public_message_button',  20 );
}
add_action( 'after_setup_theme', 'yproject_setup', 15 );

if ( ! isset( $content_width ) ) $content_width = 960;

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
function yproject_change_user_cap() {
    if ( is_user_logged_in() ) {
	global $editor_styles;
	$editor_styles = (array) $editor_styles;
	$stylesheet = 'editor-style.css';
	$stylesheet    = (array) $stylesheet;
	$editor_styles = array_merge( $editor_styles, $stylesheet );
	
	$role_subscriber = get_role("subscriber");
	$role_subscriber->add_cap( 'read' );
	$role_subscriber->add_cap( 'upload_files' );
	$role_subscriber->add_cap('edit_others_pages' );
	$role_subscriber->add_cap('edit_published_pages' );
	$role_subscriber->remove_cap('publish_pages' );
	$role_subscriber->remove_cap('edit_pages' );
	$role_subscriber->remove_cap('edit_others_posts' );
	$role_subscriber->remove_cap('edit_published_posts' );
	$role_subscriber->remove_cap('publish_posts' );
	$role_subscriber->remove_cap('edit_posts' );
    }
}
add_action('init', 'yproject_change_user_cap');

function yproject_my_files_only( $wp_query ) {
    global $current_user, $pagenow;
    if( !is_a( $current_user, 'WP_User') ) return;
    if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' ) return;
    if( !current_user_can('level_5') ) $wp_query->set('author', $current_user->ID );
    return;
}
add_filter('parse_query', 'yproject_my_files_only' );

function yproject_bbp_get_forum_title($title) {
    $campaign_post = get_post($title);
    return  $campaign_post->post_title;
}
add_filter('bbp_get_forum_title', 'yproject_bbp_get_forum_title');
?>