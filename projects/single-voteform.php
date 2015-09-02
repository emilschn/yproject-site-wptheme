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
			Merci pour votre vote.
			
		<?php else: ?>

			<div class="light">
				<?php
				global $vote_errors;
				if (isset($vote_errors)) { foreach ($vote_errors as $vote_error_message) {
				?>
					<span class="errors"><?php echo $vote_error_message; ?></span><br />
				<?php } } ?>
					
				<form name="ypvote" action="<?php echo get_permalink($post->ID); ?>" method="POST" class="ypvote-form" enctype="multipart/form-data">

					<strong>Impacts et coh&eacute;rence du projet</strong><br />
					<em>Comment &eacute;valuez-vous les <a id="scroll-to-utilite-societale" href="javascript:void(0);">impacts soci&eacute;taux</a> de ce projet (5 &eacute;tant la meilleure note) ?</em><br />
					<ul class="impact-list">
					    <li><span>Economie</span> <?php ypcf_display_impact_select('impact_economy', $impact_economy); ?></li>
					    <li><span>Environnement</span> <?php ypcf_display_impact_select('impact_environment', $impact_environment); ?></li>
					    <li><span>Social</span> <?php ypcf_display_impact_select('impact_social', $impact_social); ?></li>
					    <li><span>Autre</span> <input type="text" name="impact_other" placeholder="Pr&eacute;ciser..." value="<?php if ($impact_other != '') {echo $impact_other;} ?>" /></li>
					</ul>
					
					
					<em>Ces impacts sont-ils suffisants pour que ce projet soit en financement sur WEDOGOOD.co ?</em><br />
					<label><input type="radio" id="btn-validate_project-true" name="validate_project" value="1" <?php if ($validate_project == 1) echo 'checked="checked"'; ?>>Oui</label><br />
					<p id="validate_project-true" <?php if ($validate_project != 1) echo 'style="display: none;"'; ?>>
					    Je serais int&eacute;ress&eacute; pour investir :
					    <input type="text" name="invest_sum" placeholder="0" size="10" value="<?php if ($invest_sum !== false) echo $invest_sum; ?>" />&euro;<br />
					    
					    <?php if ($campaign->funding_type() != 'fundingdonation'): ?>
					    Je pense que le risque est :
					    <select name="invest_risk">
						<option value="0"></option>
						<option value="1" <?php if ($invest_risk == 1) echo 'selected'; ?>>(1) tr&egrave;s faible</option>
						<option value="2" <?php if ($invest_risk == 2) echo 'selected'; ?>>(2) plut&ocirc;t faible</option>
						<option value="3" <?php if ($invest_risk == 3) echo 'selected'; ?>>(3) mod&eacute;r&eacute;</option>
						<option value="4" <?php if ($invest_risk == 4) echo 'selected'; ?>>(4) &eacute;lev&eacute;</option>
						<option value="5" <?php if ($invest_risk == 5) echo 'selected'; ?>>(5) tr&egrave;s &eacute;lev&eacute;</option>
					    </select>
					    <?php endif; ?>
					</p>
					<label><input type="radio" id="btn-validate_project-false" name="validate_project" value="0" <?php if ($validate_project == 0) echo 'checked="checked"'; ?>>Non</label><br />
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