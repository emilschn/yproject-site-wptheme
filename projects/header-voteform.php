<?php
global $wpdb;
$table_name = $wpdb->prefix . "ypcf_project_votes";

$impact_economy = 1;
$impact_environment = 1;
$impact_social = 1;
$impact_other = '';
$validate_project = -1;
$invest_sum = false;
$invest_risk = false;
$more_info_impact = false;
$more_info_service = false;
$more_info_team = false;
$more_info_finance = false;
$more_info_other = '';
$advice = '';
global $vote_errors;
$vote_errors = array();

if ( is_user_logged_in() && $campaign->end_vote_remaining() > 0 ) {
	if (isset($_POST['submit_vote'])) { 
		$is_vote_valid = true;
		
		//Notes des impacts
		$impact_economy = (isset($_POST[ 'impact_economy' ])) ? intval($_POST[ 'impact_economy' ]) : 0;
		$impact_environment = (isset($_POST[ 'impact_environment' ])) ? intval($_POST[ 'impact_environment' ]) : 0;
		$impact_social = (isset($_POST[ 'impact_social' ])) ? intval($_POST[ 'impact_social' ]) : 0;
		$impact_other = (isset($_POST[ 'impact_other' ])) ? stripslashes(htmlentities($_POST[ 'impact_other' ], ENT_QUOTES | ENT_HTML401)) : '';
		
		//Est-ce que le projet est validé
		$validate_project = (isset($_POST[ 'validate_project' ])) ? intval($_POST[ 'validate_project' ]) : -1;
		if ($validate_project === -1) {
			array_push($vote_errors, 'Vous n&apos;avez pas r&eacute;pondu si les impacts sont suffisants.');
			$is_vote_valid = false;
		}
		if ($validate_project == 1) {
			//Projet validé + Somme pret à investir
			if (isset($_POST[ 'invest_sum' ])) {
				//Si on n'a rien rempli, on considère que c'est 0
				if ($_POST[ 'invest_sum' ] == '') {
					$invest_sum = 0;
					
				//Si la somme n'est pas numérique & supérieure à 0, on affiche une erreur
				} elseif (!is_numeric($_POST[ 'invest_sum' ]) || $_POST[ 'invest_sum' ] < 0) {
					array_push($vote_errors, 'La somme &agrave; investir n&apos;est pas valide.');
					$is_vote_valid = false;
					
				//Sinon c'est ok (on arrondit quand même)
				} else {
					$invest_sum = intval(round($_POST[ 'invest_sum' ]));
				}
			}
			//Projet validé + Risque d'investissement
			$invest_risk = (isset($_POST[ 'invest_risk' ])) ? intval($_POST[ 'invest_risk' ]) : 0;
			if ($invest_risk <= 0) {
				array_push($vote_errors, 'Vous n&apos;avez pas s&eacute;lectionn&eacute; de risque d&apos;investissement.');
				$is_vote_valid = false;
			}
		}

		//Plus d'infos
		$more_info_impact = (isset($_POST[ 'more_info_impact' ])) ? intval($_POST[ 'more_info_impact' ]) : false;
		$more_info_service = (isset($_POST[ 'more_info_service' ])) ? intval($_POST[ 'more_info_service' ]) : false;
		$more_info_team = (isset($_POST[ 'more_info_team' ])) ? intval($_POST[ 'more_info_team' ]) : false;
		$more_info_finance = (isset($_POST[ 'more_info_finance' ])) ? intval($_POST[ 'more_info_finance' ]) : false;
		$more_info_other = (isset($_POST[ 'more_info_other' ])) ? stripslashes(htmlentities($_POST[ 'more_info_other' ], ENT_QUOTES | ENT_HTML401)) : '';
		
		//Conseils
		$advice = (isset($_POST[ 'advice' ])) ? stripslashes(htmlentities($_POST[ 'advice' ], ENT_QUOTES | ENT_HTML401)) : '';

		$user_id = wp_get_current_user()->ID;
		$campaign_id = $campaign->ID;



		// Vérifie si l'utilisateur a deja voté
		$hasvoted_results = $wpdb->get_results( 'SELECT id FROM '.$table_name.' WHERE post_id = '.$campaign_id.' AND user_id = '.$user_id );
		if ( !empty($hasvoted_results[0]->id) ) {
			array_push($vote_errors, 'D&eacutesol&eacute vous avez d&egraveja vot&eacute, merci !');
			
		} else if ($is_vote_valid) {
			//Ajout à la base de données
			$vote_result = $wpdb->insert( $table_name, array ( 
				'user_id'                 => $user_id,
				'post_id'		  => $campaign_id,
				'impact_economy'          => $impact_economy, 
				'impact_environment'      => $impact_environment, 
				'impact_social'           => $impact_social, 
				'impact_other'            => $impact_other, 
				'validate_project'        => $validate_project, 
				'invest_sum'		  => $invest_sum, 
				'invest_risk'		  => $invest_risk, 
				'more_info_impact'        => $more_info_impact, 
				'more_info_service'       => $more_info_service, 
				'more_info_team'          => $more_info_team, 
				'more_info_finance'       => $more_info_finance, 
				'more_info_other'         => $more_info_other, 
				'advice'		  => $advice 
			)); 
			if (!$vote_result) array_push($vote_errors, 'Probl&egrave;me de prise en compte du vote.');


			// Construction des urls utilisés dans les liens du fil d'actualité
			// url d'une campagne précisée par son nom 
			$campaign_url  = get_permalink($post->ID);
			$post_title = $post->post_title;
			$url_campaign = '<a href="'.$campaign_url.'">'.$post_title.'</a>';
			//url d'un utilisateur précis
			$user_display_name      = wp_get_current_user()->display_name;
			$url_profile = '<a href="' . bp_core_get_userlink($user_id, false, true) . '">' . $user_display_name . '</a>';

			bp_activity_add(array (
				'component' => 'profile',
				'type'      => 'voted',
				'action'    => $url_profile.' a voté sur le projet '.$url_campaign
			));
		}
	}

}else if(!is_user_logged_in() && $campaign->end_vote_remaining() > 0){ 
if (isset($_POST['submit_vote'])) {
	?>
		<span class="errors">Vous devez vous connecter pour voter</span><br />
	<?php
	}
}
?>