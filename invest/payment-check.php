<?php
$check_return = filter_input( INPUT_GET, 'check-return' );
if ( !empty( $check_return ) ) {
	locate_template( 'invest/payment-check-return.php', true );
	
} else {

	global $campaign;
	if (!isset($campaign)) {
		$campaign = atcf_get_current_campaign();
	}

	if (isset($campaign)): ?>

		<?php
		global $current_breadcrumb_step; $current_breadcrumb_step = 3;
		locate_template( 'invest/breadcrumb.php', true );
		$campaign_organization = $campaign->get_organization();
		$organization_obj = new WDGOrganization( $campaign_organization->wpref );
		$amount = $_SESSION['redirect_current_amount_part'];
		$wdginvestment = WDGInvestment::current();
		$wdginvestment->set_status( WDGInvestment::$status_waiting_check );
		?>

		<?php _e( "Afin d'investir par ch&egrave;que, prenez une photo du ch&egrave;que que vous souhaitez faire &agrave; l'ordre de", 'yproject' ); ?>
		<?php echo $organization_obj->get_name(); ?>
		<?php _e( "et envoyez cette photo gr&acirc;ce au formulaire ci-dessous.", 'yproject' ); ?><br /><br />

		<?php _e( "Ensuite, envoyez-nous ce ch&egrave;que &agrave; l'adresse suivante :", 'yproject' ); ?><br />
		WE DO GOOD<br />
		7 rue Mathurin Brissonneau<br />
		44100 Nantes<br /><br />

		<?php _e( "Le ch&egrave;que ne sera encaiss&eacute; que si la campagne r&eacute;ussit.", 'yproject' ); ?><br /><br />

		<?php if ( $amount > 1500 ): ?>
			<?php _e( "Lorsque nous l'aurons valid&eacute;, vous recevrez un contrat d'investissement &agrave; signer en ligne, via notre partenaire Signsquid.", 'yproject' ); ?><br /><br />
		<?php endif; ?>

		<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST" enctype="multipart/form-data">
			<input type="file" name="check_picture" />
			<button type="submit" class="button"><?php _e( "Envoyer", 'yproject' ); ?></button>
			<input type="hidden" name="action" value="post_invest_check" />
			<input type="hidden" name="campaign_id" value="<?php echo $campaign->ID; ?>" />
		</form>
			
		<br /><br />

		<?php _e( "Si vous ne pouvez pas prendre le ch&egrave;que en photo pour l'instant,", 'yproject' ); ?>
		<?php _e( "vous pouvez confirmer votre investissement", 'yproject' ); ?>
		<?php _e( "et nous envoyer ce chèque &agrave; l'adresse investir@wedogood.co quand ce sera possible.", 'yproject' ); ?>
		
		<br /><br />

		<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST">
			<button type="submit" class="button"><?php _e( "Confirmer et envoyer plus tard", 'yproject' ); ?></button>
			<input type="hidden" name="action" value="post_confirm_check" />
			<input type="hidden" name="campaign_id" value="<?php echo $campaign->ID; ?>" />
		</form>
			
		<br /><br />

	<?php endif;

}