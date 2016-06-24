<?php
global $campaign;
if (isset($_GET['campaign_id'])) {
	$post = get_post($_GET['campaign_id']);
	$campaign = atcf_get_campaign( $post );
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

			<script type="text/javascript">
			  	var phase=0;
				function masquer_div(idok,id1,id2)
				{
					if(idok=='phase1'){
		  				if (1>phase)
		  				{
							document.getElementById(idok).className = "left";
		  				}else{
							document.getElementById(idok).className = "right";
		  				}
		  				phase=1;
					}
					if(idok=='phase2'){
		  				if (2>phase)
		  				{
							document.getElementById(idok).className = "left";
		  				}else{
							document.getElementById(idok).className = "right";
		  				}
		  				phase=2;
					}
					if(idok=='phase3'){
		  				if (3>phase)
		  				{
							document.getElementById(idok).className = "left";
		  				}else{
							document.getElementById(idok).className = "right";
		  				}
		  				phase=3;		  				
					}
			       document.getElementById(idok).style.display = 'block';
			       document.getElementById(id1).style.display = 'none';
			       document.getElementById(id2).style.display = 'none';

				}
				function masquer_sauf_div1()
				{
					masquer_div('phase1','phase2','phase3');
				}
				function masquer_sauf_div2()
				{
					masquer_div('phase2','phase1','phase3');
				}
				function masquer_sauf_div3()
				{
					masquer_div('phase3','phase1','phase2');
				}
				function afficher_div_true()
				{
					document.getElementById('validate_project-true').style.display = 'block';
					document.getElementById('validate_project-false').style.display = 'none';

				}
				function afficher_div_false()
				{
					document.getElementById('validate_project-true').style.display = 'block';
					document.getElementById('validate_project-false').style.display = 'none';

				}

				document.getElementById('aCliquer').get(0).onClick();
			
			function AfficheRange1(newVal){
 				document.getElementById('valBox1').innerHTML=newVal+" / 5";
 			}

			function AfficheRange2(newVal){
 				document.getElementById('valBox2').innerHTML=""+newVal+" / 5";
 			}


			function AfficheRange3(newVal){
 				document.getElementById('valBox3').innerHTML=""+newVal+" / 5";
 			}

			function AfficheRange4(newVal){
				if (newVal == 1){
					var resultat = 'tr&egrave;s faible';
				}
				if (newVal == 2){
					var resultat = 'plut&ocirc;t faible';
				}					
				if (newVal == 3){
					var resultat = 'mod&eacute;r&eacute;';
				}	
				if (newVal == 4){
					var resultat = '&eacute;lev&eacute;';
				}
				if (newVal == 5){
					var resultat = 'tr&egrave;s &eacute;lev&eacute;';
				}
				document.getElementById('valBox4').innerHTML=resultat;
  			}
 			</script>
				<div id="lightbox">
					<?php 
					$perma = get_permalink($post->ID);
					echo do_shortcode('
					[yproject_lightbox id="vote"]'."
					<!-- phase 1 -->
					<form name='ypvote' action='".$perma."' method='POST' class='ypvote-form_v3' enctype='multipart/form-data'>
					<main class='container'>
						<div id='phase1' onchange='avancement(".'1,"phase1"'.")'>
							<div class='block1'>
								<strong>Impacts et coh&eacute;rence du projet</strong><br />
								<em>Comment &eacute;valuez-vous les impacts soci&eacute;taux de ce projet ?</em><br />
								<ul class='impact-list' style='list-style-type:none;'>
								    <li><span id='impact_span'>Economie</span>
								    </br>
							    		<input id='note_eco' type='range' min='1' max='5' step='1' id='impact_economy_v3' name='impact_economy' value='3'
											 onkeyup='AfficheRange1(this.value)'
											 onchange='AfficheRange1(this.value)'
							    		>
							    		<span id='valBox1' class='range-slider__value' >3 / 5</span>
									</li>
								    <li><span id='impact_span' >Environnement</span>
							    		</br>
							    		<input id='note_environnement' type='range'min='1' max='5' step='1' id='impact_environment_v3' name='impact_environment' value='3'
								    		 onchange='AfficheRange2(this.value)'
	  										 onkeyup='AfficheRange2(this.value)'
								    	>
								    	<span id='valBox2' class='range-slider__value' >3 / 5</span>
								    </li>

								    <li><span id='impact_span'>Social</span>
							    		</br>
							    		<input id='note_social' type='range'min='1' max='5' step='1' id='impact_social_v3' name='impact_social' value='3'
								    		 onchange='AfficheRange3(this.value)'
	  										 onkeyup='AfficheRange3(this.value)'
								    	>
							    		<span id='valBox3' class='range-slider__value' >3 / 5</span>

								   </li>

								    <li><span id='impact_span'>Autre</span>
									</br>
								     <input type='text' name='impact_other' placeholder='Pr&eacute;ciser...' value=''/></li>
								</ul>
								</br>
								<div id='em_impact'>
								<em>Ces impacts sont-ils suffisants pour que ce projet soit en financement sur WEDOGOOD.co ?</em><br />
								</div>
								<div class='radio_validate'>
									<label><input type='radio' id='btn-validate_project-true' id='validate_project_v3' name='validate_project' checked='checked' value='1' onclick='afficher_div_true();'>
									<span>
									Oui
									</span>
									</label><br />
									<label><input type='radio' id='btn-validate_project-false' id='validate_project_v3' name='validate_project' value='0' onclick='afficher_div_false();'>
									<span>
									Non
									</span>
									</label><br />
								</div>
							</div>
							<div class='suivant'>
								<img src='".get_stylesheet_directory_uri()."/images/fleche_suivant_possible.png' onclick='masquer_sauf_div2();' style='cursor:pointer;'/>
							</div>
							</br>
						</div>
							<!-- phase 2 -->

						<div id='phase2'  onchange='avancement(".'1,"phase1"'.")'>
							<div class='retour'>
								<img src='".get_stylesheet_directory_uri()."/images/fleche_suivant_possible.png' onclick='masquer_sauf_div1();' style='transform: rotate(180deg); cursor:pointer;'/>
							</div>
							<div class='block2'>
								<strong>Remarques</strong><br />
								<em>Avez-vous besoin de plus d&apos;informations concernant l&apos;un des aspects suivants ?</em><br />
								<ul class='more-info-list_v3' style='list-style-type:none;'>
								    <li><label><input type='checkbox' id='more_info_service_v3' name='more_info_service' value='1'><span>Le produit / service</span></label></li>
								    <li><label><input type='checkbox' id='more_info_impact_v3' name='more_info_impact' value='1'><span>L&apos;impact soci&eacute;tal</span></label></li>
								    <li><label><input type='checkbox' id='more_info_team_v3' name='more_info_team' value='1'><span>La structuration de l&apos;&eacute;quipe</span></label></li>
								    <li><label><input type='checkbox' id='more_info_finance_v3' name='more_info_finance' value='1'><span>Le pr&eacute;visionnel financier</span></label></li>

								    <li>Autre : <input type='text' id='more_info_other_v3' name='more_info_other' placeholder='Pr&eacute;ciser...'/> </li>
								</ul>
							</div>
							<div class='suivant'>
								<img src='".get_stylesheet_directory_uri()."/images/fleche_suivant_possible.png' onclick='masquer_sauf_div3()' style='cursor:pointer;'/>
							</div>
						</br>
						</div>

							<!-- phase 3 -->

						<div id='phase3'  onchange='avancement(".'1,"phase1"'.")' >
							<div class='retour'>
								<img src='".get_stylesheet_directory_uri()."/images/fleche_suivant_possible.png' onclick='masquer_sauf_div2();' style='transform:rotate(180deg); cursor:pointer;'/>
							</div>
							<div class='block3'>
								<div id='validate_project-true'>

								    <?php if (".$campaign->funding_type()." != 'fundingdonation'): ?>
								    Je pense que le risque est : <span id='valBox4' class='range-slider__value' >mod&eacute;r&eacute;</span>
								    </br>
									<img class='smiley' src='".get_stylesheet_directory_uri()."/images/bonhomme_content.png' />
								  	<input id='note_risque' type='range'min='1' max='5' step='1'  id='invest_risk_v3' name='invest_risk' value='3'
								    		 onchange='AfficheRange4(this.value)'
	  										 onkeyup='AfficheRange4(this.value)'
								    >
									<img class='smiley' src='".get_stylesheet_directory_uri()."/images/bonhomme_pas_content.png' />
								    <?php endif; ?>
									</br>
									<div id='investir_sum_v3'>
									    Je serais int&eacute;ress&eacute; pour investir :
	    								</br>
									    <input type='text' id='invest_sum_v3' name='invest_sum' placeholder='0' size='10' />&euro;<br />
								    </div>
								<br />
								</div>
								    <strong>Conseils</strong><br />
									<em>Quels conseils ou encouragements souhaitez-vous donner au(x) porteur(s) de ce projet ?</em><br />
									<textarea id='area_conseil' id='advice_v3' type='text' name='advice'></textarea><br/><br/>
									<ul class='more-info-list' style='list-style-type:none;'>
									    <li><label><input type='checkbox' name='share_conseil' value='1'><span>Cliquez ICI si vous voulez publier vos conseils en commentaire du projet.</span></label></li>
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