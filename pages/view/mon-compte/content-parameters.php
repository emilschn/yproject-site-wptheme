<?php global $stylesheet_directory_uri, $country_list; ?>
<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUserDetailsForm = $page_controler->get_user_details_form();
$fields_hidden = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_hidden );
$fields_basics = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_basics );
$fields_complete = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_complete );
$fields_extended = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_extended );
$WDGUserPasswordForm = $page_controler->get_user_password_form();
if ( $WDGUserPasswordForm ) {
	$fields_password_hidden = $WDGUserPasswordForm->getFields(WDG_Form_User_Password::$field_group_hidden );
	$fields_password_visible = $WDGUserPasswordForm->getFields( WDG_Form_User_Password::$field_group_password );
}
$form_feedback = $page_controler->get_user_form_feedback();
?>


<form method="POST" enctype="multipart/form-data" class="db-form form-register v3 full">
		
	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<h2><?php _e( "Enregistrez vos informations", 'yproject' ); ?></h2>

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

	<?php foreach ( $fields_basics as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<?php if ( !empty( $fields_complete ) ): ?>
	<?php foreach ( $fields_complete as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>
	<?php endif; ?>

	<?php if ( !empty( $fields_extended ) ): ?>
	<?php foreach ( $fields_extended as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>
	<?php endif; ?>
	
	<?php /*if (!isset($_SESSION['redirect_current_campaign_id'])): ?>
	<label for="avatar_image" class="standard-label"><?php _e( "Avatar", 'yproject' ); ?></label>
	<input type="file" name="avatar_image" id="avatar_image" />
	<input type="checkbox" name="reset_avatar"> Supprimer l'avatar actuel
	<?php $facebook_meta = get_user_meta($current_user->ID, 'social_connect_facebook_id', true); ?>
	<?php if ( !empty( $facebook_meta ) ): ?>
	<input type="checkbox" name="facebook_avatar">Utiliser l'avatar facebook
	<?php endif; ?>
	<?php endif;*/ ?>
	
	<p class="align-left">
	<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
	</p>

	<div id="user-details-form-buttons">
		<button type="submit" class="button save red"><?php _e( "Enregistrer les modifications", 'yproject' ); ?></button>
	</div>
	
</form>
<br>

<?php if ( $WDGUserPasswordForm ): ?>
<hr>

<br>
<br>
<form method="post" class="db-form form-register v3 full" enctype="multipart/form-data">
	<h2><?php _e( "Modification de mot de passe", 'yproject' ); ?></h2>

	<?php foreach ( $fields_password_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<?php if ( !empty( $fields_password_visible ) ): ?>
	<?php foreach ( $fields_password_visible as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>
	<?php endif; ?>

	<div id="user-details-form-buttons">
		<button type="submit" class="button save red"><?php _e( "Enregistrer les modifications", 'yproject' ); ?></button>
	</div>
</form>
<?php endif; ?>