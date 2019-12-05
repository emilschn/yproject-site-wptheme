<?php
    $page_controler = WDG_Templates_Engine::instance()->get_controler();
    $WDGUserIdentityDocsForm = $page_controler->get_user_identitydocs_form();
    $fields_hidden = $WDGUserIdentityDocsForm->getFields(WDG_Form_User_Identity_Docs::$field_group_hidden );
	$fields_files = $WDGUserIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_files );
	$fields_phone_notification = $WDGUserIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_phone_notification );
	$fields_phone_number = $WDGUserIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_phone_number );
?>

<form method="POST" enctype="multipart/form-data" class="<?php echo $page_controler->get_form_css_classes();?>">
	
	<p class="align-justify">
		<?php _e( "Afin d'authentifier votre compte, Lemon Way (prestataire de services de paiement agr&eacute;&eacute;) a besoin de deux documents justificatifs d'identit&eacute;.", 'yproject' ); ?>
		<?php _e( "Ces documents sont imm&eacute;diatement transmis, puis v&eacute;rifi&eacute;s sous 48h par Lemon Way. Ils sont d'abord analys&eacute;s par des services automatiques puis par une personne physique en cas d'erreur ou de cas particulier.", 'yproject' ); ?><br>
		<?php _e( "En cas d'erreur manifeste de l'analyse de vos documents, vous pouvez nous contacter &agrave; l'adresse investir@wedogood.co ou sur le chat en ligne.", 'yproject' ); ?><br><br>
	</p>
		
	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<?php foreach ( $fields_files as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>
	
	<p class="align-left">
		<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
	</p>

	<?php foreach ( $fields_phone_notification as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>
	<div class="align-left phone-info">
		<?php _e( "Ce SMS sera envoy&eacute; uniquement lorsque tous les documents sont valid&eacute;s, ou en cas de documents incomplets ou refus&eacute;s.", 'yproject' ); ?>
	</div>

	<div class="phone-number-hidden">
		<?php foreach ( $fields_phone_number as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>
	</div>
	
	<div id="user-identify-docs-form-buttons">
		<button type="submit" class="button save red"><?php _e( "Envoyer les documents", 'yproject' ); ?></button>
	</div>
	
</form>