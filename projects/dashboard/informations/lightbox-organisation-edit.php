<?php

/* 
 * Lightbox d'édition d'une organisation
 * 
 */
global $campaign_id;


global $current_organisation;
if (!isset($current_organisation)) {
    $organisation_obj = YPOrganisation::current();
} else {
    $organisation_obj = new YPOrganisation($current_organisation->organisation_wpref);
}

$WDGUser_current = WDGUser::current();
$organisation_obj->send_kyc();
$organisation_obj->submit_transfer_wallet_lemonway();

?>
	    
<div class="center margin-height">


		<ul class="errors"></ul>

        <h1><?php _e('&Eacute;diter l\'organisation','yproject'); echo "&nbsp;"; ?></h1>

        <form id="orgaedit_form" action="" method="POST" enctype="multipart/form-data" class="wdg-forms" data-action="save_edit_organisation">

                <?php
                /**
                 * Données générales
                 */
                ?>
                <label for="org_name"><?php _e('D&eacute;nomination sociale', 'yproject'); ?></label>
                <em id="org_name"><?php echo $organisation_obj->get_name(); ?></em><br />


                <label for="org_email"><?php _e('E-mail de contact', 'yproject'); ?></label>
                <input type="text" name="org_email" value="<?php echo $organisation_obj->get_email(); ?>" /><br />

                <label for="org_description"><?php _e("Descriptif de l'activit&eacute;", 'yproject'); ?></label>
                <input type="text" name="org_description" value="<?php echo $organisation_obj->get_description(); ?>" /><br />

                <label for="org_legalform"><?php _e('Forme juridique', 'yproject'); ?></label>
                <input type="text" name="org_legalform" value="<?php echo $organisation_obj->get_legalform(); ?>" /><br />

                <label for="org_idnumber"><?php _e('Num&eacute;ro SIREN', 'yproject'); ?></label>
                <input type="text" name="org_idnumber" value="<?php echo $organisation_obj->get_idnumber(); ?>" /><br />

                <label for="org_rcs"><?php _e('RCS', 'yproject'); ?></label>
                <input type="text" name="org_rcs" value="<?php echo $organisation_obj->get_rcs(); ?>" /><br />

                <label for="org_capital"><?php _e('Capital social (en euros)', 'yproject'); ?></label>
                <input type="text" name="org_capital" value="<?php echo $organisation_obj->get_capital(); ?>" /><br />

                <label for="org_ape"><?php _e('Code APE', 'yproject'); ?></label>
                <input type="text" name="org_ape" value="<?php echo $organisation_obj->get_ape(); ?>" /><br />

                <h2 class="underlined"><?php _e('Si&egrave;ge social', 'yproject'); ?></h2>
                <label for="org_address"><?php _e('Adresse', 'yproject'); ?></label>
                <input type="text" name="org_address" value="<?php echo $organisation_obj->get_address(); ?>" /><br />

                <label for="org_postal_code"><?php _e('Code postal', 'yproject'); ?></label>
                <input type="text" name="org_postal_code" value="<?php echo $organisation_obj->get_postal_code(); ?>" /><br />

                <label for="org_city"><?php _e('Ville', 'yproject'); ?></label>
                <input type="text" name="org_city" value="<?php echo $organisation_obj->get_city(); ?>" /><br />

                <label for="org_nationality"><?php _e('Pays', 'yproject'); ?></label>
                <select name="org_nationality" id="org_nationality">
                        <?php 
                        locate_template( array("country_list.php"), true );
                        global $country_list;
                        $selected_country = $organisation_obj->get_nationality();
                        foreach ($country_list as $country_code => $country_name): ?>
                                <option value="<?php echo $country_code; ?>" <?php if ($country_code == $selected_country) { echo 'selected="selected"'; } ?>><?php echo $country_name; ?></option>
                        <?php endforeach; ?>
                </select><br />


                <?php
                /**
                 * Informations bancaires
                 */
                ?>
                <h3><?php _e('Informations bancaires - si vous souhaitez faire un virement d&apos;une somme obtenue', 'yproject'); ?></h3>
                <label for="org_bankownername"><?php _e('Nom du propri&eacute;taire du compte', 'yproject'); ?></label>
                <input type="text" name="org_bankownername" value="<?php echo $organisation_obj->get_bank_owner(); ?>" /> <br />

                <label for="org_bankowneraddress"><?php _e('Adresse du compte', 'yproject'); ?></label>
                <input type="text" name="org_bankowneraddress" value="<?php echo $organisation_obj->get_bank_address(); ?>" /> <br />

                <label for="org_bankowneriban"><?php _e('IBAN', 'yproject'); ?></label>
                <input type="text" name="org_bankowneriban" value="<?php echo $organisation_obj->get_bank_iban(); ?>" /> <br />

                <label for="org_bankownerbic"><?php _e('BIC', 'yproject'); ?></label>
                <input type="text" name="org_bankownerbic" value="<?php echo $organisation_obj->get_bank_bic(); ?>" /> <br />


                <?php
                /**
                 * Documents
                 */
                ?>
                <h3><?php _e('Documents', 'yproject'); ?></h3>
                <?php _e("Afin de lutter contre le blanchiment d'argent, les organisations doivent transmettre des documents d'identification.", 'yproject'); ?><br />
                <?php _e("Ces fichiers doivent avoir un poids inf&eacute;rieur &agrave; 4Mo et doivent &ecirc;tre dans l'un des formats suivants :", 'yproject'); ?> 
                <?php foreach (WDGKYCFile::$authorized_format_list as $format_str) {
                        echo $format_str . ', ';
                } ?>
                <br /><br />

                <strong><?php _e("Scan ou copie d'un RIB", 'yproject'); ?></strong><br />
                <?php
                $current_filelist_bank = WDGKYCFile::get_list_by_owner_id($organisation_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_bank);
                $current_file_bank = $current_filelist_bank[0];
                if ( isset($current_file_bank) ):
                ?>
                <a id="org_doc_bank" target="_blank" href="<?php echo $current_file_bank->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_bank->date_uploaded; ?></a><br />
                <?php endif; ?>
                <input type="file" name="org_doc_bank" /> <br /><br />

                <strong><?php _e("K-BIS ou &eacute;quivalent &agrave; un registre du commerce", 'yproject'); ?></strong><br />
                <?php _e("Datant de moins de 3 mois", 'yproject'); ?><br />
                <?php
                $current_filelist_kbis = WDGKYCFile::get_list_by_owner_id($organisation_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_kbis);
                $current_file_kbis = $current_filelist_kbis[0];
                if ( isset($current_file_kbis) ):
                ?>
                <a id="org_doc_kbis" target="_blank" href="<?php echo $current_file_kbis->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_kbis->date_uploaded; ?></a><br />
                <?php endif; ?>
                <input type="file" name="org_doc_kbis" /> <br /><br />

                <strong><?php _e("Statuts de la soci&eacute;t&eacute;, certifi&eacute;s conformes à l'original par le g&eacute;rant", 'yproject'); ?></strong><br />
                <?php
                $current_filelist_status = WDGKYCFile::get_list_by_owner_id($organisation_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_status);
                $current_file_status = $current_filelist_status[0];
                if ( isset($current_file_status) ):
                ?>
                <a id="org_doc_status" target="_blank" href="<?php echo $current_file_status->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_status->date_uploaded; ?></a><br />
                <?php endif; ?>
                <input type="file" name="org_doc_status" /> <br /><br />

                <strong><?php _e("Justificatif d'identit&eacute; du g&eacute;rant ou du pr&eacute;sident", 'yproject'); ?></strong><br />
                <?php _e("Pour une personne fran&ccedil;aise : carte d'identit&eacute; recto-verso ou passeport fran&ccedil;ais.", 'yproject'); ?><br />
                <?php _e("Sinon : le titre de s&eacute;jour et le passeport d'origine.", 'yproject'); ?><br />
                <?php
                $current_filelist_id = WDGKYCFile::get_list_by_owner_id($organisation_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_id);
                $current_file_id = $current_filelist_id[0];
                if ( isset($current_file_id) ):
                ?>
                <a id="org_doc_id" target="_blank" href="<?php echo $current_file_id->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_id->date_uploaded; ?></a><br />
                <?php endif; ?>
                <input type="file" name="org_doc_id" /> <br /><br />

                <strong><?php _e("Justificatif de domicile du g&eacute;rant ou du pr&eacute;sident", 'yproject'); ?></strong><br />
                <?php _e("Datant de moins de 3 mois, provenant d'un fournisseur d'&eacute;nergie (&eacute;lectricit&eacute;, gaz, eau) ou d'un bailleur, ou un relev&eacute; d'imp&ocirc;t datant de moins de 3 mois", 'yproject'); ?><br />
                <?php
                $current_filelist_home = WDGKYCFile::get_list_by_owner_id($organisation_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_home);
                $current_file_home = $current_filelist_home[0];
                if ( isset($current_file_home) ):
                ?>
                <a id="org_doc_home" target="_blank" href="<?php echo $current_file_home->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_home->date_uploaded; ?></a><br />
                <?php endif; ?>
                <input type="file" name="org_doc_home" /> <br /><br />

                <input type="hidden" name="campaign_id" value="<?php echo $campaign_id; ?>" />
				<input type="hidden" name="action" value="save_edit_organisation" />

                <?php DashboardUtility::create_save_button("orgaedit_form"); ?>
        </form>


        <?php if ( $WDGUser_current->is_admin() ): ?>
                <h3><?php _e('Lemonway', 'yproject'); ?></h3>

                <?php $organisation_lemonway_authentication_status = $organisation_obj->get_lemonway_status(); ?>
                <?php if ($organisation_lemonway_authentication_status == YPOrganisation::$lemonway_status_blocked): ?>
                        <?php _e("Afin de s'authentifier chez notre partenaire Lemonway, les informations suivantes sont n&eacute;cessaires : e-mail, description, num&eacute;ro SIREN. Ainsi que les 5 documents ci-dessus.", 'yproject'); ?><br />
                <?php elseif ($organisation_lemonway_authentication_status == YPOrganisation::$lemonway_status_ready): ?>
                        <form action="" method="POST" enctype="multipart/form-data">
                                <input type="submit" class="button" name="authentify_lw" value="<?php _e("Authentifier chez Lemonway", 'yproject'); ?>" />
                        </form>
                <?php elseif ($organisation_lemonway_authentication_status == YPOrganisation::$lemonway_status_waiting): ?>
                        <?php _e("L'organisation est en cours d'authentification aupr&egrave;s de notre partenaire.", 'yproject'); ?>
                        <form action="" method="POST" enctype="multipart/form-data">
                                <input type="submit" class="button" name="authentify_lw" value="<?php _e("Authentifier chez Lemonway", 'yproject'); ?>" />
                        </form>
                <?php elseif ($organisation_obj->is_registered_lemonway_wallet()): ?>
                        <?php _e("L'organisation est bien authentifi&eacute;e aupr&egrave;s de notre partenaire.", 'yproject'); ?>
                <?php elseif ($organisation_lemonway_authentication_status == YPOrganisation::$lemonway_status_rejected): ?>
                        <?php _e("L'organisation a &eacute;t&eacute; refus&eacute;e par notre partenaire.", 'yproject'); ?>
                <?php endif; ?>

        <?php endif; ?>



        <?php
        /**
         * Porte-monnaie
         */
        ?>
        <h2 class="underlined"><?php _e( 'Porte-monnaie', 'yproject' ); ?></h2>
        <?php // Porte-monnaie LW ?>
        <?php $lemonway_balance = $organisation_obj->get_lemonway_balance(); ?>
        Vous disposez de <?php echo $lemonway_balance; ?>&euro; dans votre porte-monnaie.<br /><br />

        <?php if ( $WDGUser_current->is_admin() ): ?>

                <?php if ( $lemonway_balance > 0 ): ?>

                        <div style="background: #DDD">
                                Ce formulaire n'est accessible qu'en administration :<br />
                                <form action="" method="POST">
                                        <input type="hidden" name="submit_transfer_wallet_lemonway" value="1" />
                                        Somme à verser au porteur de projet : <input type="text" name="transfer_amount" value="0" /><br />
                                        Somme à prendre en commission : <input type="text" name="transfer_commission" value="0" /><br />
                                        <input type="submit" value="Verser" />
                                </form>
                        </div>

                <?php endif; ?>

        <?php endif; ?>


        <?php
        /**
         * Transferts d'argent
         */
        ?>
        <h2 class="underlined"><?php _e( 'Transferts d&apos;argent', 'yproject' ); ?></h2>
        <?php
        $args = array(
            'author'	    => $organisation_obj->get_wpref(),
            'post_type'	    => 'withdrawal_order',
            'post_status'   => 'any',
            'orderby'	    => 'post_date',
            'order'			=>  'ASC'
        );
        $transfers = get_posts($args);
        if ($transfers) :
        ?>
        <ul class="user_history">
                <?php foreach ( $transfers as $post ) :
                        $post = get_post($post);
                        $date_lemonway = new DateTime( '2016-08-03' );
                        $date_transfer = new DateTime( $post->post_date );
                        $post_amount = ($date_transfer > $date_lemonway) ? $post->post_title : $post->post_title / 100;
                        if ($post->post_status == 'publish') {
                            ?>
                            <li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- Termin&eacute;</li>
                            <?php
                        } else if ($post->post_status == 'draft') {
                            ?>
                            <li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- Annul&eacute;</li>
                            <?php
                        } else {
                            ?>
                            <li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- En cours</li>
                            <?php
                        }
                endforeach; ?>
        </ul>
        <?php else: ?>
                Aucun transfert.
        <?php endif; ?>                      

</div>