<?php global $WDGOrganization; ?>
<?php
$WDGOrganizationDetailsForm = new WDG_Form_Organization_Details( $WDGOrganization->get_wpref(), TRUE );
$fields_hidden = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_hidden );
$fields_complete = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_complete );
$fields_address = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_address );
?>


<h2><?php _e( "Informations de", 'yproject' ); ?> <?php echo $WDGOrganization->get_name(); ?></h2>

<form method="POST" class="db-form form-register v3 full" action="<?php echo admin_url( 'admin-post.php?action=user_account_organization_details' ); ?>">
		
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
	
	<p class="align-left">
		<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
	</p>

	<div id="organization-details-form-buttons">
		<button type="submit" class="button save red"><?php _e( "Enregistrer les modifications", 'yproject' ); ?></button>
	</div>
	
</form>