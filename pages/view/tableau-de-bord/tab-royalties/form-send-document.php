<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$fields_hidden = $page_controler->get_form_document()->getFields( WDG_Form_Declaration_Document::$field_group_hidden );
$fields_document = $page_controler->get_form_document()->getFields( WDG_Form_Declaration_Document::$field_group_document );
?>

<h3><?php _e( "Justificatifs", 'yproject' ); ?></h3>
<?php _e( "D&egrave;s que vous les avez, transmettez-nous les documents attestant du chiffre d'affaires r&eacute;alis&eacute; (comptes annuels, d&eacute;clarations de TVA, attestation de votre expert-comptable...).", 'yproject' ); ?>
<br><br>

<div class="db-form v3 full center">
	<?php if ( $page_controler->get_form_document_feedback_message() == 'success' ): ?>
		<div class="wdg-message confirm">
			<?php _e( "Document transmis &agrave; WE DO GOOD", 'yproject' ); ?>
		</div>
	<?php elseif ( $page_controler->get_form_document_feedback_message() == 'error' ): ?>
		<div class="wdg-message error">
			<?php _e( "Le document ne s'est pas ajout&eacute;", 'yproject' ); ?>
		</div>
	<?php endif; ?>
	
	<button type="button" id="display-form-send-document" class="button blue"><?php _e( "Transmettre un document", 'yproject' ); ?></button>
</div>

<form action="<?php echo $page_controler->get_form_document_action(); ?>" method="post" enctype="multipart/form-data" id="form-send-document" class="db-form v3 full center bg-white hidden">
	
	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>

	<?php foreach ( $fields_document as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>

	<button type="submit" class="button red clear"><?php _e( "Enregistrer", 'yproject' ); ?></button>

	<div class="clear"></div>

</form>
<br><br>

