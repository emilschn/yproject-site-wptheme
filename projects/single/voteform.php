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
$share_advice_before='';

$style_sum='';
$style_risk='';
$style_impacts='';
$style_errors="style='display:none;'";

$style_valBox1_1_before="display:none !important;";
$style_valBox1_2_before="display:none !important;";
$style_valBox1_3_before="display:inline-block !important;";
$style_valBox1_4_before="display:none !important;";
$style_valBox1_5_before="display:none !important;";

$style_valBox2_1_before="display:none !important;";
$style_valBox2_2_before="display:none !important;";
$style_valBox2_3_before="display:inline-block !important;";
$style_valBox2_4_before="display:none !important;";
$style_valBox2_5_before="display:none !important;";

$style_valBox3_1_before="display:none !important;";
$style_valBox3_2_before="display:none !important;";
$style_valBox3_3_before="display:inline-block !important;";
$style_valBox3_4_before="display:none !important;";
$style_valBox3_5_before="display:none !important;";


$style_valBox4_1_before="display:none !important;";
$style_valBox4_2_before="display:none !important;";
$style_valBox4_3_before="display:inline-block !important;";
$style_valBox4_4_before="display:none !important;";
$style_valBox4_5_before="display:none !important;";


$message_errors='';

$term_impact_1="Très faible";
$term_impact_2="Faible";
$term_impact_3="Modéré";
$term_impact_4="Fort";
$term_impact_5="Très fort";

$term_risk_1="très faible";
$term_risk_2="faible";
$term_risk_3="modéré";
$term_risk_4="élevé";
$term_risk_5="très élevé";

$validate_project_checked_true=" checked='checked' ";
$validate_project_checked_false="";
$style_lightbox='';

if (isset($_GET['vote_check'])&&$_GET['vote_check']==0){
	$style_errors='';
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

	if($validate_project_before==0)
	{
		$validate_project_checked_false=" checked='checked' ";
		$validate_project_checked_true="";
	}
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


	if(isset($_GET['share_advice'])&&($_GET['share_advice']))
		$share_advice_before=" checked='checked' ";

	$message_errors="Saisie(s) incorrecte(s) : </br>";
	if(isset($_GET['check_risk'])&&($_GET['check_risk']=='')){
		$message_errors=$message_errors.'- Le risque est mal indiqué.</br>';
		$style_risk="style='border-style: groove !important; border-color:#EA4F51; border-radius: 5px;'";
	}
	if(isset($_GET['check_sum'])&&($_GET['check_sum']==''))
	{
		$message_errors=$message_errors.'- La somme que vous serez prêt à investir est incorrecte.</br>';
		$style_sum="style='border-style: groove !important; border-color:#EA4F51; border-radius: 5px;'";
	}
	if(isset($_GET['check_impacts'])&&($_GET['check_impacts']==''))
	{
		$message_errors=$message_errors.'- Les impacts sociaux de ce projet ont été mal remplis.</br>';
		$style_impacts="style='border-style: groove !important; border-color:#EA4F51; border-radius: 5px;'";
	}
	$message_errors=$message_errors."Veuillez corriger les champs en rouge.";


	$style_valBox1_3_before="display:none;";

	if($impact_economy_before==1)
		$style_valBox1_1_before="display:inline-block !important;";
	else if($impact_economy_before==2)
		$style_valBox1_2_before="display:inline-block !important;";
	else if($impact_economy_before==4)
		$style_valBox1_4_before="display:inline-block !important;";
	else if($impact_economy_before==5)
		$style_valBox1_5_before="display:inline-block !important;";
	else
		$style_valBox1_3_before="display:inline-block !important;";


	$style_valBox2_3_before="display:none !important;";

	if($impact_environment_before==1)
		$style_valBox2_1_before="display:inline-block !important;";
	else if($impact_environment_before==2)
		$style_valBox2_2_before="display:inline-block !important;";
	else if($impact_environment_before==4)
		$style_valBox2_4_before="display:inline-block !important;";
	else if($impact_environment_before==5)
		$style_valBox2_5_before="display:inline-block !important;";
	else
		$style_valBox2_3_before="display:inline-block !important;";


	$style_valBox3_3_before="display:none !important;";

	if($impact_social_before==1)
		$style_valBox3_1_before="display:inline-block !important;";
	else if($impact_social_before==2)
		$style_valBox3_2_before="display:inline-block !important;";
	else if($impact_social_before==4)
		$style_valBox3_4_before="display:inline-block !important;";
	else if($impact_social_before==5)
		$style_valBox3_5_before="display:inline-block !important;";
	else
		$style_valBox3_3_before="display:inline-block !important;";

	$style_valBox4_3_before="display:none !important;";

	if($invest_risk_before==1)
		$style_valBox4_1_before="display:inline-block !important;";
	else if($invest_risk_before==2)
		$style_valBox4_2_before="display:inline-block !important;";
	else if($invest_risk_before==4)
		$style_valBox4_4_before="display:inline-block !important;";
	else if($invest_risk_before==5)
		$style_valBox4_5_before="display:inline-block !important;";
	else
		$style_valBox4_3_before="display:inline-block !important;";

	$style_lightbox="style='display:block !important;'";
}

