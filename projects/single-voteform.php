<?php
if (isset($_GET['campaign_id'])) {
	$post = get_post($_GET['campaign_id']);
	$campaign = atcf_get_campaign( $post );
}

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
					$invest_sum = round($_POST[ 'invest_sum' ]);
				}
			}
			//Projet validé + Risque d'investissement
			$invest_risk = (isset($_POST[ 'invest_risk' ])) ? $_POST[ 'invest_risk' ] : 0;
			if ($invest_risk <= 0) {
				array_push($vote_errors, 'Vous n&apos;avez pas s&eacute;lectionn&eacute; de risque d&apos;investissement.');
				$is_vote_valid = false;
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
	
        $hasvoted_results = $wpdb->get_results( 'SELECT id FROM '.$table_name.' WHERE post_id = '.$campaign->ID.' AND user_id = '.wp_get_current_user()->ID );
        $has_voted = false;
	if ( !empty($hasvoted_results[0]->id) ) $has_voted = true;
} else {
	if (isset($_POST['submit_vote'])) {
	?>
		<span class="errors">Vous devez vous connecter pour voter</span><br />
	<?php
	}
}
?>
		
<div id="project_vote_link" class="dark">Voter</div>

<div id="project_vote_zone">
    
<?php
if ($campaign->end_vote_remaining() > 0) {
	if ( is_user_logged_in() ) :
		function displayImpactSelect($name, $value, $min = 1, $max = 5) {
			?>
			<select name="<?php echo $name; ?>">
				<?php for ($i = $min; $i <= $max; $i++) { ?>
					<option value="<?php echo $i; ?>" <?php if ($i == $value) { ?>selected<?php } ?>><?php echo $i; ?></option>
				<?php } ?>
			</select>
			<?php
		}
	    
		if ($has_voted): ?>
			Merci pour votre vote.
			
		<?php else: ?>

			<div class="light">
				<?php
				foreach ($vote_errors as $vote_error_message) {
				?>
					<span class="errors"><?php echo $vote_error_message; ?></span><br />
				<?php
				}
				?>
					
				<form name="ypvote" action="<?php echo get_permalink($post->ID); ?>" method="POST" class="ypvote-form" enctype="multipart/form-data">

					<strong>Impacts et coh&eacute;rence du projet</strong><br />
					<em>Comment &eacute;valuez-vous les impacts soci&eacute;taux de ce projet ?</em><br />
					<ul class="impact-list">
					    <li><span>Economie</span> <?php displayImpactSelect('impact_economy', $impact_economy); ?></li>
					    <li><span>Environnement</span> <?php displayImpactSelect('impact_environment', $impact_environment); ?></li>
					    <li><span>Social</span> <?php displayImpactSelect('impact_social', $impact_social); ?></li>
					    <li><span>Autre</span> <input type="text" name="impact_other" placeholder="Pr&eacute;ciser..." value="<?php if ($impact_other != '') {echo $impact_other;} ?>" /></li>
					</ul>
					
					
					<em>Ces impacts sont-ils suffisants pour que ce projet soit en financement sur WEDOGOOD.co ?</em><br />
					<input type="radio" id="btn-validate_project-true" name="validate_project" value="1" <?php if ($validate_project == 1) echo 'checked="checked"'; ?>>Oui<br />
					<p id="validate_project-true" <?php if ($validate_project != 1) echo 'style="display: none;"'; ?>>
					    Je serais pr&ecirc;t &agrave; investir :
					    <input type="text" name="invest_sum" placeholder="0" size="10" value="<?php if ($invest_sum !== false) echo $invest_sum; ?>" />&euro;<br />
					    
					    Je pense que le risque est :
					    <select name="invest_risk">
						<option value="0"></option>
						<option value="1" <?php if ($invest_risk == 1) echo 'selected'; ?>>(1) tr&egrave;s faible</option>
						<option value="2" <?php if ($invest_risk == 2) echo 'selected'; ?>>(2) plut&ocirc;t faible</option>
						<option value="3" <?php if ($invest_risk == 3) echo 'selected'; ?>>(3) mod&eacute;r&eacute;</option>
						<option value="4" <?php if ($invest_risk == 4) echo 'selected'; ?>>(4) &eacute;lev&eacute;</option>
						<option value="5" <?php if ($invest_risk == 5) echo 'selected'; ?>>(5) tr&egrave;s &eacute;lev&eacute;</option>
					    </select>
					</p>
					<input type="radio" id="btn-validate_project-false" name="validate_project" value="0" <?php if ($validate_project == 0) echo 'checked="checked"'; ?>>Non<br />
					<p id="validate_project-false" <?php if ($validate_project != 0) echo 'style="display: none;"'; ?>>
					    <em>Si le projet recueille une majorit&eacute; de non, il ne pourra pas &ecirc;tre financ&eacute; sur le site et ne pourra se repr&eacute;senter au vote avant 3 mois.</em>
					</p><br />
					
					<strong>Remarques</strong><br />
					<em>Avez-vous besoin de plus d&apos;informations concernant l&apos;un des aspects suivants ?</em><br />
					<ul class="more-info-list">
					    <li><label><input type="checkbox" name="more_info_impact" value="1" <?php if ($more_info_impact) echo 'checked="checked"'; ?>>L&apos;impact soci&eacute;tal</label></li>
					    <li><label><input type="checkbox" name="more_info_service" value="1" <?php if ($more_info_service) echo 'checked="checked"'; ?>>Le produit/service</label></li>
					    <li><label><input type="checkbox" name="more_info_team" value="1" <?php if ($more_info_team) echo 'checked="checked"'; ?>>La structuration de l&apos;&eacute;quipe</label></li>
					    <li><label><input type="checkbox" name="more_info_finance" value="1" <?php if ($more_info_finance) echo 'checked="checked"'; ?>>Le pr&eacute;visionnel financier</label></li>

					    <li>Autre : <input type="text" name="more_info_other" placeholder="Pr&eacute;ciser..." <?php if ($more_info_other != "" && $more_info_other != false) echo 'value="'.$more_info_other.'"'; ?>/> </li>
					</ul><br />
					

					<strong>Conseils</strong><br />
					<em>Quels conseils ou encouragements souhaitez-vous donner au(x) porteur(s) de ce projet ?</em><br />
					<textarea type="text" name="advice"><?php if ($advice != "" && $advice != false) echo $advice; ?></textarea><br /><br />

					<em>Vous pouvez poser vos questions sur le forum du projet.</em><br /><br />
					
					<input type="submit" name="submit_vote" value="Voter" />
				</form>
			</div>
		<?php endif;
	else :
	    ?>
	    <span class="errors">Vous devez vous connecter pour voter.</span>
	    <?php
	endif;
	
} else {
?>
	<span id="tab-end-vote">
		Les votes sont cl&ocirc;tur&eacute;s pour ce projet, merci
	</span>
<?php
}
?>
    
</div>