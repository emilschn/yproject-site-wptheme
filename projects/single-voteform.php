<?php
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
	    
		if ($has_voted): ?>
			<div class="timeout-lightbox wdg-lightbox" data-duration="10000000000000000">
				<div class="wdg-lightbox-click-catcher"></div>
				<div class="wdg-lightbox-padder align-center">
				    Merci pour votre vote !<br />
				    Prochaine &eacute;tape : le financement du projet.<br />
				    L&apos;aventure ne fait que commencer : parlez-en autour de vous !
					</br>
						<a id="share-btn-a" href="javascript:WDGProjectPageFunctions.share_btn_click();">
							<div id="share-btn-div" style="
								filter: invert(1);
						        -webkit-filter: invert(1);
						        -moz-filter: invert(1);
						        -o-filter: invert(1);
						        -ms-filter: invert(1);
						        "
						        class="stats_btn">
								<p id="share-txt">Partager</p>	
							</div>
						</a>
				</div>
			</div>
			
		<?php else: ?>

			<script type="text/javascript">
				function masquer_div(idok,id1,id2)
				{
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
 				document.getElementById('valBox1').innerHTML=""+newVal+" sur 5";
 			}

			function AfficheRange2(newVal){
 				document.getElementById('valBox2').innerHTML=""+newVal+" sur 5";
 			}


			function AfficheRange3(newVal){
 				document.getElementById('valBox3').innerHTML=""+newVal+" sur 5";
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


				<p>

					<a href="#lightbox_voter" id="aCliquer" class="wdg-button-lightbox-open" data-lightbox="vote" 
					style=""
					onclick="masquer_sauf_div1();"
					>
					<?php _e('Je crois en ce projet !', 'yproject'); ?></a>
				</p>
				<div id="lightbox">
					<?php 
					$bool=false;
					$perma = get_permalink($post->ID);
					echo do_shortcode('
					[yproject_lightbox id="vote"]'."
					<!-- phase 1 -->
					<form name='ypvote' action='".$perma."' method='POST' class='ypvote-form' enctype='multipart/form-data'>

						<div id='phase1'>
							<strong>Impacts et coh&eacute;rence du projet</strong><br />
							<em>Comment &eacute;valuez-vous les <a id='scroll-to-utilite-societale' href='javascript:void(0);'>impacts soci&eacute;taux</a> de ce projet (5 &eacute;tant la meilleure note) ?</em><br />
							<ul class='impact-list' style='list-style-type:none;'>
							    <li><span>Economie <span id='valBox1'>3 sur 5</span></span>
						    		
						    		<input id='note_eco' type='range' min='1' max='5' step='1' name='impact_economy' value='3'
						    		 onchange='AfficheRange1(this.value)'
										 onkeyup='AfficheRange1(this.value)'
						    		>
								</li>
							    <li><span>Environnement<span id='valBox2'>3 sur 5</span></span>
						    		<input id='note_environnement' type='range'min='1' max='5' step='1' name='impact_environment' value='3'
							    		 onchange='AfficheRange2(this.value)'
  										 onkeyup='AfficheRange2(this.value)'
							    	>
							    </li>

							    <li><span>Social<span id='valBox3'>3 sur 5</span></span>
						    		<input id='note_social' type='range'min='1' max='5' step='1' name='impact_social' value='3'
							    		 onchange='AfficheRange3(this.value)'
  										 onkeyup='AfficheRange3(this.value)'
							    	>
							   </li>

							    <li><span>Autre</span> <input type='text' name='impact_other' placeholder='Pr&eacute;ciser...' value=''/></li>
							</ul>
							
							<em>Ces impacts sont-ils suffisants pour que ce projet soit en financement sur WEDOGOOD.co ?</em><br />
							<label><input type='radio' id='btn-validate_project-true' name='validate_project' value='1' onclick='afficher_div_true();'>Oui</label><br />
							<label><input type='radio' id='btn-validate_project-false' name='validate_project' value='0' onclick='afficher_div_false();'>Non</label><br />
							<label>
								<button type='button' id='phase' onclick='masquer_sauf_div2();'> Suivant </button>
							 </label>
							</br>
						</div>
							<!-- phase 2 -->

						<div id='phase2'>

							<strong>Remarques</strong><br />
							<em>Avez-vous besoin de plus d&apos;informations concernant l&apos;un des aspects suivants ?</em><br />
							<ul class='more-info-list' style='list-style-type:none;'>
							    <li><label><input type='checkbox' name='more_info_service' value='1'>Le produit/service</label></li>
							    <li><label><input type='checkbox' name='more_info_impact' value='1'>L&apos;impact soci&eacute;tal</label></li>
							    <li><label><input type='checkbox' name='more_info_team' value='1'>La structuration de l&apos;&eacute;quipe</label></li>
							    <li><label><input type='checkbox' name='more_info_finance' value='1'>Le pr&eacute;visionnel financier</label></li>

							    <li>Autre : <input type='text' name='more_info_other' placeholder='Pr&eacute;ciser...'/> </li>
							</ul>

							<label>
								<button button type='button' id='phase' onclick='masquer_sauf_div1();'> Retour </button>
								<button button type='button' id='phase' onclick='masquer_sauf_div3()'> Suivant </button>
							</label>

							</br>
						</div>

							<!-- phase 3 -->

						<div id='phase3'>


							<p id='validate_project-true'>
							    Je serais int&eacute;ress&eacute; pour investir :
							    <input type='text' name='invest_sum' placeholder='0' size='10' />&euro;<br />
							    
							    <?php if (".$campaign->funding_type()." != 'fundingdonation'): ?>
							    Je pense que le risque est :
							    <span id='valBox4'>mod&eacute;r&eacute;</span>
							  	<input id='note_environnement' type='range'min='1' max='5' step='1' name='invest_risk' value='3'
							    		 onchange='AfficheRange4(this.value)'
  										 onkeyup='AfficheRange4(this.value)'
							    >
							    <?php endif; ?>
							</p>
							<p id='validate_project-false'>
							    <em>Si le projet recueille une majorit&eacute; de non, il ne pourra pas &ecirc;tre financ&eacute; sur le site et ne pourra se repr&eacute;senter au vote avant 3 mois.</em>
							</p><br />

							    <strong>Conseils</strong><br />
								<em>Quels conseils ou encouragements souhaitez-vous donner au(x) porteur(s) de ce projet ?</em><br />
								<textarea type='text' name='advice'></textarea><br/><br/>

								<em>Vous pouvez poser vos questions sur le forum du projet.</em><br /><br />

							<br />

								<button button type='button' id='phase' onclick='masquer_sauf_div2();'> Retour </button>
								<input type='submit' name='submit_vote' value='Voter' />
							</br>
						</div>
					</form>	
				".'[/yproject_lightbox]'); 
				?>
			</div>
		<?php endif;?>


		<?php
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