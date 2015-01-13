<?php
function query_projects_preview($nb=0){
	return queryHomeProjects($nb,'preview');
}
function query_projects_vote($nb=0){
	return queryHomeProjects($nb,'vote','desc');
}
function query_projects_collecte($nb=0){
	return queryHomeProjects($nb,'collecte');
}
function query_projects_funded($nb=0){
	return queryHomeProjects($nb,'funded');
}
function query_projects_archive($nb=0){
	return queryHomeProjects($nb,'archive');
}

function queryHomeProjects($nb,$type,$order = 'asc') {
	$query_options = array(
		'showposts' => $nb,
		'post_type' => 'download',
		'post_status' => 'publish',
		'meta_query' => array (

			array (
				'key' => 'campaign_vote',
				'value' => $type
				),
			array (
				'key' => 'campaign_end_date',
				'compare' => '>',
				'value' => date('Y-m-d H:i:s')
			)
		),
		'orderby' => 'post_date',
		'order' => $order
	);
	return query_posts( $query_options );

}

function queryFinishedProjects($nb,$type) {
	$query_options = array(
		'showposts' => $nb,
		'post_type' => 'download',
		'post_status' => 'publish',
		'meta_query' => array (
			array (
				'key' => 'campaign_vote',
				'value' => $type
			)
		),
		'orderby' => 'post_date',
		'order' => 'asc'
	);
	return query_posts( $query_options );
}

class YPProjectLib {
	public static function edit_team() {
		$buffer = '';
		if (isset($_REQUEST['action']) && isset($_GET['campaign_id'])) {
			switch ($_REQUEST['action']) {
				case 'yproject-add-member':
					if (!empty($_POST['new_team_member'])) {
						$user_by_login = get_user_by('login', $_POST['new_team_member']);
						$user_by_mail = get_user_by('email', $_POST['new_team_member']);
						if ($user_by_login === FALSE && $user_by_mail === FALSE) {
							$buffer = 'Nous n&apos;avons pas trouv&eacute; d&apos;utilisateur correspondant.';
						} else {
							//Récupération du bon id wordpress
							$user_wp_id = '';
							if ($user_by_login !== FALSE) $user_wp_id = $user_by_login->ID;
							else if ($user_by_mail !== FALSE) $user_wp_id = $user_by_mail->ID;
							//Récupération des infos existantes sur l'API
							$user_api_id = BoppLibHelpers::get_api_user_id($user_wp_id);
							$project_api_id = BoppLibHelpers::get_api_project_id($_GET['campaign_id']);
							BoppLibHelpers::check_create_role(BoppLibHelpers::$project_team_member_role['slug'], BoppLibHelpers::$project_team_member_role['title']);
							//Ajout à l'API
							BoppLib::link_user_to_project($project_api_id, $user_api_id, BoppLibHelpers::$project_team_member_role['slug']);
							$buffer = TRUE;
						}
					} else {
						$buffer = 'Merci de renseigner un identifiant ou un email.';
					}
					break;
				    
				case 'yproject-remove-member':
					if (!isset($_POST['user_to_remove'])) {
						$buffer = 'BUMP';
					} else {
						//Récupération des infos existantes sur l'API
						$user_api_id = BoppLibHelpers::get_api_user_id($_POST['user_to_remove']);
						$project_api_id = BoppLibHelpers::get_api_project_id($_GET['campaign_id']);
						//Supprimer dans l'API
						BoppLib::unlink_user_from_project($project_api_id, $user_api_id, BoppLibHelpers::$project_team_member_role['slug']);
						$buffer = TRUE;
					}
					break;
			}
		}
		return $buffer;
	}
	
