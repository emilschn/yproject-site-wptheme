<?php 
/**
 * Enregistrement fait dans le plugin pour gérer la redirection éventuelle au bon moment
 * Le reste de la page devrait être fait dans un shortcode. On verra ça plus tard.
 */ 
if (!is_user_logged_in()) wp_redirect(site_url());
if (session_id() == '') session_start();

?>
<?php get_header(); ?>

    <div id="content">
	<div class="padder">
	    <?php locate_template( array( 'members/single/admin-bar.php' ), true ); ?>
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
				<span class="errors"><?php _e( 'L&apos;adresse e-mail renseign&eacute;e est invalide ou d&eacute;j&agrave; utilis&eacute;e', 'yproject' ); ?></span><br />
				
			    <?php
				$valid = false;
			    endif;
			    ?>
				
			    <?php if ($valid) { ?>
			    <span class="invest_success"><?php _e('Informations utilisateur enregistr&eacute;es', 'yproject'); ?></span><br />
			    <?php } ?>
				
		    <?php }; ?>
		    <?php 
			if (isset($_SESSION['error_invest'])) {
			    for ($i = 0; $i < count($_SESSION['error_invest']); $i++) {
			    ?>
			    <span class="errors"><?php echo $_SESSION['error_invest'][$i]; ?></span><br />
			    <?php
			    }
			    unset($_SESSION['error_invest']);
			}
		    ?>

		    <form name="update-form" class="standard-form" action="<?php echo get_permalink($page_update_account->ID); ?>" method="post" enctype="multipart/form-data">


			<h4 style="padding-left: 20px;"><?php _e('Ces informations sont n&eacute;cessaires pour investir dans un projet.', 'yproject'); ?></h4>

			    <div id="form_infoperso_projet">
				<label for="update_gender" class="standard-label">Vous &ecirc;tes</label>
				<select name="update_gender" id="update_gender">
				    <option value="female"<?php if ($current_user->get('user_gender') == "female") echo ' selected="selected"';?>>une femme</option>
				    <option value="male"<?php if ($current_user->get('user_gender') == "male") echo ' selected="selected"';?>>un homme</option>
				</select><br />

				<label for="update_firstname" class="standard-label"><?php _e( 'Pr&eacute;nom', 'yproject' ); ?></label>
				<input type="text" name="update_firstname" id="update_firstname" value="<?php echo $current_user->user_firstname; ?>" /><br />

				<label for="update_lastname" class="standard-label"><?php _e( 'Nom', 'yproject' ); ?></label>
				<input type="text" name="update_lastname" id="update_lastname" value="<?php echo $current_user->user_lastname; ?>" /><br />

				<label for="update_publicname" class="standard-label">Nom public</label>
				<input type="text" name="update_publicname" id="update_publicname" value="<?php echo $current_user->display_name; ?>" /><br />

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

				<label for="update_birthplace" class="standard-label">Ville de naissance</label>
				<input type="text" name="update_birthplace" id="update_birthplace" value="<?php echo $current_user->get('user_birthplace'); ?>" /><br />

				<?php require_once("country_list.php"); ?>
				<label for="update_nationality" class="standard-label"><?php _e( 'Nationalit&eacute;', 'yproject' ); ?></label>
				<select name="update_nationality" id="update_nationality">
				    <option value=""></option>
				    <?php 
					foreach ($country_list as $country_code => $country_name) {
				    ?>
					    <option value="<?php echo $country_code; ?>"<?php if ($current_user->get('user_nationality') == $country_code) echo ' selected="selected"';?>><?php echo $country_name; ?></option>
				    <?php 
					}
				    ?>
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

			    </div>
		

			<?php 
			//Si l'utilisateur n'est pas connecté avec Facebook, et qu'on ne vient pas d'une redirection d'investissement, on peut afficher les données "sensibles" : email et mot de passe
			if ((strpos($current_user->user_url, 'facebook.com') === false) && !isset($_SESSION['redirect_current_campaign_id'])) {
			?>
			    <h4 style="padding-left: 20px;"><?php _e('Informations de base', 'yproject'); ?></h4>

				<div id="form_infoperso_projet">
					<label for="update_email" class="large-label"><?php _e( 'Adresse e-mail', 'yproject' ); ?></label>
					<input type="text" name="update_email" id="update_email" value="<?php echo $current_user->user_email; ?>" /><br />

					<label for="update_password" class="large-label"><?php _e( 'Nouveau mot de passe', 'yproject' ); ?><?php _e(' (vide si pas de changement)', 'yproject'); ?></label>
					<input type="password" name="update_password" id="update_password" value="" /><br />

					<label for="update_password_confirm" class="large-label"><?php _e( 'Confirmer le nouveau mot de passe', 'yproject' ); ?><?php _e(' (vide si pas de changement)', 'yproject'); ?></label>
					<input type="password" name="update_password_confirm" id="update_password_confirm" value="" /><br /><br />

					<label for="update_password_current" class="standard-label"><?php _e( 'Mot de passe', 'yproject' ); ?>*</label>
					<input type="password" name="update_password_current" id="update_password_current" value="" />
				</div>
			<?php } elseif (strpos($current_user->user_url, 'facebook.com') !== false) { ?>
			    <h4 style="padding-left: 20px;">Contact</h4>

				<div id="form_infoperso_projet">
				    <label for="update_email_contact" class="large-label">Adresse e-mail de contact</label>
				    <input type="text" name="update_email_contact" id="update_email_contact" value="<?php echo $current_user->user_email; ?>" /><br />
				</div>
			<?php } ?>
		
		
		
			<?php
			//Si l'utilisateur veut investir pour une nouvelle organisation
			if (isset($_SESSION['redirect_current_invest_type'])) {
			    if ($_SESSION['redirect_current_invest_type'] == "new_organisation") {
				editOrganisation();
			    } elseif ($_SESSION['redirect_current_invest_type'] != "user") { 
				editOrganisation($_SESSION['redirect_current_invest_type']);
			    }
			} else {
			    //Parcourir toutes les organisations
			    $group_ids = BP_Groups_Member::get_group_ids( $current_user->ID );
			    foreach ($group_ids['groups'] as $group_id) {
				$group = groups_get_group( array( 'group_id' => $group_id ) );
				$group_type = groups_get_groupmeta($group_id, 'group_type');
				if ($group->status == 'private' && $group_type == 'organisation' && BP_Groups_Member::check_is_admin($current_user->ID, $group_id)) {
				    editOrganisation($group_id);
				}
			    }
			}
			?>
		
			    
			<center><input type="submit" value="Enregistrer les modifications" /></center>

			<?php if (isset($_SESSION['redirect_current_amount_part'])) { ?>
			    <input type="hidden" name="amount_part" value="<?php echo $_SESSION['redirect_current_amount_part']; ?>" />
			<?php } ?>
			<?php if (isset($_SESSION['redirect_current_invest_type']) && $_SESSION['redirect_current_invest_type'] != "new_organisation") { ?>
			    <input type="hidden" name="invest_type" value="<?php echo $_SESSION['redirect_current_invest_type']; ?>" />
			<?php } ?>
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

