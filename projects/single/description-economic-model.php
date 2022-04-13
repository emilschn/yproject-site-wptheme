<?php
global $campaign;
$campaign_organization = $campaign->get_organization();
$WDGOrganization = new WDGOrganization( $campaign_organization->wpref, $campaign_organization );
$funding_duration = $campaign->funding_duration();
$estimated_turnover = $campaign->estimated_turnover();
$estimated_sales = $campaign->estimated_sales();

$total_turnover = 0;
foreach ( $estimated_turnover as $year => $amount ) {
	$total_turnover += $amount;
}
$profitability_ratio = round( ( $total_turnover * $campaign->roi_percent_estimated() / 100 ) / $campaign->goal( false ), 5 );
$profitability_percent = $profitability_ratio * 100 - 100;

$contract_start_date = new DateTime( $campaign->contract_start_date() );
$file_name_contract_orga = site_url() . '/wp-content/plugins/appthemer-crowdfunding/includes/contracts/' .  $campaign->backoffice_contract_orga();
?>

<?php // 3 zones du haut ?>
<table class="economic-intro">
	<tr>
		<td class="left <?php if ( $campaign->is_hidden() ) { ?>two-cols<?php } ?>">
			<h5><?php _e( 'project.single.description.economic.TARGET_PROFITABILITY', 'yproject' ); ?></h5>
			<span class="economic-data">
				<?php echo sprintf( __( 'project.single.description.economic.TARGET_PROFITABILITY_RATIO', 'yproject' ), UIHelpers::format_number( $profitability_ratio ), $funding_duration ); ?>
				<span class="economic-data-details"><?php echo sprintf( __( 'project.single.description.economic.TARGET_PROFITABILITY_PERCENT', 'yproject' ), UIHelpers::format_number( $profitability_percent ) ); ?></span>
			</span>
			<br>
			<span><?php echo sprintf( __( 'project.single.description.economic.TARGET_PROFITABILITY_RISK', 'yproject' ), $campaign->maximum_profit_str() ); ?></span>
			<br>
			<a href="#"><?php _e( 'project.single.description.economic.TARGET_PROFITABILITY_CALCULATE', 'yproject' ); ?></a>
		</td>

		<?php if ( !$campaign->is_hidden() ): ?>
		<td class="left">
			<h5><?php _e( 'project.single.description.economic.RISK', 'yproject' ); ?></h5>

			<?php if ( $campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_validated || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote ): ?>
				<span><?php _e( 'project.single.description.economic.RISK_VOTE', 'yproject' ); ?></span>
				<br>
				<span><?php _e( 'project.single.description.economic.RISK_VOTE_DESCRIPTION', 'yproject' ); ?></span>

			<?php else: ?>
				<?php
				$vote_results = WDGCampaignVotes::get_results( $campaign->ID );
				$risk_rate = round( $vote_results[ 'average_risk' ], 2 );
				?>
				<span class="economic-data">
				<?php if ( $risk_rate < 1.5 ): ?>
					<?php _e( 'project.single.description.economic.RISK_VERY_WEAK', 'yproject' ); ?>
				<?php elseif ( $risk_rate < 2.5 ): ?>
					<?php _e( 'project.single.description.economic.RISK_WEAK', 'yproject' ); ?>
				<?php elseif ( $risk_rate < 3.5 ): ?>
					<?php _e( 'project.single.description.economic.RISK_MIDDLE', 'yproject' ); ?>
				<?php elseif ( $risk_rate < 4.5 ): ?>
					<?php _e( 'project.single.description.economic.RISK_STRONG', 'yproject' ); ?>
				<?php else: ?>
					<?php _e( 'project.single.description.economic.RISK_VERY_STRONG', 'yproject' ); ?>
				<?php endif; ?>
				</span>
				<br>
				<span><?php echo sprintf( __( 'project.single.description.economic.RISK_RATE_DESCRIPTION', 'yproject' ), $risk_rate ); ?></span>
			<?php endif; ?>
		</td>
		<?php endif; ?>

		<td class="left third <?php if ( $campaign->is_hidden() ) { ?>two-cols<?php } ?>">
			<h5><?php _e( 'project.single.description.economic.ROYALTIES_PER_QUARTER', 'yproject' ); ?></h5>
			<span class="economic-data"><?php echo sprintf( __( 'project.single.description.economic.ROYALTIES_PER_QUARTER_MAX', 'yproject' ), UIHelpers::format_number( $campaign->roi_percent_estimated() ) ); ?></span>
			<br>
			<span><?php echo sprintf( __( 'project.single.description.economic.ROYALTIES_PER_QUARTER_MAX_DESCRIPTION', 'yproject' ), UIHelpers::format_number( $campaign->goal( false ) ) ); ?></span>
		</td>
	</tr>
