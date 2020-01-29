<?php global $WDGOrganization; ?>
<?php
	$page_controler = WDG_Templates_Engine::instance()->get_controler();
	$WDGOrganizationDetailsForm = new WDG_Form_Organization_Details( $WDGOrganization->get_wpref(), TRUE );
	$fields_hidden = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_hidden );
	$fields_complete = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_complete );
	$fields_address = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_address );
	$fields_admin = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_admin );
	$WDGUser_current = WDGUser::current();
?>

<form method="POST" class="<?php echo $page_controler->get_form_css_classes();?>" action="<?php echo admin_url( 'admin-post.php?action=user_account_organization_details' ); ?>" novalidate>
		
	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<?php if ( !empty( $form_feedback[ 'errors' ] ) ): ?>
	<div class="form-error-general align-left">
		<?php _e( "Certaines erreurs ont bloqu&eacute; l'enregistrement de vos donn&eacute;es :", 'yproject' ); ?><br>
		<?php foreach ( $form_feedback[ 'errors' ] as $error ): ?>
			- <?php echo $error[ 'text' ]; ?><br>
		<?php endforeach; ?>
		<br><br>
	</div>
	<?php endif; ?>
	<?php if ( !empty( $form_feedback[ 'success' ] ) ): ?>
	<div class="form-success-general align-left">
		<?php foreach ( $form_feedback[ 'success' ] as $message ): ?>
			<?php echo $message; ?>
		<?php endforeach; ?>
		<br><br>
	</div>
	<?php endif; ?>

	<?php foreach ( $fields_complete as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<h2><?php _e( "Si&egrave;ge social" ); ?></h2>
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
		<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
	</p>

	<div id="organization-details-form-buttons">
		<button type="submit" class="button save red <?php if ( $page_controler->get_campaign() !== FALSE && !$page_controler->get_campaign()->is_preparing() ) { ?>confirm<?php } ?>">
			<?php _e( "Enregistrer les modifications", 'yproject' ); ?>
		</button>
	</div>
	
</form>