<?php
global $campaign, $stylesheet_directory_uri, $is_simulator_shortcode;
$campaign_id = $campaign->ID;
$campaign_status = $campaign->campaign_status();
$funding_duration = $campaign->funding_duration();
$funding_duration_str = ( $funding_duration == 0 ) ? __( "une dur&eacute;e ind&eacute;termin&eacute;e", 'yproject' ) : $funding_duration. " " .__( "ans", 'yproject' );
$funding_duration_str_2 = ( $funding_duration == 0 ) ? $campaign->funding_duration_infinite_estimation() . ' ' .__( "ans", 'yproject' ) : $funding_duration. " " .__( "ans", 'yproject' );
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
						<label for="init_invest" style="text-align: left;"><?php echo sprintf( __( "Je recevrais tous les %s :", 'yproject' ), $campaign_periodicity_str ); ?></label>
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

					<div class="calculateurRoyaltiesContainer" style="position: relative; height:auto; width:96%; margin: auto;">
						<canvas id="calculateurRoyalties"></canvas>
					</div>

					<script>
						// options de configurations : https://www.chartjs.org/docs/latest/charts/line.html
						const labelsRoyaltiesChart = [
							0,
							<?php $index_label = 1; ?>
							<?php foreach ( $estimated_turnover as $i => $value ): ?>
								<?php echo $index_label++; ?>,
							<?php endforeach; ?>
						];
					
						<?php
						$estimated_turnover_length = count( $estimated_turnover ) + 1;
						$amount_to_consider = max( $campaign->minimum_goal(), $campaign->current_amount( FALSE ) );
						$turnover_to_reach_investment = ceil( $amount_to_consider / $campaign->roi_percent_estimated() * 100 );
						$turnover_to_reach_maximum_profit = 0;
						if ( $campaign->maximum_profit() != 'infinite' ) {
							$turnover_to_reach_maximum_profit = ceil( $campaign->maximum_profit_amount() / $campaign->roi_percent_estimated() * 100 );
						}
						?>
						const dataRoyaltiesChart = {
							labels: labelsRoyaltiesChart,
							datasets: [
								// Voir line styling : https://www.chartjs.org/docs/latest/charts/line.html#line-styling
								{
									data: Array.apply(null, new Array(<?php echo $estimated_turnover_length; ?>)).map(Number.prototype.valueOf, <?php echo $turnover_to_reach_investment; ?>),
									fill: false,
									radius: 0,
									borderColor: "rgba(0,0,0,0.1)",
									borderDash: [6, 3],
									borderWidth: 2,
									label: 'Montant investi',
								},
								<?php // if ( $turnover_to_reach_maximum_profit > 0 ): ?>
								{
									data: Array.apply(null, new Array(<?php echo $estimated_turnover_length; ?>)).map(Number.prototype.valueOf, <?php echo $turnover_to_reach_maximum_profit; ?>),
									fill: false,
									radius: 0,
									borderColor: "rgba(51,51,51,1)",
									borderDash: [6, 3],
									borderWidth: 1,
									label: 'Retour sur investissement maximum'
								},
								<?php // endif; ?>
								{
									label: '€',
									fill: true,
									backgroundColor: 'rgb(179, 218, 225)',
									borderColor: 'rgb(0, 135, 155)',
									pointBackgroundColor: 'rgba(0,135,155,1)',
									data: Array.apply(null, new Array(<?php echo $estimated_turnover_length; ?>)).map(Number.prototype.valueOf, 0)
								}
							]
						};
						const configRoyaltiesChart = {
							type: 'line',
							data: dataRoyaltiesChart,
							options: {
								responsive: true,
								maintainAspectRatio: false,
								scales: {
									x: {
										display: true,
										title: {
											display: true,
											text: 'Années',
										},
										grid: {
											// Masque la grille du fond
											display: false
										}
									},
									y: {
										display: true,
										title: {
											// Masque la légende verticale
											display: false
										},
										ticks: {
											// Ne pas afficher les chiffres en légende verticale
											display: false
										},
										grid: {
											// Masque la grille du fond
											display: false
										}
									}
								},
								plugins: {
									legend: {
										display: true,
										position: 'bottom',
										labels: {
											boxHeight: 1,
											boxWidth: 25,
											padding: 25,
										}
									},
									// Pas réussi à le faire fonctionner : placer la légende en haut de l'écran
									title: {
										position: 'top',
										align: 'end'
									}
								}
							}
						};

						var royaltiesChart = new Chart(
							document.getElementById('calculateurRoyalties'),
							configRoyaltiesChart
						);
					</script>

					<?php if ( count( $estimated_turnover ) > 0 ): ?>
						<div class="hidden">
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
					
				<div class="align-left" style="font-size: 14px;">
					<?php _e( "Retour sur investissement vis&eacute; :", 'yproject' ); ?><br>
					<strong><span>x<span class="roi_ratio_on_total">... </span></span></strong> <?php echo __( "votre investissement initial en", 'yproject' ). ' ' .$funding_duration_str_2; ?>
					(<?php _e( "soit", 'yproject' ); ?> + <span><span class="roi_percent_total">...</span> %</span>)
				</div>
				
				<div class="project-rewards-alert align-left" style="font-size: 14px;">
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
							<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'connexion' ); ?>" class="button red"><?php _e( "Investir", 'yproject' ); ?></a>
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