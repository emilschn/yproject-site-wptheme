<?php 
// Transforme un tag en meta keyword
function csv_tags(){
		$posttags = get_the_tags();
	if ($posttags) {
		foreach((array)$posttags as $tag) {
			$csv_tags .= $tag->name . ',';
		}
		echo '<meta name="keywords" content="'.$csv_tags.'" />';
	}
}
//possibilité de mettre tag aux pages
function tags_support_all(){
	register_taxonomy_for_object_type('post_tag', 'page');
}
add_action('init', 'tags_support_all');

//assure que les tags sont inclus dans les requetes
function tags_support_query($wp_query){
	if($wp_query->get('tag')) $wp_query->set('post_type', 'any');
}
add_action('pre_get_posts', 'tags_support_query');

//Chargement de la css de buddypress
if ( !function_exists( 'bp_dtheme_enqueue_styles' ) ) :
	function bp_dtheme_enqueue_styles() {}
endif;

//Enlever les "magic quotes"
$_POST      = array_map( 'stripslashes_deep', $_POST );
$_GET       = array_map( 'stripslashes_deep', $_GET );
$_COOKIE    = array_map( 'stripslashes_deep', $_COOKIE );
$_REQUEST   = array_map( 'stripslashes_deep', $_REQUEST );

//Définition de la largeur de l'affichage
if ( ! isset( $content_width ) ) $content_width = 960;

//Définition du domaine pour les traductions
function yproject_setup() {
	load_child_theme_textdomain( 'yproject', get_stylesheet_directory() . '/languages' );
	remove_action( 'bp_member_header_actions',    'bp_send_public_message_button',  20 );
}
add_action( 'after_setup_theme', 'yproject_setup', 15 );

add_action('wp_insert_comment', array('NotificationsEmails', 'new_comment'), 99 ,2);
add_action('bbp_new_topic', array('NotificationsEmails', 'new_topic'), 99 ,2);

//Sécurité
remove_action("wp_head", "wp_generator");
add_filter('login_errors',create_function('$a', "return null;"));