<?php
function editOrganisation($orga_id = false) {
    global $country_list;
    
    $orga_title = 'Nouvelle organisation';
    $name_suffix = '';
    $org_name = ''; $org_email = ''; $org_nationality = ''; $org_type = '';
    $org_legalform = ''; $org_idnumber = ''; $org_capital = ''; $org_rcs = '';
    $org_address = ''; $org_postal_code = ''; $org_city = '';
    
    if ($orga_id != '') {
	$name_suffix = '_' . $orga_id;
	$group = groups_get_group( array( 'group_id' => $orga_id ) );
	$org_name = $group->name;
	$orga_title = 'Informations de l\'organisation <strong>' . $org_name . '</strong>';
	$organisation_user = get_user_by('id', $group->creator_id);
	$org_email = $organisation_user->user_email;
	$org_address = $organisation_user->get('user_address'); 
	$org_postal_code = $organisation_user->get('user_postal_code'); 
	$org_city = $organisation_user->get('user_city'); 
	$org_nationality = $organisation_user->get('user_nationality');
	$org_legalform = $organisation_user->get('organisation_legalform'); 
	$org_idnumber = $organisation_user->get('organisation_idnumber'); 
	$org_rcs = $organisation_user->get('organisation_rcs'); 
	$org_capital = $organisation_user->get('organisation_capital'); 
    } else {
	if (isset($_POST["new_org_name" . $name_suffix])) $org_name = $_POST["new_org_name" . $name_suffix];
	if (isset($_POST["new_org_email" . $name_suffix])) $org_email = $_POST["new_org_email" . $name_suffix];
	if (isset($_POST["new_org_type" . $name_suffix])) $org_type = $_POST["new_org_type" . $name_suffix];
	if (isset($_POST["new_org_legalform" . $name_suffix])) $org_legalform = $_POST["new_org_legalform" . $name_suffix];
	if (isset($_POST["new_org_idnumber" . $name_suffix])) $org_idnumber = $_POST["new_org_idnumber" . $name_suffix];
	if (isset($_POST["new_org_rcs" . $name_suffix])) $org_rcs = $_POST["new_org_rcs" . $name_suffix];
	if (isset($_POST["new_org_capital" . $name_suffix])) $org_capital = $_POST["new_org_capital" . $name_suffix];
	if (isset($_POST["new_org_address" . $name_suffix])) $org_address = $_POST["new_org_address" . $name_suffix];
	if (isset($_POST["new_org_postal_code" . $name_suffix])) $org_postal_code = $_POST["new_org_postal_code" . $name_suffix];
	if (isset($_POST["new_org_city" . $name_suffix])) $org_city = $_POST["new_org_city" . $name_suffix];
	if (isset($_POST["new_org_nationality" . $name_suffix])) $org_nationality = $_POST["new_org_nationality" . $name_suffix];
    }
?>
    <h4 style="padding-left: 20px;"><?php echo $orga_title; ?></h4>

	<div id="form_infoperso_projet">

	    <label for="new_org_name<?php echo $name_suffix; ?>" class="standard-label">D&eacute;nomination sociale</label>
	    <input type="text" name="new_org_name<?php echo $name_suffix; ?>" id="new_org_name" value="<?php echo $org_name; ?>" /><br />

	    <label for="new_org_email<?php echo $name_suffix; ?>" class="standard-label">e-mail de contact</label>
	    <input type="text" name="new_org_email<?php echo $name_suffix; ?>" id="new_org_email" value="<?php echo $org_email; ?>" /><br />

	    <label for="new_org_type<?php echo $name_suffix; ?>" class="standard-label">Type d&apos;organisation</label>
	    <em>Pour l&apos;instant, seules les sociétés peuvent investir.</em><br />

	    <label for="new_org_legalform<?php echo $name_suffix; ?>" class="standard-label">Forme juridique</label>
	    <input type="text" name="new_org_legalform<?php echo $name_suffix; ?>" id="new_org_legalform" value="<?php echo $org_legalform; ?>" /><br />

	    <label for="new_org_idnumber<?php echo $name_suffix; ?>" class="standard-label">Num&eacute;ro d&apos;immatriculation</label>
	    <input type="text" name="new_org_idnumber<?php echo $name_suffix; ?>" id="new_org_idnumber" value="<?php echo $org_idnumber; ?>" /><br />

	    <label for="new_org_rcs<?php echo $name_suffix; ?>" class="standard-label">RCS</label>
	    <input type="text" name="new_org_rcs<?php echo $name_suffix; ?>" id="new_org_rcs" value="<?php echo $org_rcs; ?>" /><br />

	    <label for="new_org_capital<?php echo $name_suffix; ?>" class="standard-label">Capital social (en euros)</label>
	    <input type="text" name="new_org_capital<?php echo $name_suffix; ?>" id="new_org_capital" value="<?php echo $org_capital; ?>" /><br />

	    <label for="new_org_address<?php echo $name_suffix; ?>" class="standard-label">Si&egrave;ge social</label>
	    <input type="text" name="new_org_address<?php echo $name_suffix; ?>" id="new_org_address" value="<?php echo $org_address; ?>" /><br />

	    <label for="new_org_postal_code<?php echo $name_suffix; ?>" class="standard-label">Code postal</label>
	    <input type="text" name="new_org_postal_code<?php echo $name_suffix; ?>" id="new_org_postal_code" value="<?php echo $org_postal_code; ?>" /><br />

	    <label for="new_org_city<?php echo $name_suffix; ?>" class="standard-label">Ville</label>
	    <input type="text" name="new_org_city<?php echo $name_suffix; ?>" id="new_org_city" value="<?php echo $org_city; ?>" /><br />

	    <label for="new_org_nationality<?php echo $name_suffix; ?>" class="standard-label">Pays</label>
	    <select name="new_org_nationality<?php echo $name_suffix; ?>" id="new_org_nationality">
		<option value=""></option>
		<?php 
		    foreach ($country_list as $country_code => $country_name) {
		?>
			<option value="<?php echo $country_code; ?>"<?php if ($org_nationality == $country_code) echo ' selected="selected"'; ?>><?php echo $country_name; ?></option>
		<?php 
		    }
		?>
	    </select><br /><br /><br />
	    
	    <strong>Identification</strong><br />
	    <?php if ($orga_id == '' || !ypcf_mangopay_is_user_strong_authentication_sent($organisation_user->ID)) { ?>
		Afin de lutter contre le blanchiment d&apos;argent, pour tout investissement de plus de <strong><?php echo YP_STRONGAUTH_AMOUNT_LIMIT; ?>&euro;</strong> sur l&apos;ann&eacute;e, nous devons transmettre les pi&egrave;ces d&apos;identit&eacute; suivantes &agrave; notre partenaire Mangopay
		(Les fichiers doivent &ecirc;tre de type jpeg, gif, png ou pdf et leur poids inf&eacute;rieur &agrave; 2 Mo) :<br /><br />
		<label for="new_org_file_cni<?php echo $name_suffix; ?>" class="large-label">CNI et fonction de la personne physique qui agit pour son compte</label>
		<input type="file"name="new_org_file_cni<?php echo $name_suffix; ?>" /><br /><br />
		<label for="new_org_file_status<?php echo $name_suffix; ?>" class="large-label">Statuts sign&eacute;s</label>
		<input type="file"name="new_org_file_status<?php echo $name_suffix; ?>" /><br /><br />
		<label for="new_org_file_extract<?php echo $name_suffix; ?>" class="large-label">Extrait du registre de commerce datant de moins de 3 mois</label>
		<input type="file"name="new_org_file_extract<?php echo $name_suffix; ?>" /><br /><br />
		<label for="new_org_file_declaration<?php echo $name_suffix; ?>" class="large-label">D&eacute;claration de b&eacute;n&eacute;ficiaire &eacute;conomique (si on n&apos;identifie pas d&apos;actionnaires personnes physiques dans les statuts)</label>
		<input type="file"name="new_org_file_declaration<?php echo $name_suffix; ?>" /><br />
		<br /><br />
	    
	    <?php } elseif (ypcf_mangopay_is_user_strong_authenticated($organisation_user->ID)) { ?>
		Cette organisation est identifi&eacute;e et valid&eacute;e par notre partenaire Mangopay. Vous pouvez maintenant investir les sommes que vous souhaitez.<br /><br />
	    
	    <?php } else { ?>
		Les fichiers permettant de valider vos investissements sont en cours d&apos;&eacute;tude chez notre partenaire Mangopay. Merci de votre compr&eacute;hension.<br /><br />
	    
	    <?php } ?>

	    <?php if ($orga_id === false) { ?>
		<input type="checkbox" name="new_organisation_capable" />Je d&eacute;clare &ecirc;tre en capacit&eacute; de repr&eacute;senter cette organisation.<br />
		<input type="hidden" name="new_organisation" value="1" />
	    <?php } else { ?>
		<input type="hidden" name="update_organisation" value="1" />
		<input type="hidden" name="update_organisation_<?php echo $orga_id; ?>" value="1" />
	    <?php } ?>

	</div>
<?php
}
?>