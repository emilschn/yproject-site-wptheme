<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<h2><?php _e( "Documents", 'yproject' ); ?></h2>
<div class="db-form v3 center">
	<br>
	
	<?php if ( $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_funded || $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_closed ): ?>
	<a href="<?php echo $page_controler->get_campaign()->get_funded_certificate_url(); ?>" download="attestation-levee-fonds.pdf" class="button red"><?php _e( "Attestation de lev&eacute;e de fonds", 'yproject' ); ?></a>
	<?php else: ?>
	<?php _e( "Prochainement :" ); ?> <?php _e( "Attestation de lev&eacute;e de fonds", 'yproject' ); ?>
	<?php endif; ?>
	<br><br>
	
	<?php if ( $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_funded || $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_closed ): ?>
		
		<?php if ( $page_controler->can_access_admin() ): ?>

			<?php $campaign_bill = new WDGCampaignBill( $page_controler->get_campaign(), WDGCampaignBill::$tool_name_quickbooks, WDGCampaignBill::$bill_type_crowdfunding_commission ); ?>
			<?php if ( $campaign_bill->can_generate() ): ?>
			<form action="<?php echo admin_url( 'admin-post.php?action=generate_campaign_bill'); ?>" method="post" id="generate_campaign_bill_form" class="field admin-theme">
				/!\ <?php _e( "Ce bouton cr&eacute;era une nouvelle facture sur l'outil de facturation. Assurez-vous que cette facture n'a pas déjà été générée auparavant.", 'yproject' ); ?> /!\<br>
				<?php _e( "Les informations suivantes seront utilis&eacute;es pour g&eacute;n&eacute;rer la facture :", 'yproject' ); ?>

				<ul>
					<li><?php echo $campaign_bill->get_line_title(); ?></li>
					<li><?php echo nl2br( $campaign_bill->get_line_description() ); ?></li>
					<li><?php echo nl2br( $campaign_bill->get_bill_description() ); ?></li>
				</ul>
				<br>
				<div class="align-center">
					<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>" />
					<button class="button blue-pale"><?php _e( "G&eacute;n&eacute;rer la facture de la levée de fonds", 'yproject' ); ?></button>
				</div>
			</form>

			<?php else: ?>
			<div class="field admin-theme">
				Vous ne pouvez pas encore générer la facture pour cette campagne.
				Avez-vous vérifié que l'identifiant Quickbooks et la commission sont bien paramétrés ?
			</div>
			<?php endif; ?>
			<br><br>
		
		<?php endif; ?>
		
		
		<?php if ( FALSE ): ?>
			
		<?php else: ?>
			<?php _e( "Retrouvez prochainement ici la facture de votre lev&eacute;e de fonds.", 'yproject' ); ?>
		<?php endif; ?>
		<br><br><br>
	
		
		<?php if ( $page_controler->get_campaign_contracts_url() ): ?>
			<a href="<?php echo $page_controler->get_campaign_contracts_url(); ?>" download="contrats.zip" class="button red"><?php _e( "T&eacute;l&eacute;charger les fichiers des contrats", 'yproject' ); ?></a>
		<?php else: ?>
			<?php _e( "Retrouvez prochainement ici les contrats des investisseurs.", 'yproject' ); ?>
		<?php endif; ?>
		
	<?php else: ?>
		<?php _e( "Retrouvez prochainement ici vos documents : facture de lev&eacute;e de fonds et contrats des investisseurs.", 'yproject' ); ?>
	<?php endif; ?>
	
	<br><br>
	
</div>