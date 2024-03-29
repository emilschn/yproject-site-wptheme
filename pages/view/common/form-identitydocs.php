
<?php
	global $stylesheet_directory_uri;
	$page_controler = WDG_Templates_Engine::instance()->get_controler();
	$WDGUserIdentityDocsForm = $page_controler->get_user_identitydocs_form();
	$fields_hidden = $WDGUserIdentityDocsForm->getFields(WDG_Form_User_Identity_Docs::$field_group_hidden );
	$fields_files = $WDGUserIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_files );
	$fields_phone_notification = $WDGUserIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_phone_notification );
	$fields_phone_number = $WDGUserIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_phone_number );
	
	$WDGUser_displayed = $page_controler->get_displayed_user();
	$is_authentified = $WDGUser_displayed->is_lemonway_registered();
	$is_project_owner = $WDGUser_displayed->is_project_owner();
?>

<form method="POST" enctype="multipart/form-data" class="<?php echo $page_controler->get_form_css_classes();?> account-form">

	<p class="align-justify">
		<?php _e( 'account.identitydocs.AUTHENTICATION_TEXT_1', 'yproject' ); ?>
		<?php _e( 'account.identitydocs.AUTHENTICATION_TEXT_2', 'yproject' ); ?><br>
		<?php _e( 'account.identitydocs.AUTHENTICATION_TEXT_3', 'yproject' ); ?><br><br>
	</p>

	<?php if ( $page_controler->has_kyc_duplicates() ): ?>
		<div class="wdg-message error">
			<?php $kyc_duplicates = $page_controler->get_kyc_duplicates(); ?>
			<?php _e( 'account.identitydocs.SOME_FILES_DOUBLE', 'yproject' ); ?><br>
			<?php foreach ( $kyc_duplicates as $str_duplicate ): ?>
				- <?php echo $str_duplicate; ?><br>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( $WDGUserIdentityDocsForm->hasErrors() ): ?>
		<div class="wdg-message error">
			<?php $form_errors = $WDGUserIdentityDocsForm->getPostErrors(); ?>
			<?php foreach ( $form_errors as $form_error ): ?>
				<?php echo $form_error[ 'text' ]; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

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
	
	<?php if ( !$is_authentified || $is_project_owner ): ?>
		<div id="user-identify-docs-form-buttons">
			<button type="submit" class="button save red">
				<span class="button-text">
					<?php _e( 'account.identitydocs.SEND_DOCUMENTS', 'yproject' ); ?>
				</span>
				<span class="button-loading loading align-center hidden">
					<img class="alignverticalmiddle marginright" src="<?php echo $stylesheet_directory_uri; ?>/images/loading-grey.gif" width="30" alt="chargement" /><?php _e( 'common.SENDING', 'yproject' ); ?>			
				</span>
			</button>
		</div>
	<?php endif; ?>
	
</form>