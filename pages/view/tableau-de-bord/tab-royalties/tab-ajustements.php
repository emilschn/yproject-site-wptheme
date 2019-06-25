<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
if ( $page_controler->can_access_admin() ) {
	$fields_hidden = $page_controler->get_form_adjustment()->getFields( WDG_Form_Adjustement::$field_group_hidden );
	$fields_adjustment = $page_controler->get_form_adjustment()->getFields( WDG_Form_Adjustement::$field_group_adjustment );
}
?>

<div id="stat-subtab-ajustements" class="stat-subtab hidden">
	
	<h3><?php _e( "Somme des reliquats non-vers&eacute;s aux investisseurs", 'yproject' ); ?></h3>
	<?php
	$sum_remaining_amount = 0;
	$declaration_list = WDGROIDeclaration::get_list_by_campaign_id( $page_controler->get_campaign_id() );
	foreach ( $declaration_list as $declaration ) {
		$sum_remaining_amount += $declaration->remaining_amount;
	}
	?>
	<?php echo UIHelpers::format_number( $sum_remaining_amount ); ?> &euro;<br><br>

	<h3><?php _e( "Documents &agrave; traiter (pas encore affect&eacute;s &agrave; un ajustement)", 'yproject' ); ?></h3>
	<?php $files = WDGWPREST_Entity_Project::get_files_unused( $page_controler->get_campaign()->get_api_id(), 'project_document' ); ?>
	<?php foreach ( $files as $file_item ): $file_item_metadata = json_decode( $file_item->metadata ); ?>
		<a href="<?php echo $file_item->url; ?>" target="_blank"><?php echo $file_item_metadata->name; ?></a><br>
		<?php echo $file_item_metadata->details; ?>
		<br><br>
	<?php endforeach; ?>
	<br>
	
	<h3><?php _e( "Ajustements", 'yproject' ); ?></h3>
	<?php if ( $page_controler->can_access_admin() ): ?>
		<div class="db-form v3 full center admin-theme">
			<?php if ( $page_controler->get_form_adjustment_feedback_message() == 'success' ): ?>
				<div class="wdg-message confirm">
					<?php _e( "Ajustement ajout&eacute;", 'yproject' ); ?>
				</div>
			<?php elseif ( $page_controler->get_form_adjustment_feedback_message() == 'error' ): ?>
				<div class="wdg-message error">
					<?php _e( "L'ajustement ne s'est pas ajout&eacute;", 'yproject' ); ?>
				</div>
			<?php endif; ?>

			<button type="button" id="display-form-add-adjustment" class="button admin-theme"><?php _e( "Ajouter un ajustement", 'yproject' ); ?></button>
		</div>
	
	

		<form action="<?php echo $page_controler->get_form_adjustment_action(); ?>" method="post" id="form-add-adjustment" class="db-form v3 full center bg-white admin-them hidden">

			<?php foreach ( $fields_hidden as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
			<?php endforeach; ?>

			<?php foreach ( $fields_adjustment as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
			<?php endforeach; ?>

			<button type="submit" class="button admin-theme clear"><?php _e( "Ajouter", 'yproject' ); ?></button>

			<div class="clear"></div>

		</form>
		<br><br>
	<?php endif; ?>
	
	
	A venir...<br><br>
	
	
</div>