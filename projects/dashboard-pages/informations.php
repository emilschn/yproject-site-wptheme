<?php

function print_informations_page()
{
    locate_template('country_list.php', true);
    global $country_list, $campaign, $campaign_id, $WDGAuthor, $WDGUser_current;

    $user_is_author = $WDGAuthor->wp_user->ID == $WDGUser_current->wp_user->ID;

    ?>
    <div class="bloc-grid">
        <div class="display-bloc" data-tab-target="tab-user-infos">
            <div class="infobloc-title">
                Infos personnelles
            </div>
        </div>
        <div class="display-bloc" data-tab-target="tab-organization">
            <div class="infobloc-title">
                L'organisation
            </div>
        </div>
        <div class="display-bloc" data-tab-target="tab-project">
            <div class="infobloc-title">
                Le projet
            </div>
        </div>
        <div class="display-bloc" data-tab-target="tab-funding">
            <div class="infobloc-title">
                Besoin de financement
            </div>
        </div>
        <div class="display-bloc" data-tab-target="tab-communication">
            <div class="infobloc-title">
                Votre communication
            </div>
        </div>
        <div class="display-bloc" data-tab-target="tab-contract">
            <div class="infobloc-title">
                Contractualisation
            </div>
        </div>
    </div>

    <div id="tab-container">
        <div class="tab-content" id="tab-user-infos">
            <?php if ($user_is_author) {
                ?><p>Complétez vos informations personnelles de porteur de projet</p><?php
            } else {
                ?><p>Seul le créateur du projet peut compléter ses informations personnelles</p><?php
            } ?>

            <form id="userinfo_form" data-campaignid="<?php echo $campaign->ID; ?>" class="wdg-forms">
                <?php if ($user_is_author) ?><input type="hidden" id="input_is_project_holder" name="is_project_holder"
                                                    value="1"><?php ; ?>
                <ul id="userinfo_form_errors" class="errors">

                </ul>

                <label for="update_gender" class="standard-label"><?php _e("Vous &ecirc;tes", 'yproject'); ?></label>
                <select name="update_gender" id="update_gender" <?php if (!$user_is_author) echo "disabled"; ?>>
                    <option
                        value="female"<?php if ($WDGAuthor->wp_user->get('user_gender') == "female") echo ' selected="selected"'; ?>>
                        une femme
                    </option>
                    <option
                        value="male"<?php if ($WDGAuthor->wp_user->get('user_gender') == "male") echo ' selected="selected"'; ?>>
                        un homme
                    </option>
                </select><br/>

                <label for="update_firstname" class="standard-label"><?php _e('Pr&eacute;nom', 'yproject'); ?></label>
                <?php if ($user_is_author) { ?>
                    <input type="text" name="update_firstname" id="update_firstname"
                           value="<?php echo $WDGAuthor->wp_user->user_firstname; ?>"/>
                <?php } else echo $WDGAuthor->wp_user->user_firstname; ?>
                <br/>

                <label for="update_lastname" class="standard-label"><?php _e('Nom', 'yproject'); ?></label>
                <?php if ($user_is_author) { ?>
                    <input type="text" name="update_lastname" id="update_lastname"
                           value="<?php echo $WDGAuthor->wp_user->user_lastname; ?>"/>
                <?php } else echo $WDGAuthor->wp_user->user_lastname; ?>
                <br/>

                <label for="update_birthday_day"
                       class="standard-label"><?php _e('Date de naissance', 'yproject'); ?></label>
                <select name="update_birthday_day"
                        id="update_birthday_day" <?php if (!$user_is_author) echo "disabled"; ?>>
                    <?php for ($i = 1; $i <= 31; $i++) { ?>
                        <option
                            value="<?php echo $i; ?>"<?php if ($WDGAuthor->wp_user->get('user_birthday_day') == $i) echo ' selected="selected"'; ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>
                <select name="update_birthday_month"
                        id="update_birthday_month" <?php if (!$user_is_author) echo "disabled"; ?>>
                    <?php
                    $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                    for ($i = 1; $i <= 12; $i++) { ?>
                        <option
                            value="<?php echo $i; ?>"<?php if ($WDGAuthor->wp_user->get('user_birthday_month') == $i) echo ' selected="selected"'; ?>><?php _e($months[$i - 1]); ?></option>
                    <?php }
                    ?>
                </select>
                <select name="update_birthday_year"
                        id="update_birthday_year" <?php if (!$user_is_author) echo "disabled"; ?>>
                    <?php for ($i = date("Y"); $i >= 1900; $i--) { ?>
                        <option
                            value="<?php echo $i; ?>"<?php if ($WDGAuthor->wp_user->get('user_birthday_year') == $i) echo ' selected="selected"'; ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>
                <br/>

                <label for="update_birthplace"
                       class="standard-label"><?php _e('Ville de naissance', 'yproject'); ?></label>
    <?php if ($user_is_author) { ?>
                <input type="text" name="update_birthplace" id="update_birthplace"
                       value="<?php echo $WDGAuthor->wp_user->get('user_birthplace'); ?>"/>
<?php } else { echo $WDGAuthor->wp_user->get('user_birthplace'); } ?>
                <br/>

                <label for="update_nationality"
                       class="standard-label"><?php _e('Nationalit&eacute;', 'yproject'); ?></label>
                <select name="update_nationality"
                        id="update_nationality" <?php if (!$user_is_author) echo "disabled"; ?>>
                    <option value=""></option>
                    <?php foreach ($country_list as $country_code => $country_name) : ?>
                        <option
                            value="<?php echo $country_code; ?>"<?php if ($WDGAuthor->wp_user->get('user_nationality') == $country_code) echo ' selected="selected"'; ?>><?php echo $country_name; ?></option>
                    <?php endforeach; ?>
                </select><br/>


                <label for="update_address" class="standard-label"><?php _e('Adresse', 'yproject'); ?></label>
                <?php if ($user_is_author) { ?>
                    <input type="text" name="update_address" id="update_address"
                           value="<?php echo $WDGAuthor->wp_user->get('user_address'); ?>"/>
                <?php } else { echo $WDGAuthor->wp_user->get('user_address'); } ?>
                <br/>


                <label for="update_postal_code" class="standard-label"><?php _e('Code postal', 'yproject'); ?></label>
                <?php if ($user_is_author) { ?>
                    <input type="text" name="update_postal_code" id="update_postal_code"
                           value="<?php echo $WDGAuthor->wp_user->get('user_postal_code'); ?>"/>
                <?php } else { echo $WDGAuthor->wp_user->get('user_postal_code'); } ?>
                <br/>


                <label for="update_city" class="standard-label"><?php _e('Ville', 'yproject'); ?></label>
                <?php if ($user_is_author) { ?>
                    <input type="text" name="update_city" id="update_city"
                           value="<?php echo $WDGAuthor->wp_user->get('user_city'); ?>"/>
                <?php } else { echo $WDGAuthor->wp_user->get('user_city'); } ?>
                <br/>


                <label for="update_country" class="standard-label"><?php _e('Pays', 'yproject'); ?></label>
                <?php if ($user_is_author) { ?>
                    <input type="text" name="update_country" id="update_country"
                           value="<?php echo $WDGAuthor->wp_user->get('user_country'); ?>"/>
                <?php } else { echo $WDGAuthor->wp_user->get('user_country'); } ?>
                <br/>


                <label for="update_mobile_phone"
                       class="standard-label"><?php _e('T&eacute;l&eacute;phone mobile', 'yproject'); ?></label>
                <?php if ($user_is_author) { ?>
                    <input type="text" name="update_mobile_phone" id="update_mobile_phone"
                           value="<?php echo $WDGAuthor->wp_user->get('user_mobile_phone'); ?>"/>
                <?php } else { echo $WDGAuthor->wp_user->get('user_mobile_phone'); } ?>
                <br/><br/>


                <?php if ($user_is_author) { ?><p id="userinfo_form_button" class="align-center">
                    <input type="submit" value="<?php _e("Enregistrer", 'yproject'); ?>" class="button"/>
                    </p>
                    <p id="userinfo_form_loading" class="align-center" hidden>
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement"/>
                    </p><?php } ?>
            </form>
        </div>

        <div class="tab-content" id="tab-organization">
            Orga
        </div>

        <div class="tab-content" id="tab-project">
            <?php
            //Gestion des catégories
            $campaign_categories = get_the_terms($campaign_id, 'download_category');
            $selected_category = 0;
            $selected_activity = 0;
            $terms_category = get_terms( 'download_category', array('slug' => 'categories', 'hide_empty' => false));
            $term_category_id = $terms_category[0]->term_id;
            $terms_activity = get_terms( 'download_category', array('slug' => 'activities', 'hide_empty' => false));
            $term_activity_id = $terms_activity[0]->term_id;
            if ($campaign_categories) {
                foreach ($campaign_categories as $campaign_category) {
                    if ($campaign_category->parent == $term_category_id) {
                        $selected_category = $campaign_category->term_id;
                    }
                    if ($campaign_category->parent == $term_activity_id) {
                        $selected_activity = $campaign_category->term_id;
                    }
                }
            }
            ?>
            <form action="" method="POST" enctype="multipart/form-data" class="wdg-forms">
                <label for="project-name">Nom du projet :</label>
                <input type="text" name="project-name" value="<?php echo $post_campaign->post_title; ?>" /><br />

                <label for="categories">Cat&eacute;gorie :</label>
                <?php wp_dropdown_categories( array(
                    'hide_empty'  => 0,
                    'taxonomy'    => 'download_category',
                    'selected'    => $selected_category,
                    'echo'        => 1,
                    'child_of'    => $term_category_id,
                    'name'        => 'categories'
                ) ); ?><br />

                <a id="picture-head"></a><a id="video-zone"></a><a id="project-owner"></a><?php /* ancres déplacées pour cause de menu... */ ?>
                <label for="activities">Secteur d&apos;activit&eacute; :</label>
                <?php wp_dropdown_categories( array(
                    'hide_empty'  => 0,
                    'taxonomy'    => 'download_category',
                    'selected'    => $selected_activity,
                    'echo'        => 1,
                    'child_of'    => $term_activity_id,
                    'name'        => 'activities'
                ) ); ?><br />

                <label for="project-location">Localisation :</label>
                <select name="project-location">
                    <?php
                    $locations = atcf_get_locations();
                    $location_str = '';
                    foreach ($locations as $location) {
                        $selected_str = ($location == $campaign->location()) ? 'selected="selected"' : '';
                        $location_str .= '<option ' . $selected_str . '>' . $location . '</option>';
                    }
                    echo $location_str;
                    ?>
                </select><br />


            </form>
        </div>

        <div class="tab-content" id="tab-funding">
            <form action="" method="POST" enctype="multipart/form-data" class="wdg-forms">
                <label for="fundingduration">Dur&eacute;e du financement :</label>
                <input type="text" name="fundingduration" value="<?php echo $campaign->funding_duration(); ?>"> ann&eacute;es.<br />

                <label>Pourcentage de reversement estim&eacute; : </label>
                <input type="text" name="fundingduration" value="<?php echo $campaign->roi_percent_estimated(); ?>"> ann&eacute;es.<br />

                <label>CA pr&eacute;visionnel</label>

                <label>Montant demand&eacute; :</label>
                Minimum : <input type="text" name="minimum_goal" size="10" value="<?php echo $campaign->minimum_goal(); ?>"> &euro; (Min. 500&euro;) -
                Maximum : <input type="text" name="maximum_goal" size="10" value="<?php echo $campaign->goal(false); ?>"> &euro;

            </form>
        </div>

        <div class="tab-content" id="tab-communication">
            Communication
        </div>

        <div class="tab-content" id="tab-contract">
            Contract
        </div>
    </div>
    <?php
}
