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


        <?php if ($is_admin): ?>
        <div class="tab-content">
            <div class="admin-block">
                <h3>[ADMIN] <?php _e('Ajouter un paiement par ch&egrave;que', 'yproject'); ?></h3>

                <?php if (isset($_POST['action']) && $_POST['action'] == 'add-check-investment') {
                    $add_check_result = $campaign->add_investment('check', $_POST['email'], $_POST['value'], $_POST['username'], $_POST['password'], $_POST['gender'], $_POST['firstname'], $_POST['lastname'], $_POST['orga_email'], $_POST['orga_name']);
                    if ($add_check_result !== FALSE) { ?>
                        <span class="success">Investissement ajouté</span>
                    <?php } else { ?>
                        <span class="errors" style="color: black;">Erreur lors de l'ajout</span>
                    <?php }
                } ?>

                <form method="POST" action="">
                    <label for="email"><?php _e('E-mail :', 'yproject'); ?>*</label> <input type="text" name="email" <?php if (isset($_POST['email']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['email']; ?>"<?php } ?> /><br />
                    <label for="value"><?php _e('Somme :', 'yproject'); ?>*</label> <input type="text" name="value" <?php if (isset($_POST['value']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['value']; ?>"<?php } ?> /><br />
                    <label for="username"><?php _e('Login :', 'yproject'); ?></label> <input type="text" name="username" <?php if (isset($_POST['username']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['username']; ?>"<?php } ?> /><br />
                    <label for="password"><?php _e('Mot de passe :', 'yproject'); ?></label> <input type="text" name="password" <?php if (isset($_POST['password']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['password']; ?>"<?php } ?> /><br />
                    <label for="gender"><?php _e('Genre :', 'yproject'); ?></label>
                    <select name="gender">
                        <option value="female" <?php if (isset($_POST['gender']) && $_POST['gender'] == "female" && $add_check_result === FALSE) { ?>selected="selected"<?php } ?>>Mme</option>
                        <option value="male" <?php if (isset($_POST['gender']) && $_POST['gender'] == "male" && $add_check_result === FALSE) { ?>selected="selected"<?php } ?>>Mr</option>
                    </select><br />
                    <label for="firstname"><?php _e('Pr&eacute;nom :', 'yproject'); ?></label> <input type="text" name="firstname" <?php if (isset($_POST['firstname']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['firstname']; ?>"<?php } ?> /><br />
                    <label for="lastname"><?php _e('Nom :', 'yproject'); ?></label> <input type="text" name="lastname" <?php if (isset($_POST['lastname']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['lastname']; ?>"<?php } ?> /><br /><br />

                    -- <?php _e("Si il s'agit d'une organisation :", 'yproject'); ?><br />
                    -- <label for="orga_email"><?php _e("E-mail de l'organisation :", 'yproject'); ?></label> <input type="text" name="orga_email" <?php if (isset($_POST['orga_email']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['orga_email']; ?>"<?php } ?> /><br />
                    -- <label for="orga_name"><?php _e("Nom de l'organisation (si n'existe pas d&eacute;j&agrave;) :", 'yproject'); ?></label> <input type="text" name="orga_name" <?php if (isset($_POST['orga_name']) && $add_check_result === FALSE) { ?>value="<?php echo $_POST['orga_name']; ?>"<?php } ?> /><br /><br />

                    <button type="submit" class="button"><?php _e('Ajouter', 'yproject'); ?></button>
                    <input type="hidden" name="action" value="add-check-investment" />
                </form>
            </div>
        </div>
        <?php endif;
}