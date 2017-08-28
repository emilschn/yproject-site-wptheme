<?php global $page_controler, $stylesheet_directory_uri, $country_list; ?>


<form method="post" class="db-form form-register" enctype="multipart/form-data">
	
	<h2><?php _e( "Informations personnelles", 'yproject' ); ?></h2>

	<div class="field">
		<label for="update_email"><?php _e( "Adresse e-mail", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<input type="email" name="update_email" id="update_email" value="<?php echo $page_controler->get_user_data( 'email' ); ?>" />
			</span>
		</div>
	</div>

	<div class="field">
		<label for="update_gender"><?php _e( "Vous &ecirc;tes", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<select name="update_gender" id="update_gender">
					<option value="female" <?php selected( $page_controler->get_user_data( 'gender' ), 'female' ); ?>><?php _e( "une femme", 'yproject' ); ?></option>
					<option value="male" <?php selected( $page_controler->get_user_data( 'gender' ), 'male' ); ?>><?php _e( "un homme", 'yproject' ); ?></option>
				</select>
			</span>
		</div>
	</div>
	
	<div class="field">
		<label for="update_firstname"><?php _e( "Pr&eacute;nom", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<input type="text" name="update_firstname" id="update_firstname" value="<?php echo $page_controler->get_user_data( 'firstname' ); ?>" />
			</span>
		</div>
	</div>
	
	<div class="field">
		<label for="update_lastname"><?php _e( "Nom", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<input type="text" name="update_lastname" id="update_lastname" value="<?php echo $page_controler->get_user_data( 'lastname' ); ?>" />
			</span>
		</div>
	</div>
	
	<div class="field">
		<label for="update_publicname"><?php _e( "Nom public", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<input type="text" name="update_publicname" id="update_publicname" value="<?php echo $page_controler->get_user_data( 'display_name' ); ?>" />
			</span>
		</div>
	</div>
	
	<div class="field">
		<label for="update_birthday_day"><?php _e( "Date de naissance", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<select name="update_birthday_day" id="update_birthday_day">
					<?php for ($i = 1; $i <= 31; $i++) { ?>
						<option value="<?php echo $i; ?>" <?php selected( $page_controler->get_user_data( 'birthday_day' ), $i); ?>><?php echo $i; ?></option>
					<?php } ?>
				</select>
			</span>
			<span class="field-value">
				<select name="update_birthday_month" id="update_birthday_month">
					<?php $months = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ); ?>
					<?php for ( $i = 1; $i <= 12; $i++ ): ?>
						<option value="<?php echo $i; ?>" <?php selected( $page_controler->get_user_data( 'birthday_month' ), $i); ?>><?php _e( $months[ $i - 1 ] ); ?></option>
					<?php endfor; ?>
				</select>
			</span>
			<span class="field-value">
				<select name="update_birthday_year" id="update_birthday_month">
					<?php for ( $i = date("Y"); $i >= 1900; $i-- ): ?>
						<option value="<?php echo $i; ?>" <?php selected( $page_controler->get_user_data( 'birthday_year' ), $i); ?>><?php echo $i; ?></option>
					<?php endfor; ?>
				</select>
			</span>
		</div>
	</div>
	
	<div class="field">
		<label for="update_birthplace"><?php _e( "Ville de naissance", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<input type="text" name="update_birthplace" id="update_birthplace" value="<?php echo $page_controler->get_user_data( 'birthplace' ); ?>" />
			</span>
		</div>
	</div>
	
	<div class="field">
		<label for="update_nationality"><?php _e( "Nationalit&eacute;", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<select name="update_nationality" id="update_nationality">
					<option value=""></option>
					<?php foreach ( $country_list as $country_code => $country_name ) : ?>
						<option value="<?php echo $country_code; ?>" <?php selected( $page_controler->get_user_data( 'nationality' ), $country_code ); ?>><?php echo $country_name; ?></option>
					<?php endforeach; ?>
				</select>
			</span>
		</div>
	</div>
	
	<div class="field">
		<label for="update_address"><?php _e( "Adresse", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<input type="text" name="update_address" id="update_address" value="<?php echo $page_controler->get_user_data( 'address' ); ?>" />
			</span>
		</div>
	</div>
	
	<div class="field">
		<label for="update_postal_code"><?php _e( "Code postal", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<input type="text" name="update_postal_code" id="update_postal_code" value="<?php echo $page_controler->get_user_data( 'postal_code' ); ?>" />
			</span>
		</div>
	</div>
	
	<div class="field">
		<label for="update_city"><?php _e( "Ville", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<input type="text" name="update_city" id="update_city" value="<?php echo $page_controler->get_user_data( 'city' ); ?>" />
			</span>
		</div>
	</div>
	
	<div class="field">
		<label for="update_country"><?php _e( "Pays", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<input type="text" name="update_country" id="update_country" value="<?php echo $page_controler->get_user_data( 'country' ); ?>" />
			</span>
		</div>
	</div>
	
	<div class="field">
		<label for="update_mobile_phone"><?php _e( "T&eacute;l&eacute;phone mobile", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<input type="text" name="update_mobile_phone" id="update_mobile_phone" value="<?php echo $page_controler->get_user_data( 'mobile_phone' ); ?>" />
			</span>
		</div>
	</div>
	
	
	<?php if (!isset($_SESSION['redirect_current_campaign_id'])): ?>
	
	<div class="field">
		<label for="update_description"><?php _e( "Description", 'yproject' ); ?></label>
		<div class="field-container">
			<span class="field-value">
				<textarea name="update_description" id="update_description"><?php echo $page_controler->get_user_data( 'description' ); ?></textarea>
			</span>
		</div>
	</div>
	
	
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