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

add_action( 'wp_enqueue_scripts', 'yproject_enqueue_script' );
function yproject_enqueue_script(){
	if ( !is_admin() ) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', (dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery.min.js'), false);
		wp_enqueue_script('jquery');
	}
	
	global $post;
	$is_campaign = (get_post_meta($post->ID, 'campaign_goal', TRUE) != '');
	wp_enqueue_script( 'wdg-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/common.js', array('jquery', 'jquery-ui-dialog'), '1.1.004');
	if ($is_campaign) { wp_enqueue_script( 'wdg-script2', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/project_bopp.js', array('jquery', 'jquery-ui-dialog')); }
	wp_enqueue_script( 'jquery-form', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery.form.js', array('jquery'));
	wp_enqueue_script( 'jquery-ui-wdg', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery-ui.min.js', array('jquery'));
	wp_enqueue_script( 'chart-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/chart.new.js', array('wdg-script'));
//	wp_enqueue_script( 'wdg-ux-helper', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-ux-helper.js', array('wdg-script'));
	
	wp_enqueue_script('qtip', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery.qtip.js', array('jquery'));
	wp_enqueue_style('qtip', dirname( get_bloginfo('stylesheet_url')).'/_inc/css/jquery.qtip.min.css', null, false, false);
	
	wp_localize_script( 'wdg-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
	if ($is_campaign) { wp_localize_script( 'wdg-script2', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) ); }
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
	if (!empty($campaign->ID) && is_object( $campaign ) && ($campaign->campaign_status() == 'preparing') && !YPProjectLib::current_user_can_edit($campaign_id)) {
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
 */
function set_cover_position(){
	if (isset($_POST['top'])) {
		update_post_meta($_POST['id_campaign'],'campaign_cover_position', $_POST['top']);
		do_action('wdg_delete_cache',array('project-'.$_POST['id_campaign'].'-header-second'));
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
		do_action('wdg_delete_cache',array('project-'.$_POST['id_campaign'].'-content'));
	}
}
add_action( 'wp_ajax_setCursorPosition', 'set_cursor_position' );

	
function update_jy_crois(){
	global $post;

	if (isset($_POST['id_campaign'])) $post = get_post($_POST['id_campaign']);
	$campaign = atcf_get_campaign( $post );
	return $campaign->manage_jycrois();
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
	$user_avatar = UIHelpers::get_user_avatar($user_id);

	bp_activity_add(array (
		'component' => 'profile',
		'type'      => 'jycrois',
		'action'    => $user_avatar.' '.$url_profile.' a commentÃƒÂ© '.$url_blog
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
				$signsquid_status = ypcf_get_signsquidstatus_from_infos($signsquid_infos);
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
				$project['days_remaining'] = $campaign->days_remaining();
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
							    
								<?php foreach ($payments as $payment_id => $payment) : ?>
							    
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
								
								<?php endforeach; ?>
									
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
