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
		<?php _e( 'account.identitydocs.AUTHENTICATION_TEXT_1', 'yproject' ); ?>
		<?php _e( 'account.identitydocs.AUTHENTICATION_TEXT_2', 'yproject' ); ?><br>
		<?php _e( 'account.identitydocs.AUTHENTICATION_TEXT_3', 'yproject' ); ?><br><br>
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
		* <?php _e( 'common.REQUIRED_FIELDS', 'yproject' ); ?><br>
	</p>

	<?php foreach ( $fields_phone_notification as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>
	<div class="align-left phone-info">
		<?php _e( 'account.identitydocs.SMS_EXPLAINED', 'yproject' ); ?>
	</div>

	<div class="phone-number-hidden">
		<?php foreach ( $fields_phone_number as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>
	</div>
	
	<div id="user-identify-docs-form-buttons">
		<button type="submit" class="button save red"><?php _e( 'account.identitydocs.SEND_DOCUMENTS', 'yproject' ); ?></button>
	</div>
	
</form>