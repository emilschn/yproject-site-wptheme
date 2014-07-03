<?php
//Chargement de la css de buddypress
if ( !function_exists( 'bp_dtheme_enqueue_styles' ) ) :
	function bp_dtheme_enqueue_styles() {}
endif;

//Définition de la largeur de l'affichage
if ( ! isset( $content_width ) ) $content_width = 960;

//Définition du domaine pour les traductions
function yproject_setup() {
	load_child_theme_textdomain( 'yproject', get_stylesheet_directory() . '/languages' );
	remove_action( 'bp_member_header_actions',    'bp_send_public_message_button',  20 );
}
add_action( 'after_setup_theme', 'yproject_setup', 15 );

//Sécurité
remove_action("wp_head", "wp_generator");
add_filter('login_errors',create_function('$a', "return null;"));

add_action( 'wp_enqueue_scripts', 'yproject_enqueue_script' );
function yproject_enqueue_script(){
	if ( !is_admin() ) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"), false);
		wp_enqueue_script('jquery');
	}
	wp_enqueue_script( 'wdg-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/common.js', array('jquery', 'jquery-ui-dialog'));
	wp_localize_script( 'wdg-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
	wp_enqueue_script( 'chart-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/chart.new.js', array('wdg-script'));
}

/** GESTION DU LOGIN **/
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


function yproject_redirect_login() {
	if (isset($_POST['redirect-page'])) {
		$page_id = $_POST['redirect-page'];
		$page = get_page($page_id);
		wp_redirect(get_permalink($page));
	} else {
		wp_redirect(home_url());
	}
	exit;
}
add_action('wp_login', 'yproject_redirect_login');

function yproject_redirect_logout(){
	if (isset($_GET['page_id'])) {
		$page_id = $_GET['page_id'];
		$page = get_page($page_id);
		wp_redirect(get_permalink($page));
	} else {
		wp_redirect(home_url());
	}
	exit;
}
add_action('wp_logout', 'yproject_redirect_logout');

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
/** FIN GESTION DU LOGIN **/


/** GESTION DES ROLES UTILISATEURS **/
//Permet à tous les utilisateurs inscrits d'insérer des images
function yproject_change_user_cap() {
	if ( is_user_logged_in() ) {
		//Redéfinit le style de tinymce
		global $editor_styles;
		$editor_styles = (array) $editor_styles;
		$stylesheet    = 'editor-style.css';
		$stylesheet    = (array) $stylesheet;
		$editor_styles = array_merge( $editor_styles, $stylesheet );

		//Redéfinit le role utilisateur pour permettre l'upload de fichier
		$role_subscriber = get_role("subscriber");
		$role_subscriber->add_cap( 'level_0' );
		$role_subscriber->remove_cap( 'level_1' );
		$role_subscriber->add_cap( 'read' );
		$role_subscriber->add_cap( 'upload_files' );
		$role_subscriber->remove_cap( 'publish_pages' );
		$role_subscriber->remove_cap( 'edit_pages' );
		$role_subscriber->remove_cap( 'edit_private_pages' );
		$role_subscriber->add_cap( 'edit_published_pages' );
		$role_subscriber->add_cap( 'edit_others_pages' );
		$role_subscriber->remove_cap( 'publish_posts' );
		$role_subscriber->remove_cap( 'edit_post' );
		$role_subscriber->remove_cap( 'edit_posts' );
		$role_subscriber->remove_cap( 'edit_private_posts' );
		$role_subscriber->remove_cap( 'edit_published_posts' );
		$role_subscriber->remove_cap( 'edit_others_posts' );
	}
}
add_action('init', 'yproject_change_user_cap');

//Permet de n'afficher que les images uploadées par l'utilisateur en cours
function yproject_my_files_only( $wp_query ) {
	global $current_user, $pagenow;
	if( !is_a( $current_user, 'WP_User') ) return;
	if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' ) return;
	if( !current_user_can('level_5') ) $wp_query->set('author', $current_user->ID );
	return;
}
add_filter('parse_query', 'yproject_my_files_only' );

//Interdit l'accès à l'admin pour les utilisateurs qui ne sont pas admins
function yproject_admin_init() {
	global $pagenow;
	if ($pagenow != 'media-new.php' && $pagenow != 'async-upload.php' && $pagenow != 'media-upload.php' && $pagenow != 'media.php' && !current_user_can('level_5') ) {
		wp_redirect( site_url() );
		exit;
	}
}
//add_action( 'admin_init', 'yproject_admin_init' );
/** FIN GESTION DES ROLES UTILISATEURS **/



