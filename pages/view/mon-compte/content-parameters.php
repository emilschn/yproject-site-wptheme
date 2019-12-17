<?php global $stylesheet_directory_uri, $country_list; ?>
<?php
	$page_controler = WDG_Templates_Engine::instance()->get_controler();
	$WDGUserPasswordForm = $page_controler->get_user_password_form();
	if ( $WDGUserPasswordForm ) {
		$fields_password_hidden = $WDGUserPasswordForm->getFields( WDG_Form_User_Password::$field_group_hidden );
		$fields_password_visible = $WDGUserPasswordForm->getFields( WDG_Form_User_Password::$field_group_password );
	} else {
		$WDGUserUnlinkFacebookForm = $page_controler->get_user_unlink_facebook_form();
		$fields_unlink_facebook_hidden = $WDGUserUnlinkFacebookForm->getFields( WDG_Form_User_Unlink_Facebook::$field_group_hidden );
		$fields_unlink_facebook_visible = $WDGUserUnlinkFacebookForm->getFields( WDG_Form_User_Unlink_Facebook::$field_group_password );
	}
?>


<h2><?php _e( "Enregistrez vos informations", 'yproject' ); ?></h2>


<?php 
	locate_template( array( 'pages/view/common/form-parameters.php'  ), true );
?>

<br>
<hr>
<br>
<br>

<?php if ( $WDGUserPasswordForm ): ?>
	<form method="post" class="db-form form-register v3 full" enctype="multipart/form-data">
		<h2><?php _e( "Modification de mot de passe", 'yproject' ); ?></h2>

		<?php foreach ( $fields_password_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<?php foreach ( $fields_password_visible as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<div id="user-details-form-buttons">
			<button type="submit" class="button save red"><?php _e( "Enregistrer les modifications", 'yproject' ); ?></button>
		</div>
	</form>

<?php else: ?>
	<form method="post" class="db-form form-register v3 full" enctype="multipart/form-data">
		<h2><?php _e( "D&eacute;lier mon compte Facebook", 'yproject' ); ?></h2>

		<?php foreach ( $fields_unlink_facebook_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<?php foreach ( $fields_unlink_facebook_visible as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<div id="user-details-form-buttons">
			<button type="submit" class="button save red"><?php _e( "D&eacute;lier mon compte Facebook et appliquer ce mot de passe", 'yproject' ); ?></button>
		</div>
	</form>


<?php endif;