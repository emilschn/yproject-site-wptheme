<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php
$fields_hidden = $page_controler->get_form()->getFields( WDG_Form_User_Identity_Docs::$field_group_hidden );
$fields_files = $page_controler->get_form()->getFields( WDG_Form_User_Identity_Docs::$field_group_files );
$fields_files_orga = $page_controler->get_form()->getFields( WDG_Form_User_Identity_Docs::$field_group_files_orga );
$fields_phone_notification = $page_controler->get_form()->getFields( WDG_Form_User_Identity_Docs::$field_group_phone_notification );
$fields_phone_number = $page_controler->get_form()->getFields( WDG_Form_User_Identity_Docs::$field_group_phone_number );
$form_errors = $page_controler->get_form_errors();
?>

<?php if ( $page_controler->is_form_success_displayed() ): ?>
	<div class="center upload-docs">
		<?php if ( $page_controler->is_form_file_sent_displayed() ): ?>
			<div class="wdg-message confirm">
				<?php _e( "Nous avons bien re&ccedil;u vos documents. Ils ont &eacute;t&eacute; transmis &agrave; notre prestataire Lemon Way pour validation.", 'yproject' ); ?>
			</div>
		<?php endif; ?>

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

	<form method="POST" enctype="multipart/form-data" class="db-form v3 full bg-white enlarge identitydocs">
	
		<p class="align-justify resp-item-1">
			<?php _e( "Les justificatifs d'identit&eacute; sont imm&eacute;diatement transmis, puis v&eacute;rifi&eacute;s sous 48h par notre prestataire de paiement, Lemon Way.", 'yproject' ); ?>
			<?php _e( "Ils sont d'abord analys&eacute;s par des services automatiques puis par une personne physique en cas d'erreur ou de cas particulier.", 'yproject' ); ?><br>
			<?php _e( "En cas d'erreur manifeste de l'analyse de vos documents, vous pouvez nous contacter &agrave; l'adresse investir@wedogood.co ou sur le chat en ligne.", 'yproject' ); ?><br><br>
		</p>

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

		<p class="align-left resp-item-5">
			<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
		</p>

		<?php if ( $fields_phone_notification ): foreach ( $fields_phone_notification as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; endif; ?>
		<div class="align-left phone-info">
			<?php _e( "Ce SMS sera envoy&eacute; uniquement lorsque tous les documents sont valid&eacute;s, ou en cas de documents incomplets ou refus&eacute;s.", 'yproject' ); ?>
		</div>

		<div class="phone-number-hidden">
			<?php foreach ( $fields_phone_number as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
		</div>

		<div class="align-center resp-item-6">
			<button type="submit" class="button save red"><?php _e( "Envoyer les documents", 'yproject' ); ?></button>
			<br><br>
			<button type="submit" class="button save transparent"><?php _e( "Envoyer plus tard", 'yproject' ); ?></button>
			<div style="margin-top: 8px;">
				(<?php _e( "et investir plus tard", 'yproject' ); ?>)
			</div>
		</div>

	</form>
<?php endif; ?>