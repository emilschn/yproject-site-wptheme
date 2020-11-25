<?php global $WDGOrganization; ?>
<?php
	$page_controler = WDG_Templates_Engine::instance()->get_controler();
	$WDGOrganizationIdentityDocsForm = new WDG_Form_User_Identity_Docs( $WDGOrganization->get_wpref(), TRUE );
	$fields_hidden = $WDGOrganizationIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_hidden );
	$fields_files = $WDGOrganizationIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_files );
	$fields_files_orga = $WDGOrganizationIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_files_orga );
?>

<form method="POST" enctype="multipart/form-data" class="<?php echo $page_controler->get_form_css_classes();?>" action="<?php echo admin_url( 'admin-post.php?action=user_account_organization_identitydocs' ); ?>">
	
	<p class="align-justify">
		<?php _e( "Les justificatifs d'identit&eacute; sont imm&eacute;diatement transmis, puis v&eacute;rifi&eacute;s sous 48h par notre prestataire de paiement, Lemon Way.", 'yproject' ); ?>
		<?php _e( "Ils sont d'abord analys&eacute;s par des services automatiques puis par une personne physique en cas d'erreur ou de cas particulier.", 'yproject' ); ?><br>
		<?php _e( "En cas d'erreur manifeste de l'analyse de vos documents, vous pouvez nous contacter &agrave; l'adresse investir@wedogood.co ou sur le chat en ligne.", 'yproject' ); ?><br><br>
	</p>

	<?php $kyc_duplicates = $WDGOrganizationIdentityDocsForm->getDuplicates(); ?>
	<?php if ( !empty( $kyc_duplicates ) ): ?>
		<div class="wdg-message error">
			<?php _e( "Certains fichiers ont &eacute;t&eacute; transmis en doublon :", 'yproject' ); ?><br>
			<?php foreach ( $kyc_duplicates as $str_duplicate ): ?>
				- <?php echo $str_duplicate; ?><br>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
		
	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<?php if ($page_controler->get_controler_name() == 'mon-compte'): ?>
		<?php foreach ( $fields_files as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php foreach ( $fields_files_orga as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>
	
	<span style="color: #EE0000;"><em>--&gt; <?php _e( "Si une quatri&egrave;me personne ou une personne morale d&eacute;tient au moins 25% de votre capital, merci de nous le signaler sur support@wedogood.co.", 'yproject' ); ?></em></span>
	<br><br>
	
	<p class="align-left">
		<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
	</p>

	<div id="user-identify-docs-form-buttons">
		<button type="submit" class="button save red"><?php _e( "Envoyer les documents", 'yproject' ); ?></button>
	</div>
	
</form>