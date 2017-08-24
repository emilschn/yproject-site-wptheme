<?php global $is_admin, $stylesheet_directory_uri; ?>

<?php if ( $is_admin ): ?>

<?php if (isset($_POST['action']) && $_POST['action'] == 'add-check-investment') {
	$add_check_result = $campaign->add_investment(
			'check', $_POST['email'], $_POST['value'], 'publish',
			$_POST['username'], $_POST['password'],
			$_POST['gender'], $_POST['firstname'], $_POST['lastname'],
			$_POST['birthday_day'], $_POST['birthday_month'], $_POST['birthday_year'],
			$_POST['birthplace'], $_POST['nationality'], $_POST['address'],
			$_POST['postal_code'], $_POST['city'], $_POST['country'], $_POST['iban'],
			$_POST['orga_email'], $_POST['orga_name']);
	if ($add_check_result !== FALSE) { ?>
		<span class="success">Investissement ajouté</span>
	<?php } else { ?>
		<span class="errors" style="color: black;">Erreur lors de l'ajout</span>
	<?php }
} ?>

<?php ob_start(); ?>
<div class="tab-content align-left">
	<h3><?php _e('Ajouter un paiement par ch&egrave;que', 'yproject'); ?></h3>

	<form method="POST" action="<?php echo admin_url( 'admin-post.php?action=add_new_check'); ?>">
		
		<div class="field">
			<label for="add-check-input-email"><?php _e('E-mail :', 'yproject'); ?>*</label>
			<input type="text" id="add-check-input-email" name="email" <?php if (isset($_POST['email']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['email']; ?>"<?php } ?> />
		</div>
		<div class="align-center">
			<a id="add-check-search-email" class="button"><?php _e( "Rechercher", 'yproject' ); ?></a>
			<br /><br />
			<img id="add-check-search-loading" class="hidden" src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading" />
			<span id="add-check-feedback-found-user" class="add-check-feedback hidden"><?php _e( "Un utilisateur (personne physique) correspond &agrave; cet e-mail.", 'yproject' ); ?></span>
			<span id="add-check-feedback-found-orga" class="add-check-feedback hidden"><?php _e( "Une organisation (personne morale) correspond &agrave; cet e-mail. Il faudrait un autre e-mail pour l'investisseur.", 'yproject' ); ?></span>
			<span id="add-check-feedback-not-found" class="add-check-feedback hidden"><?php _e( "Aucun compte ne correspond &agrave; cet e-mail.", 'yproject' ); ?></span>
		</div>
		
		
		<div class="field"><label for="value"><?php _e('Somme :', 'yproject'); ?>*</label>
			<input type="text" id="add-check-input-value" name="value" <?php if (isset($_POST['value']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['value']; ?>"<?php } ?> /></div>
		<div class="field"><label for="username"><?php _e('Login :', 'yproject'); ?></label>
			<input type="text" id="add-check-input-username" name="username" <?php if (isset($_POST['username']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['username']; ?>"<?php } ?> /></div>
		<div class="field"><label for="password"><?php _e('Mot de passe :', 'yproject'); ?></label>
			<input type="text" id="add-check-input-password" name="password" <?php if (isset($_POST['password']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['password']; ?>"<?php } ?> /></div>

		<div class="field">
			<label for="gender"><?php _e('Genre :', 'yproject'); ?></label>
			<select name="gender" id="add-check-input-gender">
				<option value="female" <?php if (isset($_POST['gender']) && $_POST['gender'] == "female" && $add_check_result === FALSE) { ?>selected="selected"<?php } ?>>Mme</option>
				<option value="male" <?php if (isset($_POST['gender']) && $_POST['gender'] == "male" && $add_check_result === FALSE) { ?>selected="selected"<?php } ?>>Mr</option>
			</select>
		</div>
		<div class="field"><label for="firstname"><?php _e('Pr&eacute;nom :', 'yproject'); ?></label> <input type="text" id="add-check-input-firstname" name="firstname" <?php if (isset($_POST['firstname']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['firstname']; ?>"<?php } ?> /></div>
		<div class="field"><label for="lastname"><?php _e('Nom :', 'yproject'); ?></label> <input type="text" id="add-check-input-lastname" name="lastname" <?php if (isset($_POST['lastname']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['lastname']; ?>"<?php } ?> /></div>

		<div class="field">
			<label for="birthday_day"><?php _e( 'Date de naissance :', 'yproject' ); ?></label>
			<select id="add-check-input-birthday-day" name="birthday_day">
				<?php for ($i = 1; $i <= 31; $i++) { ?>
					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php } ?>
			</select>
			<select id="add-check-input-birthday-month" name="birthday_month">
				<?php
				$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
				for ($i = 1; $i <= 12; $i++) { ?>
					<option value="<?php echo $i; ?>"><?php _e($months[$i - 1]); ?></option>
				<?php }
				?>
			</select>
			<select id="add-check-input-birthday-year" name="birthday_year">
				<?php for ($i = date("Y"); $i >= 1900; $i--) { ?>
					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php } ?>
			</select>
		</div>

		<div class="field">
			<label for="birthplace"><?php _e( 'Ville de naissance :', 'yproject' ); ?></label>
			<input type="text" id="add-check-input-birthplace" name="birthplace" />
		</div>

		<?php locate_template( 'country_list.php', true ); global $country_list; ?>
		<div class="field">
			<label for="nationality"><?php _e( 'Nationalit&eacute; :', 'yproject' ); ?></label>
			<select id="add-check-input-nationality" name="nationality">
				<option value=""></option>
				<?php foreach ($country_list as $country_code => $country_name) : ?>
					<option value="<?php echo $country_code; ?>"><?php echo $country_name; ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="field">
			<label for="address"><?php _e( 'Adresse :', 'yproject' ); ?></label>
			<input type="text" id="add-check-input-address" name="address" />
		</div>

		<div class="field">
			<label for="postal_code"><?php _e( 'Code postal :', 'yproject' ); ?></label>
			<input type="text" id="add-check-input-postal-code" name="postal_code" />
		</div>

		<div class="field">
			<label for="city"><?php _e( 'Ville :', 'yproject' ); ?></label>
			<input type="text" id="add-check-input-city" name="city" />
		</div>

		<div class="field">
			<label for="country"><?php _e( 'Pays :', 'yproject' ); ?></label>
			<input type="text" id="add-check-input-country" name="country" />
		</div>

		<input type="hidden" name="iban" value="" />

		<br /><br />

		<hr class="form-separator"/>
		<h3><?php _e("Si il s'agit d'une organisation :", 'yproject'); ?></h3>
		<div class="field">
			<label for="orga_email"><?php _e("E-mail de l'organisation :", 'yproject'); ?></label>
			<input type="text" id="add-check-input-orga-email" name="orga_email" <?php if (isset($_POST['orga_email']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['orga_email']; ?>"<?php } ?> /></div>
		<div class="field"><label for="orga_name"><?php _e("Nom de l'organisation (si n'existe pas d&eacute;j&agrave;) :", 'yproject'); ?></label>
			<input type="text" id="add-check-input-orga-name" name="orga_name" <?php if (isset($_POST['orga_name']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['orga_name']; ?>"<?php } ?> /></div>

		<p class="align-center">
			<button type="submit" class="button admin-theme"><?php _e('Ajouter', 'yproject'); ?></button>
		</p>
		<input type="hidden" name="action" value="add-check-investment" />
	</form>
</div>

<?php 
$lightbox_content = ob_get_clean();
echo do_shortcode('[yproject_lightbox id="add-check" scrolltop="1"]'.$lightbox_content.'[/yproject_lightbox]');
?>

<?php endif;