	/**
	 * Détermine si un utilisateur peut éditer la page d'un projet
	 * @param int $campaign_id
	 * @return boolean
	 */
	public static function current_user_can_edit($campaign_id) {
		//Il faut que l'id de projet soit défini
		if (!isset($campaign_id) || empty($campaign_id)) return FALSE;
	    
		//Il faut qu'il soit connecté
		if (!is_user_logged_in()) return FALSE;
		
		//On autorise les admin
		if (current_user_can('manage_options')) return TRUE;
	    
		//On autorise l'auteur
		$post_campaign = get_post($campaign_id);
		$current_user = wp_get_current_user();
		$current_user_id = $current_user->ID;
		if ($current_user_id == $post_campaign->post_author) return TRUE;
		
		//On autorise les personnes de l'équipe projet
		$project_api_id = BoppLibHelpers::get_api_project_id($campaign_id);
		$team_member_list = BoppLib::get_project_members_by_role($project_api_id, BoppLibHelpers::$project_team_member_role['slug']);
		foreach ($team_member_list as $team_member) {
			if ($current_user_id == $team_member->wp_user_id) return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * Gère le formulaire d'ajout d'actualité
	 */
	public static function form_validate_news_add($campaign_id) {
		if (!YPProjectLib::current_user_can_edit($campaign_id) 
				|| !isset($_POST['action'])
				|| $_POST['action'] != 'ypcf-campaign-add-news') {
			return FALSE;
		}

		$current_user = wp_get_current_user();
		$post_campaign = get_post($campaign_id);

		$category_slug = $post_campaign->ID . '-blog-' . $post_campaign->post_title;
		$category_obj = get_category_by_slug($category_slug);

		$blog = array(
			'post_title'    => $_POST['posttitle'],
			'post_content'  => $_POST['postcontent'],
			'post_status'   => 'publish',
			'post_author'   => $current_user->ID,
			'post_category' => array($category_obj->cat_ID)
		);

		wp_insert_post($blog, true);
		do_action('wdg_delete_cache', array( 'project-'.$post_campaign->ID.'-header' ));
	}
	
	/**
	 * Gère le formulaire de paramètres projets
	 */
	public static function form_validate_edit_parameters() {
		$buffer = TRUE;
	    
		if (!isset($_GET["campaign_id"])) { return FALSE; }
		$campaign_id = $_GET["campaign_id"];
		$post_campaign = get_post($campaign_id);
		
		if (!YPProjectLib::current_user_can_edit($campaign_id) 
				|| !isset($_POST['action'])
				|| $_POST['action'] != 'edit-project-parameters') {
			return FALSE;
		}
		
		$title = sanitize_text_field($_POST['project-name']);
		if (!empty($title)) {
			wp_update_post(array(
				'ID' => $campaign_id,
				'post_title' => $title
			));
		} else {
			$buffer = FALSE;
		}
		
		$cat_cat_id = -1; $cat_act_id = -1;
		if (isset($_POST['categories'])) { $cat_cat_id = $_POST['categories']; } else { $buffer = FALSE; }
		if (isset($_POST['activities'])) { $cat_act_id = $_POST['activities']; } else { $buffer = FALSE; }
		if ($cat_cat_id != -1 && $cat_act_id != -1) {
			$cat_ids = array_map( 'intval', array($cat_cat_id, $cat_act_id) );
			wp_set_object_terms($campaign_id, $cat_ids, 'download_category');
		}
		
		if (isset($_POST['project-location'])) {
			update_post_meta($campaign_id, 'campaign_location', $_POST['project-location']);
		} else {
			$buffer = FALSE;
		}
		
		
		if (isset($_POST['project-organisation'])) {
			//Récupération de l'ancienne organisation
			$api_project_id = BoppLibHelpers::get_api_project_id($post_campaign->ID);
			$current_organisations = BoppLib::get_project_organisations_by_role($api_project_id, BoppLibHelpers::$project_organisation_manager_role['slug']);
			$current_organisation = FALSE;
			if (count($current_organisations) > 0) {
			    $current_organisation = $current_organisations[0];
			}
			
			$delete = FALSE;
			$update = FALSE;
			
			//On met à jour : si une nouvelle organisation est renseignée et différente de celle d'avant
			//On supprime : si la nouvelle organisation renseignée est différente de celle d'avant
			if (!empty($_POST['project-organisation'])) {
				$organisation_selected = new YPOrganisation($_POST['project-organisation']);
				if ($current_organisation === FALSE || $current_organisation->organisation_wpref != $organisation_selected->get_wpref()) {
					$update = TRUE;
					if ($current_organisation !== FALSE) {
						$delete = TRUE;
					}
				}
				
			//On supprime : si rien n'est sélectionné + il y avait quelque chose avant
			} else {
				if ($current_organisation !== FALSE) {
					$delete = TRUE;
				}
			}
			
			if ($delete) {
				BoppLib::unlink_organisation_from_project($api_project_id, $current_organisation->id);
			}
				
			if ($update) {
				$api_organisation_id = $organisation_selected->get_bopp_id();
				BoppLib::link_organisation_to_project($api_project_id, $api_organisation_id, BoppLibHelpers::$project_organisation_manager_role['slug']);
			}
		} else {
			$buffer = FALSE;
		}
		
		if (isset($_POST['fundingtype'])) { 
			if ($_POST['fundingtype'] == 'fundingdevelopment' || $_POST['fundingtype'] == 'fundingproject') {
				update_post_meta($campaign_id, 'campaign_funding_type', $_POST['fundingtype']); 
			} else {
				$buffer = FALSE;
			}
		}
		if (isset($_POST['fundingduration'])) { 
			$duration = $_POST['fundingduration'];
			if (is_numeric($duration) && $duration > 0 && (int)$duration == $duration) {
				update_post_meta($campaign_id, 'campaign_funding_duration', $duration); 
			} else {
				$buffer = FALSE;
			}
		}
		if (isset($_POST['minimum_goal'])) {
			$minimum_goal = $_POST['minimum_goal'];
			if (is_numeric($minimum_goal) && $minimum_goal > 0 && (int)$minimum_goal == $minimum_goal) {
				update_post_meta($campaign_id, 'campaign_minimum_goal', $minimum_goal); 
			} else {
				$buffer = FALSE;
			}
		}
		if (isset($_POST['maximum_goal'])) {
			$goal = $_POST['maximum_goal'];
			if (is_numeric($goal) && $goal > 0 && (int)$goal == $goal) {
				if ($goal < $minimum_goal) $goal = $minimum_goal;
				update_post_meta($campaign_id, 'campaign_goal', $goal); 
			} else {
				$buffer = FALSE;
			}
		}
		    
		return $buffer;
	}
}