</table>


<?php // Revenus du projet ?>
<h4><?php _e( 'project.single.description.economic.PROJECT_REVENUES', 'yproject' ); ?></h4><br>
<p><?php echo sprintf( __( 'project.single.description.economic.PROJECT_REVENUES_PREVIOUS_YEAR', 'yproject' ), UIHelpers::format_number( $campaign->turnover_previous_year() ) ); ?></p>

<div class="table-container">
	<table>
		<thead>
			<tr>
				<th></th>
				<?php for ( $i = 1; $i <= $funding_duration; $i++ ): ?>
				<th><?php _e( "Ann&eacute;e", 'yproject' ); ?> <?php echo $i; ?></th>
				<?php endfor; ?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th><?php _e( "project.single.description.economic.COUNT_SALES", 'yproject' ); ?></th>
				<?php for ( $i = 1; $i <= $funding_duration; $i++ ): ?>
				<td><?php echo !empty($estimated_sales[ $i ]) ? $estimated_sales[ $i ] : 0; ?></th>
				<?php endfor; ?>
			</tr>
			<tr>
				<th><?php _e( "project.single.description.economic.ESTIMATED_TURNOVER", 'yproject' ); ?></th>
				<?php for ( $i = 1; $i <= $funding_duration; $i++ ): ?>
				<td><?php echo UIHelpers::format_number( $estimated_turnover[ $i ], 2, '&nbsp;' ); ?>&nbsp;€</th>
				<?php endfor; ?>
			</tr>
		</tbody>
	</table>
</div>

<!-- TODO Graphique -->


<?php // Votre investissement ?>
<h4><?php _e( 'project.single.description.economic.YOUR_INVESTMENT', 'yproject' ); ?></h4>
<p>
	<?php echo sprintf( __( 'project.single.description.economic.YOUR_INVESTMENT_DESCRIPTION_1', 'yproject' ), $WDGOrganization->get_name() ); ?>
	<?php echo sprintf( __( 'project.single.description.economic.YOUR_INVESTMENT_DESCRIPTION_2', 'yproject' ), UIHelpers::format_number( $campaign->roi_percent_estimated() ), UIHelpers::format_number( $campaign->goal( false ) ) ); ?>
	<br>
	<?php echo sprintf( __( 'project.single.description.economic.YOUR_INVESTMENT_DESCRIPTION_3', 'yproject' ), $WDGOrganization->get_name(), UIHelpers::format_number( $profitability_percent ), $funding_duration ); ?>
	<br>
	<br>
	<strong class="title-question"> <?php _e( 'project.single.description.economic.YOUR_INVESTMENT_QUESTION_1', 'yproject' ); ?></strong>
	<br>
	<?php echo sprintf( __( 'project.single.description.economic.YOUR_INVESTMENT_QUESTION_1_ANSWER', 'yproject' ), $funding_duration, $WDGOrganization->get_name() ); ?>
	<br>
	<br>
	<strong class="title-question"> <?php _e( 'project.single.description.economic.YOUR_INVESTMENT_QUESTION_2', 'yproject' ); ?></strong>
	<br>
	<?php echo sprintf( __( 'project.single.description.economic.YOUR_INVESTMENT_QUESTION_2_ANSWER', 'yproject' ), $funding_duration, $contract_start_date->format( 'd/m/Y' ) ); ?>
	<br>
	<br>
	<?php _e( 'project.single.description.economic.YOUR_INVESTMENT_YOUR_CONTRACT', 'yproject' ); ?>
	<br>
	<br>
	<a href="<?php echo $file_name_contract_orga; ?>" target="_blank"><?php _e( 'project.single.description.economic.YOUR_INVESTMENT_STANDARD_CONTRACT', 'yproject' ); ?></a>
	<br>
	<i><?php _e( 'project.single.description.economic.YOUR_INVESTMENT_STANDARD_CONTRACT_DESCRIPTION', 'yproject' ); ?></i>
</p>


