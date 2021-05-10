<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$WDGUser_current = WDGUser::current();

$list_current_organizations = $page_controler->get_current_user_organizations();
$list_intentions_to_confirm = $page_controler->get_intentions_to_confirm();

if ( $WDGUser_current->is_admin() ) {
	$has_pending_wire_investments = $WDGUser_displayed->has_pending_wire_investments();
	$pending_wire_investments = $WDGUser_displayed->get_pending_wire_investments();
}

?>

<h2><?php _e( 'account.investments.INVESTMENTS_OF', 'yproject' ); ?> <?php echo $page_controler->get_user_name(); ?></h2>

<p>
	<?php _e( 'account.common.INFORMATION_BELOW_PERSONAL_ACCOUNT', 'yproject' ); ?><br>
	<?php if ( count( $list_current_organizations ) > 0 ): ?>
		<?php _e( 'account.common.IF_INVESTMENT_ORGA', 'yproject' ); ?>
	<?php endif; ?>
</p>

<?php if ( $WDGUser_current->is_admin() ): ?>
	<?php $change_investor_feedback = $page_controler->get_form_user_change_investor_feedback(); ?>
	<?php if ( !empty( $change_investor_feedback[ 'errors' ] ) ): ?>
		<?php foreach ( $change_investor_feedback[ 'errors' ] as $error_item ): ?>
			<div class="wdg-message error">
				<?php echo $error_item[ 'text' ]; ?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php if ( !empty( $change_investor_feedback[ 'success' ] ) ): ?>
		<div class="wdg-message confirm">
			<?php echo $change_investor_feedback[ 'success' ]; ?>
		</div>
	<?php endif; ?>
<?php endif; ?>


<?php if ( $WDGUser_current->is_admin() && $has_pending_wire_investments ): ?>
	<div class="admin-theme">
		<strong><?php _e( "Virements en attente: ", 'yproject' ); ?></strong><br>
		<?php foreach ( $pending_wire_investments as $wire_investment ): ?>
			<br>
			<?php
				$WDGInvestment = new WDGInvestment( $wire_investment->ID );
				$post_campaign = atcf_get_campaign_post_by_payment_id($wire_investment->ID);
				$campaign = atcf_get_campaign($post_campaign);
			?>
			<strong><?php _e( "Identifiants du virement :", 'yproject' ); ?></strong><br>
			<?php echo $campaign->get_name(); ?><br>
			<?php echo $WDGInvestment->get_saved_date(); ?><br>
			<?php $lw_wallet_amount = intval($WDGInvestment->get_saved_amount()) ?>
			<form action="" method="POST" enctype="multipart/form-data" class="db-form align-left">
				<input type="hidden" name="action" value="change_wire_value">
				<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>">
				<input type="hidden" name="investment_id" value="<?php echo $wire_investment->ID; ?>">
				<input type="hidden" name="payment_key" value="<?php echo $WDGInvestment->get_payment_key(); ?>">
				<input type="hidden" name="campaign_id" value="<?php echo $campaign->ID; ?>">
				<!-- TODO : essayer de récupérer dans les logs (?) le vrai montant du virement  -->
				<label for="amount_of_wire"><?php echo sprintf( __( "Montant du virement :", 'yproject' ) ); ?></label>
				<span class="field-value">
					<input type="text" name="amount_to_wire" id="amount_to_wire" value="<?php echo $lw_wallet_amount; ?>" class="format-number">
					<span class="field-money">&euro;</span>
				</span>
				<button type="submit" class="button blue"><?php _e( "Modifier le montant du virement", 'yproject' ); ?></button>
			</form>

		<?php endforeach; ?>
	</div>
<?php endif; ?>


<div id="investment-synthesis-<?php echo $WDGUser_displayed->get_wpref(); ?>" class="investment-synthesis hidden">
	<span class="publish-count">0</span> <?php _e( 'account.investments.INVESTMENTS_VALIDATED', 'yproject' ); ?><span class="pending-str hidden">, <span class="pending-count">0</span> <?php _e( 'account.investments.INVESTMENTS_PENDING', 'yproject' ); ?></span>.
</div>

<div id="investment-synthesis-pictos-<?php echo $WDGUser_displayed->get_wpref(); ?>" class="investment-synthesis-pictos hidden">
	<div class="funded-projects">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-balloon.png" alt="montgolfiere" width="80" height="80">
		<span class="data">0</span><br>
		<span class="txt"><?php _e( 'account.investments.PROJECTS_FUNDED', 'yproject' ); ?></span>
	</div>
	
	<div class="amount-invested">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-arrows.png" alt="fleche" width="81" height="80">
		<span class="data">0 &euro;</span><br>
		<span class="txt"><?php _e( 'account.investments.INVESTED', 'yproject' ); ?></span>
	</div>
	
	<div class="royalties-received">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-money.png" alt="monnaie" width="97" height="80">
		<span class="data">0 &euro;</span><br>
		<span class="txt"><?php _e( 'account.investments.ROYALTIES_RECEIVED', 'yproject' ); ?></span>
		
	</div>
