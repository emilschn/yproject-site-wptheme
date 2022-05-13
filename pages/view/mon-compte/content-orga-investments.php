<?php
global $stylesheet_directory_uri, $WDGOrganization;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_current = WDGUser::current();

$has_pending_wire_investments = $WDGOrganization->has_pending_wire_investments();
$pending_wire_investments = $WDGOrganization->get_pending_wire_investments();
?>
<h2><?php _e( 'account.investments.orga.TITLE', 'yproject' ); ?>  <?php echo $WDGOrganization->get_name(); ?></h2>

<?php if ( $WDGUser_current->is_admin() && $has_pending_wire_investments ): ?>		
	<div class="admin-theme">
		<strong><?php _e( "Virements en attente : ", 'yproject' ); ?></strong><br>
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
				<input type="hidden" name="user_id" value="<?php echo $WDGUser_current->get_wpref(); ?>">
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

<div id="investment-synthesis-<?php echo $WDGOrganization->get_wpref(); ?>" class="investment-synthesis hidden">
	<span class="publish-count">0</span> <?php _e( 'account.investments.INVESTMENTS_VALIDATED', 'yproject' ); ?><span class="pending-str hidden">, <span class="pending-count">0</span> <?php _e( 'account.investments.INVESTMENTS_PENDING', 'yproject' ); ?></span>.
</div>

<div id="investment-synthesis-pictos-<?php echo $WDGOrganization->get_wpref(); ?>" class="investment-synthesis-pictos hidden">
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

<div id="ajax-loader-<?php echo $WDGOrganization->get_wpref(); ?>" class="center" style="text-align: center;"><img id="ajax-loader-img-<?php echo $WDGOrganization->get_wpref(); ?>" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>

