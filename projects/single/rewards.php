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
			<?php // CAPITAL // ?>
			<?php if ($campaign->funding_type() == 'fundingproject'): ?>
				<?php _e('Retour sur investissement', 'yproject'); ?>
			<?php else: ?>
				<?php _e('Contreparties', 'yproject'); ?>
			<?php endif; ?>
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
		
		<input type="hidden" id="roi_percent_project" value="<?php echo $campaign->roi_percent_estimated(); ?>" />
		<input type="hidden" id="roi_goal_project" value="<?php echo $campaign->goal(false); ?>" />
		<?php if ($campaign_status == "collecte"): ?>
		<form method="GET" action="<?php echo get_permalink($page_invest->ID); ?>">
		<?php endif; ?>
			
			<div class="project-rewards-padder">
				<div class="left">
					<img src="<?php echo $stylesheet_directory_uri;?>/images/macarons/macaron-R.png" alt="Royalties" />
				</div>

				<div class="left align-center">
					<?php if ($campaign->funding_duration() > 0 && $campaign->roi_percent_estimated() > 0 && $firstpayment_year > 2014): ?>

						<?php _e("Si j'investis :", 'yproject'); ?>
						<input type="text" name="init_invest" class="init_invest" /> &euro; <button class="init_invest_count button"><?php _e('Calculer', 'yproject'); ?></button><br />

						<?php if (is_user_logged_in()): ?>
							<div>
								<table>
									<tr>
										<?php $max_turnover = max($estimated_turnover); ?>
										<?php foreach ($estimated_turnover as $i => $value) ://for ($i = $firstpayment_year; $i < $firstpayment_year + $funding_duration; $i++) : ?>
										<?php $height = 100 - round($value / $max_turnover * 100); ?>
										<td>
											<div><div style="height: <?php echo $height; ?>%;"></div></div>
											<?php echo $i; ?><span class="hidden estimated-turnover-<?php echo $i; ?>"><?php echo $value; ?></span>
										</td>
										<?php endforeach; ?>
									</tr>
									<tr>
										<?php for ($i = 0; $i < $funding_duration; $i++) : ?>
										<td><span class="roi_amount_user<?php echo $i; ?>">0</span>&euro;</td>
										<?php endfor; ?>
									</tr>
								</table>
							</div>

							<?php _e("Je recevrai", 'yproject'); ?> <span class="roi_amount_user">0</span> &euro;* <br />
							<?php _e("soit", 'yproject'); ?> <span class="roi_percent_user">0</span>% <?php _e("du chiffre d'affaire vers&eacute; tous les trimestres.", 'yproject'); ?><br />
						
						<?php else: ?>
							<div class="hidden">
								<p>
									<?php _e("Afin de r&eacute;pondre aux recommandations des autorit&eacute;s financi&egrave;res sur la pr&eacute;vention du risque repr&eacute;sent&eacute; par l&apos;investissement participatif,", 'yproject'); ?><br />
									<?php _e("vous devez &ecirc;tre inscrit et connect&eacute; pour acc&eacute;der à la totalit&eacute; du projet.", 'yproject'); ?>
								</p>
								<a href="#register" id="register" class="wdg-button-lightbox-open button" data-lightbox="register" data-redirect="<?php echo get_permalink(); ?>"><?php _e("Inscription", 'yproject'); ?></a>
								<a href="#connexion" id="connexion" class="wdg-button-lightbox-open button" data-lightbox="connexion" data-redirect="<?php echo get_permalink(); ?>"><?php _e("Connexion", 'yproject'); ?></a>
							</div>
						
						<?php endif; ?>

					<?php endif; ?>
				</div>
				<div class="clear"></div>
			</div>

		<?php if (is_user_logged_in()): ?>
			<div class="project-rewards-alert">
				<?php _e("Rendement vis&eacute; :", "yproject"); ?> 
				<span class="info-user">
					<span class="roi_percent_average">...</span><?php _e("% par an en moyenne*", 'yproject'); ?>
					(<?php _e("soit", 'yproject'); ?> <span class="roi_ratio_on_total">...</span> <?php echo __("en", 'yproject'). ' '. $funding_duration. ' ' .__("ans", "yproject"); ?>)</span><br />

				<span class="small-alert">* <?php echo sprintf( __("Ces valeurs sont estim&eacute;es selon les pr&eacute;visions du porteur de projet. Risque de perte int&eacute;grale de l&apos;investissement. Gain maximum : x%s.", "yproject"), $campaign->maximum_profit() ); ?></span>
			</div>

			<?php if ($campaign_status == "collecte"): ?>
				<div class="align-center">
					<br /><br />
					<input type="submit" value="<?php _e("Investir", "yproject"); ?>" class="button red" />
					<input type="hidden" name="campaign_id" value="<?php echo $campaign_id; ?>" />
					<input type="hidden" name="invest_start" value="1" />
				</div>
			</form>
			<?php endif; ?>
		<?php endif; ?>
	    
	    
		<?php // DON // ?>
		<?php elseif ($campaign->funding_type() == 'fundingdonation'): ?>
		<img src="<?php echo $stylesheet_directory_uri;?>/images/macarons/macaron-D.png" alt="Don" />
	    
	    
		<?php endif; ?>
	</div>
</div>