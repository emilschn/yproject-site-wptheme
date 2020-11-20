<?php
global $stylesheet_directory_uri, $WDGOrganization;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_current = WDGUser::current();

$has_wire_investments_0 = $WDGOrganization->has_wire_investments_0();
$wire_investments_0 = $WDGOrganization->get_wire_investments_0();
?>
<h2><?php _e( "Investissements de", 'yproject' ); ?>  <?php echo $WDGOrganization->get_name(); ?></h2>

<?php if ( $WDGUser_current->is_admin() && $has_wire_investments_0 ): ?>		
	<div class="admin-theme">
		<strong><?php _e( "Attention, il y a des virements de 0&euro; &agrave; corriger : ", 'yproject' ); ?></strong><br>
		<?php foreach ( $wire_investments_0 as $wire_investment ): ?>
			<br>
			<?php 
				$WDGInvestment = new WDGInvestment( $wire_investment->ID ); 	
				$post_campaign = atcf_get_campaign_post_by_payment_id($wire_investment->ID);
				$campaign = atcf_get_campaign($post_campaign);				
			?>
			<strong><?php _e( "Identifiants du virement :", 'yproject' ); ?></strong><br>
			<?php echo $campaign->get_name(); ?><br>
			<?php echo $WDGInvestment->get_saved_date(); ?><br>
			<?php echo $wire_investment->ID.' - '.$WDGInvestment->get_payment_key(); ?><br>
			<form action="" method="POST" enctype="multipart/form-data" class="db-form v3 full align-left">
				<input type="hidden" name="action" value="change_wire_value">
				<input type="hidden" name="user_id" value="<?php echo $WDGOrganization->get_wpref(); ?>">
				<input type="hidden" name="investment_id" value="<?php echo $wire_investment->ID; ?>">
				<input type="hidden" name="campaign_id" value="<?php echo $campaign->ID; ?>">

				<div id="field-amount_of_wire" class="field field-text-money">
					<!-- TODO : essayer de récupérer dans les logs (?) le vrai montant du virement  -->
					<?php $lw_wallet_amount = 0 ?>
					<label for="amount_of_wire"><?php echo sprintf( __( "Montant r&eacute;el du virement :", 'yproject' ), UIHelpers::format_number( $lw_wallet_amount ) ); ?></label>
					<div class="field-container">
						<span class="field-value">
							<input type="text" name="amount_to_wire" id="amount_to_wire" value="<?php echo $lw_wallet_amount; ?>" class="format-number">
							<span class="field-money">&euro;</span>
						</span>
					</div>
				</div>

				<button type="submit" class="button blue"><?php _e( "Modifier le montant du virement", 'yproject' ); ?></button>
			</form>

		<?php endforeach; ?>
	</div>
<?php endif; ?>

<div id="investment-synthesis-<?php echo $WDGOrganization->get_wpref(); ?>" class="investment-synthesis hidden">
	<span class="publish-count">0</span> <?php _e( "investissements valid&eacute;s", 'yproject' ); ?><span class="pending-str hidden">, <span class="pending-count">0</span> en attente</span>.
</div>

<div id="investment-synthesis-pictos-<?php echo $WDGOrganization->get_wpref(); ?>" class="investment-synthesis-pictos hidden">
	<div class="funded-projects">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-balloon.png" alt="montgolfiere" width="80" height="80">
		<span class="data">0</span><br>
		<span class="txt"><?php _e( "projets financ&eacute;s", 'yproject' ); ?></span>
	</div>
	
	<div class="amount-invested">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-arrows.png" alt="fleche" width="81" height="80">
		<span class="data">0 &euro;</span><br>
		<span class="txt"><?php _e( "investis", 'yproject' ); ?></span>
	</div>
	
	<div class="royalties-received">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-money.png" alt="monnaie" width="97" height="80">
		<span class="data">0 &euro;</span><br>
		<span class="txt"><?php _e( "royalties re&ccedil;ues", 'yproject' ); ?></span>
		
	</div>
</div>
	
<div id="ajax-loader-<?php echo $WDGOrganization->get_wpref(); ?>" class="center" style="text-align: center;"><img id="ajax-loader-img-<?php echo $WDGOrganization->get_wpref(); ?>" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>

