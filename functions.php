<?php

//Définition de la largeur de l'affichage
if ( ! isset( $content_width ) ) $content_width = 960;


$wdg_functions_loaded_required_once = array(
	'functions/templates-engine.php',
	'functions/wordpress-events.php',
	'functions/ui-helpers.php',
	'functions/shortcode-manager.php'
);
foreach ( $wdg_functions_loaded_required_once as $file_to_locate ) {
	locate_template( $file_to_locate, true );
}


/** BACK-OFFICE USERS **/
function yproject_user_contact_methods( $user_contact ) {
	$user_contact['user_mobile_phone'] = __('T&eacute;l&eacute;phone');
	$user_contact['user_address'] = __('Adresse');
	$user_contact['user_postal_code'] = __('Code Postal');
	$user_contact['user_city'] = __('Ville');
	$user_contact['user_country'] = __('Pays');
	$user_contact['user_api_login'] = __('API Login');
	$user_contact['user_api_password'] = __('API Password');
	return $user_contact;
}
add_filter( 'user_contactmethods', 'yproject_user_contact_methods' );

function wdg_admin_user_profile( $user ) {
?>
<h2><?php _e( "Porteur de projet", 'yproject' ); ?></h2>
<table class="form-table">
	<tr>
		<th>
			<label for="contract_override"><?php _e( "Remplacement du contrat d'investissement", 'yproject' ); ?></label>
		</th>
		<td>
			<?php echo wp_editor( html_entity_decode( $user->get('wdg-contract-override') ), 'contract_override' ); ?>
		</td>
	</tr>
	<tr>
		<th>
			<label for="contract_nb_custom_fields"><?php _e( "Nombre de champs personnalis&eacute;s &agrave; ajouter", 'yproject' ); ?></label>
		</th>
		<td>
			<input type="text" name="contract_nb_custom_fields" value="<?php echo $user->get('wdg-contract-nb-custom-fields'); ?>" />
		</td>
	</tr>
</table>
<?php	
}
add_action('show_user_profile', 'wdg_admin_user_profile');
add_action('edit_user_profile', 'wdg_admin_user_profile');
 
function wdg_admin_save_user_profile( $user_id ) {
	if ( current_user_can( 'edit_user', $user_id ) ) {
		if ( !empty( $_POST['contract_override'] ) ) {
			update_user_meta( $user_id, 'wdg-contract-override', $_POST['contract_override'] );
		}
		if ( !empty( $_POST['contract_nb_custom_fields'] ) ) {
			update_user_meta( $user_id, 'wdg-contract-nb-custom-fields', $_POST['contract_nb_custom_fields'] );
		}
	}
}
add_action('edit_user_profile_update', 'wdg_admin_save_user_profile');
add_action('personal_options_update', 'wdg_admin_save_user_profile');
/** FIN BACK-OFFICE USERS **/




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

function update_subscription_mail(){
	global $wpdb;
        
	if (isset($_POST['id_campaign']) && isset($_POST['subscribe'])) {
            $table_jcrois = $wpdb->prefix . "jycrois";
            $user_item = wp_get_current_user();
            //var_dump($user_item);
            $user_id = $user_item->ID;
            
            $feed = $wpdb->update( $table_jcrois,
                    array(
                        'subscribe_news' => $_POST['subscribe']
                    ),
                    array(
                        'campaign_id' => $_POST['id_campaign'],
                        'user_id' => $user_id
                    ));
            if ($feed !== false){
                echo $_POST['subscribe'];
            }
	}
}
add_action( 'wp_ajax_update_subscription_mail', 'update_subscription_mail' );


