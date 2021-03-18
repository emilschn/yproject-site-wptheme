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
	
	$WDGUser_current = $page_controler->get_current_admin_user();
	$WDGUser_override = $page_controler->get_current_user();
	if ( $WDGUser_current->is_admin() ) {
		$WDGUserDeleteForm = $page_controler->get_user_form_delete();
		if ( $WDGUserDeleteForm ) {
			$fields_delete_hidden = $WDGUserDeleteForm->getFields( WDG_Form_User_Delete::$field_group_hidden );
		}
	}
?>


<h2><?php _e( 'account.parameters.SAVE_PARAMETERS', 'yproject' ); ?></h2>


<?php 
	locate_template( array( 'pages/view/common/form-parameters.php'  ), true );
?>

<br>
<hr>
<br>
<br>

<?php if ( $WDGUserPasswordForm ): ?>
	<form method="post" class="db-form form-register v3 full bg-white" enctype="multipart/form-data">
		<h2><?php _e( 'account.parameters.PASSWORD_MODIFICATION', 'yproject' ); ?></h2>

		<?php foreach ( $fields_password_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<?php foreach ( $fields_password_visible as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<div id="user-details-form-buttons">
			<img style="display: none; margin:auto"  id="image" src="http://wedogood.local/wp-content/themes/yproject/images/loading.gif" />
			<button type="submit" class="button save red"><?php _e( 'common.SAVE_MODIFICATION', 'yproject' ); ?></button>
		</div>
	</form>

<?php else: ?>
	<form method="post" class="db-form form-register v3 full" enctype="multipart/form-data">
		<h2><?php _e( 'account.parameters.UNLINK_FACEBOOK', 'yproject' ); ?></h2>

		<?php foreach ( $fields_unlink_facebook_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<?php foreach ( $fields_unlink_facebook_visible as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<div id="user-details-form-buttons">
			<img style="display: none; margin:auto"  id="image" src="http://wedogood.local/wp-content/themes/yproject/images/loading.gif" />
			<button type="submit" class="button save red"><?php _e( 'account.parameters.UNLINK_FACEBOOK_APPLY_PASSWORD', 'yproject' ); ?></button>
		</div>
	</form>


<?php endif; ?>

<?php /* si l'utilisateur courant est un admin et qu'il prend le contrôle d'un autre utilisateur, il a accès à une fonction de suppression d'utilisateur */ ?>
<?php if ( $page_controler->admin_is_overriding_user() ): ?>
	<br>
	<hr>
	<br>
	<div class="field admin-theme">
		<form method="post" class="db-form form-register v3 full" enctype="multipart/form-data">
			<?php echo $WDGUserDeleteForm->getNonce(); ?>
			<h2>Supprimer ce compte utilisateur</h2>
			Vous êtes : <?php echo $WDGUser_current->get_email(); ?><br>
			et vous pouvez supprimer le compte de : <?php echo $WDGUser_override->get_email(); ?><br>
			<br>
			<br>
			<?php foreach ( $fields_delete_hidden as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
			<div id="user-details-form-buttons">
				<img style="display: none; margin:auto"  id="image" src="http://wedogood.local/wp-content/themes/yproject/images/loading.gif" />
				<button type="submit" class="button save red">Supprimer ce compte utilisateur</button>
			</div>
		</form>
	</div>
<?php endif; ?>