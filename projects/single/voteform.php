<?php
global $campaign;
if (isset($_GET['campaign_id'])) {
	$post = get_post($_GET['campaign_id']);
	$campaign = atcf_get_campaign( $post );
}


$impact_economy_before=3;
$impact_environment_before=3;
$impact_social_before=3;
$impact_other_before='';
$validate_project_before=1;
$invest_sum_before=0;
$more_info_impact_before="";
$more_info_service_before="";
$more_info_team_before="";
$more_info_finance_before=0;
$more_info_other_before='';
$invest_risk_before=3;
$advice_before='';
$share_conseil_before='';

$style_somme='';
$style_risque='';
$style_impacts='';
$style_erreurs='style="display:none;"';

$message_erreur='';

$terme_economie='Moyen';
$terme_environment='Moyen';
$terme_social='Moyen';


if (isset($_GET['vote_check'])&&$_GET['vote_check']==0){
	$style_erreurs='';
	if(isset($_GET['impact_economy']))
		$impact_economy_before=$_GET['impact_economy'];

	if(isset($_GET['impact_environment']))
		$impact_environment_before=$_GET['impact_environment'];
		
	if(isset($_GET['impact_social']))
		$impact_social_before=$_GET['impact_social'];

	if(isset($_GET['impact_other']))
		$impact_other_before=$_GET['impact_other'];

	if(isset($_GET['validate_project']))
		$validate_project_before=$_GET['validate_project'];

	if(isset($_GET['invest_sum']))
		$invest_sum_before=$_GET['invest_sum'];

	if(isset($_GET['more_info_impact'])&&($_GET['more_info_impact']))
		$more_info_impact_before=" checked='checked' ";

	if(isset($_GET['more_info_service'])&&($_GET['more_info_service']))
		$more_info_service_before=" checked='checked' ";

	if(isset($_GET['more_info_team'])&&($_GET['more_info_team']))
		$more_info_team_before=" checked='checked' ";

	if(isset($_GET['more_info_finance'])&&($_GET['more_info_finance']))
		$more_info_finance_before=" checked='checked' ";

	if(isset($_GET['more_info_other']))
		$more_info_other_before=$_GET['more_info_other'];

	if(isset($_GET['invest_risk']))
		$invest_risk_before=$_GET['invest_risk'];

	if(isset($_GET['advice']))
		$advice_before=$_GET['advice'];

	if(isset($_GET['share_conseil'])&&($_GET['share_conseil']))
		$share_conseil_before=" checked='checked' ";

	$message_erreur="Saisie(s) incorrecte(s) : </br>";
	if(isset($_GET['check_risque'])&&($_GET['check_risque']=='')){
		$message_erreur=$message_erreur.'- Le risque est mal indiqué.</br>';
		$style_risque="style='border-style: groove !important; border-color:#EA4F51; border-radius: 5px;'";
	}
	if(isset($_GET['check_somme'])&&($_GET['check_somme']==''))
	{
		$message_erreur=$message_erreur.'- La somme que vous serez prêt à investir est incorrecte.</br>';
		$style_somme="style='border-style: groove !important; border-color:#EA4F51; border-radius: 5px;'";
	}
	if(isset($_GET['check_impacts'])&&($_GET['check_impacts']==''))
	{
		$message_erreur=$message_erreur.'- Les impacts sociaux de ce projet ont été mal remplis.</br>';
		$style_impacts="style='border-style: groove !important; border-color:#EA4F51; border-radius: 5px;'";
	}
	$message_erreur=$message_erreur."Veuillez corriger les champs en rouge.";

	if($impact_economy_before==1)
		$terme_economie='Très faible';
	else if($impact_economy_before==2)
		$terme_economie='Faible';
	else if($impact_economy_before==4)
		$terme_economie='Fort';
	else if($impact_economy_before==5)
		$terme_economie='Très Fort';


	if($impact_environment_before==1)
		$terme_environment='Très faible';
	else if($impact_environment_before==2)
		$terme_environment='Faible';
	else if($impact_environment_before==4)
		$terme_environment='Fort';
	else if($impact_environment_before==5)
		$terme_environment='Très Fort';

	if($impact_social_before==1)
		$terme_social='Très faible';
	else if($impact_social_before==2)
		$terme_social='Faible';
	else if($impact_social_before==4)
		$terme_social='Fort';
	else if($impact_social_before==5)
		$terme_social='Très Fort';

}