/** SHORTCODES ACCUEIL **/
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

/** FIN SHORTCODES ACCUEIL **/



function yproject_bbp_get_forum_title($title) {
    $campaign_post = get_post($title);
    return  $campaign_post->post_title;
}
add_filter('bbp_get_forum_title', 'yproject_bbp_get_forum_title');

/**
 * Ajoute rel=0 à la fin de l'url de la vidéo
 * @param type $embed
 * @return type
 */
function remove_related_videos($embed) {
    if (strstr($embed,'http://www.youtube.com/embed/')) {
	return str_replace('feature=oembed','feature=oembed&rel=0',$embed);
    } else {
	return $embed;
    }
}
add_filter('oembed_result', 'remove_related_videos', 1, true);



/**
 * Permet d'envoyer la position de l'image de couverture d'un projet.
 * 
 */
function set_cover_position(){
	if(isset($_POST['top'])){
		$post_meta=get_post_meta($_POST['id_campaign'], 'campaign_cover_position', TRUE);
		if($post_meta==''){
			add_post_meta($_POST['id_campaign'], 'campaign_cover_position', $_POST['top'], TRUE);
			 }
		update_post_meta($_POST['id_campaign'],'campaign_cover_position', $_POST['top']);
	}
	do_action('wdg_delete_cache',array('project-'.$post->ID.'-header-second'));

}
add_action( 'wp_ajax_setCoverPosition', 'set_cover_position' );

/**
 * Permet d'envoyer la position de l'image de couverture d'un projet.
 * 
 */
function set_cursor_position(){
	if(isset($_POST['top'])){
		$post_meta_top=get_post_meta($_POST['id_campaign'], 'campaign_cursor_top_position', TRUE);
		$post_meta_left=get_post_meta($_POST['id_campaign'], 'campaign_cursor_left_position', TRUE);
		if($post_meta_top==''){
			add_post_meta($_POST['id_campaign'], 'campaign_cursor_top_position', $_POST['top'], TRUE);
		}
		if($post_meta_left==''){
			add_post_meta($_POST['id_campaign'], 'campaign_cursor_left_position', $_POST['left'], TRUE);
		}
		update_post_meta($_POST['id_campaign'],'campaign_cursor_top_position', $_POST['top']);
		update_post_meta($_POST['id_campaign'],'campaign_cursor_left_position', $_POST['left']);
		do_action('wdg_delete_cache',array('project-'.$post->ID.'-content'));
	}

}
add_action( 'wp_ajax_setCursorPosition', 'set_cursor_position' );


