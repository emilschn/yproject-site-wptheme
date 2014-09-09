<?php
function query_projects_preview($nb=0){
	return queryHomeProjects($nb,'preview');
}
function query_projects_vote($nb=0){
	return queryHomeProjects($nb,'vote');
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

function queryHomeProjects($nb,$type) {
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
		'order' => 'asc'
	);
	return query_posts( $query_options );

}

class YPProjectLib {
	public static $project_team_member_role = array(
						    'slug' => 'project-team-member', 
						    'title' => 'Membre equipe projet');
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
							BoppLibHelpers::check_create_role(YPProjectLib::$project_team_member_role['slug'], YPProjectLib::$project_team_member_role['title']);
							//Ajout à l'API
							BoppLib::link_user_to_project($project_api_id, $user_api_id, YPProjectLib::$project_team_member_role['slug']);
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
						BoppLib::unlink_user_from_project($project_api_id, $user_api_id, YPProjectLib::$project_team_member_role['slug']);
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
		$team_member_list = BoppLib::get_project_members_by_role($project_api_id, YPProjectLib::$project_team_member_role['slug']);
		foreach ($team_member_list as $team_member) {
			if ($current_user_id == $team_member->wp_user_id) return TRUE;
		}
		
		return FALSE;
	}
}
?>