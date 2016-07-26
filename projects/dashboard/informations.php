<?php

function print_informations_page()
{
    locate_template('country_list.php', true);
    global $country_list;
    global $campaign_id, $campaign, $post_campaign,
           $WDGAuthor, $WDGUser_current;

    $user_is_author = $WDGAuthor->wp_user->ID == $WDGUser_current->wp_user->ID;

    function is_necessary_now($critical_status){
        global $campaign;
        $priorities = ATCF_Campaign::get_campaign_status_priority();

        return($priorities[$campaign->campaign_status()] >= $priorities[$critical_status]);
    }

    function necessary_class($critical_status){
        if (is_necessary_now($critical_status)){
            echo ' necessary ';
        }
    }

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
            <form id="userinfo_form" data-campaignid="<?php echo $campaign->ID; ?>">
                <?php if ($user_is_author) {
                    ?><p>Complétez vos informations personnelles de porteur de projet</p>
                    <input type="hidden" id="input_is_project_holder" name="is_project_holder" value="1"/><?php
                } else {
                    ?><p>Seul le créateur du projet peut compléter ses informations personnelles</p><?php
                }?>

                <ul id="userinfo_form_errors" class="errors">

                </ul>

                <?php
                DashboardUtility::create_select_field(gender, "Vous &ecirc;tes",
                    array("une femme", "un homme"), array("female", "male"), $WDGAuthor->wp_user->get('user_gender'), null, $user_is_author);

                DashboardUtility::create_text_field("firstname", "Prénom", $WDGAuthor->wp_user->user_firstname, null, $user_is_author);

                DashboardUtility::create_text_field("lastname", "Nom", $WDGAuthor->wp_user->user_lastname, null, $user_is_author);

                $bd = new DateTime();
                if(!empty($WDGAuthor->wp_user->get('user_birthday_year'))){
                    $bd->setDate(intval($WDGAuthor->wp_user->get('user_birthday_year')),
                        intval($WDGAuthor->wp_user->get('user_birthday_month')),
                        intval($WDGAuthor->wp_user->get('user_birthday_day')));
                }

                DashboardUtility::create_date_field("birthday", "Date de naissance", $bd, null, $user_is_author);

                DashboardUtility::create_text_field("birthplace", "Ville de naissance", $WDGAuthor->wp_user->get('user_birthplace'), null, $user_is_author);

                DashboardUtility::create_select_field('nationality', "Nationalit&eacute;",
                    array_values($country_list), array_keys($country_list), $WDGAuthor->wp_user->get('user_nationality'), null, $user_is_author);

                DashboardUtility::create_text_field("mobile_phone", "T&eacute;l&eacute;phone mobile", $WDGAuthor->wp_user->get('user_mobile_phone'),
                    "Ce num&eacute;ro sera celui utilis&eacute; pour vous contacter &agrave; propos de votre projet" , $user_is_author);

                DashboardUtility::create_text_field('email', "Adresse &eacute;lectronique", $WDGAuthor->wp_user->user_email,
                    "Pour modifier votre adresse e-mail de contact, rendez-vous dans vos param&egrave;tres de compte", false);

                DashboardUtility::create_text_field("address", "Adresse", $WDGAuthor->wp_user->get('user_address'), null, $user_is_author);

                DashboardUtility::create_text_field("postal_code", "Code postal", $WDGAuthor->wp_user->get('user_postal_code'), null, $user_is_author);

                DashboardUtility::create_text_field("city", "Ville", $WDGAuthor->wp_user->get('user_city'), null, $user_is_author);

                DashboardUtility::create_text_field("country", "Pays", $WDGAuthor->wp_user->get('user_country'), null, $user_is_author) ?>

                <br/>


                <?php if ($user_is_author) { ?><p id="userinfo_form_button" class="align-center">
                    <input type="submit" value="<?php _e("Enregistrer", 'yproject'); ?>" class="button"/>
                    </p>
                    <p id="userinfo_form_loading" class="align-center" hidden>
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement"/>
                    </p><?php } ?>
            </form>
        </div>

        <div class="tab-content" id="tab-organization">
            <form id="orgainfo_form">
                <ul id="orgainfo_form_errors" class="errors">

                </ul>

                <?php
                // Gestion des organisations
                $str_organisations = '';
                global $current_user;
                $api_project_id = BoppLibHelpers::get_api_project_id($post_campaign->ID);
                $current_organisations = BoppLib::get_project_organisations_by_role($api_project_id, BoppLibHelpers::$project_organisation_manager_role['slug']);
                if (isset($current_organisations) && count($current_organisations) > 0) {
                    $current_organisation = $current_organisations[0];
                }
                $api_user_id = BoppLibHelpers::get_api_user_id($post_campaign->post_author);
                $organisations_list = BoppUsers::get_organisations_by_role($api_user_id, BoppLibHelpers::$organisation_creator_role['slug']);
                if ($organisations_list) {
                    foreach ($organisations_list as $organisation_item) {
                        $selected_str = ($organisation_item->id == $current_organisation->id) ? 'selected="selected"' : '';
                        $str_organisations .= '<option ' . $selected_str . ' value="'.$organisation_item->organisation_wpref.'">' .$organisation_item->organisation_name. '</option>';
                    }
                }
                ?>
                <label for="project-organisation">Organisation :</label>
                <?php if ($str_organisations != ''): ?>
                    <select name="project-organisation" id="update_project_organisation">
                        <option value=""></option>
                        <?php echo $str_organisations; ?>
                    </select>
                    <?php if ($current_organisation!=null){
                        $page_edit_orga = get_page_by_path('editer-une-organisation');
                        $edit_org = '<a id="edit-orga-button" class="button" 
                            data-url-edit="'.  get_permalink($page_edit_orga->ID) .'?orga_id='.'" 
                            href="'.  get_permalink($page_edit_orga->ID) .'?orga_id='.$current_organisation->organisation_wpref.'">';
                        $edit_org .= 'Editer '.$current_organisation->organisation_name.'</a>';
                        echo $edit_org;
                    } ?>

                <?php else: ?>
                    <?php _e('Le porteur de projet n&apos;est li&eacute; &agrave; aucune organisation.', 'yproject'); ?>
                    <input type="hidden" name="project-organisation" value="" />
                <?php endif;

                $page_new_orga = get_page_by_path('creer-une-organisation'); ?>
                <a href="<?php echo get_permalink($page_new_orga->ID); ?>" class="button">Cr&eacute;er une organisation</a>

                <br />
                <p id="orgainfo_form_button" class="align-center">
                    <input type="submit" value="<?php _e("Enregistrer", 'yproject'); ?>" class="button"/>
                </p>
                <p id="orgainfo_form_loading" class="align-center" hidden>
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement"/>
                </p>
            </form>
        </div>

        <div class="tab-content" id="tab-project">
            <?php
            //Gestion des catégories
            $campaign_categories = get_the_terms($campaign_id, 'download_category');
            $selected_category = 0;
            $selected_activity = 0;
            $terms_category = get_terms('download_category', array('slug' => 'categories', 'hide_empty' => false));
            $term_category_id = $terms_category[0]->term_id;
            $terms_activity = get_terms('download_category', array('slug' => 'activities', 'hide_empty' => false));
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
            <form id="projectinfo_form">
                <ul id="projectinfo_form_errors" class="errors">

                </ul>

                <?php
                DashboardUtility::create_text_field("project_name", "Nom du projet", $post_campaign->post_title);

                DashboardUtility::create_wpeditor_field('backoffice_summary', 'R&eacute;sum&eacute; du projet', $campaign->backoffice_summary(),
                    $infobubble="Décrivez-nous votre projet. Les informations sont traitées de façon confidentielles");?>

                <label for="categories">Cat&eacute;gorie :</label>
                <?php wp_dropdown_categories(array(
                    'hide_empty' => 0,
                    'taxonomy' => 'download_category',
                    'selected' => $selected_category,
                    'echo' => 1,
                    'child_of' => $term_category_id,
                    'name' => 'categories',
                    'id' => 'update_project_category'
                )); ?><br/>

                <label for="activities">Secteur d&apos;activit&eacute;:</label>
                <?php wp_dropdown_categories(array(
                    'hide_empty' => 0,
                    'taxonomy' => 'download_category',
                    'selected' => $selected_activity,
                    'echo' => 1,
                    'child_of' => $term_activity_id,
                    'name' => 'activities',
                    'id' => 'update_project_activity'
                )); ?><br/>

                <?php
                $locations = atcf_get_locations();
                DashboardUtility::create_select_field('project_location', "Localisation", $locations, $locations, $campaign->location());

                ?>

                <p id="projectinfo_form_button" class="align-center">
                    <input type="submit" value="<?php _e("Enregistrer", 'yproject'); ?>" class="button"/>
                </p>
                <p id="projectinfo_form_loading" class="align-center" hidden>
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement"/>
                </p>
            </form>
        </div>

        <div class="tab-content" id="tab-funding">
            <ul id="projectfunding_form_errors" class="errors">

            </ul>
            <form action="" id="projectfunding_form">
                <?php
                DashboardUtility::create_number_field("maximum_goal", "Montant maximal demand&eacute;", $campaign->goal(false), 0, null, 1, "&euro;");

                DashboardUtility::create_number_field("minimm_goal", "Palier minimal", $campaign->minimum_goal(false), 0, null, 1, "&euro;",
                    "Au-del&agrave; de ce palier, la collecte sera valid&eacute; mais rien n'emp&ecirc;che d'avoir un objectif plus ambitieux !");

                DashboardUtility::create_number_field("funding_duration", "Dur&eacute;e du financement", $campaign->funding_duration(), 1, 100, 1, "ann&eacute;es");

                DashboardUtility::create_number_field("roi_percent_estimated", "Pourcentage de reversement estim&eacute;", $campaign->roi_percent_estimated(), 0, 100, 0.01, "% du CA");

                DashboardUtility::create_date_field("first_payment", "Première date de versement", new DateTime($campaign->first_payment_date()),false);

                ?>

                <label>CA pr&eacute;visionnel</label>
                <ul id="estimated-turnover">
                    <?php foreach (($campaign->estimated_turnover()) as $year => $turnover) : ?>
                        <li><label>Année <span class="year"><?php echo $year?></span></label><input type="text" value="<?php echo $turnover?>"/>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <p id="projectfunding_form_button" class="align-center">
                    <input type="submit" value="<?php _e("Enregistrer", 'yproject'); ?>" class="button"/>
                </p>
                <p id="projectfunding_form_loading" class="align-center" hidden>
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement"/>
                </p>
            </form>
        </div>

        <div class="tab-content" id="tab-communication">
            <ul id="communication_form_errors" class="errors">

            </ul>
            <form action="" id="communication_form">
                <?php
                DashboardUtility::create_text_field('website', 'Site web', $campaign->campaign_external_website());

                DashboardUtility::create_text_field('facebook', 'Page Facebook', $campaign->facebook_name(),"",true,"www.facebook.com/","PageFacebook");

                DashboardUtility::create_text_field('twitter', 'Twitter', $campaign->twitter_name(),"",true,"@","CompteTwitter");

                ?>
                <p id="communication_form_button" class="align-center">
                    <input type="submit" value="<?php _e("Enregistrer", 'yproject'); ?>" class="button"/>
                </p>
                <p id="communication_form_loading" class="align-center" hidden>
                    <img src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement"/>
                </p>
            </form>
        </div>

        <div class="tab-content" id="tab-contract">
            Contract
        </div>
    </div>
    <?php
}
