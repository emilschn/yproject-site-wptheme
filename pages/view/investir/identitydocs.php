<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php
$fields_hidden = $page_controler->get_form()->getFields( WDG_Form_User_Identity_Docs::$field_group_hidden );
$fields_files = $page_controler->get_form()->getFields( WDG_Form_User_Identity_Docs::$field_group_files );
$fields_files_orga = $page_controler->get_form()->getFields( WDG_Form_User_Identity_Docs::$field_group_files_orga );
$form_errors = $page_controler->get_form_errors();
?>

<?php if ( $page_controler->is_form_success_displayed() ): ?>
	<div class="center upload-docs">
		<div class="wdg-message confirm">
			<?php _e( "Nous avons bien re&ccedil;u vos documents. Ils ont &eacute;t&eacute; transmis &agrave; notre prestataire Lemon Way pour validation.", 'yproject' ); ?>
		</div>

		<div>
			<?php echo sprintf( __( "Nous avons enregistr&eacute; votre intention d'investissement de %s &euro;.", 'yproject' ), $page_controler->get_current_investment()->get_session_amount() ); ?><br>
			<?php _e( "Lorsque les documents seront valid&eacute;s, vous recevrez une notification vous permettant de finaliser votre investissement en proc&eacute;dant au paiement.", 'yproject' ); ?><br>
			<?php _e( "Vous pourrez aussi le retrouver au sein de votre compte personnel.", 'yproject' ); ?><br><br>
			<?php _e( "Merci, et &agrave; bient&ocirc;t !", 'yproject' ); ?><br><br>
		</div>
		
		<div class="align-center">
			<a href="<?php echo $page_controler->get_current_campaign()->get_public_url(); ?>" class="button transparent"><?php _e( "Retour au projet" ); ?></a>
			<br><br>
		</div>
	</div>

<?php else: ?>
	<?php if ( !empty( $form_errors ) ): ?>
		<div class="wdg-message error">
			<?php foreach ( $form_errors as $form_error ): ?>
				<?php echo $form_error; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<form method="POST" enctype="multipart/form-data" class="db-form v3 full bg-white enlarge">

		<?php foreach ( $fields_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<?php foreach ( $fields_files as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<?php if ( !empty( $fields_files_orga ) ): ?>
			<?php foreach ( $fields_files_orga as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
		<?php endif; ?>

		<p class="align-left">
			<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
		</p>

		<div>
			<button type="submit" class="button save red"><?php _e( "Envoyer les documents", 'yproject' ); ?></button>
		</div>

	</form>
<?php endif; ?>