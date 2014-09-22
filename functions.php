<?php
//Chargement de la css de buddypress
if ( !function_exists( 'bp_dtheme_enqueue_styles' ) ) :
	function bp_dtheme_enqueue_styles() {}
endif;

//DÃ©finition de la largeur de l'affichage
if ( ! isset( $content_width ) ) $content_width = 960;

//DÃ©finition du domaine pour les traductions
function yproject_setup() {
	load_child_theme_textdomain( 'yproject', get_stylesheet_directory() . '/languages' );
	remove_action( 'bp_member_header_actions',    'bp_send_public_message_button',  20 );
}
add_action( 'after_setup_theme', 'yproject_setup', 15 );

//SÃ©curitÃ©
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
//Permet Ã  tous les utilisateurs inscrits d'insÃ©rer des images
function yproject_change_user_cap() {
	if ( is_user_logged_in() ) {
		//RedÃ©finit le style de tinymce
		global $editor_styles;
		$editor_styles = (array) $editor_styles;
		$stylesheet    = 'editor-style.css';
		$stylesheet    = (array) $stylesheet;
		$editor_styles = array_merge( $editor_styles, $stylesheet );

		//RedÃ©finit le role utilisateur pour permettre l'upload de fichier
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
		$role_subscriber->add_cap( 'edit_published_posts' );
		$role_subscriber->add_cap( 'edit_others_posts' );
	}
}
add_action('init', 'yproject_change_user_cap');

//Permet de n'afficher que les images uploadÃ©es par l'utilisateur en cours
function yproject_my_files_only( $wp_query ) {
	global $current_user, $pagenow;
	if( !is_a( $current_user, 'WP_User') ) return;
	if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' ) return;
	if( !current_user_can('level_5') ) $wp_query->set('author', $current_user->ID );
	return;
}
add_filter('parse_query', 'yproject_my_files_only' );

//Interdit l'accÃ¨s Ã  l'admin pour les utilisateurs qui ne sont pas admins
function yproject_admin_init() {
	global $pagenow;
	if ($pagenow != 'media-new.php' && $pagenow != 'async-upload.php' && $pagenow != 'media-upload.php' && $pagenow != 'media.php' && !current_user_can('level_5') ) {
		wp_redirect( site_url() );
		exit;
	}
}
//add_action( 'admin_init', 'yproject_admin_init' );

