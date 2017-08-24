<?php

function print_contacts_page() {

    global $can_modify,
           $campaign_id, $campaign, $post_campaign,
           $WDGAuthor, $WDGUser_current,
           $is_admin, $is_author;

	$send_mail_success = filter_input( INPUT_GET, 'send_mail_success' );
        ?>

        <div class="head"><?php _e('Contacts', 'yproject'); ?></div>
		
		<?php if (!empty($send_mail_success)): ?>
		<div class="success"><?php _e( "E-mails envoy&eacute;s avec succ&egrave;s !", 'yproject' ); ?></div>
		<?php endif; ?>
		
        <div class="tab-content-large">
            <div id="ajax-contacts-load" class="ajax-investments-load" style="text-align: center;" data-value="<?php echo $campaign->ID?>">
                <img id="ajax-loader-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" />
            </div>
        </div>

        <div class="tab-content" id="send-mail-tab" style="display: none">
            <h2><?php _e("Envoyer un mail", 'yproject')?></h2>
            <form id="direct-mail" method="POST" action="<?php echo admin_url( 'admin-post.php?action=send_project_mail'); ?>">
                <p><?php _e("Le message sera envoyé &agrave", 'yproject')?> <strong id="nb-mailed-contacts">0</strong> personnes</p>
                <input type="hidden" id="mail_recipients" name="mail_recipients"/>
                <input type="hidden" name="campaign_id" value="<?php echo $campaign_id?>"/>
                <div class="step-write">
                    <p><strong><?php _e("Vous pouvez utiliser les variables suivantes : ", 'yproject'); ?></strong>
                    <?php DashboardUtility::get_infobutton('Au moment de l\'envoi, les variables seront remplacées par les valeurs correspondantes.<br/><br/>
                        Ainsi, par exemple, <b>%userfirstname%</b> sera remplacé par le prénom de l\'utilisateur qui recevra le message.',true)?></p>
                    <ul style="list-style-type: square;">
                        <li><i>%userfirstname%</i> : Prénom de l'utilisateur destinataire</li>
                        <li><i>%userlastname%</i> : Nom de famille de l'utilisateur destinataire</li>
                        <li><i>%investwish%</i> : Intention d'investissement</li>
                    </ul>
                    <label><strong>Objet du mail : </strong>
                        <input typ="text" name="mail_title" id="mail-title" value=""></label>
                    <br/><br/>

                    <?php
                    $previous_content = filter_input(INPUT_POST, 'mail_content');
                    if (empty($previous_content)) {
                        $previous_content = __("Bonjour ", 'yproject') . "%userfirstname%,<br />";
                        $previous_content .= __("Merci d'avoir investi dans notre projet !", 'yproject') ."<br />";
                        $previous_content .= __("A bient&ocirc;t !", 'yproject');
                    }
                    wp_editor( $previous_content, 'mail_content',
                        array(
                            'media_buttons' => true,
                            'quicktags'     => false,
                            'tinymce'       => array(
                                'plugins'				=> 'wordpress, paste, wplink, textcolor',
                                'paste_remove_styles'   => true
                            )
                        )
                    );
                    ?>
                    <br/>

                    <p class="align-center">
                        <a id="mail-preview-button" class="button"><?php _e('Prévisualisation', 'yproject'); ?></a>
                    </p>
                </div>
                <div class="step-confirm" hidden>
                    <h3>Aperçu du message</h3>
                    <div class="preview-title"></div>
                    <div class="preview"></div>

                    <p class="align-center">
                        <a id="mail-back-button" class="button"><?php _e('Editer', 'yproject'); ?></a>
                        <button type="submit" id="mail-send-button" class="button"><?php _e('Envoyer le message', 'yproject'); ?></button>
                    </p>
                </div>
            </form>
        </div>

        <?php if ($is_admin): ?>
			<div class="admin-theme-block align-center">
				<a href="#contacts" class="wdg-button-lightbox-open button" data-lightbox="add-check"><?php _e("Ajouter un ch&egrave;que","yproject") ?></a>
				<?php locate_template( array( 'projects/dashboard/contacts/lightbox-add-check.php' ), true ); ?>
			</div>
        <?php endif;
}