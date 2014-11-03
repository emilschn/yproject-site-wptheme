<?php
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
	wp_enqueue_script( 'wdg-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/common.js', array('jquery', 'jquery-ui-dialog'));
	wp_enqueue_script( 'wdg-script2', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/project_bopp.js', array('jquery', 'jquery-ui-dialog'));
	wp_enqueue_script( 'jquery-form-wdg', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery.form.js', array('jquery'));
	wp_enqueue_script( 'jquery-qtips2', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery.form.js', array('jquery'));
	wp_enqueue_script( 'jquery-ui-wdg', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery-ui.min.js', array('jquery'));
	
	wp_localize_script( 'wdg-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
	wp_localize_script( 'wdg-script2', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
	wp_enqueue_script( 'chart-script', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/chart.new.js', array('wdg-script'));
	
	wp_enqueue_script('qtip', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/jquery.qtip.js', array('jquery'));
	wp_enqueue_style('qtip', dirname( get_bloginfo('stylesheet_url')).'/_inc/css/jquery.qtip.min.css', null, false, false);
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
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/horloge.png" />
									<?php echo $project['days_remaining']; ?>
							    </div>
							    <div class="project_preview_item_picto">
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cible.png" />
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

/******** NAME ********/
function update_name() {
	global $wpdb, $post;
	$wp_project_id = $_POST['wpProjectId'];

	$post_update = array();
	$post_update['ID'] = $wp_project_id;
	if (isset($_POST['projectName']) && $_POST['projectName'] != "") $post_update['post_title'] = $_POST['projectName'];
	wp_update_post($post_update);

	$id = BoppLibHelpers::get_api_project_id($wp_project_id);
	$wp_project_video = $_POST['projectVideo'];
	BoppLib::update_project(
		$id,
		array(
			'wp_project_id' =>  $wp_project_id, 
			'wp_project_name' => $_POST['projectName'], 
			'wp_project_slogan' => $_POST['projectSlogan']
			)
		);
	header("Content-type: text/plain");
	$arr = array (
		"project_name" =>$_POST['projectName'],
		"project_slogan" => $_POST['projectSlogan']
		); 
	echo json_encode($arr);
	die();
}
add_action( 'wp_ajax_update_name', 'update_name' );


/******** DESCRIPTION ********/
function update_description() {
	global $wpdb, $post;
	$wp_project_id = $_POST['wpProjectId'];
	$id = BoppLibHelpers::get_api_project_id($wp_project_id);
	BoppLib::update_project(
		$id,
		array(
			'wp_project_id' =>  $wp_project_id, 
			'wp_project_description' => $_POST['projectDescription'], 
			)
		);
	header("Content-type: text/plain");
	$arr = array (
		"project_description" => $_POST['projectDescription'],
		); 
	echo json_encode($arr);
	die();
}
add_action( 'wp_ajax_update_description', 'update_description' );

/******** VIDEO ********/
function update_video() {
	global $wpdb, $post;
	$wp_project_id = $_POST['wpProjectId'];
	$id = BoppLibHelpers::get_api_project_id($wp_project_id);
	BoppLib::update_project(
		$id,
		array(
			'wp_project_id' =>  $wp_project_id, 
			'wp_project_video' => $_POST['projectVideo'], 
			)
		);
	header("Content-type: text/plain");
	$arr = array (
		"project_video" => $_POST['projectVideo']
		); 
	echo json_encode($arr);
	die();
}
add_action( 'wp_ajax_update_video', 'update_video' );

/******** EN QUOI CONSISTE LE PROJET ********/
function update_project() {
	global $wpdb, $post;
	$wp_project_id = $_POST['wpProjectId'];
	$id = BoppLibHelpers::get_api_project_id($wp_project_id);
	BoppLib::update_project(
		$id,
		array(
			'wp_project_id' =>  $wp_project_id, 
			'wp_project_category' => $_POST['projectCategory'], 
			'wp_project_business_sector' => $_POST['projectBusinessSector'], 
			'wp_project_funding_type' => $_POST['projectFundingType'], 
			'wp_project_funding_duration' => $_POST['projectFundingDuration'], 
			'wp_project_return_on_investment' => $_POST['projectReturnOnInvestment'], 
			'wp_project_investor_benefit' => $_POST['projectInvestorBenefit'], 
			'wp_project_summary' => $_POST['projectSummary']
			)
		);
	$arr = array (
		"project_category" => $_POST['projectCategory'],
		"project_business_sector" => $_POST['projectBusinessSector'],
		"project_funding_type" => $_POST['projectFundingType'],
		"project_funding_duration" => $_POST['projectFundingDuration'],
		"project_return_on_investment" => $_POST['projectReturnOnInvestment'],
		"project_investor_benefit" => $_POST['projectInvestorBenefit'],
		"project_summary" => $_POST['projectSummary']
		);
	echo json_encode($arr); 
	die();
}
add_action( 'wp_ajax_update_project', 'update_project' );

/******** QUELLE EST L'UTILITÉ SOCIÉTALE DU PROJET ? ********/
function update_societal() {
	global $wpdb, $post;
	$wp_project_id = $_POST['wpProjectId'];
	$id = BoppLibHelpers::get_api_project_id($wp_project_id);
	BoppLib::update_project(
		$id,
		array(
			'wp_project_id' =>  $wp_project_id, 
			'wp_project_economy_excerpt' => $_POST['projectEconomyExcerpt'], 
			'wp_project_social_excerpt' => $_POST['projectSocialExcerpt'], 
			'wp_project_environment_excerpt' => $_POST['projectEnvironmentExcerpt'], 
			'wp_project_mission' => $_POST['projectMission'], 
			'wp_project_economy' => $_POST['projectEconomy'], 
			'wp_project_social' => $_POST['projectSocial'], 
			'wp_project_environment' => $_POST['projectEnvironment'], 
			'wp_project_measure_performance' => $_POST['projectMeasurePerformance'], 
			'wp_project_good_point' => $_POST['projectGoodPoint']
			)
		);
	header("Content-type: text/plain");
	$arr = array (
		"project_economy_excerpt" => $_POST['projectEconomyExcerpt'],
		"project_social_excerpt" => $_POST['projectSocialExcerpt'],
		"project_environment_excerpt" => $_POST['projectEnvironmentExcerpt'],
		"project_mission" => $_POST['projectMission'],
		"project_economy" => $_POST['projectEconomy'],
		"project_social" => $_POST['projectSocial'],
		"project_environment" => $_POST['projectEnvironment'],
		"project_measure_performance" => $_POST['projectMeasurePerformance'],
		"project_good_point" => $_POST['projectGoodPoint']
		); 
	echo json_encode($arr);
	die();
}
add_action( 'wp_ajax_update_societal', 'update_societal' );

/******** QUELLE EST L'OPPORTUNITÉ ÉCONOMIQUE DU PROJET ? ********/
function update_economy() {
	global $wpdb, $post;
	$wp_project_id = $_POST['wpProjectId'];
	$id = BoppLibHelpers::get_api_project_id($wp_project_id);
	BoppLib::update_project(
		$id,
		array(
			'wp_project_id' =>  $wp_project_id, 
			'wp_project_context_excerpt' => $_POST['projectContextExcerpt'], 
			'wp_project_market_excerpt' => $_POST['projectMarketExcerpt'], 
			'wp_project_context' => $_POST['projectContext'], 
			'wp_project_market' => $_POST['projectMarket']
			)
		);
	header("Content-type: text/plain");
	$arr = array (
		'project_context_excerpt' => $_POST['projectContextExcerpt'], 
		'project_market_excerpt' => $_POST['projectMarketExcerpt'], 
		'project_context' => $_POST['projectContext'], 
		'project_market' => $_POST['projectMarket']
		); 
	echo json_encode($arr);
	die();
}
add_action( 'wp_ajax_update_economy', 'update_economy' );

/******** QUEL EST LE MODÈLE ÉCONOMIQUE DU PROJET ?********/
function update_model() {
	global $wpdb, $post;
	$wp_project_id = $_POST['wpProjectId'];
	$id = BoppLibHelpers::get_api_project_id($wp_project_id);
	BoppLib::update_project(
		$id,
		array(
			'wp_project_id' =>  $wp_project_id, 
			'wp_project_worth_offer' => $_POST['projectWorthOffer'], 
			'wp_project_client_collaborator' => $_POST['projectClientCollaborator'], 
			'wp_project_business_core' => $_POST['projectBusinessCore'], 
			'wp_project_income' => $_POST['projectIncome'], 
			'wp_project_cost' => $_POST['projectCost'], 
			'wp_project_collaborators_canvas' => $_POST['projectCollaboratorsCanvas'], 
			'wp_project_activities_canvas' => $_POST['projectActivitiesCanvas'], 
			'wp_project_ressources_canvas' => $_POST['projectRessourcesCanvas'], 
			'wp_project_worth_offer_canvas' => $_POST['projectWorthOfferCanvas'],
			'wp_project_customers_relations_canvas' => $_POST['projectCustomersRelationsCanvas'],
			'wp_project_chain_distribution_canvas' => $_POST['projectChainDistributionsCanvas'], 
			'wp_project_clients_canvas' => $_POST['projectClientsCanvas'], 
			'wp_project_cost_structure_canvas' => $_POST['projectCostStructureCanvas'], 
			'wp_project_source_income_canvas' => $_POST['projectSourceOfIncomeCanvas'], 
			'wp_project_financial_board' => $_POST['projectFinancialBoard'], 
			'wp_project_perspectives' => $_POST['projectPerspectives']
			)
);
header("Content-type: text/plain");
$arr = array (
	'project_worth_offer' => $_POST['projectWorthOffer'], 
	'project_client_collaborator' => $_POST['projectClientCollaborator'], 
	'project_business_core' => $_POST['projectBusinessCore'], 
	'project_income' => $_POST['projectIncome'], 
	'project_cost' => $_POST['projectCost'], 
	'project_collaborators_canvas' => $_POST['projectCollaboratorsCanvas'], 
	'project_activities_canvas' => $_POST['projectActivitiesCanvas'], 
	'project_ressources_canvas' => $_POST['projectRessourcesCanvas'], 
	'project_worth_offer_canvas' => $_POST['projectWorthOfferCanvas'],
	'project_customers_relations_canvas' => $_POST['projectCustomersRelationsCanvas'],
	'project_chain_distribution_canvas' => $_POST['projectChainDistributionsCanvas'], 
	'project_clients_canvas' => $_POST['projectClientsCanvas'], 
	'project_cost_structure_canvas' => $_POST['projectCostStructureCanvas'], 
	'project_source_income_canvas' => $_POST['projectSourceOfIncomeCanvas'], 
	'project_financial_board' => $_POST['projectFinancialBoard'], 
	'project_perspectives' => $_POST['projectPerspectives'], 
	); 
echo json_encode($arr);
die();
}
add_action( 'wp_ajax_update_model', 'update_model' );


/******** QUI PORTE LE PROJET ? ********/
function update_members() {
	global $wpdb, $post;
	$wp_project_id = $_POST['wpProjectId'];
	$id = BoppLibHelpers::get_api_project_id($wp_project_id);
	BoppLib::update_project(
		$id,
		array(
			'wp_project_id' =>  $wp_project_id, 
			'wp_project_other_information' => $_POST['projectOtherInformation']
			)
		);
	header("Content-type: text/plain");
	$arr = array (
		'project_other_information' => $_POST['projectOtherInformation']
		); 
	echo json_encode($arr);
	die();
}
add_action( 'wp_ajax_update_members', 'update_members' );

function save_image_home() {
	//simple Security check
	$image = $_FILES[ 'image_home' ];

	//get POST data
	$campaign_id = $_POST['post_id'];

	//require the needed files
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');
	//then loop over the files that were sent and store them using  media_handle_upload();
	if ($_FILES) {
		if (isset($_FILES[ 'files' ])) $files = $_FILES[ 'files' ];
		$edd_files  = array();
		$upload_overrides = array( 'test_form' => false );
		if ( ! empty( $files ) ) {
			foreach ( $files[ 'name' ] as $key => $value ) {
				if ( $files[ 'name' ][$key] ) {
					$file = array(
						'name'     => $files[ 'name' ][$key],
						'type'     => $files[ 'type' ][$key],
						'tmp_name' => $files[ 'tmp_name' ][$key],
						'error'    => $files[ 'error' ][$key],
						'size'     => $files[ 'size' ][$key]
						);

					$upload = wp_handle_upload( $file, $upload_overrides );

					if ( isset( $upload[ 'url' ] ) )
						$edd_files[$key]['file'] = $upload[ 'url' ];
					else
						unset($files[$key]);
				}
			}
		}
	}
	//and if you want to set that image as Post  then use:

	if (!empty($image)) {
		if (isset($_FILES[ 'files' ])) $files = $_FILES[ 'files' ];

		$edd_files  = array();
		$upload_overrides = array( 'test_form' => false );
		if ( ! empty( $files ) ) {
			foreach ( $files[ 'name' ] as $key => $value ) {
				if ( $files[ 'name' ][$key] ) {
					$file = array(
						'name'     => $files[ 'name' ][$key],
						'type'     => $files[ 'type' ][$key],
						'tmp_name' => $files[ 'tmp_name' ][$key],
						'error'    => $files[ 'error' ][$key],
						'size'     => $files[ 'size' ][$key]
						);

					$upload = wp_handle_upload( $file, $upload_overrides );

					if ( isset( $upload[ 'url' ] ) )
						$edd_files[$key]['file'] = $upload[ 'url' ];
					else
						unset($files[$key]);
				}
			}
		}
	}    
	$upload = wp_handle_upload( $image, $upload_overrides );
	if (isset($upload[ 'url' ])) {
		$attachment = array(
			'guid'           => $upload[ 'url' ], 
			'post_mime_type' => $upload[ 'type' ],
			'post_title'     => 'image_home',
			'post_content'   => '',
			'post_status'    => 'inherit'
			);
		global $wpdb;
		$table_posts = $wpdb->prefix . "posts";
		$campaign_id = $_POST['post_id'];
		//Suppression dans la base de données de l'ancienne image
		$old_attachement_id=$wpdb->get_var( "SELECT * FROM $table_posts WHERE post_parent=$campaign_id and post_title='image_home'" );
		wp_delete_attachment( $old_attachement_id, true );

		$attach_id = wp_insert_attachment( $attachment, $upload[ 'file' ], $campaign_id );		

		wp_update_attachment_metadata( 
			$attach_id, 
			wp_generate_attachment_metadata( $attach_id, $upload[ 'file' ] ) 
			);

		
	}
	$attachmentsGet = get_posts( array(
		'post_type' => 'attachment',
		'post_parent' => $campaign_id,
		'post_mime_type' => 'image'
		));
	$image_obj_home = '';
	$image_obj_header = '';
	$image_src_home = '';
	$image_src_header = '';
	    //Si on en trouve bien une avec le titre "image_home" on prend celle-là
	foreach ($attachmentsGet as $attachment) {
		if ($attachment->post_title == 'image_home') $image_obj_home = wp_get_attachment_image_src($attachment->ID, "full");
	}
	    //Sinon on prend la première image rattachée à l'article
	if ($image_obj_home != '') $image_src_home = $image_obj_home[0];
	echo $image_src_home; 
	die();
}

add_action( 'wp_ajax_save_image_home', 'save_image_home' );



function save_image() {
		//get POST data
	$campaign_id = $_POST['post_id'];

		//require the needed files
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');

	$image_header = $_FILES[ 'image' ];
	$path = $_FILES['image']['name'];
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	
	if (!empty($image_header)) {
		if (isset($_FILES[ 'files' ])) $files = $_FILES[ 'files' ];

		$edd_files  = array();
		$upload_overrides = array( 'test_form' => false );
		if ( ! empty( $files ) ) {
			foreach ( $files[ 'name' ] as $key => $value ) {
				if ( $files[ 'name' ][$key] ) {
					$file = array(
						'name'     => $files[ 'name' ][$key],
						'type'     => $files[ 'type' ][$key],
						'tmp_name' => $files[ 'tmp_name' ][$key],
						'error'    => $files[ 'error' ][$key],
						'size'     => $files[ 'size' ][$key]
						);

					$upload = wp_handle_upload( $file, $upload_overrides );

					if ( isset( $upload[ 'url' ] ) )
						$edd_files[$key]['file'] = $upload[ 'url' ];
					else
						unset($files[$key]);
				}
			}
		}

		$upload = wp_handle_upload( $image_header, $upload_overrides );
		if (isset($upload[ 'url' ])) {
			$attachment = array(
				'guid'           => $upload[ 'url' ], 
				'post_mime_type' => $upload[ 'type' ],
				'post_title'     => 'image_header',
				'post_content'   => '',
				'post_status'    => 'inherit'
				);
			
			$is_image_accepted = true;
			switch (strtolower($ext)) {
				case 'png':
				$image_header = imagecreatefrompng($upload[ 'file' ]);
				break;
				case 'jpg':
				case 'jpeg':
				$image_header = imagecreatefromjpeg($upload[ 'file' ]);
				break;
				default:
				$is_image_accepted = false;
				break;
			}
			if($is_image_accepted){
				for($i=0; $i<10 ; $i++){
					imagefilter ($image_header, IMG_FILTER_GAUSSIAN_BLUR);
					imagefilter ($image_header , IMG_FILTER_SELECTIVE_BLUR );
				}
				$withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $upload[ 'file' ]);
				$img_name = $withoutExt.'_blur.jpg';
				imagejpeg($image_header,$img_name);
				global $wpdb;
				$table_posts = $wpdb->prefix . "posts";
			    //Suppression dans la base de données de l'ancienne image
				$old_attachement_id=$wpdb->get_var( "SELECT * FROM $table_posts WHERE post_parent=$campaign_id and post_title='image_header'" );
				wp_delete_attachment( $old_attachement_id, true );
				$attach_id = wp_insert_attachment( $attachment, $img_name, $campaign_id  );		

				wp_update_attachment_metadata( 
					$attach_id, 
					wp_generate_attachment_metadata( $attach_id, $img_name ) 
					);
			    //Suppression de la position de la couverture
				delete_post_meta($campaign_id , 'campaign_cover_position');


				add_post_meta( $campaign_id , '_thumbnail_id', absint( $attach_id ) );
			}
		}


		
		$attachmentsGet = get_posts( array(
			'post_type' => 'attachment',
			'post_parent' => $campaign_id,
			'post_mime_type' => 'image'
			));
		$image_obj_home = '';
		$image_obj_header = array();
		$image_src_home = '';
		$image_src_header = '';
	    //Si on en trouve bien une avec le titre "image_home" on prend celle-là
		foreach ($attachmentsGet as $attachment) {
			if ($attachment->post_title == 'image_header') array_push ($image_obj_header, wp_get_attachment_image_src($attachment->ID, "full"));

		}
	    //Sinon on prend la première image rattachée à l'article
		if ($image_obj_header != '') $image_src_header = $image_obj_header[0];
		//echo $image_src_header; 
		echo $image_obj_header[0][0];

	}
	die();
}


add_action( 'wp_ajax_save_image', 'save_image' );

function cancel_project() {
	$wp_project_id = $_POST['wpProjectId'];
	$id =  BoppLib::get_projectwp($wp_project_id);
	echo $id; 
	die();
}
add_action( 'wp_ajax_update_project', 'update_project' );
?>
