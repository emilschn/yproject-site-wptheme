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
	$WDGuser_current = WDGUser::current();
	$user_id = $WDGuser_current->wp_user->ID;
	$campaign = new ATCF_Campaign( $_POST['id_campaign'] );

	$campaign_id = filter_input( INPUT_POST, 'id_campaign' );
	$property = filter_input( INPUT_POST, 'property' );
	$lang = filter_input( INPUT_POST, 'lang' );
	
	$meta_key = $property.'_add_value_reservation_'.$lang;
	$meta_value = get_post_meta( $campaign_id, $meta_key, TRUE );
	$WDGUser = new WDGUser( $meta_value[ 'user' ] );
	$name = $WDGUser->get_firstname()." ".$WDGUser->get_lastname();
	
	$return_values = array(
			"response" => "done",
			"values" => $_POST['property'],
			"user" => $name,
			"md5content" => null
	);

	if ( !empty($meta_value) ) {
	    if ( $meta_value[ 'user' ] == $user_id ) {			
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
					//nouvelle instance pour récupérer le contenu à jour
					$description_campaign = new ATCF_Campaign( $_POST['id_campaign'] );
					$return_values[ 'md5content' ] = md5( $description_campaign->description() );
				} else {
					update_post_meta($_POST['id_campaign'], 'campaign_description' . $current_lang, $_POST['value']);
					$return_values[ 'md5content' ] = md5( $campaign->description() );
				}
				delete_post_meta( $campaign_id, $meta_key );
			}
			break;
		case "societal_challenge":
			if ( $buffer ) {
				update_post_meta($_POST['id_campaign'], 'campaign_societal_challenge' . $current_lang, $_POST['value']);
				$return_values[ 'md5content' ] = md5( $campaign->societal_challenge() );
				delete_post_meta( $campaign_id, $meta_key );
			}
			break;
		case "added_value":
			if ( $buffer ) {
				update_post_meta($_POST['id_campaign'], 'campaign_added_value' . $current_lang, $_POST['value']);
				$return_values[ 'md5content' ] = md5( $campaign->added_value() );
				delete_post_meta( $campaign_id, $meta_key );
			}
			break;
		case "economic_model":
			if ( $buffer ) {
				update_post_meta($_POST['id_campaign'], 'campaign_economic_model' . $current_lang, $_POST['value']);
				$return_values[ 'md5content' ] = md5( $campaign->economic_model() );
				delete_post_meta( $campaign_id, $meta_key );
			}
			break;
		case "implementation":
			if ( $buffer ) {
				update_post_meta($_POST['id_campaign'], 'campaign_implementation' . $current_lang, $_POST['value']);
				$return_values[ 'md5content' ] = md5( $campaign->implementation() );
				delete_post_meta( $campaign_id, $meta_key );
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
		var listDates = [];
		listDates.push(<?php echo date_abs($date_collecte_start); ?>);
		<?php foreach ($allamount as $date => $amount): ?>
			listDates.push(<?php echo date_abs($date); ?>);
		<?php endforeach; ?>
		listDates.push(<?php echo date_abs($date_collecte_end); ?>);
		new Chart(
			document.getElementById("canvas-line-block"),
			{
				"type":"line",
				"data":{
					"labels": listDates,
					"datasets":[
						{
							backgroundColor : "rgba(204,204,204,0.25)",
							borderColor : "rgba(180,180,180,0.5)",
							pointColor : "rgba(0,0,0,0)",
							pointStrokeColor : "rgba(0,0,0,0)",
							data: [
								{
									x: <?php echo date_abs($date_collecte_start); ?>,
									y: 0
								},
								{
									x: <?php echo date_abs($date_collecte_end); ?>,
									y: <?php echo $campaign->minimum_goal(false); ?>
								}
							],
							title : "But progression"
						},
						{
							backgroundColor : "rgba(0,0,0,0)",
							borderColor : "rgba(140,140,140,0.5)",
							pointColor : "rgba(0,0,0,0)",
							pointStrokeColor : "rgba(0,0,0,0)",
							data: [
								{
									x: <?php echo date_abs($date_collecte_start); ?>,
									y: <?php echo $campaign->minimum_goal(false); ?>
								},
								{
									x: <?php echo date_abs($date_collecte_end); ?>,
									y: <?php echo $campaign->minimum_goal(false); ?>
								}
							],
							title : "But"
						}
						<?php if (count($datesinvest)!=0): ?>
						,
						{
							backgroundColor : "rgba(255,73,76,0.25)",
							borderColor : "rgba(255,73,76,0.5)",
							pointColor : "rgba(0,0,0,0)",
							pointStrokeColor : "rgba(0,0,0,0)",
							data : [
								{
									x: <?php echo date_abs($date_collecte_start); ?>,
									y: <?php echo $campaign->current_amount(false); ?>
								},
								{
									x: <?php echo date_abs($date_collecte_end); ?>,
									y: <?php echo $campaign->current_amount(false); ?>
								}
							],
							title : "linetoday"
						},
						{
							backgroundColor : "rgba(255,73,76,0.5)",
							borderColor : "rgba(255,73,76,1)",
							pointColor : "rgba(255,73,76,1)",
							pointStrokeColor : "rgba(199,46,49,1)",
							data : [
								<?php foreach ($cumulamount as $date => $amount): ?>
								{
									x: <?php echo date_abs($date); ?>,
									y: <?php echo $amount; ?>
								},
								<?php endforeach; ?>
							],
							title : "investissements"
						}
						<?php endif; ?>
					]
				},
				options:{
					legend:{
						display: false
					}
				}
			}
		);
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
	$input_is_short_version = filter_input(INPUT_POST, 'is_short_version');
	$investments_list = WDGCampaignInvestments::get_list( $campaign_id, TRUE );
	if ( $input_is_short_version == '1' ) {
		unset( $investments_list[ 'campaign' ] );
		unset( $investments_list[ 'payments_data' ] );
		unset( $investments_list[ 'investors_list' ] );
		$campaign = new ATCF_Campaign( $campaign_id );
		if ( $campaign->get_hide_investors() ) {
			unset( $investments_list[ 'investors_string' ] );
		}
	}
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

if ( !function_exists( 'array_key_first' ) ) {
	function array_key_first( $array ) {
		reset( $array );
		return key( $array );
	}
}

if ( !function_exists( 'array_key_last' ) ) {
	function array_key_last( $array ) {
		if ( !is_array( $array ) || empty( $array ) ) {
			return NULL;
		}
		return array_keys( $array )[ count( $array ) - 1 ];
	}
}