<?php // Echéancier prévisionnel des versements ?>
<h4><?php _e( 'project.single.description.economic.TRANSFERS_TIMELINE', 'yproject' ); ?></h4>
<p>
	<?php _e( 'project.single.description.economic.TRANSFERS_TIMELINE_DESCRIPTION', 'yproject' ); ?>
</p>

<?php
$nb_declarations_per_year = $campaign->get_declarations_count_per_year();
$nb_months_between_declarations = 12 / $nb_declarations_per_year;
$timeline_length = $funding_duration * $nb_declarations_per_year;
?>
<table class="declarations-table">
	<thead>
		<tr>
			<th><?php _e( 'project.single.description.economic.TRANSFERS_TIMELINE_TABLE_REVENUES', 'yproject' ); ?></th>
			<th><?php _e( 'project.single.description.economic.TRANSFERS_TIMELINE_TABLE_DATE', 'yproject' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$months = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
		$first_payment_date = new DateTime( $campaign->first_payment_date() );
		$loop_date = new DateTime( $campaign->contract_start_date() );
		$loop_date->setDate( $loop_date->format( 'Y' ), $loop_date->format( 'm' ), $first_payment_date->format( 'd' ) );
		$current_year = 0;
		?>
		<?php for ( $i = 0; $i < $timeline_length; $i++ ): ?>
			<?php
			$add_year = false;
			if ( $loop_date->format( 'Y' ) != $current_year ) {
				$current_year = $loop_date->format( 'Y' );
				$add_year = true;
			}
			?>

			<?php if ( $add_year ): ?>
			<tr>
				<th colspan="2"><?php echo $current_year; ?></th>
			</tr>
			<?php endif; ?>

			<?php
			$month_str = '';
			for ( $j = 0; $j < $nb_months_between_declarations; $j++ ) {
				if ( $month_str != '' ) {
					$month_str .= ', ';
				}
				$month_str .= ucfirst( __( $months[ $loop_date->format( 'm' ) - 1 ] ) );
				$loop_date->add( new DateInterval( 'P1M' ) );
			}
			$transfer_date = $loop_date->format( 'd/m/Y' );
			?>
			<tr>
				<td><?php echo $month_str; ?></td>
				<td><?php echo $transfer_date; ?></td>
			</tr>
		<?php endfor; ?>
	</tbody>
</table>


<?php // Facteurs de risques ?>
<h4><?php _e( 'project.single.description.economic.RISKS_REASONS', 'yproject' ); ?></h4>
<p>
	<?php _e( 'project.single.description.economic.RISKS_REASONS_DESCRIPTION_1', 'yproject' ); ?>
	<br>
	<?php echo sprintf( __( 'project.single.description.economic.RISKS_REASONS_DESCRIPTION_2', 'yproject' ), UIHelpers::format_number( $campaign->total_previous_funding() ) ); ?>
</p>
<?php echo html_entity_decode( $campaign->total_previous_funding_description() ); ?>

<p>
	<strong class="campaign-risk"> <?php _e( 'project.single.description.economic.RISKS_REASON_FINANCE', 'yproject' ); ?></strong>
	<br>
	<?php $has_finance_str = $campaign->has_sufficient_working_capital() ? __( 'project.single.description.economic.RISKS_REASON_FINANCE_DESCRIPTION_YES', 'yproject' ) : __( 'project.single.description.economic.RISKS_REASON_FINANCE_DESCRIPTION_NO', 'yproject' ); ?>
	<?php echo sprintf( __( 'project.single.description.economic.RISKS_REASON_FINANCE_DESCRIPTION', 'yproject' ), $has_finance_str ); ?>
</p>
<?php if ( $campaign->working_capital_subsequent() != '' ): ?>
	<div>
		<?php _e( 'project.single.description.economic.RISKS_REASON_FINANCE_SUBSEQUENT', 'yproject' ); ?>
	</div>
	<?php echo html_entity_decode( $campaign->working_capital_subsequent() ); ?>
<?php endif; ?>

<?php echo html_entity_decode( $campaign->financial_risks_others() ); ?>
<p>
	<i><?php _e( 'project.single.description.economic.RISKS_REASON_NEW_ONES', 'yproject' ); ?></i>
	<br>
	<br>
	<?php echo sprintf( __( 'project.single.description.economic.RISKS_REASON_CONTACT', 'yproject' ), $WDGOrganization->get_email() ); ?>
</p>
