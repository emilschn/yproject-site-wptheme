<?php global $stylesheet_directory_uri, $country_list; ?>
<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUserDetailsForm = $page_controler->get_user_details_form();
$fields_hidden = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_hidden );
$fields_basics = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_basics );
$fields_complete = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_complete );
$fields_extended = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_extended );
?>


<form method="post" class="db-form form-register v3 full" enctype="multipart/form-data">
		
	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<span class="form-error-general"></span>

	<h3><?php _e( "Enregistrez vos informations", 'yproject' ); ?></h3>

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


	<div id="user-details-form-buttons">

		<button class="button save red" data-close="user-details" data-open="user-details-confirmation"><?php _e( "Enregistrer", 'yproject' ); ?></button>

		<div class="loading align-center hidden">
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading" />
		</div>

	</div>
	
	
	<?php if (!isset($_SESSION['redirect_current_campaign_id'])): ?>
	
	
	<label for="avatar_image" class="standard-label"><?php _e( "Avatar", 'yproject' ); ?></label>
	<input type="file" name="avatar_image" id="avatar_image" />
	<input type="checkbox" name="reset_avatar"> Supprimer l'avatar actuel
	<?php $facebook_meta = get_user_meta($current_user->ID, 'social_connect_facebook_id', true); ?>
	<?php if ( !empty( $facebook_meta ) ): ?>
	<input type="checkbox" name="facebook_avatar">Utiliser l'avatar facebook
	<?php endif; ?>
	
	<?php endif; ?>
	
            
	<div class="box_connection_buttons">
		<input type="submit" name="wp-submit" class="button red" value="<?php _e( "Enregistrer les modifications", 'yproject'); ?>" />
	</div>

	<?php if (isset($_SESSION['redirect_current_amount_part'])): ?>
		<input type="hidden" name="amount_part" value="<?php echo $_SESSION['redirect_current_amount_part']; ?>" />
	<?php endif; ?>
	<?php if (isset($_SESSION['redirect_current_invest_type']) && $_SESSION['redirect_current_invest_type'] != "new_organization"): ?>
		<input type="hidden" name="invest_type" value="<?php echo $_SESSION['redirect_current_invest_type']; ?>" />
	<?php endif; ?>
	<input type="hidden" name="update_user_posted" value="posted" />
	<input type="hidden" name="update_user_id" value="<?php echo $page_controler->get_user_id(); ?>" />
	
</form>
	
<hr />

<form method="post" class="db-form form-register">
	<h2><?php _e( "Modification de mot de passe", 'yproject' ); ?></h2>
	
	
	<div class="field">
		<label for="update_password"><?php _e( "Nouveau mot de passe", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<input type="password" name="update_password" id="update_password" />
			</span>
		</div>
	</div>
	
	<div class="field">
		<label for="update_password_confirm"><?php _e( "Confirmer le nouveau mot de passe", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<input type="password" name="update_password_confirm" id="update_password_confirm" />
			</span>
		</div>
	</div>
	
	<div class="field">
		<label for="update_password_current"><?php _e( "Mot de passe actuel", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<input type="password" name="update_password_current" id="update_password_current" />
			</span>
		</div>
	</div>
            
	<div class="box_connection_buttons">
		<input type="submit" name="wp-submit" class="button red" value="<?php _e( "Modifier", 'yproject'); ?>" />
	</div>
</form>