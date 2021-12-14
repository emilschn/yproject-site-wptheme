<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$today_date = new DateTime();
?>

<h2><?php _e( "Documents", 'yproject' ); ?></h2>
<div class="db-form v3 center">
	<div class="align-left">
		<br>
		<strong><?php _e( "Documents g&eacute;n&eacute;raux :", 'yproject' ); ?></strong>
		<br><br>
		<a href="<?php echo site_url(); ?>/wp-content/uploads/2018/08/WDG-kit-expert-comptable-2.pdf" target="_blank"><?php _e( "Kit pour expert comptable", 'yproject' ); ?></a>
		<br><br><br><br>
	</div>
	
	<div class="align-left">
		<strong><?php _e( "Documents de votre lev&eacute;e de fonds :", 'yproject' ); ?></strong>
		<br><br>
	</div>
	<?php if ( $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_funded || $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_closed ): ?>
		<a href="<?php echo $page_controler->get_campaign()->get_funded_certificate_url(); ?>?time=<?php echo time(); ?>" download="attestation-levee-fonds.pdf" class="button red"><?php _e( "Attestation de lev&eacute;e de fonds", 'yproject' ); ?></a>
		<br>
		<form action="<?php echo admin_url( 'admin-post.php?action=generate_campaign_funded_certificate'); ?>" method="post" id="generate_campaign_funded_certificate" class="field">
			<div class="align-center">
				<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>">
				Date du dernier investissement à prendre en compte : <input type="text" name="date_end" value="<?php echo $today_date->format( 'Y-m-d' ); ?>">
				<br>
				Champs libre : <input type="text" name="free_field" size="50" value="" placeholder="Attestation temporaire XXX ; Annule et remplace l'attestation du XX/XX/XXXX, ...">
				<br>
				Frais complémentaires : <input type="text" name="additionnal_fees" value="" placeholder="50"> € TTC
				<br>
				<button class="button red"><?php _e( "Reg&eacute;n&eacute;rer l'attestation de lev&eacute;e de fonds", 'yproject' ); ?></button>
			</div>
		</form>
		
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
					<button class="button admin-theme"><?php _e( "G&eacute;n&eacute;rer la facture de la levée de fonds", 'yproject' ); ?></button>
				</div>
			</form>

			<?php else: ?>
			<div class="field admin-theme">
				Vous ne pouvez pas encore générer la facture pour cette campagne.<br>
				Paramètres à vérifier :<br>
				- Identifiant Quickbooks (Organisation)<br>
				- Commission de la plateforme (Financement)<br>
				- Type de produit Quickbooks (Campagne)<br>
				- Acquisition Quickbooks (Campagne)<br>
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
			
		<?php if ( $page_controler->can_access_admin() ): ?>
			<form action="<?php echo admin_url( 'admin-post.php?action=generate_campaign_contracts_archive'); ?>" method="post" class="field admin-theme">
				<br><br><strong>Mieux vaut attendre la fin de la période de rétractation pour appuyer sur ce bouton !</strong><br><br>
				<div class="align-center">
					<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>" />
					<button class="button admin-theme"><?php _e( "G&eacute;n&eacute;rer le zip de la liste des contrats", 'yproject' ); ?></button>
				</div>
			</form>
			<br><br>
		<?php endif; ?>
		
	<?php else: ?>
		<?php _e( "Retrouvez prochainement ici vos documents : facture de lev&eacute;e de fonds et contrats des investisseurs.", 'yproject' ); ?>
	<?php endif; ?>
	
	<br><br>
	
	
	<?php if ( $page_controler->can_access_admin() ): ?>
		<form action="<?php echo admin_url( 'admin-post.php?action=generate_yearly_fiscal_documents'); ?>" method="post" class="field admin-theme">
			<div class="align-center">
				<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>" />
				<input type="hidden" name="fiscal_year" value="0" />
				<input type="hidden" name="init" value="1" />
				<button class="button admin-theme">G&eacute;n&eacute;rer les fichiers pour les déclarations aux impots</button>
			</div>
		</form>
		<br><br>

		<?php if ( $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_closed ): ?>
			<div class="field admin-theme">
				<?php _e( "En cas de recouvrement", 'yproject' ); ?><br><br>
				<?php $campaign_debt_files = new WDGCampaignDebtFiles( $page_controler->get_campaign() ); ?>
				<a href="<?php echo $campaign_debt_files->get_recover_certificate(); ?>?time=<?php echo time(); ?>" download="debt-certificate.pdf" class="button admin-theme"><?php _e( "Attestation de cr&eacute;ance", 'yproject' ); ?></a>
				<a href="<?php echo $campaign_debt_files->get_recover_list(); ?>?time=<?php echo time(); ?>" download="investors-list.csv" class="button admin-theme"><?php _e( "Liste de l'&eacute;tat des versements", 'yproject' ); ?></a>
			</div>
		<?php endif; ?>


	<?php endif; ?>
	
</div>