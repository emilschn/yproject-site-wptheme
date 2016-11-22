<?php
/* 
 * Lightbox content for organization creation
 * 
 */

//YPOrganisation::submit_new(FALSE);
global $campaign_id
?>

<div id="content">

	<div class="padder">
	    
                <div class="margin-height">


                        <?php global $errors_submit_new, $errors_create_orga; ?>
                        <?php if (count($errors_submit_new->errors) > 0 || count($errors_create_orga) > 0): ?>
                        <ul class="errors">
                                <?php $error_messages = $errors_submit_new->get_error_messages(); var_dump($error_messages); ?>
                                <?php foreach ($error_messages as $error_message): ?>
                                        <li><?php echo $error_message; ?></li>
                                <?php endforeach; ?>
                                <?php foreach ($errors_create_orga as $error_create_orga): ?>
                                        <li><?php echo $error_create_orga; ?></li>
                                <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                        <h1><?php _e('Cr&eacute;er une organisation','yproject')?></h1>
                        <form action="" method="POST" enctype="multipart/form-data" class="wdg-forms" data-action="save_new_organisation">

                                <label for="org_name"><?php _e('D&eacute;nomination sociale', 'yproject'); ?>*</label>
                                <input type="text" name="org_name" value="<?php echo filter_input(INPUT_POST, 'org_name'); ?>" /><br />

                                <label for="org_email"><?php _e('e-mail de contact', 'yproject'); ?>*</label>
                                <input type="text" name="org_email" value="<?php echo filter_input(INPUT_POST, 'org_email'); ?>" /><br />

                                <?php /*
                                <label for="org_type"><?php _e('Type d&apos;organisation', 'yproject'); ?></label>
                                <em>Pour l&apos;instant, seules les sociétés peuvent investir.</em><br />
                                 */ ?>

                                <label for="org_legalform"><?php _e('Forme juridique', 'yproject'); ?>*</label>
                                <input type="text" name="org_legalform" value="<?php echo filter_input(INPUT_POST, 'org_legalform'); ?>" /><br />

                                <label for="org_idnumber"><?php _e('Num&eacute;ro SIREN', 'yproject'); ?>*</label>
                                <input type="text" name="org_idnumber" value="<?php echo filter_input(INPUT_POST, 'org_idnumber'); ?>" /><br />

                                <label for="org_rcs"><?php _e('RCS', 'yproject'); ?>*</label>
                                <input type="text" name="org_rcs" value="<?php echo filter_input(INPUT_POST, 'org_rcs'); ?>" /><br />

                                <label for="org_capital"><?php _e('Capital social (en euros)', 'yproject'); ?>*</label>
                                <input type="text" name="org_capital" value="<?php echo filter_input(INPUT_POST, 'org_capital'); ?>" /><br />

                                <label for="org_ape"><?php _e('Code APE', 'yproject'); ?>*</label>
                                <input type="text" name="org_ape" value="<?php echo filter_input(INPUT_POST, 'org_ape'); ?>" /><br />

                                <h2><?php _e('Si&egrave;ge social', 'yproject'); ?></h2>
                                <label for="org_address"><?php _e('Adresse', 'yproject'); ?>*</label>
                                <input type="text" name="org_address" value="<?php echo filter_input(INPUT_POST, 'org_address'); ?>" /><br />

                                <label for="org_postal_code"><?php _e('Code postal', 'yproject'); ?>*</label>
                                <input type="text" name="org_postal_code" value="<?php echo filter_input(INPUT_POST, 'org_postal_code'); ?>" /><br />

                                <label for="org_city"><?php _e('Ville', 'yproject'); ?>*</label>
                                <input type="text" name="org_city" value="<?php echo filter_input(INPUT_POST, 'org_city'); ?>" /><br />

                                <label for="org_nationality"><?php _e('Pays', 'yproject'); ?>*</label>
                                <select name="org_nationality" id="org_nationality">
                                        <?php
                                        locate_template( array("country_list.php"), true );
                                        global $country_list;
                                        $selected_country = filter_input(INPUT_POST, 'org_nationality');
                                        foreach ($country_list as $country_code => $country_name): ?>
                                                <option value="<?php echo $country_code; ?>" <?php if ($country_code == $selected_country) { echo 'selected="selected"'; } ?>><?php echo $country_name; ?></option>
                                        <?php endforeach; ?>
                                </select><br />


                                <h2><?php _e('Informations bancaires - si vous souhaitez faire un virement d&apos;une somme obtenue', 'yproject'); ?></h2>
                                <label for="org_bankownername"><?php _e('Nom du propri&eacute;taire du compte', 'yproject'); ?></label>
                                <input type="text" name="org_bankownername" value="<?php echo filter_input(INPUT_POST, 'org_bankownername'); ?>" /> <br />

                                <label for="org_bankowneraddress"><?php _e('Adresse du compte', 'yproject'); ?></label>
                                <input type="text" name="org_bankowneraddress" value="<?php echo filter_input(INPUT_POST, 'org_bankowneraddress'); ?>" /> <br />

                                <label for="org_bankowneriban"><?php _e('IBAN', 'yproject'); ?></label>
                                <input type="text" name="org_bankowneriban" value="<?php echo filter_input(INPUT_POST, 'org_bankowneriban'); ?>" /> <br />

                                <label for="org_bankownerbic"><?php _e('BIC', 'yproject'); ?></label>
                                <input type="text" name="org_bankownerbic" value="<?php echo filter_input(INPUT_POST, 'org_bankownerbic'); ?>" /> <br />


                                <input type="checkbox" name="org_capable" /><?php _e('Je d&eacute;clare &ecirc;tre en capacit&eacute; de repr&eacute;senter cette organisation.', 'yproject'); ?><br />


                                <input type="hidden" name="campaign_id" value="<?php echo $campaign_id; ?>" />
                                <input type="submit" value="<?php _e('Enregistrer', 'yproject'); ?>" />

                        </form>


                </div>
	    
	</div><!-- .padder -->
	
</div><!-- #content -->	