function print_user_avatar($user_id){
		
	    $bp = buddypress();
	    $bp->avatar->full->default = get_stylesheet_directory_uri() . "/images/default_avatar.jpg";
	    
	    $profile_type = "";
	    $google_meta = get_user_meta($user_id, 'social_connect_google_id', true);
	    if (isset($google_meta) && $google_meta != "") $profile_type = ""; //TODO : Remplir avec "google" quand on gèrera correctement
	    $facebook_meta = get_user_meta(bp_displayed_user_id(), 'social_connect_facebook_id', true);
	    if (isset($facebook_meta) && $facebook_meta != "") $profile_type = "facebook";
	    
	    $url = get_stylesheet_directory_uri() . "/images/default_avatar.jpg";
	    switch ($profile_type) {
		case "google":
		    $meta_explode = explode("id?id=", $google_meta);
		    $social_id = $meta_explode[1];
		    $url = "http://plus.google.com/s2/photos/profile/" . $social_id . "?sz=149";
		    echo '<img src="' .$url . '" width="150"/>';
		    break;
		case "facebook":
		    $url = "https://graph.facebook.com/" . $facebook_meta . "/picture?type=normal";
		    echo '<img src="' .$url . '" width="150"/>';
		    break;
		default :
		    //bp_displayed_user_avatar( 'type=full' );
		    echo '<img src="'.$url.'" width="150" />';
		    break;
	    }
}
function get_user_avatar($user_id){
		
	    $bp = buddypress();
	    $bp->avatar->full->default = get_stylesheet_directory_uri() . "/images/default_avatar.jpg";
	    
	    $profile_type = "";
	    $google_meta = get_user_meta($user_id, 'social_connect_google_id', true);
	    if (isset($google_meta) && $google_meta != "") $profile_type = ""; //TODO : Remplir avec "google" quand on gèrera correctement
	    $facebook_meta = get_user_meta(bp_displayed_user_id(), 'social_connect_facebook_id', true);
	    if (isset($facebook_meta) && $facebook_meta != "") $profile_type = "facebook";
	    
	    $url = get_stylesheet_directory_uri() . "/images/default_avatar.jpg";
	    switch ($profile_type) {
		case "google":
		    $meta_explode = explode("id?id=", $google_meta);
		    $social_id = $meta_explode[1];
		    $url = "http://plus.google.com/s2/photos/profile/" . $social_id . "?sz=149";
		    return '<img src="' .$url . '" width="150"/>';
		    break;
		case "facebook":
		    $url = "https://graph.facebook.com/" . $facebook_meta . "/picture?type=normal";
		    return '<img src="' .$url . '" width="150"/>';
		    break;
		default :
		    //bp_displayed_user_avatar( 'type=full' );
		    return '<img src="'.$url.'" width="150" />';
		    break;
	    }
}
function update_jy_crois(){
	global $wpdb, $post;
	$table_jcrois = $wpdb->prefix . "jycrois";

	if (isset($_POST['id_campaign'])) $post = get_post($_POST['id_campaign']);
	$campaign = atcf_get_campaign( $post );
	$campaign_id = $campaign->ID;

	// Construction des urls utilisés dans les liens du fil d'actualité
	// url d'une campagne précisée par son nom 
	$campaign_url = get_permalink($_POST['id_campaign']);
	$post_title = $post->post_title;
	$url_campaign = '<a href="'.$campaign_url.'">'.$post_title.'</a>';
	//url d'un utilisateur précis
	$user_id = wp_get_current_user()->ID;
	$user_display_name = wp_get_current_user()->display_name;
	$url_profile = '<a href="' . bp_core_get_userlink($user_id, false, true) . '">' . $user_display_name . '</a>';

	//J'y crois
	if(isset($_POST['jy_crois']) && $_POST['jy_crois'] == 1){
		$wpdb->insert( 
			$table_jcrois,
			array(
				'user_id'	    => $user_id,
				'campaign_id'   => $campaign_id
			)
		); 
		bp_activity_add(array (
			'component' => 'profile',
			'type'      => 'jycrois',
			'action'    => $url_profile.' croit au projet '.$url_campaign
		));
		
	//J'y crois pas
	} else if (isset($_POST['jy_crois']) && $_POST['jy_crois'] == 0) { 
		$wpdb->delete( 
			$table_jcrois,
			array(
				'user_id'      => $user_id,
				'campaign_id'  => $campaign_id
			)
		);
		// Inserer l'information dans la table du fil d'activité  de la BDD wp_bp_activity 
		bp_activity_delete(array (
			'user_id'   => $user_id,
			'component' => 'profile',
			'type'      => 'jycrois',
			'action'    => $url_profile.' croit au projet '.$url_campaign
		));
	}
	echo $wpdb->get_var( "SELECT count(campaign_id) FROM $table_jcrois WHERE campaign_id = $campaign_id" );
}
add_action( 'wp_ajax_update_jy_crois', 'update_jy_crois' );

function comment_blog_post(){
	global $wpdb, $post;
	// Construction des urls utilisés dans les liens du fil d'actualité
	// url d'une campagne précisée par son nom 
	$post_title = $post->post_title;
	$url_blog = '<a href="'.get_permalink( $post->ID ).'">'.$post_title.'</a>';
	//url d'un utilisateur précis
	$user_id                = wp_get_current_user()->ID;
	$user_display_name      = wp_get_current_user()->display_name;
	$url_profile = '<a href="' . bp_core_get_userlink($user_id, false, true) . '"> ' . $user_display_name . '</a>';
	$user_avatar=get_user_avatar($user_id);

	bp_activity_add(array (
		'component' => 'profile',
		'type'      => 'jycrois',
		'action'    => $user_avatar.' '.$url_profile.' a commenté '.$url_blog
	    ));
}
add_action('comment_post','comment_blog_post');
?>