function yproject_page_template( $template ) {
	locate_template( array("requests/projects.php"), true );
	global $post;
	$campaign = atcf_get_campaign( $post );
	$campaign_id = $post->ID;
	if (is_object( $campaign ) && ($campaign->campaign_status() == 'preparing') && !YPProjectLib::current_user_can_edit($campaign_id)) {
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
/** FIN GESTION DES ROLES UTILISATEURS **/



/** SHORTCODES ACCUEIL **/
/**
 * Les fonctions gÃ¨rent l'affichage de la partie centrale de la page d'accueil (participer Ã  un projet, proposer un projet)
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

/**
 * BIBLIOTHEQUE POUR VERIFICATIONS
 */
function yproject_check_user_can_see_project_page() {
	//Si l'utilisateur n'est pas connectÃ©, on redirige sur la page de connexion
	if (!is_user_logged_in()) {
		$page_connexion = get_page_by_path('connexion');
		wp_redirect(get_permalink($page_connexion->ID));
		exit();
	}
	//Si la campagne n'est pas dÃ©finie, on retourne Ã  l'accueil
	if (!isset($_GET['campaign_id'])) {
		wp_redirect(site_url());
		exit();
	}
}


function yproject_bbp_get_forum_title($title) {
    $campaign_post = get_post($title);
    return  $campaign_post->post_title;
}
add_filter('bbp_get_forum_title', 'yproject_bbp_get_forum_title');

/**
 * Ajoute rel=0 Ã  la fin de l'url de la vidÃ©o
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


function print_user_avatar($user_id, $size = 'normal'){
	echo get_user_avatar($user_id, $size);
}
function get_user_avatar($user_id, $size = 'normal'){
	switch ($size) {
	    case 'normal':
		$width = 150;
		break;
	    case 'thumb':
		$width = 50;
		break;
	}

	$avatar_path = '';
	$upload_dir = wp_upload_dir();
	
	if ( file_exists( BP_AVATAR_UPLOAD_PATH . '/avatars/' . bp_loggedin_user_id() . '/avatar.jpg' )) {
		$avatar_path = $upload_dir['baseurl'] . '/avatars/' . bp_loggedin_user_id() . '/avatar.jpg';
		return '<img src="' .$avatar_path . '" width="' . $width . '" height="' . $width . '"/>';
		
	} elseif (file_exists( BP_AVATAR_UPLOAD_PATH. '/avatars/' . bp_loggedin_user_id() . '/avatar.png' )) {
		$avatar_path = $upload_dir['baseurl'] . '/avatars/' . bp_loggedin_user_id() . '/avatar.png';
		return '<img src="' . $avatar_path . '" width="' . $width . '" height="' . $width . '"/>';
		
	} else {
		$bp = buddypress();
		$bp->avatar->full->default = get_stylesheet_directory_uri() . "/images/default_avatar.jpg";

		$profile_type = "";
		$google_meta = get_user_meta($user_id, 'social_connect_google_id', true);
		if (isset($google_meta) && $google_meta != "") $profile_type = ""; //TODO : Remplir avec "google" quand on gÃ¨rera correctement
		$facebook_meta = get_user_meta($user_id, 'social_connect_facebook_id', true);
		if (isset($facebook_meta) && $facebook_meta != "") $profile_type = "facebook";

		$url = get_stylesheet_directory_uri() . "/images/default_avatar.jpg";
		switch ($profile_type) {
		    case "google":
			$meta_explode = explode("id?id=", $google_meta);
			$social_id = $meta_explode[1];
			$url = "http://plus.google.com/s2/photos/profile/" . $social_id . "?sz=".($width-1);
			return '<img src="' .$url . '" width="'.$width.'"/>';
			break;
		    case "facebook":
			if ($size == 'thumb') {
			    $size = 'square';
			}
			$url = "https://graph.facebook.com/" . $facebook_meta . "/picture?type=" . $size;
			return '<img src="' .$url . '" width="'.$width.'"/>';
			break;
		    default :
			return '<img src="'.$url.'" width="'.$width.'" />';
			break;
		}
	}
}
function update_jy_crois(){
	global $wpdb, $post;
	$table_jcrois = $wpdb->prefix . "jycrois";

	if (isset($_POST['id_campaign'])) $post = get_post($_POST['id_campaign']);
	$campaign = atcf_get_campaign( $post );
	$campaign_id = $campaign->ID;

	// Construction des urls utilisÃ©s dans les liens du fil d'actualitÃ©
	// url d'une campagne prÃ©cisÃ©e par son nom 
	$campaign_url = get_permalink($_POST['id_campaign']);
	$post_title = $post->post_title;
	$url_campaign = '<a href="'.$campaign_url.'">'.$post_title.'</a>';
	    
	//url d'un utilisateur prÃ©cis
	$user_id = wp_get_current_user()->ID;
	$user_display_name = wp_get_current_user()->display_name;
	$url_profile = '<a href="' . bp_core_get_userlink($user_id, false, true) . '">' . $user_display_name . '</a>';
	$user_avatar = get_user_avatar($user_id);

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
			'action'    => $user_avatar . $url_profile.' croit au projet '.$url_campaign
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
		// Inserer l'information dans la table du fil d'activitÃ©  de la BDD wp_bp_activity 
		bp_activity_delete(array (
			'user_id'   => $user_id,
			'component' => 'profile',
			'type'      => 'jycrois',
			'action'    => $user_avatar . $url_profile . ' croit au projet '.$url_campaign
		));
	}
	echo $wpdb->get_var( "SELECT count(campaign_id) FROM $table_jcrois WHERE campaign_id = $campaign_id" );
}
add_action( 'wp_ajax_update_jy_crois', 'update_jy_crois' );

function comment_blog_post(){
	global $wpdb, $post;
	// Construction des urls utilisÃ©s dans les liens du fil d'actualitÃ©
	// url d'une campagne prÃ©cisÃ©e par son nom 
	$post_title = $post->post_title;
	$url_blog = '<a href="'.get_permalink( $post->ID ).'">'.$post_title.'</a>';
	//url d'un utilisateur prÃ©cis
	$user_id                = wp_get_current_user()->ID;
	$user_display_name      = wp_get_current_user()->display_name;
	$url_profile = '<a href="' . bp_core_get_userlink($user_id, false, true) . '"> ' . $user_display_name . '</a>';
	$user_avatar=get_user_avatar($user_id);

	bp_activity_add(array (
		'component' => 'profile',
		'type'      => 'jycrois',
		'action'    => $user_avatar.' '.$url_profile.' a commentÃ© '.$url_blog
	    ));
}
add_action('comment_post','comment_blog_post');

function print_user_projects(){
    
	global $wpdb, $post, $user_projects;
	$is_same_user = (bp_displayed_user_id() == bp_loggedin_user_id());
	$str_believe = "J&apos;y crois";
	$str_vote = "J&apos;ai vot&eacute;";
	$str_investment = "J&apos;ai investi";
	$str_not_believe = "Je n&apos;y crois pas";
	$str_not_vote = "Je n&apos;ai pas vot&eacute;";
	$str_not_investment = "Je n&apos;ai pas investi";
	if (!$is_same_user) {
		$str_believe = "Y croit";
		$str_vote = "A vot&eacute;";
		$str_investment = "A investi";
		$str_not_believe = "N&apos;y croit pas";
		$str_not_vote = "N&apos;a pas vot&eacute;";
		$str_not_investment = "N&apos;a pas investi";
	}

	if(isset($_POST['user_id'])){
		$purchases = edd_get_users_purchases(bp_displayed_user_id(), -1, false, array('completed', 'pending', 'publish', 'failed', 'refunded'));
		if($purchases!=''){?>
			<h3> Afficher les projets : </h3>
			<form id="filter-projects">
  				<label><input type="checkbox" name="filter" value="jycrois">
  				<?php echo $str_believe; ?>
				</label>
		
   				<label><input type="checkbox" name="filter" value="voted">
  				<?php echo $str_vote; ?>
				</label>
		
   				<label><input type="checkbox" name="filter" value="invested" checked="checked">
  				<?php echo $str_investment; ?>
				</label>
			</form>
			<?php
			foreach ( $purchases as $post ) : setup_postdata( $post );
				$downloads = edd_get_payment_meta_downloads($post->ID); 
				$download_id = '';
				if (!is_array($downloads[0])){
				$download_id = $downloads[0];
				$post_camp = get_post($download_id);
				$campaign = atcf_get_campaign($post_camp);
			  	$payment_status = ypcf_get_updated_payment_status($post->ID);
				$contractid = ypcf_get_signsquidcontractid_from_invest($post->ID);
				$signsquid_infos = signsquid_get_contract_infos_complete($contractid);
				$signsquid_status = ypcf_get_signsquidstatus_from_infos($signsquid_infos);
				$payment_date=date_i18n( get_option('date_format'),strtotime(get_post_field('post_date', $post->ID)));

				$percent = min(100, $campaign->percent_minimum_completed(false));
				$width = 150 * $percent / 100;
				$width_min = 0;
				if ($percent >= 100 && $campaign->is_flexible()) {
				    $percent_min = $campaign->percent_minimum_to_total();
				    $width_min = 150 * $percent_min / 100;
				}
				$investors_group_id = get_post_meta($campaign->ID, 'campaign_investors_group', true);
				$group_exists = (is_numeric($investors_group_id) && ($investors_group_id > 0));
				$is_user_group_member = groups_is_user_member(bp_displayed_user_id(), $investors_group_id);
				$group_link='';
				if ($group_exists && $is_user_group_member){
					$group_obj = groups_get_group(array('group_id' => $investors_group_id));
					$group_link = bp_get_group_permalink($group_obj);
				}
				
				//Infos relatives au projet
				$user_projects[$campaign->ID]['ID']=$campaign->ID;
				$user_projects[$campaign->ID]['title']=$post_camp->post_title;
				$user_projects[$campaign->ID]['width_min']=$width_min;
				$user_projects[$campaign->ID]['width']=$width;
				$user_projects[$campaign->ID]['days_remaining']=$campaign->days_remaining();
				$user_projects[$campaign->ID]['percent_minimum_completed']=$campaign->percent_minimum_completed();
				$user_projects[$campaign->ID]['minimum_goal']=$campaign->minimum_goal(true);
				//Infos relatives Ã  l'investissement de l'utilisateur.
				//$user_projects[$post->ID]['signsquid_infos']=$signsquid_infos;
				$user_projects[$campaign->ID]['payments'][$post->ID]['signsquid_status']=$signsquid_status;
				$user_projects[$campaign->ID]['payments'][$post->ID]['payment_date']=$payment_date;
				$user_projects[$campaign->ID]['payments'][$post->ID]['payment_amount']=edd_get_payment_amount( $post->ID );
				$user_projects[$campaign->ID]['payments'][$post->ID]['payment_status']=edd_get_payment_status( $post, true );
				//Lien vers le groupe d'investisseur
				$user_projects[$campaign->ID]['group_link']=$group_link;
				//On initialise has_voted et jy_crois
				$user_projects[$campaign->ID]['jy_crois']=0;
				$user_projects[$campaign->ID]['has_voted']=0;
				}
			endforeach;

			$table= $wpdb->prefix.'jycrois';
			$user_id=bp_displayed_user_id();
			$projects_jy_crois = $wpdb->get_results("SELECT campaign_id FROM $table WHERE user_id=$user_id");
			foreach ($projects_jy_crois as $project) {
				$user_projects[$project->campaign_id]['jy_crois']=1;
				$user_projects[$project->campaign_id]['ID']=$project->campaign_id;
			}
			$table=$wpdb->prefix.'ypcf_project_votes';
			$projects_votes = $wpdb->get_results("SELECT post_id FROM $table WHERE user_id=$user_id");
			foreach ($projects_votes as $project) {
				$user_projects[$project->post_id]['has_voted']=1;
			}
		 
			?>
			<div class="center">
			<?php
			foreach ($user_projects as $project) {
				$payments=$project['payments'];
				$data_jycrois=0;
				$data_voted=0;
				$data_invested=0;
				if($project['jy_crois']===1)$data_jycrois=1;
				if($project['has_voted']===1)$data_voted=1;
				if(count($project['payments'])>0)$data_invested=1;
				if($project['title']==''){//Si le projet n'est pas complet
					$post_camp = get_post($project['ID']);
					$campaign = atcf_get_campaign($post_camp);
					$percent = min(100, $campaign->percent_minimum_completed(false));
					$width = 150 * $percent / 100;
					$width_min = 0;
					if ($percent >= 100 && $campaign->is_flexible()) {
					    $percent_min = $campaign->percent_minimum_to_total();
					    $width_min = 150 * $percent_min / 100;
					}
					$investors_group_id = get_post_meta($campaign->ID, 'campaign_investors_group', true);
					$group_exists = (is_numeric($investors_group_id) && ($investors_group_id > 0));
					$is_user_group_member = groups_is_user_member(bp_displayed_user_id(), $investors_group_id);
					$group_link='';
					if ($group_exists && $is_user_group_member){
						$group_obj = groups_get_group(array('group_id' => $investors_group_id));
						$group_link = bp_get_group_permalink($group_obj);
					}
					//Infos relatives au projet
					$project['ID']=$campaign->ID;
					$project['title']=$post_camp->post_title;
					$project['width_min']=$width_min;
					$project['width']=$width;
					$project['days_remaining']=$campaign->days_remaining();
					$project['percent_minimum_completed']=$campaign->percent_minimum_completed();
					$project['minimum_goal']=$campaign->minimum_goal(true);
					//Lien vers le groupe d'investisseur
					$project['group_link']=$group_link;
				}
			?>
				<div id="<?php echo $project['ID'] ?>-project" class="history-projects" 
					data-value="<?php echo $project['ID'] ?>"
					data-jycrois="<?php echo $data_jycrois; ?>"
					data-voted="<?php echo $data_voted;?>"
					data-invested="<?php echo $data_invested;?>"
					>
					<a href="<?php echo get_permalink($project['ID']); ?>"><h3><?php echo $project['title']; ?></h3></a>
					<div class="project_preview_item_infos">
						<div class="project_preview_item_picto" style="width:45px">
							<?php if($project['jy_crois'] === 1) { ?>
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png" alt="<?php echo $str_believe; ?>" title="<?php echo $str_believe; ?>" />
							<?php } else { ?>
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good_gris.png" alt="<?php echo $str_not_believe; ?>" title="<?php echo $str_not_believe; ?>" />
								<span data-jycrois="0"></span>
							<?php } ?>
						</div>
						<div class="project_preview_item_picto" style="width:45px">
							<?php if($project['has_voted'] === 1) { ?>
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodvote.png" alt="<?php echo $str_vote; ?>" title="<?php echo $str_vote; ?>" />
								<span data-voted="1"></span>
							<?php } else { ?>
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodvote_gris.png" alt="<?php echo $str_not_vote; ?>" title="<?php echo $str_not_vote; ?>" />
							<?php } ?>
						</div>
						<div class="project_preview_item_picto" style="width:45px">
							<?php if(count($project['payments']) > 0) { ?>
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodmains.png" alt="<?php echo $str_investment; ?>" title="<?php echo $str_investment; ?>" />
								<span data-invested="1"></span>
							<?php } else { ?>
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodmains_gris.png" alt="<?php echo $str_not_investment; ?>" title="<?php echo $str_not_investment; ?>" />
							<?php } ?>
						</div>  
					</div>
					<div class="project_preview_item_progress">
						<div class="project_preview_item_progressbg">
				   			<div class="project_preview_item_progressbar" style="width:<?php echo $project['width'] ?>px">
								<?php if ($project['width_min'] > 0): ?>
								<div style="width: <?php echo $project->width_min; ?>px; height: 100%; border: 0px; border-right: 1px solid white;">&nbsp;</div>
								<?php else: ?>
								&nbsp;
								<?php endif; ?>
							</div>
						</div>
						<span class="project_preview_item_progressprint"><?php echo $project['percent_minimum_completed']; ?></span>
					</div>
					<div class="user-history-pictos">
						<div class="project_preview_item_pictos">
							<div class="project_preview_item_infos">
							    <div class="project_preview_item_picto" style="width:45px">
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/horloge.png" />
									<?php echo $project['days_remaining']; ?>
							    </div>
							    <div class="project_preview_item_picto">
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cible.png" />
									<?php echo $project['minimum_goal']; ?>
							    </div> 
							</div>
						    
							<?php if ($is_same_user): ?>
						    
							<?php
							    //Boutons pour Annuler l'investissement | Recevoir le code Ã  nouveau
							    //Visibles si la collecte est toujours en cours, si le paiement a bien Ã©tÃ© validÃ©, si le contrat n'est pas encore signÃ©
							    if ($campaign->is_active() && !$campaign->is_collected() && !$campaign->is_funded() && $campaign->vote() == "collecte" && $payment_status == "publish" && is_object($signsquid_infos) && $signsquid_infos->{'status'} != 'Agreed') :
							?>
							<div class="project_preview_item_cancel">
								<?php if ($signsquid_infos != '' && is_object($signsquid_infos)):
								    $page_my_investments = get_page_by_path('mes-investissements');
								?>
								<a href="<?php echo get_permalink($page_my_investments->ID); ?>?invest_id_resend=<?php echo $post_invest->ID; ?>"><?php _e("Renvoyer le code de confirmation", "yproject"); ?></a><br />
								<?php endif;
								$page_cancel_invest = get_page_by_path('annuler-un-investissement');
								?>
								<a href="<?php echo get_permalink($page_cancel_invest->ID); ?>?invest_id=<?php echo $post_invest->ID; ?>"><?php _e("Annuler mon investissement", "yproject"); ?></a>
							</div>
							<?php
							    endif;
							?>

							<?php
							    //Lien vers le groupe d'investisseurs du projet
							    //Visible si le groupe existe et que l'utilisateur est bien dans ce groupe
							    $investors_group_id = get_post_meta($campaign->ID, 'campaign_investors_group', true);
							    $group_exists = (is_numeric($investors_group_id) && ($investors_group_id > 0));
							    $is_user_group_member = groups_is_user_member(bp_displayed_user_id(), $investors_group_id);
							    if ($group_exists && $is_user_group_member):
								$group_obj = groups_get_group(array('group_id' => $investors_group_id));
								$group_link = bp_get_group_permalink($group_obj);
							?>
							<div class="project_preview_item_infos" style="width: 120px;">
							    <a href="<?php echo $group_link; ?>">Acc&eacute;der au groupe priv&eacute;</a>
							</div>
							<?php
							    endif;
							?>
						    
							<?php endif; ?>
							<div style="clear: both"></div>
						</div>
					</div>
					
					<?php if(count($payments) > 0 && $is_same_user) {?>
						<div class="show-payments"  data-value="<?php echo $project['ID'];?>">
							D&eacute;tails des investissements
						</div>
						
						<div class="user-history-payments">
							<table class="user-history-payments-list">
								<?php foreach ($payments as $payment) { 
									?>
									<tr class="user-payments-list-item">
										<td class="user-payment-item user-payment-date">
											<?php echo $payment['payment_date']; ?>
										</td>
										<td class="user-payment-item user-payment-amount">
											<?php echo $payment['payment_amount'].' &euro;'; ?>
										</td>
										<td class="user-payment-item user-payment-status">
											<?php echo $payment['payment_status']; ?>
										</td>
										<td class="user-payment-item user-payment-signsquid-status">
											<?php echo $payment['signsquid_status']; ?>
										</td>
									</tr>
								<?php } ?>
							</table>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php
			}
		
		} else {
			echo "Aucun projet.";

		}
	}
	exit();
}
add_action( 'wp_ajax_print_user_projects', 'print_user_projects' );
add_action( 'wp_ajax_nopriv_print_user_projects', 'print_user_projects' );
?>
