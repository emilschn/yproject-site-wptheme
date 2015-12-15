<?php
global $campaign, $stylesheet_directory_uri;
$campaign_id = $campaign->ID;
$page_invest = get_page_by_path('investir');
$campaign_status = $campaign->campaign_status();
$funding_duration = $campaign->funding_duration();
$firstpayment_date = $campaign->first_payment_date();
$firstpayment_year = mysql2date( 'Y', $firstpayment_date, false );
$estimated_turnover = $campaign->estimated_turnover();
?>
<div class="project-rewards center">
	<div class="project-rewards-title separator-title">
		<span> 
			<?php _e('Contreparties', 'yproject'); ?>
		</span>
	</div>
    
	<div class="project-rewards-content clearfix">
		<?php // CAPITAL // ?>
		<?php if ($campaign->funding_type() == 'fundingdevelopment'): ?>
		<div class="left">
			<img src="<?php echo $stylesheet_directory_uri;?>/images/macarons/macaron-K.png" alt="Capital" />
		</div>
		<div class="left">
			<?php echo $campaign->part_value(); ?> &euro; <?php _e("ou plus", "yproject"); ?><br />
			<?php _e("Recevez une part du capital de cette coop&eacute;rative. Une part :", "yproject"); ?> <?php echo $campaign->part_value(); ?> &euro;.<br />
			<?php _e("(R&eacute;duction d&apos;imp&ocirc;t : 18% IR. Rendement esp&eacute;r&eacute; : X % par an)", "yproject"); ?>
		</div>

	    

		<?php // ROYALTIES // ?>
		<?php elseif ($campaign->funding_type() == 'fundingproject'): ?>
		<div class="left">
			<img src="<?php echo $stylesheet_directory_uri;?>/images/macarons/macaron-R.png" alt="Royalties" />
		</div>
		<div class="left">
			<?php if ($campaign->funding_duration() > 0 && $campaign->roi_percent() > 0 && $firstpayment_year > 2014): ?>
		    
			<?php if ($campaign_status == "collecte"): ?>
			<form method="GET" action="<?php echo get_permalink($page_invest->ID); ?>">
			<?php endif; ?>
			    
				<div class="project-rewards-intro">
				<?php echo __("Recevez tous les trimestres une part du chiffre d&apos;affaires pendant", "yproject"). ' ' .$funding_duration. ' ' .__("ans", "yproject"); ?>
				(<span class="roi_percent_project"><?php echo $campaign->roi_percent(); ?></span>% <?php _e("du CA pour", "yproject"); ?> <span class="roi_goal_project"><?php echo $campaign->minimum_goal(); ?></span>&euro; <?php _e("collect&eacute;s", "yproject"); ?>).<br /><br />
				</div>
				
				<?php _e("Si j'investis :"); ?>
				<input type="text" name="init_invest" class="init_invest" /> &euro; <button class="init_invest_count"><?php _e('Calculer', 'yproject'); ?></button><br /><br />
				
				<?php _e("Je percevrai :"); ?>
				<span class="roi_percent_user">0</span>% <?php _e("du CA, soit :", "yproject"); ?><br />
				
				<div class="align-center">
				<table>
					<tr>
						<?php for ($i = $firstpayment_year; $i < $firstpayment_year + $funding_duration; $i++) : ?>
						<td><?php echo $i; ?><span class="hidden estimated-turnover-<?php echo $i; ?>"><?php echo $estimated_turnover[$i]; ?></span></td>
						<?php endfor; ?>
						<td><?php _e("Total", "yproject"); ?></td>
					</tr>
					<tr>
						<?php for ($i = 0; $i < $funding_duration; $i++) : ?>
						<td><span class="roi_amount_user<?php echo $i; ?>">0</span>&euro;</td>
						<?php endfor; ?>
						<td><span class="roi_amount_user">0</span>&euro;</td>
					</tr>
				</table>
				</div>
				<br /><br />

				<div class="align-center">
					<?php _e("Ces valeurs sont estim&eacute;es selon les pr&eacute;visions du porteur de projet.", "yproject"); ?><br />
					<?php _e("Risque de perte int&eacute;grale de l&apos;investissement. Gain maximum : x2.", "yproject"); ?>
				</div>

			<?php if ($campaign_status == "collecte"): ?>
				<div class="align-center">
					<br /><br />
					<input type="submit" value="<?php _e("Investir", "yproject"); ?>" class="button" />
					<input type="hidden" name="campaign_id" value="<?php echo $campaign_id; ?>" />
					<input type="hidden" name="invest_start" value="1" />
				</div>
			</form>
			<?php endif; ?>
		    
			<?php endif; ?>
		</div>
	    
	    
		<?php // DON // ?>
		<?php elseif ($campaign->funding_type() == 'fundingdonation'): ?>
		<img src="<?php echo $stylesheet_directory_uri;?>/images/macarons/macaron-D.png" alt="Don" />
	    
	    
		<?php endif; ?>
	</div>
</div>