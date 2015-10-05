<?php 
global $disable_logs; $disable_logs = TRUE;
$current_wdg_user = WDGUser::current();
$campaign = atcf_get_current_campaign();

if ($campaign->current_user_can_edit()) {
?>
		
<h2>Liste des <?php echo $campaign->funding_type_vocabulary()['investor_name'];?>s</h2>
<em>Si vous envoyez un mail group&eacute; &agrave; vos <?php echo $campaign->funding_type_vocabulary()['investor_name'];?>s, pensez &agrave; les mettre dans le champ CCI, pour qu&apos;ils n&apos;aient pas acc&egrave;s aux adresses des autres.</em><br /><br />


<div id="ajax-investors-load" class="ajax-investments-load" style="text-align: center;" data-value="<?php echo $campaign->ID?>">
	<img id="ajax-loader-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" />
</div>


<?php if ($current_wdg_user->is_admin()): ?>
<div class="admin-block">
	<h3>[ADMIN] <?php _e('Ajouter un paiement par ch&egrave;que', 'yproject'); ?></h3>
	
	<?php if (isset($_POST['action']) && $_POST['action'] == 'add-check-investment') {
		$add_check_result = $campaign->add_investment('check', $_POST['email'], $_POST['value'], $_POST['username'], $_POST['password'], $_POST['gender'], $_POST['firstname'], $_POST['lastname']);
		if ($add_check_result !== FALSE) { ?>
			<span class="success">Investissement ajouté</span>
		<?php } else { ?>
			<span class="errors">Erreur lors de l'ajout</span>
		<?php }
	} ?>
	
	<form method="POST" action="">
		<label for="email"><?php _e('E-mail :', 'yproject'); ?>*</label> <input type="text" name="email" /><br />
		<label for="value"><?php _e('Somme :', 'yproject'); ?>*</label> <input type="text" name="value" /><br />
		<label for="username"><?php _e('Login :', 'yproject'); ?></label> <input type="text" name="username" /><br />
		<label for="password"><?php _e('Mot de passe :', 'yproject'); ?></label> <input type="text" name="password" /><br />
		<label for="gender"><?php _e('Genre :', 'yproject'); ?></label> 
			<select name="gender">
			    <option value="female">Mme</option>
			    <option value="male">Mr</option>
			</select><br />
		<label for="firstname"><?php _e('Pr&eacute;nom :', 'yproject'); ?></label> <input type="text" name="firstname" /><br />
		<label for="lastname"><?php _e('Nom :', 'yproject'); ?></label> <input type="text" name="lastname" /><br />
		<button type="submit"><?php _e('Ajouter', 'yproject'); ?></button>
		<input type="hidden" name="action" value="add-check-investment" />
	</form>
</div>
<?php endif; ?>

<?php
}