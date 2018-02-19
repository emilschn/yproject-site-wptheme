<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="db-form v3 center">
	<br>
	
	<?php if ( $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_funded || $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_closed ): ?>
	<a href="<?php echo $page_controler->get_campaign()->get_funded_certificate_url(); ?>" download="attestation-levee-fonds.pdf" class="button red"><?php _e( "Attestation de lev&eacute;e de fonds", 'yproject' ); ?></a>
	<?php else: ?>
	<?php _e( "A venir :" ); ?> <?php _e( "Attestation de lev&eacute;e de fonds", 'yproject' ); ?>
	<?php endif; ?>
	<br><br>
	
	<?php _e( "A venir :" ); ?> <?php _e( "Facture de lev&eacute;e de fonds", 'yproject' ); ?>
	<br><br>
	
	<?php _e( "A venir :" ); ?> <?php _e( "Contrats investisseurs", 'yproject' ); ?>
	<br><br>
	
</div>