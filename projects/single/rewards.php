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
<div class="project-rewards padder">
	<h2 class="standard">
		<?php // CAPITAL // ?>
		<?php if ($campaign->funding_type() == 'fundingproject'): ?>
			/ <?php _e('Retour sur investissement', 'yproject'); ?> /
		<?php else: ?>
			/ <?php _e('Contreparties', 'yproject'); ?> /
		<?php endif; ?>
	</h2>
    
	<div class="project-rewards-content">
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
		<?php if (is_user_logged_in() && $campaign_status == ATCF_Campaign::$campaign_status_collecte): ?>
		<form method="GET" action="<?php echo get_permalink($page_invest->ID); ?>">
		<?php endif; ?>
			
			<div class="project-rewards-padder align-center">
				<?php if ($campaign->funding_duration() > 0 && $campaign->roi_percent_estimated() > 0 && $firstpayment_year > 2014): ?>

					<p class="half-form-count">
						<span class="uppercase"><?php _e("Si j'investis :", 'yproject'); ?></span>
						<input type="text" name="init_invest" class="init_invest" />
						<span class="input-suffix">&euro;</span>
						<button class="init_invest_count button blue"><?php _e('Calculer', 'yproject'); ?></button>
					</p>

					<?php if (is_user_logged_in()): ?>

						<span class="uppercase"><?php _e("Je recevrai", 'yproject'); ?></span> <span class="roi_amount_user">0</span><span> &euro;* </span><br />
						<?php _e("soit", 'yproject'); ?> <span class="roi_percent_user">0</span> % <?php _e("du chiffre d'affaires vers&eacute; tous les trimestres.", 'yproject'); ?><br />
                                                
						<div>
							<table>
								<tr>
									<?php $index = 0; $max_turnover = max( max($estimated_turnover), 1 ); ?>
									<?php foreach ($estimated_turnover as $i => $value): ?>
									<?php $height = 100 - round($value / $max_turnover * 100); ?>
									<td>
										<div><div style="height: <?php echo $height; ?>%;"><span class="roi_amount_user_container"><span class="roi_amount_user<?php echo $index; ?>">0</span> &euro;</span></div></div>
                                                                                <div class="roi_amount_base"></div>
										<?php echo $i; ?><span class="hidden estimated-turnover-<?php echo $i; ?>"><?php echo $value; ?></span>
									</td>
									<?php $index++; endforeach; ?>
								</tr>
							</table>                                       
						</div>
						<?php $base = 130 * $index; ?>
						<div class="arrow-line" style="width: <?php echo $base ?>px;"><div class="arrow-end"></div></div>
						
					<?php else: ?>
						<div class="hidden">
							<p>
								<?php _e("Afin de r&eacute;pondre aux recommandations des autorit&eacute;s financi&egrave;res sur la pr&eacute;vention du risque repr&eacute;sent&eacute; par l&apos;investissement participatif,", 'yproject'); ?><br />
								<?php _e("vous devez &ecirc;tre inscrit et connect&eacute; pour acc&eacute;der à la totalit&eacute; du projet.", 'yproject'); ?>
							</p>
							<a href="#register" id="register" class="wdg-button-lightbox-open button red" data-lightbox="register" data-redirect="<?php echo get_permalink(); ?>"><?php _e("Inscription", 'yproject'); ?></a>
							<a href="#connexion" id="connexion" class="wdg-button-lightbox-open button red" data-lightbox="connexion" data-redirect="<?php echo get_permalink(); ?>"><?php _e("Connexion", 'yproject'); ?></a>
						</div>
                                                
					<?php endif; ?>
                                               
				<?php endif; ?>                               
			</div>
                        
		<?php if (is_user_logged_in()): ?>
			<div class="project-rewards-alert">
				<?php _e("Rendement vis&eacute; :", "yproject"); ?> 
				<span class="info-user">
					<span class="roi_percent_average">...</span> <?php _e("% par an en moyenne*", 'yproject'); ?>
					(<?php _e("soit", 'yproject'); ?> <span class="roi_ratio_on_total">...</span> % <?php echo __("en", 'yproject'). ' '. $funding_duration. ' ' .__("ans", "yproject"); ?>)
				</span><br />

				<span class="small-alert">* <?php _e("Ces valeurs sont estim&eacute;es selon", "yproject");?>&nbsp;
					<a href="#top-economic_model"><?php _e("les pr&eacute;visions du porteur de projet", "yproject")?></a>.
					<?php echo sprintf( __("Risque de perte int&eacute;grale de l&apos;investissement. Gain maximum : x%s.", "yproject"), $campaign->maximum_profit() ); ?>
				</span>
			</div>

			<?php if ($campaign_status == ATCF_Campaign::$campaign_status_collecte): ?>
				<div class="align-center">
					<br />
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