?>


<div id="project_vote_zone">
<?php
if ($campaign->end_vote_remaining() > 0) {
?>
<div id="lightbox">
	<?php 
	$perma = get_permalink($post->ID);
	echo do_shortcode('[yproject_lightbox id="vote" style="'.$style_lightbox.'"]'."
		<!-- phase 1 -->
		<form name='ypvote' action='".$perma."' method='POST' class='ypvote-form_v3' enctype='multipart/form-data'>
		<main class='container'>
			<div id='errors' ".$style_errors." >
				<span>".$message_errors."</span>
				<hr color='#EA4F51'>
			</div>
			<div id='phase1'>
				<div class='block1'>
					<strong>Impacts et coh&eacute;rence du projet</strong><br />
					<em>Comment &eacute;valuez-vous les impacts soci&eacute;taux de ce projet ?</em><br />
					<ul class='impact-list' style='list-style-type:none;'>
					    <li><span class='impact_span'>Economie</span>
				    		<input id='note_eco' type='range' min='1' max='5' step='1' id='impact_economy_v3' name='impact_economy' value='".$impact_economy_before."'
								 onkeyup='display_range1(this.value)'
								 onchange='display_range1(this.value)'
				    			".$style_impacts."
				    		>
				    		<div class='result_box'>
				    			<span id='valBox1_1' class='range-slider__value' style='".$style_valBox1_1_before."'>".$term_impact_1."</span>
				    			<span id='valBox1_2' class='range-slider__value' style='".$style_valBox1_2_before."'>".$term_impact_2."</span>
				    			<span id='valBox1_3' class='range-slider__value' style='".$style_valBox1_3_before."'>".$term_impact_3."</span>
				    			<span id='valBox1_4' class='range-slider__value' style='".$style_valBox1_4_before."'>".$term_impact_4."</span>
				    			<span id='valBox1_5' class='range-slider__value' style='".$style_valBox1_5_before."'>".$term_impact_5."</span>
				    		</div>
						</li>
					    <li><span class='impact_span' >Environnement</span>
				    		<input id='note_environment' type='range'min='1' max='5' step='1' id='impact_environment_v3' name='impact_environment' value='".$impact_environment_before."'
					    		 onchange='display_range2(this.value)'
									 onkeyup='display_range2(this.value)'
									 ".$style_impacts."
					    	>
						    <div class='result_box'>
					    		<span id='valBox2_1' class='range-slider__value' style='".$style_valBox2_1_before."'>".$term_impact_1."</span>
					    		<span id='valBox2_2' class='range-slider__value' style='".$style_valBox2_2_before."'>".$term_impact_2."</span>
					    		<span id='valBox2_3' class='range-slider__value' style='".$style_valBox2_3_before."'>".$term_impact_3."</span>
					    		<span id='valBox2_4' class='range-slider__value' style='".$style_valBox2_4_before."'>".$term_impact_4."</span>
					    		<span id='valBox2_5' class='range-slider__value' style='".$style_valBox2_5_before."'>".$term_impact_5."</span>
					    	</div>
					    </li>

					    <li><span class='impact_span'>Social</span>
				    		<input id='note_social' type='range'min='1' max='5' step='1' id='impact_social_v3' name='impact_social' value='".$impact_social_before."'
					    		 onchange='display_range3(this.value)'
									 onkeyup='display_range3(this.value)'
									 ".$style_impacts."
					    	>
					    	<div class='result_box'>
					    		<span id='valBox3_1' class='range-slider__value' style='".$style_valBox3_1_before."'>".$term_impact_1."</span>
					    		<span id='valBox3_2' class='range-slider__value' style='".$style_valBox3_2_before."'>".$term_impact_2."</span>
					    		<span id='valBox3_3' class='range-slider__value' style='".$style_valBox3_3_before."'>".$term_impact_3."</span>
					    		<span id='valBox3_4' class='range-slider__value' style='".$style_valBox3_4_before."'>".$term_impact_4."</span>
					    		<span id='valBox3_5' class='range-slider__value' style='".$style_valBox3_5_before."'>".$term_impact_5."</span>
					   		</div>
					   </li>

					    <li>
					    	<span class='impact_span'>Autre</span>
					    	<input type='text' ".$style_impacts." name='impact_other' id='impact_other_v3' value='".$impact_other_before."'/>
							<div class='result_box'/>
					    </li>
					
					</ul>
					</br>
					<div id='em_impact'>
					<em>Ces impacts sont-ils suffisants pour que ce projet soit en financement sur WEDOGOOD.co ?</em><br />
					</div>
					<div class='radio_validate'>
						<label><input type='radio' id='btn-validate_project-true-v3' name='validate_project' value='1' ".$validate_project_checked_true.">
						<span class='span_validate_project'>
						Oui
						</span>
						</label><br />
						<label><input type='radio' id='btn-validate_project-false-v3' name='validate_project' value='0' ".$validate_project_checked_false.">
						<span class='span_validate_project'>
						Non
						</span>
						</label><br />
					</div>
				</div>
				<div class='next' id='hide_except_div2_phase1' style='background-image: url(".get_stylesheet_directory_uri()."/images/fleche_suivant_possible.png);' >
				</div>
				</br>
			</div>
				<!-- phase 2 -->

			<div id='phase2'>
				<div class='return' style='background-image: url(".get_stylesheet_directory_uri()."/images/fleche_suivant_possible.png);' id='hide_except_div1'>
				</div>
				<div class='block2'>
					<div id='validate_project-true'>

					    <?php if (".$campaign->funding_type()." != 'fundingdonation'): ?>
					    Je pense que le risque est :
				    		<span id='valBox4_1' class='range-slider__value' style='".$style_valBox4_1_before."'>".$term_risk_1."</span>
				    		<span id='valBox4_2' class='range-slider__value' style='".$style_valBox4_2_before."'>".$term_risk_2."</span>
				    		<span id='valBox4_3' class='range-slider__value' style='".$style_valBox4_3_before."'>".$term_risk_3."</span>
				    		<span id='valBox4_4' class='range-slider__value' style='".$style_valBox4_4_before."'>".$term_risk_4."</span>
				    		<span id='valBox4_5' class='range-slider__value' style='".$style_valBox4_5_before."'>".$term_risk_5."</span>
					    </br>
					    <div id='choice_risk'>
						  	<span class='span_risk'> Très faible </span>
						  	<input id='note_risk' type='range'min='1' max='5' step='1'  id='invest_risk_v3' name='invest_risk' value='".$invest_risk_before."'
						    		 onchange='display_range4(this.value)'
										 onkeyup='display_range4(this.value)'
										 ".$style_risk."
						    >
						    <span class='span_risk'> Très élevé </span>
					    </div>
					    <?php endif; ?>
						</br>
						<div id='investir_sum_v3'>
						    Je serais int&eacute;ress&eacute; pour investir :
							</br>
						    <input type='text' id='invest_sum_v3' name='invest_sum' value='".$invest_sum_before."' size='10' ".$style_sum." />&euro;<br />
					    </div>
						<br />
					</div>
					<strong>Remarques</strong><br />
					<em>Avez-vous besoin de plus d&apos;informations concernant l&apos;un des aspects suivants ?</em><br />
					<ul class='more-info-list_v3' style='list-style-type:none;'>
					    <li><label><input type='checkbox' id='more_info_service_v3' name='more_info_service' ".$more_info_service_before." value='1'><span class='span_more_info'>Le produit / service</span></label></li>
					    <li><label><input type='checkbox' id='more_info_impact_v3' name='more_info_impact' ".$more_info_impact_before." value='1'><span class='span_more_info'>L&apos;impact soci&eacute;tal</span></label></li>
					    <li><label><input type='checkbox' id='more_info_team_v3' name='more_info_team' ".$more_info_team_before." value='1'><span class='span_more_info' >La structuration de l&apos;&eacute;quipe</span></label></li>
					    <li><label><input type='checkbox' id='more_info_finance_v3' name='more_info_finance' ".$more_info_finance_before." value='1'><span class='span_more_info'>Le pr&eacute;visionnel financier</span></label></li>

					    <li>Autre : <input type='text' id='more_info_other_v3' name='more_info_other' class='span_more_info' placeholder='Pr&eacute;ciser...' value='".$more_info_other_before."' /> </li>
					</ul>
				</div>
				<div class='next' id='hide_except_div3' style='background-image: url(".get_stylesheet_directory_uri()."/images/fleche_suivant_possible.png);'>
				</div>
				</br>
			</div>

				<!-- phase 3 -->

			<div id='phase3' >
				<div class='return' style='background-image: url(".get_stylesheet_directory_uri()."/images/fleche_suivant_possible.png);' id='hide_except_div2_phase3' >
				</div>
				<div class='block3'>
					    <strong>Conseils</strong><br />
						<em>Quels conseils ou encouragements souhaitez-vous donner au(x) porteur(s) de ce projet ?</em><br />
						<textarea id='advice_v3' type='text' name='advice'>".$advice_before."</textarea><br/><br/>
						<ul class='more-info-list' id='more-info-share' style='list-style-type:none;'>
						    <li><label><input type='checkbox' name='share_advice' ".$share_advice_before."><span class='span_share_advice'>Je veux que mes conseils soient publiés en commentaires.</span></label></li>
						</ul>
					</br>
					<div class='vote'>
						<input type='submit' name='submit_vote' value='Voter' class='vote_submit_v3'/>
					</div>
				</div>
				</br>
			</div>
			<div class='frise'>
				<input type='button' id='go-block1' class='frise_input'/>
				<input type='button' id='go-block2' class='frise_input'/>
				<input type='button' id='go-block3' class='frise_input'/>
			</div>
		</form>".'[/yproject_lightbox]'); 
?>
</div>
<?php
}
?>
    
</div>


