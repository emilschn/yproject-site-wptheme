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
							$user_wp_id = '';
							if ($user_by_login !== FALSE) $user_wp_id = $user_by_login->ID;
							else if ($user_by_mail !== FALSE) $user_wp_id = $user_by_mail->ID;
							$user_api_id = BoppLibHelpers::get_api_user_id($user_wp_id);
							$project_api_id = BoppLibHelpers::get_api_project_id($_GET['campaign_id']);
							//TODO : Enregistrer dans l'API
							$buffer = TRUE;
						}
					} else {
						$buffer = 'Merci de renseigner un identifiant ou un email.';
					}
					break;
				    
				case 'yproject-remove-member':
					if (!isset($_GET['user_to_remove'])) {
						$buffer = 'BUMP';
					} else {
						$user_api_id = BoppLibHelpers::get_api_user_id($_GET['user_to_remove']);
						$project_api_id = BoppLibHelpers::get_api_project_id($_GET['campaign_id']);
						//TODO : Supprimer dans l'API
						$buffer = TRUE;
					}
					break;
			}
		}
		return $buffer;
	}
}
?>