<?php
locate_template( 'country_list.php', true );
global $country_list;
$WDGUser_current = WDGUser::current();
ob_start();
?>

<?php _e('Les informations suivies d&apos;une &eacute;toile sont n&eacute;cessaires pour investir sur un projet.', 'yproject'); ?><br />

<form id="lightbox_userinfo_form">
	<ul id="lightbox_userinfo_form_errors" class="errors">
		
	</ul>
	
	<label for="update_gender" class="standard-label"><?php _e("Vous &ecirc;tes", 'yproject'); ?> *</label>
	<select name="update_gender" id="update_gender">
		<option value="female"<?php if ($WDGUser_current->wp_user->get('user_gender') == "female") echo ' selected="selected"';?>>une femme</option>
		<option value="male"<?php if ($WDGUser_current->wp_user->get('user_gender') == "male") echo ' selected="selected"';?>>un homme</option>
	</select><br />

	<label for="update_firstname" class="standard-label"><?php _e( 'Pr&eacute;nom', 'yproject' ); ?> *</label>
	<input type="text" name="update_firstname" id="update_firstname" value="<?php echo $WDGUser_current->wp_user->user_firstname; ?>" /><br />

	<label for="update_lastname" class="standard-label"><?php _e( 'Nom', 'yproject' ); ?> *</label>
	<input type="text" name="update_lastname" id="update_lastname" value="<?php echo $WDGUser_current->wp_user->user_lastname; ?>" /><br />

	<label for="update_birthday_day" class="standard-label"><?php _e( 'Date de naissance', 'yproject' ); ?> *</label>
	<select name="update_birthday_day" id="update_birthday_day">
		<?php for ($i = 1; $i <= 31; $i++) { ?>
			<option value="<?php echo $i; ?>"<?php if ($WDGUser_current->wp_user->get('user_birthday_day') == $i) echo ' selected="selected"';?>><?php echo $i; ?></option>
		<?php } ?>
	</select>
	<select name="update_birthday_month" id="update_birthday_month">
		<?php
		$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		for ($i = 1; $i <= 12; $i++) { ?>
			<option value="<?php echo $i; ?>"<?php if ($WDGUser_current->wp_user->get('user_birthday_month') == $i) echo ' selected="selected"';?>><?php _e($months[$i - 1]); ?></option>
		<?php }
		?>
	</select>
	<select name="update_birthday_year" id="update_birthday_year">
		<?php for ($i = date("Y"); $i >= 1900; $i--) { ?>
			<option value="<?php echo $i; ?>"<?php if ($WDGUser_current->wp_user->get('user_birthday_year') == $i) echo ' selected="selected"';?>><?php echo $i; ?></option>
		<?php } ?>
	</select>
	<br />

	<label for="update_birthplace" class="standard-label"><?php _e( 'Ville de naissance', 'yproject' ); ?> *</label>
	<input type="text" name="update_birthplace" id="update_birthplace" value="<?php echo $WDGUser_current->wp_user->get('user_birthplace'); ?>" /><br />

	<label for="update_nationality" class="standard-label"><?php _e( 'Nationalit&eacute;', 'yproject' ); ?> *</label>
	<select name="update_nationality" id="update_nationality">
		<option value=""></option>
		<?php foreach ($country_list as $country_code => $country_name) : ?>
			<option value="<?php echo $country_code; ?>"<?php if ($WDGUser_current->wp_user->get('user_nationality') == $country_code) echo ' selected="selected"';?>><?php echo $country_name; ?></option>
		<?php endforeach; ?>
	</select><br />

	<label for="update_address" class="standard-label"><?php _e( 'Adresse', 'yproject' ); ?> *</label>
	<input type="text" name="update_address" id="update_address" value="<?php echo $WDGUser_current->wp_user->get('user_address'); ?>" /><br />

	<label for="update_postal_code" class="standard-label"><?php _e( 'Code postal', 'yproject' ); ?> *</label>
	<input type="text" name="update_postal_code" id="update_postal_code" value="<?php echo $WDGUser_current->wp_user->get('user_postal_code'); ?>" /><br />

	<label for="update_city" class="standard-label"><?php _e( 'Ville', 'yproject' ); ?> *</label>
	<input type="text" name="update_city" id="update_city" value="<?php echo $WDGUser_current->wp_user->get('user_city'); ?>" /><br />

	<label for="update_country" class="standard-label"><?php _e( 'Pays', 'yproject' ); ?> *</label>
	<input type="text" name="update_country" id="update_country" value="<?php echo $WDGUser_current->wp_user->get('user_country'); ?>" /><br />
	
	<label for="update_mobile_phone" class="standard-label"><?php _e( 'T&eacute;l&eacute;phone mobile', 'yproject' ); ?></label>
	<input type="text" name="update_mobile_phone" id="update_mobile_phone" value="<?php echo $WDGUser_current->wp_user->get('user_mobile_phone'); ?>" /><br /><br />

	<p id="lightbox_userinfo_form_button" class="align-center">
		<input type="submit" value="<?php _e( "Enregistrer", 'yproject' ); ?>" class="button" />
	</p>
	<p id="lightbox_userinfo_form_loading" class="align-center hidden">
		<img id="ajax-loader-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" />
	</p>
</form>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
echo do_shortcode('[yproject_lightbox id="userinfos"]' .$lightbox_content. '[/yproject_lightbox]');