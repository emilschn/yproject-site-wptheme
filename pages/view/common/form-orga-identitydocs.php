<?php global $WDGOrganization; ?>
<?php
	$page_controler = WDG_Templates_Engine::instance()->get_controler();
	$WDGOrganizationIdentityDocsForm = new WDG_Form_User_Identity_Docs( $WDGOrganization->get_wpref(), TRUE );
	$fields_hidden = $WDGOrganizationIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_hidden );
	$fields_files = $WDGOrganizationIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_files );
	$fields_files_orga = $WDGOrganizationIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_files_orga );
?>

<form method="POST" enctype="multipart/form-data" class="<?php echo $page_controler->get_form_css_classes();?> account-form" action="<?php echo admin_url( 'admin-post.php?action=user_account_organization_identitydocs' ); ?>">
	
	<p class="align-justify">
		<?php _e( 'account.identitydocs.orga.AUTHENTICATION_TEXT_1', 'yproject' ); ?>
		<?php _e( 'account.identitydocs.orga.AUTHENTICATION_TEXT_2', 'yproject' ); ?><br>
		<?php _e( 'account.identitydocs.orga.AUTHENTICATION_TEXT_3', 'yproject' ); ?><br><br>
	</p>

	<?php $kyc_duplicates = $WDGOrganizationIdentityDocsForm->getDuplicates(); ?>
	<?php if ( !empty( $kyc_duplicates ) ): ?>
		<div class="wdg-message error">
			<?php _e( 'account.identitydocs.SOME_FILES_DOUBLE', 'yproject' ); ?><br>
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
	
	<span style="color: #EE0000;"><em>--&gt; <?php _e( 'account.identitydocs.orga.FOURTH_PERSON', 'yproject' ); ?></em></span>
	<br><br>
	
	<p class="align-left">
		* <?php _e( 'common.REQUIRED_FIELDS', 'yproject' ); ?><br>
	</p>

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
</form>