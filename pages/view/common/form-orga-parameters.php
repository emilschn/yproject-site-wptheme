<?php global $WDGOrganization; ?>
<?php
	$page_controler = WDG_Templates_Engine::instance()->get_controler();
	$WDGOrganizationDetailsForm = new WDG_Form_Organization_Details( $WDGOrganization->get_wpref(), TRUE );
	$fields_hidden = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_hidden );
	$fields_complete = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_complete );
	$fields_address = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_address );
	$fields_admin = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_admin );
	$WDGUser_current = WDGUser::current();

	$form_feedback = FALSE;
	if ( !empty( $_SESSION[ 'account_organization_form_feedback_' . $WDGOrganization->get_wpref() ] ) ) {
		$form_feedback = $_SESSION[ 'account_organization_form_feedback_' . $WDGOrganization->get_wpref() ];

	}
?>

<form method="POST" class="<?php echo $page_controler->get_form_css_classes();?> account-form" action="<?php echo admin_url( 'admin-post.php?action=user_account_organization_details' ); ?>" novalidate>
		
	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<?php if ( !empty( $form_feedback[ 'errors' ] ) ): ?>
	<div class="form-error-general align-left">
		<?php _e( 'account.parameters.orga.ERROR', 'yproject' ); ?><br>
		<?php foreach ( $form_feedback[ 'errors' ] as $error ): ?>
			<div class="wdg-message error">
				<?php echo $error[ 'text' ]; ?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php if ( !empty( $form_feedback[ 'success' ] ) ): ?>
		<?php foreach ( $form_feedback[ 'success' ] as $message ): ?>
			<div class="wdg-message confirm">
				<?php echo $message; ?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php foreach ( $fields_complete as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<h2><?php _e( 'account.parameters.orga.HEAD_OFFICE', 'yproject' ); ?></h2>
	<?php foreach ( $fields_address as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>
	
	<?php if ( $WDGUser_current->is_admin() ): ?>		
		<div class="field admin-theme">
			<?php foreach ( $fields_admin as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<p class="align-left">
		* <?php _e( 'common.REQUIRED_FIELDS', 'yproject' ); ?><br>
	</p>

	<div id="organization-details-form-buttons">
		<button type="submit" class="button save red <?php if ( $page_controler->get_campaign() !== FALSE && !$page_controler->get_campaign()->is_preparing() ) { ?>confirm<?php } ?>">
			<span class="button-text">
				<?php _e( 'common.SAVE_MODIFICATION', 'yproject' ); ?>
			</span>
			<span class="button-loading loading align-center hidden">
				<img class="alignverticalmiddle marginright" src="<?php echo $stylesheet_directory_uri; ?>/images/loading-grey.gif" width="30" alt="chargement" /><?php _e( 'common.REGISTERING', 'yproject' ); ?>			
			</span>
		</button>
	</div>
	
</form>