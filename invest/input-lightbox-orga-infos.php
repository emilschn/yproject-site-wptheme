<?php
locate_template( 'country_list.php', true );
global $country_list;
$WDGUser_current = WDGUser::current();
ob_start();
?>

<?php _e("Les informations suivantes sont n&eacute;cessaires pour investir en tant qu'organisation.", 'yproject'); ?><br />

<form id="lightbox_orgainfos_form">
	<ul id="lightbox_orgainfos_form_errors" class="errors">
		
	</ul>
	
	<label for="org_name" class="standard-label"><?php _e("D&eacute;nomination sociale", 'yproject'); ?></label>
	<input type="text" name="org_name" id="org_name" value="" /><span id="org_name_label"></span><br />
	
	<label for="org_email" class="standard-label"><?php _e("E-mail de contact", 'yproject'); ?></label>
	<input type="text" name="org_email" id="org_email" value="" /><span id="org_email_label"></span><br />

	<label for="org_description"><?php _e("Descriptif de l'activit&eacute;", 'yproject'); ?></label>
	<input type="text" name="org_description" id="org_description" value="" /><br />

	<label for="org_legalform" class="standard-label"><?php _e("Forme juridique", 'yproject'); ?></label>
	<input type="text" name="org_legalform" id="org_legalform" value="" /><br />

	<label for="org_idnumber" class="standard-label"><?php _e("Num&eacute;ro SIREN", 'yproject'); ?></label>
	<input type="text" name="org_idnumber" id="org_idnumber" value="" /><br />

	<label for="org_rcs" class="standard-label"><?php _e("RCS", 'yproject'); ?></label>
	<input type="text" name="org_rcs" id="org_rcs" value="" /><br />

	<label for="org_capital" class="standard-label"><?php _e("Capital social (en euros)", 'yproject'); ?></label>
	<input type="text" name="org_capital" id="org_capital" value="" /><br />

	<label for="org_ape" class="standard-label"><?php _e("Code APE", 'yproject'); ?></label>
	<input type="text" name="org_ape" id="org_ape" value="" /><br />

	<label for="org_vat" class="standard-label"><?php _e("Num&eacute;ro de TVA", 'yproject'); ?></label>
	<input type="text" name="org_vat" id="org_vat" value="" /><br /><br />

	<h2><?php _e("Si&egrave;ge social", 'yproject'); ?></h2>
	<label for="org_address" class="standard-label"><?php _e("Adresse", 'yproject'); ?></label>
	<input type="text" name="org_address" id="org_address" value="" /><br />

	<label for="org_postal_code" class="standard-label"><?php _e('Code postal', 'yproject'); ?></label>
	<input type="text" name="org_postal_code" id="org_postal_code" value="" /><br />

	<label for="org_city" class="standard-label"><?php _e("Ville", 'yproject'); ?></label>
	<input type="text" name="org_city" id="org_city" value="" /><br />

	<label for="org_nationality" class="standard-label"><?php _e('Pays', 'yproject'); ?></label>
	<select name="org_nationality" id="org_nationality">
		<?php 
		foreach ($country_list as $country_code => $country_name): ?>
			<option value="<?php echo $country_code; ?>" <?php if ($country_code == $selected_country) { echo 'selected="selected"'; } ?>><?php echo $country_name; ?></option>
		<?php endforeach; ?>
	</select><br /><br />
						
	<label for="org_capable" id="org_capable_label"><input type="checkbox" name="org_capable" id="org_capable" value="1" /><?php _e("Je d&eacute;clare &ecirc;tre en capacit&eacute; de repr&eacute;senter cette organisation.", 'yproject'); ?></label><br /><br />

	<p id="lightbox_orgainfos_form_button" class="align-center">
		<input type="submit" value="<?php _e( "Enregistrer", 'yproject' ); ?>" class="button" />
	</p>
	<p id="lightbox_orgainfos_form_loading" class="align-center hidden">
		<img id="ajax-loader-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" />
	</p>
</form>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
echo do_shortcode('[yproject_lightbox id="orgainfos"]' .$lightbox_content. '[/yproject_lightbox]');