<?php
global $campaign, $stylesheet_directory_uri, $is_simulator_shortcode;
$campaign_id = $campaign->ID;
$campaign_status = $campaign->campaign_status();
$funding_duration = $campaign->funding_duration();
$funding_duration_str = ( $funding_duration == 0 ) ? __( "une dur&eacute;e ind&eacute;termin&eacute;e", 'yproject' ) : $funding_duration. " " .__( "ans", 'yproject' );
$funding_duration_str_2 = ( $funding_duration == 0 ) ? '5 ' .__( "ans", 'yproject' ) : $funding_duration. " " .__( "ans", 'yproject' );
$firstpayment_date = new DateTime( $campaign->first_payment_date() );
$firstpayment_year = $firstpayment_date->format( 'Y' );
$estimated_turnover = $campaign->estimated_turnover();
?>
<div class="project-rewards padder">
	<?php if ( empty( $is_simulator_shortcode ) ): ?>
	<h2 class="standard">
		<?php // CAPITAL // ?>
		<?php if ($campaign->funding_type() == 'fundingproject'): ?>
			/ <?php _e('Retour sur investissement', 'yproject'); ?> /
		<?php else: ?>
			/ <?php _e('Contreparties', 'yproject'); ?> /
		<?php endif; ?>
	</h2>
	<?php endif; ?>
    
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
			<div class="db-form v3 full bg-white">
				<?php /* différencier le calculateur de royalties pour l'épargne positive */ ?>
				<?php if ($campaign->is_positive_savings() ): ?>
					<input type="hidden" id="is_positive_savings" value="true"><input type="hidden" id="asset_price" value="<?php echo $campaign->minimum_goal(); ?>">
					<input type="hidden" id="asset_singular" value="<?php echo $campaign->get_asset_name_singular(); ?>">
					<input type="hidden" id="asset_plural" value="<?php echo $campaign->get_asset_name_plural(); ?>">
					<input type="hidden" id="common_goods_turnover_percent" value="<?php echo $campaign->get_api_data( 'common_goods_turnover_percent' ); ?>">
				<?php else: ?>
					<input type="hidden" id="is_positive_savings" value=false">
				<?php endif; ?>
				<input type="hidden" id="roi_percent_project" value="<?php echo $campaign->roi_percent_estimated(); ?>">
				<input type="hidden" id="roi_goal_project" value="<?php echo $campaign->goal(false); ?>">
				<input type="hidden" id="roi_maximum_profit" value="<?php echo $campaign->maximum_profit_complete(); ?>">
				<input type="hidden" id="estimated_turnover_unit" value="<?php echo $campaign->estimated_turnover_unit(); ?>">					
				
				<?php if (is_user_logged_in() && $campaign_status == ATCF_Campaign::$campaign_status_collecte): ?>
					<form method="GET" action="<?php echo WDG_Redirect_Engine::override_get_page_url( 'investir' ); ?>" class="avoid-enter-validation">
				<?php endif; ?>

				<?php if ( $campaign->roi_percent_estimated() > 0 && $firstpayment_year > 2014 ): ?>

					<div class="field">
						<label for="init_invest"><?php _e( "Si j'investissais :", 'yproject' ); ?></label>
						<div class="field-container field-init-invest">
							<span class="field-value">
								<input type="text" name="init_invest" id="init_invest" class="format-number">
								<span class="field-money">&euro;</span>
							</span>
						</div>
					</div>

					<div class="field">
						<button class="button blue" type="button"><?php _e( "Calculer", 'yproject' ); ?></button>
					</div>

					<div id="error-maximum" class="hidden wdg-message error">
						<?php _e( "Il n'est pas possible d'investir plus que l'objectif maximum recherch&eacute;.", 'yproject' ); ?>
					</div>
					<div id="error-amount" class="hidden wdg-message error">
						<?php _e( "Le montant investi doit &ecirc;tre un entier positif.", 'yproject' ); ?>
					</div>
					<div id="error-input" class="hidden wdg-message error">
						<?php _e( "Il y a un probl&egrave;me dans votre saisie.", 'yproject' ); ?>
					</div>
					<div class="field">
						<?php
							$campaign_periodicity = $campaign->get_declaration_periodicity();
							$campaign_periodicity_str = __( ATCF_Campaign::$declaration_period_list_plural[ $campaign_periodicity ], 'yproject' );
						?>
						<label for="init_invest"><?php echo sprintf( __( "Je recevrais tous les %s :", 'yproject' ), $campaign_periodicity_str ); ?></label>
						<div class="field-container align-left">
							<?php $complementary_text = '.'; ?>
							<?php if ( $campaign->contract_budget_type() == 'collected_funds' ): ?>
								<?php $complementary_text = __( " (pourcentage indicatif).", 'yproject' ); ?>
							<?php endif; ?>
							
							<?php if ($campaign->is_positive_savings() ): ?>
								<span class="roi_percent_user">0</span> % <?php echo __( "du chiffre d'affaires de", 'yproject' ) . ' '; ?><span class="nb_assets">0</span><span class="name_assets"><?php echo ' '.$campaign->get_asset_name_singular(); ?></span><?php echo ' '.__( "pendant", 'yproject' ).' '.$funding_duration_str. $complementary_text; ?><br>
							<?php else: ?>
								<span class="roi_percent_user">0</span> % <?php echo __( "du chiffre d'affaires de ce projet pendant", 'yproject' ) . ' ' .$funding_duration_str. $complementary_text; ?><br>
							<?php endif; ?>
							
							<?php _e("Soit un total de", 'yproject'); ?> <span class="roi_amount_user">0</span><span> &euro; </span><?php _e( "(brut) selon", 'yproject' ); ?>
							<?php if ( empty( $is_simulator_shortcode ) ): ?>
								<a href="#top-economic_model"><?php _e( "les pr&eacute;visions du porteur de projet :", 'yproject' )?></a>
							<?php else: ?>
								<?php _e( "les pr&eacute;visions du porteur de projet :", 'yproject' )?>
							<?php endif; ?>
						</div>
					</div>

					<?php if ( count( $estimated_turnover ) > 0 ): ?>
						<div class="margin-bottom">
							<table>
								<tr>
									<td>
										<?php _e( "Ann&eacute;e", 'yproject' ); ?>
									</td>
									<?php $index = 0; $max_turnover = max( max($estimated_turnover), 1 ); $count_estimated_turnover = count( $estimated_turnover ); ?>
									<?php foreach ( $estimated_turnover as $i => $value ): ?>
										<?php $height = 100 - round($value / $max_turnover * 100); ?>
										<td class="<?php if ( $count_estimated_turnover > 5 && $index > 1 && $index < $count_estimated_turnover - 2 ): ?>hidden<?php endif; ?>">
											<div><div style="height: <?php echo $height; ?>%;"><span class="roi_amount_user_container"><span class="roi_amount_user<?php echo $index; ?>">0&nbsp;&euro;</span></span></div></div>
											<?php echo ( $index + 1 ); ?><span class="hidden estimated-turnover-<?php echo $i; ?>"><?php echo $value; ?></span>
										</td>
										<?php if ( $count_estimated_turnover > 5 && $index == 2 ): ?>
											<td class="small">...</td>
										<?php endif; ?>
									<?php $index++; endforeach; ?>
								</tr>
							</table>
						</div>
					<?php endif; ?>

				<?php endif; ?>
					
				<div class="align-left">
					<strong><?php _e( "Retour sur investissement vis&eacute; :", 'yproject' ); ?></strong><br>
					+ <span><span class="roi_percent_total">...</span> %</span> <?php echo __( "de votre investissement initial en", 'yproject' ). ' ' .$funding_duration_str_2; ?><br>
					(<?php _e( "soit", 'yproject' ); ?> <span>x<span class="roi_ratio_on_total">...</span></span> <?php echo __( "votre investissement initial en", 'yproject' ). ' ' .$funding_duration_str_2; ?>)
				</div>
					
				
				<div class="project-rewards-alert align-left">
					<?php echo sprintf( __( "Risque de perte int&eacute;grale de l&apos;investissement. Retour sur investissement maximum : %s.", 'yproject' ), $campaign->maximum_profit_str() ); ?><br>
					* <?php _e( "Imposition : Pr&eacute;l&egrave;vement Forfaitaire Unique (flat tax) de 30% sur le b&eacute;n&eacute;fice r&eacute;alis&eacute;.", 'yproject' ); ?><br>
					<?php if ($campaign->is_positive_savings() ): ?>	
						<?php _e( "Le pourcentage peut varier selon le co&ucirc;t d'achat final de l'actif mais le rendement vis&eacute; reste le m&ecirc;me.", 'yproject' ); ?>
					<?php endif; ?>
				</div>

				<?php if ($campaign_status == ATCF_Campaign::$campaign_status_collecte): ?>
					<?php if (is_user_logged_in()): ?>
						<div class="align-center">
							<br />
							<button type="submit" class="button red"><?php _e("Investir", "yproject"); ?></button>
							<input type="hidden" name="campaign_id" value="<?php echo $campaign_id; ?>" />
							<input type="hidden" name="invest_start" value="1" />
						</div>
					</form>
					<?php else: ?>
						<div class="align-center">
							<a href="<?php echo home_url( '/connexion' ); ?>" class="button red"><?php _e( "Investir", 'yproject' ); ?></a>
						</div>
					<?php endif; ?>
					<br>
				<?php endif; ?>			
			</div>
	    
		<?php // DON // ?>
		<?php elseif ($campaign->funding_type() == 'fundingdonation'): ?>
			<img src="<?php echo $stylesheet_directory_uri;?>/images/macarons/macaron-D.png" alt="Don" />
	    
	    
		<?php endif; ?>
	</div>
</div>