?>


<div id="project_vote_zone">
<?php
if ($campaign->end_vote_remaining() > 0) {
	if ( is_user_logged_in() ) :
		function ypcf_display_impact_select($name, $value, $min = 1, $max = 5) {
			?>
			<select name="<?php echo $name; ?>">
				<?php for ($i = $min; $i <= $max; $i++) { ?>
					<option value="<?php echo $i; ?>" <?php if ($i == $value) { ?>selected<?php } ?>><?php echo $i; ?></option>
				<?php } ?>
			</select>
			<?php
		}
	    
	   	$has_voted = false;
		if ( !empty($hasvoted_results[0]->id) ) $has_voted = true;
		
		if ($has_voted): ?>
		<div id="aCliquer_fait">
				<?php _e('Merci pour votre vote', 'yproject'); ?>
		</div>

		<?php else: ?>
				<div id="lightbox">
					<?php 
					$perma = get_permalink($post->ID);
					echo do_shortcode('
					[yproject_lightbox id="vote"]'."
					<!-- phase 1 -->
					<form name='ypvote' action='".$perma."' method='POST' class='ypvote-form_v3' enctype='multipart/form-data'>
					<main class='container'>
						<div id='erreurs' ".$style_erreurs." >
							<span>".$message_erreur."</span>
							<hr color='#EA4F51'>
						</div>
						<div id='phase1'>
							<div class='block1'>
								<strong>Impacts et coh&eacute;rence du projet</strong><br />
								<em>Comment &eacute;valuez-vous les impacts soci&eacute;taux de ce projet ?</em><br />
								<ul class='impact-list' style='list-style-type:none;'>
								    <li><span id='impact_span'>Economie</span>
							    		<input id='note_eco' type='range' min='1' max='5' step='1' id='impact_economy_v3' name='impact_economy' value='".$impact_economy_before."'
											 onkeyup='AfficheRange1(this.value)'
											 onchange='AfficheRange1(this.value)'
							    			".$style_impacts."
							    		>
							    		<span id='valBox1' class='range-slider__value' >".$terme_economie."</span>
									</li>
								    <li><span id='impact_span' >Environnement</span>
							    		<input id='note_environnement' type='range'min='1' max='5' step='1' id='impact_environment_v3' name='impact_environment' value='".$impact_environment_before."'
								    		 onchange='AfficheRange2(this.value)'
	  										 onkeyup='AfficheRange2(this.value)'
	  										 ".$style_impacts."
								    	>
								    	<span id='valBox2' class='range-slider__value' >".$terme_environment."</span>
								    </li>

								    <li><span id='impact_span'>Social</span>
							    		<input id='note_social' type='range'min='1' max='5' step='1' id='impact_social_v3' name='impact_social' value='".$impact_social_before."'
								    		 onchange='AfficheRange3(this.value)'
	  										 onkeyup='AfficheRange3(this.value)'
	  										 ".$style_impacts."
								    	>
							    		<span id='valBox3' class='range-slider__value' >".$terme_social."</span>

								   </li>

								    <li><span id='impact_span'>Autre</span>
								     <input type='text' ".$style_impacts." name='impact_other' id='impact_other_v3' value='".$impact_other_before."'/></li>
								</ul>
								</br>
								<div id='em_impact'>
								<em>Ces impacts sont-ils suffisants pour que ce projet soit en financement sur WEDOGOOD.co ?</em><br />
								</div>
								<div class='radio_validate'>
									<label><input type='radio' id='btn-validate_project-true-v3' name='validate_project' checked='checked' onclick='afficher_div_true();'>
									<span>
									Oui
									</span>
									</label><br />
									<label><input type='radio' id='btn-validate_project-false-v3' name='validate_project' onclick='afficher_div_false();'>
									<span>
									Non
									</span>
									</label><br />
								</div>
							</div>
							<div class='suivant' onclick='masquer_sauf_div2();' style='cursor:pointer;'>
								<img src='".get_stylesheet_directory_uri()."/images/fleche_suivant_possible.png'/>
							</div>
							</br>
						</div>
							<!-- phase 2 -->

						<div id='phase2'>
							<div class='retour'>
								<img src='".get_stylesheet_directory_uri()."/images/fleche_suivant_possible.png' onclick='masquer_sauf_div1();' style='transform: rotate(180deg); cursor:pointer;'/>
							</div>
							<div class='block2'>
								<strong>Remarques</strong><br />
								<em>Avez-vous besoin de plus d&apos;informations concernant l&apos;un des aspects suivants ?</em><br />
								<ul class='more-info-list_v3' style='list-style-type:none;'>
								    <li><label><input type='checkbox' id='more_info_service_v3' name='more_info_service' ".$more_info_service_before." value='1'><span>Le produit / service</span></label></li>
								    <li><label><input type='checkbox' id='more_info_impact_v3' name='more_info_impact' ".$more_info_impact_before." value='1'><span>L&apos;impact soci&eacute;tal</span></label></li>
								    <li><label><input type='checkbox' id='more_info_team_v3' name='more_info_team' ".$more_info_team_before." value='1'><span>La structuration de l&apos;&eacute;quipe</span></label></li>
								    <li><label><input type='checkbox' id='more_info_finance_v3' name='more_info_finance' ".$more_info_finance_before." value='1'><span>Le pr&eacute;visionnel financier</span></label></li>

								    <li>Autre : <input type='text' id='more_info_other_v3' name='more_info_other' placeholder='Pr&eacute;ciser...' value='".$more_info_other_before."' /> </li>
								</ul>
							</div>
							<div class='suivant'>
								<img src='".get_stylesheet_directory_uri()."/images/fleche_suivant_possible.png' onclick='masquer_sauf_div3()' style='cursor:pointer;'/>
							</div>
						</br>
						</div>

							<!-- phase 3 -->

						<div id='phase3' >
							<div class='retour'>
								<img src='".get_stylesheet_directory_uri()."/images/fleche_suivant_possible.png' onclick='masquer_sauf_div2();' style='transform:rotate(180deg); cursor:pointer;'/>
							</div>
							<div class='block3'>
								<div id='validate_project-true'>

								    <?php if (".$campaign->funding_type()." != 'fundingdonation'): ?>
								    Je pense que le risque est : <span id='valBox4' class='range-slider__value' >mod&eacute;r&eacute;</span>
								    </br>
								  	<span> Très faible </span>
								  	<input id='note_risque' type='range'min='1' max='5' step='1'  id='invest_risk_v3' name='invest_risk' value='".$invest_risk_before."'
								    		 onchange='AfficheRange4(this.value)'
	  										 onkeyup='AfficheRange4(this.value)'
	  										 ".$style_risque."
								    >
								    <span> Très élevé </span>
								    <?php endif; ?>
									</br>
									<div id='investir_sum_v3'>
									    Je serais int&eacute;ress&eacute; pour investir :
	    								</br>
									    <input type='text' id='invest_sum_v3' name='invest_sum' value='".$invest_sum_before."' size='10' ".$style_somme." />&euro;<br />
								    </div>
								<br />
								</div>
								    <strong>Conseils</strong><br />
									<em>Quels conseils ou encouragements souhaitez-vous donner au(x) porteur(s) de ce projet ?</em><br />
									<textarea id='advice_v3' type='text' name='advice'>".$advice_before."</textarea><br/><br/>
									<ul class='more-info-list' style='list-style-type:none;'>
									    <li><label><input type='checkbox' name='share_conseil' ".$share_conseil_before."><span>Je veux que mes conseils soient publiés en commentaires.</span></label></li>
									</ul>
								</br>
								<div class='voter'>
									<input type='submit' name='submit_vote' value='Voter' class='voter_submit_v3'/>
								</div>
							</div>

							</br>
						</div>
					</form>	
				".'[/yproject_lightbox]'); 
				?>
			</div>
		<?php endif;?>
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