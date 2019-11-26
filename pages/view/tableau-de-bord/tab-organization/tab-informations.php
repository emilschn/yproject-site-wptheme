<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
global $campaign_id, $organization_obj;

if ( isset( $organization_obj ) ) {
   $WDGOrganization = $organization_obj;
   $WDGUser_current = WDGUser::current();
   $WDGOrganizationDetailsForm = new WDG_Form_Organization_Details( $WDGOrganization->get_wpref(), TRUE );
   $fields_hidden = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_hidden );
   $fields_complete = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_complete );
   $fields_dashboard = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_dashboard );
   $fields_address = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_address );
?>

<div id="stat-subtab-informations" class="stat-subtab">

	<h3><?php _e( "&Eacute;diter l'organisation portant le projet", 'yproject' ); ?></h3>
	<form id="orgaedit_form" action="" method="POST" enctype="multipart/form-data" class="db-form v3 full center bg-white" data-action="save_edit_organization" novalidate>
		
		<?php foreach ( $fields_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>
		<input type="hidden" name="action" value="save_edit_organization">

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

		<?php foreach ( $fields_dashboard as $field ): ?>
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
			<label for="org_id_quickbooks"><?php _e( "ID Quickbooks", 'yproject' ); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_id_quickbooks" value="<?php echo $organization_obj->get_id_quickbooks(); ?>">
				</span>
			</div>
		</div>
        <?php endif; ?>
        <p class="align-left">
			<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
		</p>

		<div id="organization-details-form-buttons">
			<button type="submit" class="button save red"><?php _e( "Enregistrer les modifications", 'yproject' ); ?></button>
		</div>
    </form>
</div>

<?php 
}