</div>

<div id="vote-intentions-<?php echo $WDGUser_displayed->get_wpref(); ?>" class="vote-intentions hidden">
	
	<?php if ( count( $list_intentions_to_confirm ) > 0 ): ?>
		<h3><?php _e( 'account.investments.INVESTMENTS_TO_CONCLUDE', 'yproject' ); ?></h3>
	
		<?php foreach ( $list_intentions_to_confirm as $intention_item ): ?>
		
			<?php if ( $intention_item[ 'status' ] == ATCF_Campaign::$campaign_status_vote || $intention_item[ 'status' ] == ATCF_Campaign::$campaign_status_collecte ): ?>
				<?php $status_str = ( $intention_item[ 'status' ] == ATCF_Campaign::$campaign_status_vote ) ? __( 'account.investments.STATUS_VOTE', 'yproject' ) : __( 'account.investments.STATUS_INVESTMENT', 'yproject' ); ?>
				<?php $button_str = ( $intention_item[ 'status' ] == ATCF_Campaign::$campaign_status_vote ) ? __( 'common.PREINVEST', 'yproject' ) : __( 'common.INVEST', 'yproject' ); ?>
				<h4><?php echo YPUIHelpers::display_number( $intention_item[ 'vote_amount' ], TRUE, 0 ). ' &euro; - ' .$intention_item[ 'campaign_name' ]. ' (' .$status_str. ')'; ?></h4>
				<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'investir' ) . '?campaign_id=' .$intention_item[ 'campaign_id' ]. '&invest_start=1&init_invest=' .$intention_item[ 'vote_amount' ]; ?>" class="button red"><?php echo $button_str; ?></a>
			<?php endif; ?>

		<?php endforeach; ?>
	
	<?php endif; ?>
	
</div>


<span class="hidden">
	<span id="invest-trans-reload"><?php _e( 'account.investments.RELOAD', 'yproject' ); ?></span>
	<span id="invest-trans-loading_problem"><?php _e( 'account.investments.LOADING_PROBLEM', 'yproject' ); ?></span>
	<span id="invest-trans-no_investments"><?php _e( 'account.investments.NO_INVESTMENTS', 'yproject' ); ?></span>
	<span id="invest-trans-no_investments_if_vote"><?php _e( 'account.investments.NO_INVESTMENTS_IF_VOTE', 'yproject' ); ?></span>
	<span id="invest-trans-my_investments_on"><?php _e( 'account.investments.MY_INVESTMENTS_ON', 'yproject' ); ?></span>
	<span id="invest-trans-investiement_duration"><?php _e( 'account.investments.INVESTMENT_DURATION', 'yproject' ); ?></span>
	<span id="invest-trans-investiement_duration_years"><?php _e( 'account.investments.INVESTMENT_DURATION_YEARS', 'yproject' ); ?></span>
	<span id="invest-trans-investiement_duration_starting"><?php _e( 'account.investments.INVESTMENT_DURATION_STARTING', 'yproject' ); ?></span>
	<span id="invest-trans-royalties_received"><?php _e( 'account.investments.ROYALTIES_RECEIVED_A', 'yproject' ); ?></span>
	<span id="invest-trans-return_on_investment"><?php _e( 'account.investments.RETURN_ON_INVESTMENT', 'yproject' ); ?></span>
	<span id="invest-trans-see_contract"><?php _e( 'account.investments.SEE_CONTRACT', 'yproject' ); ?></span>
	<span id="invest-trans-contract"><?php _e( 'invest.header.steps.CONTRACT', 'yproject' ); ?></span>
	<span id="invest-trans-finish_investment"><?php _e( 'account.investments.FINISH_INVESTMENT', 'yproject' ); ?></span>
	<span id="invest-trans-inaccessible"><?php _e( 'account.investments.INACCESSIBLE', 'yproject' ); ?></span>
	<span id="invest-trans-quarterly_payments"><?php _e( 'account.investments.QUARTERLY_PAYMENTS', 'yproject' ); ?></span>
	<span id="invest-trans-years"><?php _e( 'account.investments.YEARS', 'yproject' ); ?></span>
	<span id="invest-trans-turnover"><?php _e( 'account.investments.TURNOVER', 'yproject' ); ?></span>
	<span id="invest-trans-royalties"><?php _e( 'account.investments.ROYALTIES', 'yproject' ); ?></span>
	<span id="invest-trans-estimated"><?php _e( 'account.investments.ESTIMATED', 'yproject' ); ?></span>
	<span id="invest-trans-other_commitments"><?php _e( 'account.investments.OTHER_COMMITMENTS', 'yproject' ); ?></span>
	<span id="invest-trans-company_is_commited"><?php _e( 'account.investments.COMPANY_IS_COMMITED', 'yproject' ); ?></span>
</span>
<div id="ajax-loader-<?php echo $WDGUser_displayed->get_wpref(); ?>" class="center" style="text-align: center;"><img id="ajax-loader-img-<?php echo $WDGUser_displayed->get_wpref(); ?>" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>

