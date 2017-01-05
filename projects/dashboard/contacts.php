<?php

function print_contacts_page() {

    global $can_modify,
           $campaign_id, $campaign, $post_campaign,
           $WDGAuthor, $WDGUser_current,
           $is_admin, $is_author;

        ?>
        <div class="head"><?php _e('Contacts', 'yproject'); ?></div>
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
                        Ainsi, par exemple, <b>%username%</b> sera remplacé par le nom de l\'utilisateur qui recevra le message.',true)?></p>
                    <ul style="list-style-type: square;">
                        <li><i>%projectname%</i> : Nom du projet</li>
                        <li><i>%projecturl%</i> : Adresse du projet</li>
                        <li><i>%projectauthor%</i> : Nom du porteur de projet</li>
                        <li><i>%username%</i> : Nom de l'utilisateur destinataire</li>
                        <li><i>%investwish%</i> : Intention d'investissement</li>
                    </ul>
                    <label><strong>Objet du mail : </strong>
                        <input typ="text" name="mail_title" id="mail-title" value=""></label>
                    <br/><br/>

                    <?php
                    $previous_content = filter_input(INPUT_POST, 'mail_content');
                    if (empty($previous_content)) {
                        $previous_content = __("Bonjour ", 'yproject') . "%username%,<br />";
                        $previous_content .= __("Nous vous donnons rendez-vous &agrave; l'adresse ", 'yproject') . "%projecturl%" . __(" pour suivre la campagne !", 'yproject') ."<br />";
                        $previous_content .= __("A bient&ocirc;t !", 'yproject') ."<br />";
                        $previous_content .= "%projectauthor%";
                    }
                    wp_editor( $previous_content, 'mail_content',
                        array(
                            'media_buttons' => true,
                            'quicktags'     => false,
                            'tinymce'       => array(
                                'plugins'				=> 'paste, wplink, textcolor',
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
        <div class="tab-content">
            <div class="admin-theme-block">
                <h3><?php DashboardUtility::get_admin_infobutton(true); echo '&nbsp;';
                    _e('Ajouter un paiement par ch&egrave;que', 'yproject'); ?></h3>

                <?php if (isset($_POST['action']) && $_POST['action'] == 'add-check-investment') {
                    $add_check_result = $campaign->add_investment(
							'check', $_POST['email'], $_POST['value'],
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

				<form method="POST" action="">
					<div class="field"><label for="email"><?php _e('E-mail :', 'yproject'); ?>*</label>
						<input type="text" name="email" <?php if (isset($_POST['email']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['email']; ?>"<?php } ?> /></div>
					<div class="field"><label for="value"><?php _e('Somme :', 'yproject'); ?>*</label>
						<input type="text" name="value" <?php if (isset($_POST['value']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['value']; ?>"<?php } ?> /></div>
					<div class="field"><label for="username"><?php _e('Login :', 'yproject'); ?></label>
						<input type="text" name="username" <?php if (isset($_POST['username']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['username']; ?>"<?php } ?> /></div>
					<div class="field"><label for="password"><?php _e('Mot de passe :', 'yproject'); ?></label>
						<input type="text" name="password" <?php if (isset($_POST['password']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['password']; ?>"<?php } ?> /></div>
					
					<div class="field">
						<label for="gender"><?php _e('Genre :', 'yproject'); ?></label>
						<select name="gender">
							<option value="female" <?php if (isset($_POST['gender']) && $_POST['gender'] == "female" && $add_check_result === FALSE) { ?>selected="selected"<?php } ?>>Mme</option>
							<option value="male" <?php if (isset($_POST['gender']) && $_POST['gender'] == "male" && $add_check_result === FALSE) { ?>selected="selected"<?php } ?>>Mr</option>
						</select>
					</div>
					<div class="field"><label for="firstname"><?php _e('Pr&eacute;nom :', 'yproject'); ?></label> <input type="text" name="firstname" <?php if (isset($_POST['firstname']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['firstname']; ?>"<?php } ?> /></div>
					<div class="field"><label for="lastname"><?php _e('Nom :', 'yproject'); ?></label> <input type="text" name="lastname" <?php if (isset($_POST['lastname']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['lastname']; ?>"<?php } ?> /></div>
                    
					<div class="field">
						<label for="birthday_day"><?php _e( 'Date de naissance :', 'yproject' ); ?></label>
						<select name="birthday_day">
							<?php for ($i = 1; $i <= 31; $i++) { ?>
								<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php } ?>
						</select>
						<select name="birthday_month">
							<?php
							$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
							for ($i = 1; $i <= 12; $i++) { ?>
								<option value="<?php echo $i; ?>"><?php _e($months[$i - 1]); ?></option>
							<?php }
							?>
						</select>
						<select name="birthday_year">
							<?php for ($i = date("Y"); $i >= 1900; $i--) { ?>
								<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php } ?>
						</select>
					</div>
					
					<div class="field">
						<label for="birthplace"><?php _e( 'Ville de naissance :', 'yproject' ); ?></label>
						<input type="text" name="birthplace" />
					</div>
					
					<?php locate_template( 'country_list.php', true ); global $country_list; ?>
					<div class="field">
						<label for="nationality"><?php _e( 'Nationalit&eacute; :', 'yproject' ); ?></label>
						<select name="nationality">
							<option value=""></option>
							<?php foreach ($country_list as $country_code => $country_name) : ?>
								<option value="<?php echo $country_code; ?>"><?php echo $country_name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					
					<div class="field">
						<label for="address"><?php _e( 'Adresse :', 'yproject' ); ?></label>
						<input type="text" name="address" />
					</div>

					<div class="field">
						<label for="postal_code"><?php _e( 'Code postal :', 'yproject' ); ?></label>
						<input type="text" name="postal_code" />
					</div>

					<div class="field">
						<label for="city"><?php _e( 'Ville :', 'yproject' ); ?></label>
						<input type="text" name="city" />
					</div>

					<div class="field">
						<label for="country"><?php _e( 'Pays :', 'yproject' ); ?></label>
						<input type="text" name="country" />
					</div>

					<input type="hidden" name="iban" value="" />
					
					<br /><br />
					
                    <hr class="form-separator"/>
                    <h3><?php _e("Si il s'agit d'une organisation :", 'yproject'); ?></h3>
                    <div class="field">
                        <label for="orga_email"><?php _e("E-mail de l'organisation :", 'yproject'); ?></label>
                        <input type="text" name="orga_email" <?php if (isset($_POST['orga_email']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['orga_email']; ?>"<?php } ?> /></div>
                    <div class="field"><label for="orga_name"><?php _e("Nom de l'organisation (si n'existe pas d&eacute;j&agrave;) :", 'yproject'); ?></label>
                        <input type="text" name="orga_name" <?php if (isset($_POST['orga_name']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['orga_name']; ?>"<?php } ?> /></div>

                    <p class="align-center">
                        <button type="submit" class="button admin-theme"><?php _e('Ajouter', 'yproject'); ?></button>
                    </p>
                    <input type="hidden" name="action" value="add-check-investment" />
                </form>
            </div>
        </div>
        <?php endif;
}