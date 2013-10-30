<?php 
/**
 * Enregistrement fait dans le plugin pour gérer la redirection éventuelle au bon moment
 * Le reste de la page devrait être fait dans un shortcode. On verra ça plus tard.
 */ 
if (!is_user_logged_in()) wp_redirect(site_url());

require_once("wp-content/themes/yproject/common.php");
?>
<?php get_header(); ?>

    <div id="content">
	<div class="padder">
	    <?php printUserProfileAdminBar(true);  ?>
	    <div class="center">
		<?php 
		if (is_user_logged_in()) :
		    $page_update_account = get_page_by_path('modifier-mon-compte');
		?>
		    <h2 class="underlined"><?php _e( 'Param&egrave;tres', 'yproject' ); ?></h2>
		    
		     <?php if (isset($_POST['update_user_posted'])){ 
			    global $validate_email;
			    $valid = true;
			    if ( isset($_POST["update_password_current"]) && !wp_check_password( $_POST["update_password_current"], $current_user->data->user_pass, $current_user->ID)) :
			    ?>
				<span class="errors"><?php _e( 'Le mot de passe renseign&eacute; ne correspond pas. Les modifications ne sont pas enregistr&eacute;es.', 'yproject' ); ?></span><br />
			    
			    <?php
				$valid = false;
			    endif;
			    
			    if ($validate_email !== true):
			    ?>
				<span class="errors"><?php _e( 'L&circ;adresse e-mail renseign&eacute;e est invalide ou d&eacute;j&agrave; utilis&eacute;e', 'yproject' ); ?></span><br />
				
			    <?php
				$valid = false;
			    endif;
			    ?>
				
			    <?php if ($valid) { ?>
			    <span class="invest_success"><?php _e('Informations enregistr&eacute;es', 'yproject'); ?></span><br />
			    <?php } ?>
				
		    <?php }; ?>

		    <form name="update-form" class="standard-form" action="<?php echo get_permalink($page_update_account->ID); ?>" method="post">

			<h4><?php _e('Ces informations sont n&eacute;cessaires pour investir dans un projet.', 'yproject'); ?></h4>
			<label for="update_firstname" class="standard-label"><?php _e( 'Pr&eacute;nom', 'yproject' ); ?></label>
			<input type="text" name="update_firstname" id="update_firstname" value="<?php echo $current_user->user_firstname; ?>" /><br />

			<label for="update_lastname" class="standard-label"><?php _e( 'Nom', 'yproject' ); ?></label>
			<input type="text" name="update_lastname" id="update_lastname" value="<?php echo $current_user->user_lastname; ?>" /><br />

			<label for="update_birthday_day" class="standard-label"><?php _e( 'Date de naissance', 'yproject' ); ?></label>
			<select name="update_birthday_day" id="update_birthday_day">
			    <?php
				for ($i = 1; $i <= 31; $i++) { ?>
				    <option value="<?php echo $i; ?>"<?php if ($current_user->get('user_birthday_day') == $i) echo ' selected="selected"';?>><?php echo $i; ?></option>
				<?php }
			    ?>
			</select>
			<select name="update_birthday_month" id="update_birthday_month">
			    <?php
				$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
				for ($i = 1; $i <= 12; $i++) { ?>
				    <option value="<?php echo $i; ?>"<?php if ($current_user->get('user_birthday_month') == $i) echo ' selected="selected"';?>><?php _e($months[$i - 1]); ?></option>
				<?php }
			    ?>
			</select>
			<select name="update_birthday_year" id="update_birthday_month">
			    <?php
				for ($i = date("Y"); $i >= 1900; $i--) { ?>
				    <option value="<?php echo $i; ?>"<?php if ($current_user->get('user_birthday_year') == $i) echo ' selected="selected"';?>><?php echo $i; ?></option>
				<?php }
			    ?>
			</select>

			<br />

			<?php require_once("country_list.php"); ?>
			<label for="update_nationality" class="standard-label"><?php _e( 'Nationalit&eacute;', 'yproject' ); ?></label>
			<select name="update_nationality" id="update_nationality">
			    <?php 
				foreach ($country_list as $country_code => $country_name) {
			    ?>
				    <option value="<?php echo $country_code; ?>"<?php if ($current_user->get('user_nationality') == $country_code) echo ' selected="selected"';?>><?php echo $country_name; ?></option>
			    <?php 
				}
			    ?>
			</select><br />

			<label for="update_person_type" class="standard-label"><?php _e( 'Type de personne', 'yproject' ); ?></label>
			<select name="update_person_type" id="update_person_type">
			    <option value="NATURAL_PERSON"<?php if ($current_user->get('user_person_type') == 'NATURAL_PERSON') echo ' selected="selected"';?>><?php _e( 'Physique', 'yproject' ); ?></option>
			    <option value="LEGAL_PERSONALITY"<?php if ($current_user->get('user_person_type') == 'LEGAL_PERSONALITY') echo ' selected="selected"';?>><?php _e( 'Morale', 'yproject' ); ?></option>
			</select><br />

			<label for="update_address" class="standard-label"><?php _e( 'Adresse', 'yproject' ); ?></label>
			<input type="text" name="update_address" id="update_address" value="<?php echo $current_user->get('user_address'); ?>" /><br />
			
			<label for="update_postal_code" class="standard-label"><?php _e( 'Code postal', 'yproject' ); ?></label>
			<input type="text" name="update_postal_code" id="update_postal_code" value="<?php echo $current_user->get('user_postal_code'); ?>" /><br />
			
			<label for="update_city" class="standard-label"><?php _e( 'Ville', 'yproject' ); ?></label>
			<input type="text" name="update_city" id="update_city" value="<?php echo $current_user->get('user_city'); ?>" /><br />
			
			<label for="update_country" class="standard-label"><?php _e( 'Pays', 'yproject' ); ?></label>
			<input type="text" name="update_country" id="update_country" value="<?php echo $current_user->get('user_country'); ?>" /><br />
			
			<label for="update_mobile_phone" class="standard-label"><?php _e( 'T&eacute;l&eacute;phone mobile', 'yproject' ); ?></label>
			<input type="text" name="update_mobile_phone" id="update_mobile_phone" value="<?php echo $current_user->get('user_mobile_phone'); ?>" /><br /><br />

			<?php 
			if (strpos($current_user->user_url, 'facebook.com') === false) {
			if (session_id() == '') session_start();
			if (!isset($_SESSION['redirect_current_campaign_id'])) {
			?>
			    <h4><?php _e('Informations de base', 'yproject'); ?></h4>
			    
			    <label for="update_email" class="large-label"><?php _e( 'Adresse e-mail', 'yproject' ); ?></label>
			    <input type="text" name="update_email" id="update_email" value="<?php echo $current_user->user_email; ?>" /><br />

			    <label for="update_password" class="large-label"><?php _e( 'Nouveau mot de passe', 'yproject' ); ?><?php _e(' (vide si pas de changement)', 'yproject'); ?></label>
			    <input type="password" name="update_password" id="update_password" value="" /><br />

			    <label for="update_password_confirm" class="large-label"><?php _e( 'Confirmer le nouveau mot de passe', 'yproject' ); ?><?php _e(' (vide si pas de changement)', 'yproject'); ?></label>
			    <input type="password" name="update_password_confirm" id="update_password_confirm" value="" /><br /><br />

			    <label for="update_password_current" class="standard-label"><?php _e( 'Mot de passe', 'yproject' ); ?>*</label>
			    <input type="password" name="update_password_current" id="update_password_current" value="" />
			<?php }} ?>
			<input type="submit" name="wp-submit" id="sidebar-wp-submit" value="Enregistrer les modifications" />

			<input type="hidden" name="update_user_posted" value="posted" />
			<input type="hidden" name="update_user_id" value="<?php echo $current_user->ID; ?>" /><br /><br />
		    </form>
		<?php
		endif;
		?>
	    </div>
	</div>
    </div>

<?php get_footer(); ?>