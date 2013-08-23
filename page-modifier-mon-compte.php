<?php 
/**
 * Enregistrement fait dans le plugin pour gérer la redirection éventuelle au bon moment
 * Le reste de la page devrait être fait dans un shortcode. On verra ça plus tard.
 */ 
?>
<?php get_header(); ?>

    <div id="content" class="center">
	<div class="padder_more">
	    <?php 
	    if (is_user_logged_in()) :
		$page_update_account = get_page_by_path('modifier-mon-compte'); 
	    ?>
		<h2<?php _e( 'Modifier mon compte', 'yproject' ); ?></h2>

		<form name="update-form" class="standard-form" action="<?php echo get_permalink($page_update_account->ID); ?>" method="post">
		    
		    <h4><?php _e('Ces informations sont n&eacute;cessaires pour investir dans un projet.', 'yproject'); ?></h4>
		    <label for="update_firstname"><?php _e( 'Pr&eacute;nom', 'yproject' ); ?></label>
		    <input type="text" name="update_firstname" id="update_firstname" value="<?php echo $current_user->user_firstname; ?>" /><br />
		    
		    <label for="update_lastname"><?php _e( 'Nom', 'yproject' ); ?></label>
		    <input type="text" name="update_lastname" id="update_lastname" value="<?php echo $current_user->user_lastname; ?>" /><br />
		    
		    <label for="update_birthday_day"><?php _e( 'Date de naissance', 'yproject' ); ?></label>
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
		    <label for="update_nationality"><?php _e( 'Nationalit&eacute;', 'yproject' ); ?></label>
		    <select name="update_nationality" id="update_nationality">
			<?php 
			    foreach ($country_list as $country_code => $country_name) {
			?>
				<option value="<?php echo $country_code; ?>"<?php if ($current_user->get('user_nationality') == $country_code) echo ' selected="selected"';?>><?php echo $country_name; ?></option>
			<?php 
			    }
			?>
		    </select><br />
		    
		    <label for="update_person_type"><?php _e( 'Type de personne', 'yproject' ); ?></label>
		    <select name="update_person_type" id="update_person_type">
			<option value="NATURAL_PERSON"<?php if ($current_user->get('user_person_type') == 'NATURAL_PERSON') echo ' selected="selected"';?>><?php _e( 'Physique', 'yproject' ); ?></option>
			<option value="LEGAL_PERSONALITY"<?php if ($current_user->get('user_person_type') == 'LEGAL_PERSONALITY') echo ' selected="selected"';?>><?php _e( 'Morale', 'yproject' ); ?></option>
		    </select><br />
		    
		    <?php 
		    if (session_id() == '') session_start();
		    if (!isset($_SESSION['redirect_current_campaign_id'])) {
		    ?>
			<h4><?php _e('Informations de base', 'yproject'); ?></h4>
			<label for="update_email"><?php _e( 'Adresse e-mail', 'yproject' ); ?></label>
			<input type="text" name="update_email" id="update_email" value="<?php echo $current_user->user_email; ?>" /><br />

			<label for="update_password"><?php _e( 'Mot de passe', 'yproject' ); ?><?php _e(' (vide si pas de changement)', 'yproject'); ?></label>
			<input type="password" name="update_password" id="update_password" value="" /><br />

			<label for="update_password_confirm"><?php _e( 'Confirmation du mot de passe', 'yproject' ); ?><?php _e(' (vide si pas de changement)', 'yproject'); ?></label>
			<input type="password" name="update_password_confirm" id="update_password_confirm" value="" /><br />
		    <?php } ?>
		    

		    <input type="hidden" name="update_user_posted" value="posted" />
		    <input type="hidden" name="update_user_id" value="<?php echo $current_user->ID; ?>" />
		    
		    <input type="submit" name="wp-submit" id="sidebar-wp-submit" />
		</form>
	    <?php
	    endif;
	    ?>
	</div>
    </div>

<?php get_footer(); ?>