function print_user_projects(){
    
	global $wpdb, $post, $user_projects;
	$is_same_user = TRUE;
	$str_believe = "J&apos;y crois";
	$str_vote = "J&apos;ai &eacute;valu&eacute;";
	$str_investment = "J&apos;ai investi";
	$str_not_believe = "Je n&apos;y crois pas";
	$str_not_vote = "Je n&apos;ai pas &eacute;valu&eacute;";
	$str_not_investment = "Je n&apos;ai pas investi";
	if (!$is_same_user) {
		$str_believe = "Y croit";
		$str_vote = "A &eacute;valu&eacute;";
		$str_investment = "A investi";
		$str_not_believe = "N&apos;y croit pas";
		$str_not_vote = "N&apos;a pas &eacute;valu&eacute;";
		$str_not_investment = "N&apos;a pas investi";
	}
        
	if(isset($_POST['user_id'])){
		$payment_status = array("publish", "completed");
//		if ($is_same_user) $payment_status = array("completed", "pending", "publish", "failed", "refunded");
		$user_id = get_current_user_id();
		$purchases = edd_get_users_purchases($user_id, -1, false, $payment_status);
		$table = $wpdb->prefix.'jycrois';
		$projects_jy_crois = $wpdb->get_results("SELECT campaign_id, subscribe_news FROM $table WHERE user_id=$user_id");
                $table = $wpdb->prefix.'ypcf_project_votes';
		$projects_votes = $wpdb->get_results("SELECT post_id FROM $table WHERE user_id=$user_id");
		$check_investment = true;
		$check_vote = false;
		$check_believe = true;
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
			if  ($purchases):
				foreach ( $purchases as $post ) : setup_postdata( $post );
					$downloads = edd_get_payment_meta_downloads($post->ID); 
					$download_id = '';
					if (!is_array($downloads[0])){
					    $download_id = $downloads[0];
					    $post_camp = get_post($download_id);
					    $campaign = atcf_get_campaign($post_camp);
					    ypcf_get_updated_payment_status($post->ID);
					    $signsquid_contract = new SignsquidContract($post->ID);
					    $payment_date = date_i18n( get_option('date_format'),strtotime(get_post_field('post_date', $post->ID)));

					    //Infos relatives au projet
					    $user_projects[$campaign->ID]['ID'] = $campaign->ID;
					    //Infos relatives à l'investissement de l'utilisateur
					    $user_projects[$campaign->ID]['payments'][$post->ID]['signsquid_contract_id'] = $signsquid_contract->get_contract_id();
					    $user_projects[$campaign->ID]['payments'][$post->ID]['signsquid_status'] = $signsquid_contract->get_status_code();
					    $user_projects[$campaign->ID]['payments'][$post->ID]['signsquid_status_str'] = $signsquid_contract->get_status_str();
					    $user_projects[$campaign->ID]['payments'][$post->ID]['payment_date'] = $payment_date;
					    $user_projects[$campaign->ID]['payments'][$post->ID]['payment_amount'] = edd_get_payment_amount( $post->ID );
					    $user_projects[$campaign->ID]['payments'][$post->ID]['payment_status'] = edd_get_payment_status( $post, true );
					}
				endforeach;
			endif;

			foreach ($projects_jy_crois as $project) {
				$user_projects[$project->campaign_id]['jy_crois'] = 1;
                                $user_projects[$project->campaign_id]['subscribe_news'] = intval($project->subscribe_news);
				$user_projects[$project->campaign_id]['ID'] = $project->campaign_id;
			}
			foreach ($projects_votes as $project) {
				$user_projects[$project->post_id]['has_voted'] = 1;
			}
		 
			?>
			<div>
			<?php
			foreach ($user_projects as $project) {
				$payments = $project['payments'];
				$data_jycrois = 0;
				$data_voted = 0;
				$data_invested = 0;
				if (isset($project['jy_crois']) && $project['jy_crois'] === 1) $data_jycrois = 1;
				if (isset($project['has_voted']) && $project['has_voted'] === 1) $data_voted = 1;
				if (count($project['payments']) > 0) $data_invested = 1;
				
				$is_campaign = (get_post_meta($project['ID'], 'campaign_funding_type', TRUE) != '');
				if ( !$is_campaign ) {
					continue;
				}
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
							<div style="clear: both"></div>
						</div>
					</div>
                                        
                                        <?php if(($project['jy_crois'] === 1) && $is_same_user) {?>
                                        <div class="user-subscribe-news">
                                                <label><input type="checkbox" <?php if ($project['subscribe_news']===1){echo 'checked="checked"';}?>/>
                                                    Recevoir par mail les actualit&eacute;s du projet</label>
                                        </div>
                                        <?php } ?>
					
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
										<?php echo $payment['signsquid_status_str']; ?>
									</td>

									<?php
									//Boutons pour Annuler l'investissement | Recevoir le code à nouveau
									//Visibles si la collecte est toujours en cours, si le paiement a bien été validé, si le contrat n'est pas encore signé
									if ($campaign->is_active() && !$campaign->is_collected() && !$campaign->is_funded() && $campaign->campaign_status() == ATCF_Campaign::$campaign_status_collecte && $payment_status == "publish" && $payment['signsquid_status'] != 'Agreed') :
									?>
									<td style="width: 220px;">
										<?php /*if (!empty($payment['signsquid_contract_id'])): ?>
											<?php $page_my_investments = get_page_by_path('mes-investissements'); ?>
											<a href="<?php echo get_permalink($page_my_investments->ID); ?>?invest_id_resend=<?php echo $payment_id; ?>"><?php _e("Renvoyer le code de confirmation", "yproject"); ?></a><br />
										<?php endif;*/ ?>
										
										<?php $page_cancel_invest = get_page_by_path('annuler-un-investissement'); ?>
										<a href="<?php echo get_permalink($page_cancel_invest->ID); ?>?invest_id=<?php echo $payment_id; ?>"><?php _e("Annuler mon investissement", "yproject"); ?></a>
									</td>
									<?php endif; ?>

								</tr>
                                                                <?php if ($reward != null) { ?>
                                                                    <tr>
                                                                        <td colspan="4" class="user-payment-item user-payment-reward" style="margin-bottom: 10px;">
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
	$post_campaign = get_post($campaign_id);
	$campaign = new ATCF_Campaign($post_campaign);
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
			$wdg_user = new WDGUser( $user_wp_id );
			$api_user_id = $wdg_user->get_api_id();
            $project_api_id = $campaign->get_api_id();
            //Ajout à l'API
			WDGWPREST_Entity_Project::link_user( $project_api_id, $api_user_id, WDGWPREST_Entity_Project::$link_user_type_team );
            
            do_action('wdg_delete_cache', array(
                    'users/' . $api_user_id . '/roles/' . WDGWPREST_Entity_Project::$link_user_type_team . '/projects',
                    'projects/' . $project_api_id . '/roles/' . WDGWPREST_Entity_Project::$link_user_type_team . '/members'
            ));
            
            $user = get_userdata($user_wp_id);
            $data_new_member['id']=$user_wp_id;
            $data_new_member['firstName']=$user->first_name;
            $data_new_member['lastName']=$user->last_name;
            $data_new_member['userLink']=$user->user_login;
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
	$wdg_user = new WDGUser( $_POST['user_to_remove'] );
	$api_user_id = $wdg_user->get_api_id();
	$post_campaign = get_post($_POST['id_campaign']);
	$campaign = new ATCF_Campaign($post_campaign);
    $project_api_id = $campaign->get_api_id();
    //Supprimer dans l'API
	WDGWPREST_Entity_Project::unlink_user( $project_api_id, $api_user_id, WDGWPREST_Entity_Project::$link_user_type_team );
    do_action('wdg_delete_cache', array(
            'users/' . $api_user_id . '/roles/' . WDGWPREST_Entity_Project::$link_user_type_team . '/projects',
            'projects/' . $project_api_id . '/roles/' . WDGWPREST_Entity_Project::$link_user_type_team . '/members'
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
				'value' => ATCF_Campaign::$campaign_status_collecte
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
	$current_lang = get_locale();
	if ($current_lang == 'fr_FR') { $current_lang = ''; }
	else { $current_lang = '_' . $current_lang; }
	
	ypcf_debug_log( 'yproject_save_edit_project > property ('.$current_lang.') => ' . $_POST['property'], TRUE );
	ypcf_debug_log( 'yproject_save_edit_project > value ('.$current_lang.') => ' . $_POST['value'], TRUE );

	//Supprime la réservation de l'édition en cours
	$buffer = FALSE;
	$return_values = array(
		"response" => "done",
		"values" => $_POST['property']
	);

	$WDGuser_current = WDGUser::current();
	$user_id = $WDGuser_current->wp_user->ID;
	$campaign_id = filter_input( INPUT_POST, 'id_campaign' );
	$property = filter_input( INPUT_POST, 'property' );
	$meta_key = $property.'_add_value_reservation';
	$meta_value = get_post_meta( $campaign_id, $meta_key, TRUE ); 

	if ( !empty($meta_value) ) {
	    if ( $meta_value[ 'user' ] == $user_id ) {			
			delete_post_meta( $campaign_id, $meta_key );
			$buffer = TRUE;
	    } else {
	    	$return_values[ 'response' ] = "error";
	    }
	}
	
	switch ($_POST['property']) {
		case "title":
			wp_update_post(array(
				'ID' => $_POST['id_campaign'],
				'post_title' => $_POST['value']
			));
			break;
		case "description":
			if ( $buffer ) {
				if (empty($current_lang)) {
					wp_update_post(array(
						'ID' => $_POST['id_campaign'],
						'post_content' => $_POST['value']
					));
				} else {
					update_post_meta($_POST['id_campaign'], 'campaign_description' . $current_lang, $_POST['value']);
				}
			}
			break;
		case "societal_challenge":
			if ( $buffer ) {
				update_post_meta($_POST['id_campaign'], 'campaign_societal_challenge' . $current_lang, $_POST['value']);
			}
			break;
		case "added_value":
			if ( $buffer ) {
				update_post_meta($_POST['id_campaign'], 'campaign_added_value' . $current_lang, $_POST['value']);
			}
			break;
		case "economic_model":
			if ( $buffer ) {
				update_post_meta($_POST['id_campaign'], 'campaign_economic_model' . $current_lang, $_POST['value']);
			}
			break;
		case "implementation":
			if ( $buffer ) {
				update_post_meta($_POST['id_campaign'], 'campaign_implementation' . $current_lang, $_POST['value']);
			}
			break;
		default: 
			update_post_meta($_POST['id_campaign'], 'campaign_' . $_POST['property'] . $current_lang, $_POST['value']);
			break;
	}
	do_action('wdg_delete_cache', array( 
		'project-header-menu-' . $_POST['id_campaign'], 
		'project-content-summary-' . $_POST['id_campaign'],
		'project-content-about-' . $_POST['id_campaign'],
		'project-content-bottom-' . $_POST['id_campaign'],
		'projects-current',
		'projects-others',
		'cache_campaign_' . $_POST['id_campaign']
	));
	
	$campaign = new ATCF_Campaign( $_POST['id_campaign'] );
	if ( $campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_collecte || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_funded ) {
		$file_cacher = WDG_File_Cacher::current();
		$file_cacher->delete( $campaign->data->post_name );
	}
	echo json_encode($return_values);
	exit();
}
add_action('wp_ajax_save_edit_project', 'yproject_save_edit_project');
add_action('wp_ajax_nopriv_save_edit_project', 'yproject_save_edit_project');

function get_invests_graph(){
	global $disable_logs;
	$disable_logs = TRUE;

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
	if ( count( $datesinvest ) != 0 && ( $datesinvest[0] < $date_collecte_start || $date_collecte_start == null ) ) {
		$date_collecte_start = $datesinvest[0];
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
                        fillColor : "rgba(255,73,76,0.25)",
                        strokeColor : "rgba(255,73,76,0.5)",
                        pointColor : "rgba(0,0,0,0)",
                        pointStrokeColor : "rgba(0,0,0,0)",
                        data : [<?php echo $campaign->current_amount(false);?>,<?php echo $campaign->current_amount(false);?>],
                        xPos : [new Date(<?php foreach ($allamount as $date => $amount){$lastdate = $date;} echo date_param($lastdate); ?>),new Date(<?php echo date_format(min([new DateTime($date_collecte_end),new DateTime(null)]),'"D M d Y H:i:s O"'); ?>)],
                        title : "linetoday"
                    },{
                        fillColor : "rgba(0,0,0,0)",
                        strokeColor : "rgba(0,0,0,0)",
                        pointColor : "rgba(0,0,0,0)",
                        pointStrokeColor : "rgba(0,0,0,0)",
                        data : [<?php foreach ($allamount as $date => $amount){echo $amount.',';}?> ],
                        xPos : [<?php foreach ($allamount as $date => $amount){echo 'new Date('.date_param($date).'),';}?>],
                        title : "inv"
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
                    min = date.getMinutes().toString();
                    return '<b>'+invest+ '€</b>, le ' +date.getDate()+'/'+(date.getMonth()+1)+'/'+(date.getFullYear())+' à '+date.getHours()+'h'+(min[1]?min:"0"+min[0])
                            +'.<br/><b>Total: '+investtotal+'€</b>';
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

function get_investments_data() {
	global $disable_logs;
	$disable_logs = TRUE;
	$campaign_id = filter_input(INPUT_POST, 'id_campaign');
	$investments_list = WDGCampaignInvestments::get_list( $campaign_id, TRUE );
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
<label><input type="checkbox" class="select-options" data-selection="vote" checked="checked" /> A &eacute;valu&eacute;</label><br />
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
		else $user_list[$item->user_id] = ATCF_Campaign::$campaign_status_vote;
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
			if (WDGOrganization::is_user_organization($user_id)) {
				$organization = new WDGOrganization($user_id);
				$user_data = $organization->get_creator();
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