function yproject_enqueue_script(){
	global $can_modify, $is_campaign, $is_campaign_page;
	$campaign = atcf_get_current_campaign();
	$can_modify = ($is_campaign) && ($campaign->current_user_can_edit());
	
	if ( !is_admin() ) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', (dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery.min.js'), false);
		wp_enqueue_script('jquery');
	}
	
	wp_enqueue_script( 'wdg-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/common.js', array('jquery', 'jquery-ui-dialog'), '15.07.02');
	if ($is_campaign_page && $can_modify) { wp_enqueue_script( 'wdg-project-editor', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-project-editor.js', array('jquery', 'jquery-ui-dialog'), '15.07.02'); }
	wp_enqueue_script( 'jquery-form', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery.form.js', array('jquery'));
	wp_enqueue_script( 'jquery-ui-wdg', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery-ui.min.js', array('jquery'));
	wp_enqueue_script( 'chart-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/chart.new.js', array('wdg-script'), true, true);
//	wp_enqueue_script( 'wdg-ux-helper', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-ux-helper.js', array('wdg-script'));
	
	wp_enqueue_script('qtip', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery.qtip.js', array('jquery'));
	wp_enqueue_style('qtip', dirname( get_bloginfo('stylesheet_url')).'/_inc/css/jquery.qtip.min.css', null, false, 'all');
	
	wp_localize_script( 'wdg-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
//	if ($is_campaign) { wp_localize_script( 'wdg-script2', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) ); }
}
add_action( 'wp_enqueue_scripts', 'yproject_enqueue_script' );

//Cache serveur
function varnish_safe_http_headers() {
	header('X-UA-Compatible: IE=edge,chrome=1');
	session_cache_limiter('');
	header('Cache-Control: public, s-maxage=120');
	header('Pragma: public');
	if( !session_id() ) {
		session_start();
	}
}
add_action( 'send_headers', 'varnish_safe_http_headers' );



/** GESTION DU LOGIN **/
/**
 * Redirige les erreurs de login
 * @param type $username
 */
function yproject_front_end_login_fail($username){
        $page = $_POST['redirect-page-error']; 
        if($_POST['redirect-page-investir'] == "true"){
            wp_redirect($page.'/?login=failed&redirect=invest#connexion');
        } else if($_POST['redirect-page-investir'] == "forum") {
             wp_redirect($page.'/?login=failed&redirect=forum#connexion');
        } else {
            wp_redirect($page.'/?login=failed#connexion');
        }
	exit;
}
add_action('wp_login_failed', 'yproject_front_end_login_fail'); 


function yproject_redirect_login() {
//	$current_user = wp_get_current_user();
//	NotificationsSlack::send_to_dev('Connexion de ' . $current_user->first_name . ' ' . $current_user->last_name . ' (' . $current_user->ID . ')');
        $page_invest = get_page_by_path('investir');
        $page_id = $_POST['redirect-page'];
        $page_type = $_POST['type-page']; 
        $page_redirection = $_POST['redirect-page-investir'];
        
        if (isset($_GET['login'])) {
            $page = get_permalink($page_invest->ID).'?campaign_id='.$page_id.'&invest_start=1';
	    wp_redirect($page);
	    
        } else {
            if (isset($page_id) && isset($page_type)) {
                if ($page_type == "download") {
                    if( isset($page_redirection) && $page_redirection == "true") {
                        $page = get_permalink($page_invest->ID).'?campaign_id='.$page_id.'&invest_start=1';
                        wp_redirect($page);  
                        
                    } else if (isset($page_redirection) && $page_redirection == "forum") {
                        $forum = get_page_by_path('forum');
                        $page = get_permalink($forum->ID).'?campaign_id='.$page_id;   
                        wp_redirect($page);
                        
                    } else {
                        $page = get_page($page_id);
                        wp_redirect(get_permalink($page).'#description_du_projet');
                    }
                } else {
                    $page = get_page($page_id);
                    wp_redirect(get_permalink($page)); 
                }
		
            } else {
		wp_redirect(home_url());
	    }
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

// Rediriger une personne qui n'a rien rempli pour s'identifier 
function _catch_empty_user( $username, $pwd ) {
	if (empty($username)||empty($pwd)) {
		$page = $_POST['redirect-page-error']; 
                if($_POST['redirect-page-investir'] == "true"){
                    wp_redirect($page.'/?login=failed&redirect=invest#connexion');
                } else {
                    wp_redirect($page.'/?login=failed#connexion');
                }
	}
}
add_action( 'wp_authenticate', '_catch_empty_user', 1, 2 );

function catch_register_page_loggedin_users() {
	return home_url() . '?alreadyloggedin=1';
}
add_filter( 'bp_loggedin_register_page_redirect_to', 'catch_register_page_loggedin_users');

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
		$role_subscriber->add_cap( 'edit_published_posts' );
		$role_subscriber->add_cap( 'edit_others_posts' );
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

function yproject_page_template( $template ) {
	locate_template( array("requests/projects.php"), true );
	global $post;
	$campaign = atcf_get_campaign( $post );
	$campaign_id = $post->ID;
	if (!empty($campaign->ID) && is_object( $campaign ) && ($campaign->campaign_status() == 'preparing') && !$campaign->current_user_can_edit()) {
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

/** BACK-OFFICE USERS **/
function yproject_user_contact_methods( $user_contact ) {
	//Affichage du numéro de téléphone dans la fiche utilisateur
	$user_contact['user_mobile_phone'] = __('Telephone');
	return $user_contact;
}
add_filter( 'user_contactmethods', 'yproject_user_contact_methods' );
/** FIN BACK-OFFICE USERS **/



/**
 * BIBLIOTHEQUE POUR VERIFICATIONS
 */
function yproject_check_user_can_see_project_page() {
	//Si l'utilisateur n'est pas connecté, on redirige sur la page de connexion
	if (!is_user_logged_in()) {
		$page_connexion = get_page_by_path('connexion');
		wp_redirect(get_permalink($page_connexion->ID));
		exit();
	}
	//Si la campagne n'est pas définie, on retourne à l'accueil
	if (!isset($_GET['campaign_id'])) {
		wp_redirect(site_url());
		exit();
	}
}


/**
 * Filtres pour modification de contenu
 */
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

//Suppression de code supplémentaire généré par edd
remove_filter( 'the_content', 'edd_microdata_wrapper', 10 );

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
	$user_avatar = UIHelpers::get_user_avatar($user_id);

	bp_activity_add(array (
		'component' => 'profile',
		'type'      => 'jycrois',
		'action'    => $user_avatar.' '.$url_profile.' a commentÃƒÂ© '.$url_blog
	    ));
}
add_action('comment_post','comment_blog_post');


/**
 * Gestion ajax
 */
/**
 * Permet d'envoyer la position de l'image de couverture d'un projet.
 */
function set_cover_position(){
	if (isset($_POST['top'])) {
		update_post_meta($_POST['id_campaign'],'campaign_cover_position', $_POST['top']);
		do_action('wdg_delete_cache', array('project-header-image-' . $_POST['id_campaign'], 'project-content-summary-' . $_POST['id_campaign']));
	}
}
add_action( 'wp_ajax_setCoverPosition', 'set_cover_position' );

/**
 * Permet d'envoyer la position de l'image de couverture d'un projet.
 */
function set_cursor_position(){
	if (isset($_POST['top'])) {
		update_post_meta($_POST['id_campaign'],'campaign_cursor_top_position', $_POST['top']);
		update_post_meta($_POST['id_campaign'],'campaign_cursor_left_position', $_POST['left']);
		do_action('wdg_delete_cache', array('project-content-about-' . $_POST['id_campaign']));
	}
}
add_action( 'wp_ajax_setCursorPosition', 'set_cursor_position' );

	
function update_jy_crois(){
	global $post;

	if (isset($_POST['id_campaign'])) {
	    $post = get_post($_POST['id_campaign']);
	    do_action('wdg_delete_cache', array( 'project-header-right-'.$_POST['id_campaign'] ));
	    $campaign = atcf_get_campaign( $post );
	    return $campaign->manage_jycrois();
	}
}
add_action( 'wp_ajax_update_jy_crois', 'update_jy_crois' );


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
		$payment_status = array("publish", "completed");
//		if ($is_same_user) $payment_status = array("completed", "pending", "publish", "failed", "refunded");
		$purchases = edd_get_users_purchases(bp_displayed_user_id(), -1, false, $payment_status);
		$table = $wpdb->prefix.'jycrois';
		$user_id = bp_displayed_user_id();
		$projects_jy_crois = $wpdb->get_results("SELECT campaign_id FROM $table WHERE user_id=$user_id");
		$table = $wpdb->prefix.'ypcf_project_votes';
		$projects_votes = $wpdb->get_results("SELECT post_id FROM $table WHERE user_id=$user_id");
		$check_investment = true;
		$check_vote = false;
		$check_believe = false;
		if (empty($purchases) && count($projects_votes) > 0) { $check_investment = false; $check_vote = true; }
		if (empty($purchases) && count($projects_votes) == 0 && count($projects_jy_crois) == 0) { $check_investment = false; $check_believe = true; }
		
		if ($purchases != '' || count($projects_jy_crois) > 0 || count($projects_votes) > 0) { ?>
			<h3> Afficher les projets : </h3>
			<form id="filter-projects">
  				<label>
				<input type="checkbox" name="filter" value="jycrois" <?php if ($check_believe) { ?>checked="checked"<?php } ?>>
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png" alt="<?php echo $str_believe; ?>" title="<?php echo $str_believe; ?>" />
  				<?php echo $str_believe; ?>
				</label>
		
   				<label style="margin-left: 50px;">
				<input type="checkbox" name="filter" value="voted" <?php if ($check_vote) { ?>checked="checked"<?php } ?>>
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodvote.png" alt="<?php echo $str_vote; ?>" title="<?php echo $str_vote; ?>" />
  				<?php echo $str_vote; ?>
				</label>
		
   				<label style="margin-left: 50px;">
				<input type="checkbox" name="filter" value="invested" <?php if ($check_investment) { ?>checked="checked"<?php } ?>>
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodmains.png" alt="<?php echo $str_investment; ?>" title="<?php echo $str_investment; ?>" />
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
				$signsquid_status = ypcf_get_signsquidstatus_from_infos($signsquid_infos, edd_get_payment_amount( $post->ID ));
				$payment_date = date_i18n( get_option('date_format'),strtotime(get_post_field('post_date', $post->ID)));

				$investors_group_id = get_post_meta($campaign->ID, 'campaign_investors_group', true);
				$group_exists = (is_numeric($investors_group_id) && ($investors_group_id > 0));
				$is_user_group_member = groups_is_user_member(bp_displayed_user_id(), $investors_group_id);
				$group_link = '';
				if ($group_exists && $is_user_group_member){
					$group_obj = groups_get_group(array('group_id' => $investors_group_id));
					$group_link = bp_get_group_permalink($group_obj);
				}
				
				//Infos relatives au projet
				$user_projects[$campaign->ID]['ID'] = $campaign->ID;
				//Infos relatives à l'investissement de l'utilisateur.
				$user_projects[$campaign->ID]['payments'][$post->ID]['signsquid_infos'] = $signsquid_infos;
				$user_projects[$campaign->ID]['payments'][$post->ID]['signsquid_status'] = $signsquid_status;
				$user_projects[$campaign->ID]['payments'][$post->ID]['payment_date'] = $payment_date;
				$user_projects[$campaign->ID]['payments'][$post->ID]['payment_amount'] = edd_get_payment_amount( $post->ID );
				$user_projects[$campaign->ID]['payments'][$post->ID]['payment_status'] = edd_get_payment_status( $post, true );
				//Lien vers le groupe d'investisseur
				$user_projects[$campaign->ID]['group_link']=$group_link;
				}
			endforeach;

			foreach ($projects_jy_crois as $project) {
				$user_projects[$project->campaign_id]['jy_crois'] = 1;
				$user_projects[$project->campaign_id]['ID'] = $project->campaign_id;
			}
			foreach ($projects_votes as $project) {
				$user_projects[$project->post_id]['has_voted'] = 1;
			}
		 
			?>
			<div class="center">
			<?php
			foreach ($user_projects as $project) {
				$payments = $project['payments'];
				$data_jycrois = 0;
				$data_voted = 0;
				$data_invested = 0;
				if (isset($project['jy_crois']) && $project['jy_crois'] === 1) $data_jycrois = 1;
				if (isset($project['has_voted']) && $project['has_voted'] === 1) $data_voted = 1;
				if (count($project['payments']) > 0) $data_invested = 1;
                                
				$post_camp = get_post($project['ID']);
				$campaign = atcf_get_campaign($post_camp);
				$percent = min(100, $campaign->percent_minimum_completed(false));
				$width = 150 * $percent / 100;
				$width_min = 0;
				if ($percent >= 100 && $campaign->is_flexible()) {
				    $percent_min = $campaign->percent_minimum_to_total();
				    $width_min = 150 * $percent_min / 100;
				}
				//Infos relatives au projet
				$project['title'] = $post_camp->post_title;
				$project['width_min'] = $width_min;
				$project['width'] = $width;
				$project['days_remaining'] = $campaign->time_remaining_str();
				$project['percent_minimum_completed'] = $campaign->percent_minimum_completed();
				$project['minimum_goal'] = $campaign->minimum_goal(true);
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
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/horloge.png" alt="logo horloge" />
									<?php echo $project['days_remaining']; ?>
							    </div>
							    <div class="project_preview_item_picto">
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cible.png" alt="logo cible"/>
									<?php echo $project['minimum_goal']; ?>
							    </div> 
							</div>
						    
							<?php 
							if ($is_same_user) {
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
							}
							?>
							<div style="clear: both"></div>
						</div>
					</div>
					
					<?php if(count($payments) > 0 && $is_same_user) {?>
						<div class="show-payments"  data-value="<?php echo $project['ID'];?>">
							D&eacute;tails des investissements
						</div>
						
						<div class="user-history-payments">
							<table class="user-history-payments-list">
							    
								<?php foreach ($payments as $payment_id => $payment) { 
                                                                    $reward = get_post_meta($payment_id,'_edd_payment_reward',true);
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

									<?php
									//Boutons pour Annuler l'investissement | Recevoir le code à nouveau
									//Visibles si la collecte est toujours en cours, si le paiement a bien été validé, si le contrat n'est pas encore signé
									if ($campaign->is_active() && !$campaign->is_collected() && !$campaign->is_funded() && $campaign->vote() == "collecte" && $payment_status == "publish" && is_object($payment['signsquid_infos']) && $payment['signsquid_status'] != 'Agreed') :
									?>
									<td style="width: 220px;">
										<?php if ($payment['signsquid_infos'] != '' && is_object($payment['signsquid_infos'])): ?>
											<?php $page_my_investments = get_page_by_path('mes-investissements'); ?>
										<a href="<?php echo get_permalink($page_my_investments->ID); ?>?invest_id_resend=<?php echo $payment_id; ?>"><?php _e("Renvoyer le code de confirmation", "yproject"); ?></a><br />
										<?php endif; ?>
										
										<?php $page_cancel_invest = get_page_by_path('annuler-un-investissement'); ?>
										<a href="<?php echo get_permalink($page_cancel_invest->ID); ?>?invest_id=<?php echo $payment_id; ?>"><?php _e("Annuler mon investissement", "yproject"); ?></a>
									</td>
									<?php endif; ?>

								</tr>
                                                                <?php if ($reward != null) { ?>
                                                                    <tr>
                                                                        <td colspan="4" class="user-payment-item user-payment-reward">
                                                                            Contrepartie choisie : Palier de <?php echo $reward['amount'].' € - '.$reward['name']?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } 
                                                                } ?>
									
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

/**
 * Adds a member to a project team
 * echo the ID of the member if it has been successfully added, echo FALSE if not
 */
function add_team_member(){
    $campaign_id = intval($_POST['id_campaign']);
    $user_by_login = get_user_by('login', $_POST['new_team_member']);
    $user_by_mail = get_user_by('email', $_POST['new_team_member']);
    if ($user_by_login === FALSE && $user_by_mail === FALSE) {
            $buffer = "FALSE";
    } else {
            //Récupération du bon id wordpress
            $user_wp_id = '';
            if ($user_by_login !== FALSE) $user_wp_id = $user_by_login->ID;
            else if ($user_by_mail !== FALSE) $user_wp_id = $user_by_mail->ID;
            //Récupération des infos existantes sur l'API
            $user_api_id = BoppLibHelpers::get_api_user_id($user_wp_id);
            $project_api_id = BoppLibHelpers::get_api_project_id($campaign_id);
            BoppLibHelpers::check_create_role(BoppLibHelpers::$project_team_member_role['slug'], BoppLibHelpers::$project_team_member_role['title']);
            //Ajout à l'API
            BoppLib::link_user_to_project($project_api_id, $user_api_id, BoppLibHelpers::$project_team_member_role['slug']);
            
            do_action('wdg_delete_cache', array(
                    'users/' . $user_api_id . '/roles/' . BoppLibHelpers::$project_team_member_role['slug'] . '/projects',
                    'projects/' . $project_api_id . '/roles/' . BoppLibHelpers::$project_team_member_role['slug'] . '/members'
            ));
            
            $user = get_userdata($user_wp_id);
            $data_new_member['id']=$user_wp_id;
            $data_new_member['firstName']=$user->first_name;
            $data_new_member['lastName']=$user->last_name;
            $data_new_member['userLink']=bp_core_get_userlink($user_wp_id);
            $buffer = json_encode($data_new_member);
    }
    echo $buffer;
    exit();
}
add_action( 'wp_ajax_add_team_member', 'add_team_member' );
add_action( 'wp_ajax_nopriv_add_team_member', 'add_team_member' );

/**
 * Removes a member from a project team
 */
function remove_team_member(){
    //Récupération des infos existantes sur l'API
    $user_api_id = BoppLibHelpers::get_api_user_id($_POST['user_to_remove']);
    $project_api_id = BoppLibHelpers::get_api_project_id($_POST['id_campaign']);
    //Supprimer dans l'API
    BoppLib::unlink_user_from_project($project_api_id, $user_api_id, BoppLibHelpers::$project_team_member_role['slug']);
    do_action('wdg_delete_cache', array(
            'users/' . $user_api_id . '/roles/' . BoppLibHelpers::$project_team_member_role['slug'] . '/projects',
            'projects/' . $project_api_id . '/roles/' . BoppLibHelpers::$project_team_member_role['slug'] . '/members'
    ));
    echo "TRUE";
    exit();
}
add_action( 'wp_ajax_remove_team_member', 'remove_team_member' );
add_action( 'wp_ajax_nopriv_remove_team_member', 'remove_team_member' );

function yproject_get_current_projects() {
	$nb = isset($_POST['nb']) ? $_POST['nb'] : -1;
	$query_options = array(
		'showposts' => $nb,
		'post_type' => 'download',
		'post_status' => 'publish',
		'meta_query' => array (

			array (
				'key' => 'campaign_vote',
				'value' => 'collecte'
				),
			array (
				'key' => 'campaign_end_date',
				'compare' => '>',
				'value' => date('Y-m-d H:i:s')
			)
		),
		'orderby' => 'post_date',
		'order' => 'asc'
	);
	$posts = query_posts( $query_options );
	foreach ($posts as $post_campaign) {
		// style="margin-left: '+margin_left+'px"
		echo '<div class="ux-help-container-link"><a class="button" href="'.get_permalink($post_campaign->ID).'">'.  get_the_title($post_campaign->ID).'</a></div>';
	}
	exit();
}
add_action('wp_ajax_get_current_projects', 'yproject_get_current_projects');
add_action('wp_ajax_nopriv_get_current_projects', 'yproject_get_current_projects');


function yproject_save_edit_project() {
	switch ($_POST['property']) {
		case "title":
			wp_update_post(array(
				'ID' => $_POST['id_campaign'],
				'post_title' => $_POST['value']
			));
			break;
		case "description":
			wp_update_post(array(
				'ID' => $_POST['id_campaign'],
				'post_content' => $_POST['value']
			));
			break;
		default: 
			update_post_meta($_POST['id_campaign'], 'campaign_' . $_POST['property'], $_POST['value']);
			break;
	}
	do_action('wdg_delete_cache', array( 
		'project-header-menu-' . $_POST['id_campaign'], 
		'project-content-summary-' . $_POST['id_campaign'],
		'project-content-about-' . $_POST['id_campaign'],
		'project-content-bottom-' . $_POST['id_campaign'],
		'projects-current',
		'projects-others'
	));
	echo $_POST['property'];
	exit();
}
add_action('wp_ajax_save_edit_project', 'yproject_save_edit_project');
add_action('wp_ajax_nopriv_save_edit_project', 'yproject_save_edit_project');

function get_investors_list() {
    
        locate_template( array("requests/investments.php"), true );
        $investments_list = (json_decode($_POST['data'],true));
        $campaign = atcf_get_campaign($_POST['id_campaign']); 
        
	$is_campaign_over = ($campaign->campaign_status() == 'funded' || $campaign->campaign_status() == 'archive' || $campaign->campaign_status() == 'preparing');
        
        $classcolonnes = array('coluname',
                            'collname',
                            'colfname',
                            'colbrthday',
                            'colbrthplace',
                            'colnat',
                            'colcity',
                            'coladdr',
                            'colpcode',
                            'colcountry',
                            'colemail',
                            'colphone',
                            'colinvesmontant',
                            'colinvdate',
                            'colinvpaytype',
                            'colinvpaystate',
                            'colinvsign');
        if ($is_campaign_over) { $classcolonnes[]='colinvstate'; }
        if ($campaign->funding_type()=='fundingdonation') {
            $classcolonnes[]='colrewardprice';
            $classcolonnes[]='colrewardtext';
        }
        
        $titrecolonnes = array('Utilisateur',
                            'Nom',
                            'Prénom',
                            'Date de naissance',
                            'Ville de naissance',
                            'Nationalité',
                            'Ville',
                            'Adresse',
                            'Code postal',
                            'Pays',
                            'Mail',
                            'Téléphone',
                            'Montant investi',
                            'Date',
                            'Type de paiement',
                            'Etat du paiement',
                            'Signature');
        if ($is_campaign_over) { $titrecolonnes[]='Investissement'; }
        if ($campaign->funding_type()=='fundingdonation') {
            $titrecolonnes[]='Palier de contrepartie choisi';
            $titrecolonnes[]='Description de la contrepartie';
        }
        
        $selectiondefaut = array(true,
                            true,
                            true,
                            false,
                            false,
                            false,
                            true,
                            false,
                            false,
                            false,
                            true,
                            true,
                            true,
                            false,
                            false,
                            false,
                            false);
        if ($is_campaign_over) { $selectiondefaut[]=true; }
        if ($campaign->funding_type()=='fundingdonation') {
            $selectiondefaut[]=true;
            $selectiondefaut[]=false;
        }
        $colonnes = array_combine($classcolonnes, $titrecolonnes);
        $displaycolonnes = array_combine($classcolonnes,$selectiondefaut);
    ?>

<div id="display-options-col-div">
    <form>
    <fieldset id="display-options-col-menu">
        <ul id="display-options-col-list">
            <li id="cb-li-collall">
                <input id="cbcolall" class="check-users-columns" type="checkbox" value="all">
                </input><label for="cbcolall">Tout sélectionner</label>
            </li>
        <?php foreach($colonnes as $class=>$titre){
            //Checkbox d'affichage des colonnes : voir dans common.js
                echo '<li><input type="checkbox" ';
                if (($displaycolonnes[$class]) == true){echo 'checked ';}
                echo 'class="check-users-columns" '
                    .'value="'.$class.'" '
                    .'id="cb'.$class.'">'
                    .' <label for="cb'.$class.'">'.$titre.'</label></li> ';}
        ?>
        </ul>
    </fieldset>
    </form>
</div>

<br/>

<div class="tablescroll" >
<table class="wp-list-table" cellspacing="0" id="investors-table">
    
    <thead style="background-color: #CCC;">
    <tr>
        <?php foreach($colonnes as $class=>$titre){
            //Ecriture des nom des colonnes en haut
            echo '<td class="'.$class.'" ';
            if (($displaycolonnes[$class]) == false){echo 'hidden=""';}
            echo '>'.$titre.'</td>';}?>
    </tr>
    </thead>

    <tbody id="the-list">
	<?php
	$i = -1;
        require_once("country_list.php");
        global $country_list;
        
	foreach ( $investments_list['payments_data'] as $item ) {
		$i++;
		$bgcolor = ($i % 2 == 0) ? "#FFF" : "#EEE";

		$post_invest = get_post($item['ID']);
		$mangopay_id = edd_get_payment_key($item['ID']);
		$payment_type = 'Carte';
		$payment_state = edd_get_payment_status( $post_invest, true );
		if (strpos($mangopay_id, 'wire_') !== FALSE) {
			$payment_type = 'Virement';
		}
		$investment_state = 'Validé';
		if ($campaign->campaign_status() == 'archive' || $campaign->campaign_status() == 'preparing') {
		    $investment_state = 'Annulé';
			
		    $refund_id = get_post_meta($item['ID'], 'refund_id', TRUE);
		    if (isset($refund_id) && !empty($refund_id)) {
			$refund_obj = ypcf_mangopay_get_refund_by_id($refund_id);
			$investment_state = 'Remboursement en cours';
			if ($refund_obj->IsCompleted) {
			    if ($refund_obj->IsSucceeded) {
				$investment_state = 'Remboursé';
			    } else {
				$investment_state = 'Remboursement échoué';
			    }
			}
			
		    } else {
			$refund_id = get_post_meta($item['ID'], 'refund_wire_id', TRUE);
			if (isset($refund_id) && !empty($refund_id)) {
			    $investment_state = 'Remboursé';
			}
		    }
		}
		
		$user_data = get_userdata($item['user']);
                
                //Liste des données à afficher pour la ligne traitée
                if(YPOrganisation::is_user_organisation($item['user'])){
			$orga = new YPOrganisation($item['user']);
			$orga_user = get_user_by('email', $item['email']);
			$datacolonnes = array(
				'ORG - ' . $orga->get_name(),
				$orga_user->last_name,
				$orga_user->first_name,
				'',
				'',
				$orga->get_nationality(),
				$orga->get_rcs() .' ('.$orga->get_idnumber().')',
				$orga->get_address(),
				$orga->get_postal_code(),
				ucfirst(strtolower($country_list[$orga->get_nationality()])),
				$item['email'],
				$orga_user->user_mobile_phone,
				$item['amount'].'€',
				date_i18n( 'd/m/Y', strtotime( get_post_field( 'post_date', $item['ID'] ) ) ),
				$payment_type,
				$payment_state,
				$item['signsquid_status_text']
			);
                } else {
			$datacolonnes = array(
				bp_core_get_userlink($item['user']),
				$user_data->last_name,
				$user_data->first_name,
				$user_data->user_birthday_day.'/'.$user_data->user_birthday_month.'/'.$user_data->user_birthday_year,
				$user_data->user_birthplace,
				ucfirst(strtolower($country_list[$user_data->user_nationality])),
				$user_data->user_city,
				$user_data->user_address,
				$user_data->user_postal_code,
				$user_data->user_country,
				$user_data->user_email,
				$user_data->user_mobile_phone,
				$item['amount'].'€',
				date_i18n( /*get_option('date_format')*/ 'd/m/Y', strtotime( get_post_field( 'post_date', $item['ID'] ) ) ),
				$payment_type,
				$payment_state,
				$item['signsquid_status_text']
			);
                }
                
                if ($is_campaign_over) { $datacolonnes[]=$investment_state; }
                
                if ($campaign->funding_type()=='fundingdonation') {
                    $datacolonnes[]=$item['products'][0]['item_number']['options']['reward']['amount'];
                    $datacolonnes[]=$item['products'][0]['item_number']['options']['reward']['name'];
                }
                
                $affichedonnees = array_combine($classcolonnes, $datacolonnes);
                ?>
                
                <tr style="background-color: <?php echo $bgcolor; ?>">
                <?php
                    //Ecriture de la ligne
                    foreach($affichedonnees as $class=>$data){
                        /*echo '<td class="'.$class.'">'.$data.'</td>';*/
                        echo '<td class="'.$class.'" ';
                        if (($displaycolonnes[$class]) == false){echo 'hidden=""';}
                        echo '>'.$data.'</td>';
                    }
		?>
                </tr>
                <?php
//	    }
	}
	?>
    </tbody>
    
    <tfoot style="background-color: #CCC;">
    <tr>
        <?php foreach($colonnes as $class=>$titre){
            //Ecriture des nom des colonnes en bas
            echo '<td class="'.$class.'" ';
            if (($displaycolonnes[$class]) == false){echo 'hidden=""';}
            echo '>'.$titre.'</td>';}?>        
    </tr>
    </tfoot>
</table>
</div>
    
    
    <?php exit();
}
add_action('wp_ajax_get_investors_list', 'get_investors_list');
add_action('wp_ajax_nopriv_get_investors_list', 'get_investors_list');

function get_invests_graph(){
    $campaign = atcf_get_campaign($_POST['id_campaign']);
    
    //Recuperation donnees d'investissement
    //locate_template( array("requests/investments.php"), true );
    $data = (json_decode($_POST['data'],true));
    $investments_list = $data['payments_data'];

    /****Liste des montants cumulés triés par leur date****/

    $datesinvest = array();
    $amountinvest = array();

    foreach ( $investments_list as $item ) {
        $datesinvest[]=$item['date'];
        $amountinvest[]=$item['amount'];
    }
    $cumulamount = array_combine($datesinvest, $amountinvest);
    $allamount = array_combine($datesinvest, $amountinvest);

    sort($datesinvest);

    for($i=1; $i<count($datesinvest); $i++){
        $cumulamount[$datesinvest[$i]]=$cumulamount[$datesinvest[$i-1]]+$cumulamount[$datesinvest[$i]];
    }
    ksort($cumulamount);
    ksort($allamount);
    /******************************************************/
    //Date de début de collecte (1er investissement si l'information n'est pas enregistrée)
    $date_collecte_start = $campaign->begin_collecte_date();
    if ($date_collecte_start==null){
        if(count($datesinvest)!=0){
            $date_collecte_start = $datesinvest[0];
        }
    }
    $date_collecte_end = $campaign->end_date();
    
    //Etiquettes de dates intermédiaires
    $number_campaign_days = date_diff(date_create($date_collecte_start), date_create($date_collecte_end), true);

    $datequarter = date_add(date_create($date_collecte_start), new DateInterval('P'.round($number_campaign_days->days/4).'D'));
    $datehalf = date_add(date_create($date_collecte_start), new DateInterval('P'.round($number_campaign_days->days/2).'D'));
    $datethreequarter = date_add(date_create($date_collecte_start), new DateInterval('P'.round(($number_campaign_days->days/4)*3).'D'));
    
    $datequarterstr = date_format($datequarter,'"j/m/Y"');
    $datehalfstr = date_format($datehalf,'"j/m/Y"');
    $datethreequarterstr = date_format($datethreequarter,'"j/m/Y"');

    
    //Fonctions de formattage des dates pour JS
    function date_param($date) {
        return date_format(new DateTime($date),'"D M d Y H:i:s O"');
    }

    function date_abs($date) {
        return date_format(new DateTime($date),'"j/m/Y"');
    }
    ?>
    <script type="text/javascript">
    jQuery(document).ready( function($) {
            var ctxLine = $("#canvas-line-block").get(0).getContext("2d");
            var dataLine = {
                labels : [<?php echo date_abs($date_collecte_start); ?>,
                    <?php echo $datequarterstr; ?>,
                    <?php echo $datehalfstr; ?>,
                    <?php echo $datethreequarterstr; ?>,
                    <?php echo date_abs($date_collecte_end); ?>],
                xBegin : new Date(<?php echo date_param($date_collecte_start); ?>),
                xEnd : new Date(<?php echo date_param($date_collecte_end); ?>),
                datasets : [
                    {
                        fillColor : "rgba(204,204,204,0.25)",
                        strokeColor : "rgba(180,180,180,0.5)",
                        pointColor : "rgba(0,0,0,0)",
                        pointStrokeColor : "rgba(0,0,0,0)",
                        data : [0,<?php echo $campaign->minimum_goal(false);?>],
                        xPos : [new Date(<?php echo date_param($date_collecte_start); ?>),new Date(<?php echo date_param($date_collecte_end); ?>)],
                        title : "But progression"
                    },{
                        fillColor : "rgba(0,0,0,0)",
                        strokeColor : "rgba(140,140,140,0.5)",
                        pointColor : "rgba(0,0,0,0)",
                        pointStrokeColor : "rgba(0,0,0,0)",
                        data : [<?php echo $campaign->minimum_goal(false);?>,<?php echo $campaign->minimum_goal(false);?>],
                        xPos : [new Date(<?php echo date_param($date_collecte_start); ?>),new Date(<?php echo date_param($date_collecte_end); ?>)],
                        title : "But"
                    }
                    <?php 
                    if (count($datesinvest)!=0){?>
                    ,{
                        fillColor : "rgba(0,0,0,0)",
                        strokeColor : "rgba(0,0,0,0)",
                        pointColor : "rgba(0,0,0,0)",
                        pointStrokeColor : "rgba(0,0,0,0)",
                        data : [<?php foreach ($allamount as $date => $amount){echo $amount.',';}?> ],
                        xPos : [<?php foreach ($allamount as $date => $amount){$lastdate = $date; echo 'new Date('.date_param($date).'),';}?>],
                        title : "inv"
                    },{
                        fillColor : "rgba(255,73,76,0.25)",
                        strokeColor : "rgba(255,73,76,0.5)",
                        pointColor : "rgba(0,0,0,0)",
                        pointStrokeColor : "rgba(0,0,0,0)",
                        data : [<?php echo $campaign->current_amount(false);?>,<?php echo $campaign->current_amount(false);?>],
                        xPos : [new Date(<?php echo date_param($lastdate); ?>),new Date(<?php echo date_format(min([new DateTime($date_collecte_end),new DateTime(null)]),'"D M d Y H:i:s O"'); ?>)],
                        title : "linetoday"
                    },{
                        fillColor : "rgba(255,73,76,0.5)",
                        strokeColor : "rgba(255,73,76,1)",
                        pointColor : "rgba(255,73,76,1)",
                        pointStrokeColor : "rgba(199,46,49,1)",
                        data : [<?php foreach ($cumulamount as $date => $amount){echo $amount.',';}?> ],
                        xPos : [<?php foreach ($cumulamount as $date => $amount){echo 'new Date('.date_param($date).'),';}?>],
                        title : "investissements"
                    }<?php } 
                    if (new DateTime(null)< new DateTime($date_collecte_end)){?>
                    ,{
                        fillColor : "rgba(0,0,0,0)",
                        strokeColor : "rgba(0,0,0,0)",
                        pointColor : "rgba(110,110,110,1)",
                        pointStrokeColor : "rgba(55,55,55,1)",
                        data : [<?php echo ($campaign->current_amount(false));?>],
                        xPos : [new Date(<?php echo date_param(null); ?>)],
                        title : "aujourdhui"
                    }<?php }
                    ?>
                ]
            };

            displayAnnot = function(cat, date, invest, investtotal){
                if(cat === "investissements"){
                    return invest+ '€, le ' +date.getDate()+'/'+(date.getMonth()+1)+'/'+(date.getFullYear())+' à '+date.getHours()+'h'+date.getMinutes()+'. Total: '+investtotal+'€';
                } else if(cat=== "aujourdhui"){
                    return "Aujourd'hui vous en êtes à "+investtotal+'€'
                }
            };

            var optionsLine = {
                annotateDisplay: true,
                annotateLabel: "<%=displayAnnot(v1,v2,v3-v4,v3)%>",
                pointHitDetectionRadius: 7,

                animation: true,

                scaleOverride : true,
                scaleStartValue : 0,
                scaleSteps : 6,
                scaleStepWidth :  <?php
                    if($campaign->is_funded()){$max= ($campaign->current_amount(false));}
                    else{$max= ($campaign->minimum_goal(false));}
                    echo (round($max,0,PHP_ROUND_HALF_UP)/5);?>
            };
            var canvasLine = new Chart(ctxLine).Line(dataLine, optionsLine);
    });
    </script>
    
    
    <?php exit();
}
add_action('wp_ajax_get_invests_graph', 'get_invests_graph');
add_action('wp_ajax_nopriv_get_invests_graph', 'get_invests_graph');

function get_investments_data(){
    	locate_template( array("requests/investments.php"), true );
	$investments_list = wdg_get_project_investments($_POST['id_campaign']);
        echo json_encode($investments_list);
        exit();
}
add_action('wp_ajax_get_investments_data', 'get_investments_data');
add_action('wp_ajax_nopriv_get_investments_data', 'get_investments_data');

function get_email_selector(){
    $data = (json_decode($_POST['data'],true));
    $payments_data = $data['payments_data'];
    ?>
    <form id="email-selector">
Sélectionner :<br />
<label><input type="checkbox" class="select-options" data-selection="believe" checked="checked" /> Y croit</label><br />
<label><input type="checkbox" class="select-options" data-selection="vote" checked="checked" /> A voté</label><br />
<label><input type="checkbox" class="select-options" data-selection="invest" checked="checked" /> A investi</label><br />
<br />
</form>

<div id="email-selector-list">
<?php 
	$user_list = array();
	global $wpdb;
	
//Récupération de la liste des j'y crois
	$table_jcrois = $wpdb->prefix . "jycrois";
	$result_jcrois = $wpdb->get_results( "SELECT user_id FROM ".$table_jcrois." WHERE campaign_id = ".$_POST['id_campaign'] );
	foreach ($result_jcrois as $item) {
		$user_list[$item->user_id] = 'believe';
	}
	//Récupération de la liste des votants
	$table_votes = $wpdb->prefix . "ypcf_project_votes";
	$result_votes = $wpdb->get_results( "SELECT user_id FROM ".$table_votes." WHERE post_id = ".$_POST['id_campaign'] );
	foreach ($result_votes as $item) {
		if (!empty($user_list[$item->user_id])) $user_list[$item->user_id] .= ' vote';
		else $user_list[$item->user_id] = 'vote';
	}
	//Récupération de la liste des investisseurs
	foreach ( $payments_data as $item ) {
		if ($item['status'] == 'publish') {
			if (!empty($user_list[$item['user']])) $user_list[$item['user']] .= ' invest';
			else $user_list[$item['user']] = 'invest';
		}
	}
	
	//Affichage de la liste d'e-mails
	foreach ($user_list as $user_id => $classes) {
		if (!empty($user_id)) {
			if (YPOrganisation::is_user_organisation($user_id)) {
				$organisation = new YPOrganisation($user_id);
				$user_data = $organisation->get_creator();
				//TODO
				
			} else {
				$user_data = get_userdata($user_id);
				if (!empty($user_data->user_email)) echo '<span class="'.$classes.'">' . $user_data->user_email . ', </span>';
			}
		}
	}
?>
</div>
    <?php
    exit();
}
add_action('wp_ajax_get_email_selector', 'get_email_selector');
add_action('wp_ajax_nopriv_get_email_selector', 'get_email_selector');

/**
 * Shortcodes généraux
 */
function yproject_shortcode_lightbox_button($atts, $content = '') {
    $atts = shortcode_atts( array(
	'label' => 'Afficher',
	'id' => 'lightbox',
	'class' => 'button',
	'style' => ''
    ), $atts );
    return '<a href="#'.$atts['id'].'" class="wdg-button-lightbox-open '.$atts['class'].'" style="'.$atts['style'].'" data-lightbox="'.$atts['id'].'">'.$atts['label'].'</a>';
}
add_shortcode('yproject_lightbox_button', 'yproject_shortcode_lightbox_button');

//Shortcode lightbox standard
function yproject_shortcode_lightbox($atts, $content = '') {
    $atts = shortcode_atts( array(
	'id' => 'lightbox',
    ), $atts );
    return '<div id="wdg-lightbox-'.$atts['id'].'" class="wdg-lightbox hidden">
		<div class="wdg-lightbox-click-catcher"></div>
		<div class="wdg-lightbox-padder">
		    <div class="wdg-lightbox-button-close">
			<a href="#" class="button">X</a>
		    </div>'.do_shortcode($content).'
		</div>
	    </div>';
}
add_shortcode('yproject_lightbox', 'yproject_shortcode_lightbox');

//Shortcode grande lightbox
function yproject_shortcode_widelightbox($atts, $content = '') {
    $atts = shortcode_atts( array(
	'id' => 'lightbox',
    ), $atts );
    return '<div id="wdg-lightbox-'.$atts['id'].'" class="wdg-lightbox hidden">
		<div class="wdg-lightbox-click-catcher"></div>
		<div class="wdg-lightbox-padder wdg-widelightbox-padder">
		    <div class="wdg-lightbox-button-close">
			<a href="#" class="button">X</a>
		    </div>'.do_shortcode($content).'
		</div>
	    </div>';
}
add_shortcode('yproject_widelightbox', 'yproject_shortcode_widelightbox');


//Shortcodes lightbox Connexion 

function yproject_shortcode_connexion_lightbox($atts, $content = '') {
	ob_start();
            locate_template('common/connexion-lightbox.php',true);
            $content = ob_get_contents();
	ob_end_clean();
	echo do_shortcode('[yproject_lightbox id="connexion"]' .$content . '[/yproject_lightbox]');
}
add_shortcode('yproject_connexion_lightbox', 'yproject_shortcode_connexion_lightbox');

//Shortcode lightbox Tableau de bord
// ->TB Stats
function yproject_shortcode_statsadvanced_lightbox($atts, $content = '') {
	ob_start();
            locate_template('common/statsadvanced-lightbox.php',true);
            $content = ob_get_contents();
	ob_end_clean();
	echo do_shortcode('[yproject_lightbox id="statsadvanced"]' .$content . '[/yproject_lightbox]');
}
add_shortcode('yproject_statsadvanced_lightbox', 'yproject_shortcode_statsadvanced_lightbox');

//->TB Liste votants

function yproject_shortcode_votecontact_lightbox($atts, $content = '') {
	ob_start();
            locate_template('common/votecontact-lightbox.php',true);
            $content = ob_get_contents();
	ob_end_clean();
	echo do_shortcode('[yproject_widelightbox id="votecontact"]' .$content . '[/yproject_widelightbox]');
}
add_shortcode('yproject_votecontact_lightbox', 'yproject_shortcode_votecontact_lightbox');

//->TB Liste investisseurs
function yproject_shortcode_listinvestors_lightbox($atts, $content = '') {
	ob_start();
            locate_template('projects/single-project-investors.php',true);
            $content = ob_get_contents();
	ob_end_clean();
	echo do_shortcode('[yproject_widelightbox id="listinvestors"]' .$content . '[/yproject_widelightbox]');
}
add_shortcode('yproject_listinvestors_lightbox', 'yproject_shortcode_listinvestors_lightbox');

//->TB Passer à l'étape suivante
function yproject_shortcode_gonextstep_lightbox($atts, $content = '') {
	ob_start();
            locate_template('projects/dashboard-next-step.php',true);
            $content = ob_get_contents();
	ob_end_clean();
	echo do_shortcode('[yproject_lightbox id="gonextstep"]' .$content . '[/yproject_lightbox]');
}
add_shortcode('yproject_gonextstep_lightbox', 'yproject_shortcode_gonextstep_lightbox');
