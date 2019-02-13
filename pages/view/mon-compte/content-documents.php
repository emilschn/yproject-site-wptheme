<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$list_current_organizations = $page_controler->get_current_user_organizations();
$WDGUserTaxExemptionForm = $page_controler->get_user_tax_exemption_form();
$fields_hidden = $WDGUserTaxExemptionForm->getFields( WDG_Form_User_Tax_Exemption::$field_group_hidden );
?>

<h2><?php _e( "Documents de", 'yproject' ); ?> <?php echo $page_controler->get_user_name(); ?></h2>

<p>
	<?php _e( "Les informations ci-dessous sont celles de votre compte personnel.", 'yproject' ); ?><br>
	<?php if ( count( $list_current_organizations ) > 0 ): ?>
	<?php _e( "Retrouvez celles de vos organisations en utilisant le menu.", 'yproject' ); ?>
	<?php endif; ?>
</p>


<h3><?php _e( "Mes attestations de transactions annuelles", 'yproject' ); ?></h3>
<?php
$has_declaration = false;
$date_now = new DateTime();
?>
<?php for( $year = 2016; $year < $date_now->format('Y'); $year++ ): ?>
	<?php if ( $WDGUser_displayed->has_royalties_for_year( $year ) ): ?>
		<?php
		$has_declaration = true;
		$declaration_url = $WDGUser_displayed->get_royalties_certificate_per_year( $year );
		?>
		<a href="<?php echo $declaration_url; ?>" download="attestation-royalties-<?php echo $year; ?>.pdf" class="button blue-pale download-certificate">Télécharger l'attestation <?php echo $year; ?></a>
		<br><br>
	<?php endif; ?>
<?php endfor; ?>
<?php if ( !$has_declaration ): ?>
	<?php _e( "Aucune", 'yproject' ); ?>
<?php endif; ?>
<br>
<br>



<?php if ( $page_controler->get_can_ask_tax_exemption() ): ?>

<h3><?php _e( "Mes demandes de dispense de pr&eacute;l&egrave;vement annuelles", 'yproject' ); ?></h3>

<?php _e( "Cette demande de dispense peut &ecirc;tre faite par les investisseurs dont la r&eacute;sidence fiscale est en France.", 'yproject' ); ?><br>
<?php _e( "Une fois le montant de la souscription rembours&eacute;, toute personne soumise à l'imp&ocirc;t sur le revenu sera soumise au Pr&eacute;l&egrave;vement Forfaitaire Unique (flat tax) de 30 % sur la plus-value r&eacute;alis&eacute;e sur son investissement, dont 12,8 % de pr&eacute;l&egrave;vement forfaitaire et 17,2 % de contributions et pr&eacute;l&egrave;vements sociaux.", 'yproject' ); ?><br>
<?php _e( "Toutefois, vous pouvez nous adresser une demande de dispense pour &ecirc;tre impos&eacute; au bar&egrave;me de l'imp&ocirc;t sur le revenu selon votre taux d'imposition, si le revenu fiscal de référence de votre foyer fiscal est inf&eacute;rieur &agrave; :", 'yproject' ); ?><br>
<?php _e( "- 25 000 € (pour les contribuables c&eacute;libataires, divorc&eacute;s ou veufs) ;", 'yproject' ); ?><br>
<?php _e( "- 50 000 € (pour les contribuables soumis &agrave; imposition commune).", 'yproject' ); ?><br>
<?php _e( "Cette dispense ne s'appliquera qu'&agrave; la partie concernant le pr&eacute;l&egrave;vement forfaitaire (12,8 %).", 'yproject' ); ?><br>
<br>

<?php
$date_today = new DateTime();
$date_start_for_wdg = 2018;
?>
<?php for ( $year = $date_start_for_wdg; $year <= $date_today->format( 'Y' ); $year++ ): ?>
	<?php $tax_exemption_filename = $WDGUser_displayed->has_tax_exemption_for_year( $year ); ?>
	<?php if ( !empty( $tax_exemption_filename ) ): ?>
		<a href="<?php echo $tax_exemption_filename; ?>" download="dispense-prelevement-<?php echo $year; ?>.pdf" class="button blue-pale download-certificate">Télécharger la dispense <?php echo $year; ?></a>
		<br><br>
	<?php endif; ?>
<?php endfor; ?>

<?php if ( $page_controler->get_show_user_tax_exemption_form() ): ?>
<br><br>
<div class="db-form v3 full">
	<button id="display-tax-exemption-form" class="button blue"><?php _e( "Faire ma demande de dispense annuelle", 'yproject' ); ?></button>
	<br><br>
</div>

<form method="post" id="tax-exemption-form" class="db-form v3 full enlarge hidden">
	
	<div id="tax-exemption-preview">
		<?php echo $page_controler->get_tax_exemption_preview(); ?>
	</div>

	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>

	<button type="button" class="button transparent half left"><?php _e( "Annuler", 'yproject' ); ?></button>
	<button type="submit" class="button red half right"><?php _e( "Enregistrer ma demande de dispense", 'yproject' ); ?></button>
	
</form>

<?php endif; ?>

<br>

<?php endif; ?>