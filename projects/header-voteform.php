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

$check_impacts=true;
$check_somme = true;
$check_risque = true;

global $vote_errors;
$vote_errors = array();

if ( is_user_logged_in() && $campaign->end_vote_remaining() > 0 ) {
	if (isset($_POST['submit_vote'])) { 
		$is_vote_valid = true;
		

		//Notes des impacts
		$impact_economy = (isset($_POST[ 'impact_economy' ])) ? $_POST[ 'impact_economy' ] : 0;
		$impact_environment = (isset($_POST[ 'impact_environment' ])) ? $_POST[ 'impact_environment' ] : 0;
		$impact_social = (isset($_POST[ 'impact_social' ])) ? $_POST[ 'impact_social' ] : 0;
		$impact_other = (isset($_POST[ 'impact_other' ])) ? stripslashes(htmlentities($_POST[ 'impact_other' ], ENT_QUOTES | ENT_HTML401)) : '';
		
		//Est-ce que le projet est validé
		$validate_project = (isset($_POST[ 'validate_project' ])) ? $_POST[ 'validate_project' ] : -1;
		if ($validate_project === -1) {
			array_push($vote_errors, 'Vous n&apos;avez pas r&eacute;pondu si les impacts sont suffisants.');
			$is_vote_valid = false;
			$check_impacts = false;
		}
		if ($validate_project == 1 || $validate_project="on") {
			//Projet validé + Somme pret à investir
			if (isset($_POST[ 'invest_sum' ])) {
				//Si on n'a rien rempli, on considère que c'est 0
				if ($_POST[ 'invest_sum' ] == '') {
					$invest_sum = 0;
					
				//Si la somme n'est pas numérique & supérieure à 0, on affiche une erreur
				} elseif (!is_numeric($_POST[ 'invest_sum' ]) || $_POST[ 'invest_sum' ] < 0) {
					array_push($vote_errors, 'La somme &agrave; investir n&apos;est pas valide.');
					$is_vote_valid = false;
					$check_somme=false;
				//Sinon c'est ok (on arrondit quand même)
				} else {
					$invest_sum = round($_POST[ 'invest_sum' ]);
				}
			}
			
			if ($campaign->funding_type() != 'fundingdonation') {
			    //Projet validé + Risque d'investissement
			    $invest_risk = (isset($_POST[ 'invest_risk' ])) ? $_POST[ 'invest_risk' ] : 0;
			    if ($invest_risk <= 0) {
				    array_push($vote_errors, 'Vous n&apos;avez pas s&eacute;lectionn&eacute; de risque d&apos;investissement.');
				    $is_vote_valid = false;
				    $check_risque=false;
			    }
			} else {
			    $invest_risk = 1;
			}
		}

		//Plus d'infos
		$more_info_impact = (isset($_POST[ 'more_info_impact' ])) ? $_POST[ 'more_info_impact' ] : false;
		$more_info_service = (isset($_POST[ 'more_info_service' ])) ? $_POST[ 'more_info_service' ] : false;
		$more_info_team = (isset($_POST[ 'more_info_team' ])) ? $_POST[ 'more_info_team' ] : false;
		$more_info_finance = (isset($_POST[ 'more_info_finance' ])) ? $_POST[ 'more_info_finance' ] : false;
		$more_info_other = (isset($_POST[ 'more_info_other' ])) ? stripslashes(htmlentities($_POST[ 'more_info_other' ], ENT_QUOTES | ENT_HTML401)) : '';
		

		//Conseils
		$advice = (isset($_POST[ 'advice' ])) ? stripslashes(htmlentities($_POST[ 'advice' ], ENT_QUOTES | ENT_HTML401)) : '';

		$user_id = wp_get_current_user()->ID;
		$campaign_id = $campaign->ID;
			


		// Vérifie si l'utilisateur a deja voté
		$hasvoted_results = $wpdb->get_results( 'SELECT id FROM '.$table_name.' WHERE post_id = '.$campaign_id.' AND user_id = '.$user_id );
		
		$share_conseil =(isset($_POST[ 'share_conseil' ])) ? $_POST[ 'share_conseil' ] : false;

		if ( !empty($hasvoted_results[0]->id) ) {
			array_push($vote_errors, 'D&eacutesol&eacute vous avez d&egraveja vot&eacute, merci !');
			
		} else if ($is_vote_valid) {
			
			if($share_conseil)
			{
				// procédure pour mettre ce conseil en commentaire du projet
				if(!($advice==''))
				{

					$time = current_time('mysql');
					$current_user = wp_get_current_user();
					$user_name=$current_user->display_name;
					$user_mail=$current_user->user_email;
					$user_url=$current_user->user_url;
					$data = array(
					    'comment_post_ID' => $campaign_id,
					    'comment_author' => $user_name,
					    'comment_author_email' => $user_mail,
					    'comment_author_url' => $user_url,
					    'comment_content' => $advice,
					    'comment_type' => '',
					    'comment_parent' => 0,
					    'user_id' => $user_id,
					    'comment_author_IP' => '',
					    'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
					    'comment_date' => $time,
					    'comment_approved' => 1,
					);//author_ip inutile car l'user est forcément connecté pour voter

					wp_insert_comment($data);				
				}
			}


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
				'advice'		  => $advice,
                                'date'                    => date_format(new DateTime(), 'Y-m-d')
			)); 
			if (!$vote_result) array_push($vote_errors, 'Probl&egrave;me de prise en compte du vote.');
			global $vote_success; $vote_success = TRUE;

			do_action('wdg_delete_cache', array( 
				'project-header-right-'.$campaign_id,
				'project-stats-public-votes-'.$campaign_id
			));

			// Construction des urls utilisés dans les liens du fil d'actualité
			// url d'une campagne précisée par son nom 
			$campaign_url = get_permalink($post->ID);
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
			
			
			if ($validate_project == 1) {
				$table_jcrois = $wpdb->prefix . "jycrois";
				$users = $wpdb->get_results( "SELECT * FROM $table_jcrois WHERE campaign_id = $campaign_id AND user_id=$user_id" );
				if ( empty($users[0]->ID) ) {
					$wpdb->insert( 
						$table_jcrois,
						array(
							'user_id'	=> $user_id,
							'campaign_id'   => $campaign_id
						)
					);
				}
			}

			$campaign_url = get_permalink($post->ID);
			$link=$campaign_url."?vote_check=1";
			wp_redirect($link);
		}else{
			$campaign_url = get_permalink($post->ID);
			$link=$campaign_url."?vote_check=0
			&impact_economy=".$impact_economy."
			&impact_environment=".$impact_environment."
			&impact_social=".$impact_social."
			&impact_other=".$impact_other."
			&validate_project=".$validate_project."
			&invest_sum=".$invest_sum."
			&more_info_impact=".$more_info_impact."
			&more_info_service=".$more_info_service."
			&more_info_team=".$more_info_team."
			&more_info_finance=".$more_info_finance."
			&more_info_other=".$more_info_other."
			&advice=".$advice."
			&check_risque=".$check_risque."
			&check_somme=".$check_somme."
			&check_impacts=".$check_impacts."
			&share_conseil=".$share_conseil."
			&invest_risk=".$invest_risk;
			wp_redirect($link);
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