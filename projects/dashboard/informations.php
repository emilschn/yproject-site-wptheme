<?php

function print_informations_page()
{
    locate_template('country_list.php', true);
    global $country_list;
    global $campaign_id, $campaign, $post_campaign,
           $WDGAuthor, $WDGUser_current,
           $is_admin, $is_author;

    ?>

    <div class="head"><?php _e("Informations","yproject");?></div>
    <div class="bloc-grid">
        <div class="display-bloc" data-tab-target="tab-project">
            <i class="fa fa-lightbulb-o fa-4x aria-hidden="true"></i>
            <div class="infobloc-title">
                <?php _e("Le projet","yproject");?>
            </div>
        </div>
        <div class="display-bloc" data-tab-target="tab-user-infos">
            <i class="fa fa-user fa-4x aria-hidden="true"></i>
            <div class="infobloc-title">
                <?php _e("Informations personnelles","yproject");?>
            </div>
        </div>
        <div class="display-bloc" data-tab-target="tab-organization">
            <i class="fa fa-building fa-4x aria-hidden="true"></i>
            <div class="infobloc-title">
                <?php _e("L'organisation","yproject");?>
            </div>
        </div>
        <div class="display-bloc" data-tab-target="tab-funding">
            <i class="fa fa-money fa-4x aria-hidden="true"></i>
            <div class="infobloc-title">
                <?php _e("Besoin de financement","yproject");?>
            </div>
        </div>
        <div class="display-bloc" data-tab-target="tab-communication">
            <i class="fa fa-bullhorn fa-4x aria-hidden="true"></i>
            <div class="infobloc-title">
                <?php _e("Votre communication","yproject");?>
            </div>
        </div>
        <div class="display-bloc" data-tab-target="tab-contract">
            <span class="fa-stack fa-2x">
                <i class="fa fa-file-o fa-stack-2x"></i>
                <i class="fa fa-check-circle-o fa-stack-1x"></i>
            </span>
            <div class="infobloc-title">
                <?php _e("Contractualisation","yproject");?>
            </div>
        </div>
    </div>

    <div id="tab-container">
        <div class="tab-content" id="tab-project">
            <form id="projectinfo_form" class="db-form" data-action="save_project_infos">
                <ul class="errors">

                </ul>

                <?php
                DashboardUtility::create_field(array(
                    "id"	=> "new_project_name",
                    "type"	=> "text",
                    "label"	=> "Nom du projet",
                    "value"	=> $post_campaign->post_title
                ));

                DashboardUtility::create_field(array(
                    "id"	=> "new_backoffice_summary",
                    "type"	=> "editor",
                    "label"	=> "D&eacute;crivez-nous votre projet : ",
                    "infobubble"	=> "Ces informations seront traitées de manière confidentielle",
                    "value"	=> $campaign->backoffice_summary()
                ));


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

                <div class="field"><label for="categories">Cat&eacute;gorie</label><!--
                    --><span class="field-value" data-type="select" data-id="new_project_category"><?php
                        wp_dropdown_categories(array(
                        'hide_empty' => 0,
                        'taxonomy' => 'download_category',
                        'selected' => $selected_category,
                        'echo' => 1,
                        'child_of' => $term_category_id,
                        'name' => 'categories',
                        'id' => 'new_project_category'
                    )); ?></span></div>

                <div class="field"><label for="activities">Secteur d&apos;activit&eacute;</label><!--
                    --><span class="field-value" data-type="select" data-id="new_project_activity"><?php
                        wp_dropdown_categories(array(
                        'hide_empty' => 0,
                        'taxonomy' => 'download_category',
                        'selected' => $selected_activity,
                        'echo' => 1,
                        'child_of' => $term_activity_id,
                        'name' => 'activities',
                        'id' => 'new_project_activity'
                    )); ?></span></div>

                <?php
                $locations = atcf_get_locations();

                DashboardUtility::create_field(array(
                    "id"=>"new_project_location",
                    "type"=>"select",
                    "label"=>"Localisation",
                    "value"=>$campaign->location(),
                    "options_id"=>array_keys($locations),
                    "options_names"=>array_values($locations)
                ));


                DashboardUtility::create_field(array(
                    "id"=>"new_project_WDG_notoriety",
                    "type"=>"textarea",
                    "label"=>'"Comment avez-vous connu WDG ?"',
                    "value"=>$campaign->backoffice_WDG_notoriety(),
                    "visible"=>$is_admin,
                    "admin_theme"=>$is_admin,
                    "editable"=>false
                ));

                DashboardUtility::create_save_button("projectinfo_form"); ?>
            </form>
			
			<h3>Attention : si vous envoyez un document grâce au formulaire ci-dessous, 
				la page se rafraichira et les modifications qui ne sont pas enregistrées seront perdues.</h3>
			
            <form action="<?php echo admin_url( 'admin-post.php?action=upload_information_files'); ?>" method="post" id="projectinfo_form" enctype="multipart/form-data">
                <ul class="errors">

                </ul>

				<?php
				$file_name = $campaign->backoffice_businessplan();
				if (!empty($file_name)) {
					$file_name_exploded = explode('.', $file_name);
					$ext = $file_name_exploded[count($file_name_exploded) - 1];
					$file_name = home_url() . '/wp-content/plugins/appthemer-crowdfunding/includes/kyc/' . $file_name;
				}
                DashboardUtility::create_field(array(
                    "id"				=> "new_backoffice_businessplan",
                    "type"				=> "upload",
                    "label"				=> "Votre business plan",
                    "infobubble"		=> "Ces informations seront traitées de manière confidentielle",
                    "value"				=> $file_name,
					"download_label"	=> $post_campaign->post_title . " - BP." . $ext
                ));
				
                DashboardUtility::create_save_button("projectinfo_form"); ?>
				
				<input type="hidden" name="campaign_id" value="<?php echo $campaign_id; ?>" />
			</form>
        </div>

        <div class="tab-content" id="tab-user-infos">
            <form id="userinfo_form" class="db-form" data-action="save_user_infos_dashboard">
                <?php if ($is_author) {
                    ?><input type="hidden" id="input_is_project_holder" name="is_project_holder" value="1"/><?php
                } else {
                    ?><p><?php _e("Seul le créateur du projet peut compléter ses informations personnelles","yproject");?></p><?php
                }?>

                <ul class="errors">

                </ul>

                <?php
                DashboardUtility::create_field(array(
                    "id"=>"new_gender",
                    "type"=>"select",
                    "label"=>"Vous &ecirc;tes",
                    "value"=>$WDGAuthor->wp_user->get('user_gender'),
                    "editable"=>$is_author,
                    "options_id"=>array("female", "male"),
                    "options_names"=>array("une femme", "un homme")
                ));

                DashboardUtility::create_field(array(
                    "id"=>"new_firstname",
                    "type"=>"text",
                    "label"=>"Pr&eacute;nom",
                    "value"=>$WDGAuthor->wp_user->user_firstname,
                    "editable"=>$is_author,
                    "left_icon"=>"user",
                ));

                DashboardUtility::create_field(array(
                    "id"=>"new_lastname",
                    "type"=>"text",
                    "label"=>"Nom",
                    "value"=>$WDGAuthor->wp_user->user_lastname,
                    "editable"=>$is_author,
                    "left_icon"=>"user",
                ));

                $bd = new DateTime();
				$user_birthday_year = $WDGAuthor->wp_user->get('user_birthday_year');
                if(!empty($user_birthday_year)){
                    $bd->setDate(intval($WDGAuthor->wp_user->get('user_birthday_year')),
                        intval($WDGAuthor->wp_user->get('user_birthday_month')),
                        intval($WDGAuthor->wp_user->get('user_birthday_day')));
                }

                DashboardUtility::create_field(array(
                    "id"=>"new_birthday",
                    "type"=>"date",
                    "label"=>"Date de naissance",
                    "value"=>$bd,
                    "editable"=>$is_author
                ));

                DashboardUtility::create_field(array(
                    "id"=>"new_birthplace",
                    "type"=>"text",
                    "label"=>"Ville de naissance",
                    "value"=>$WDGAuthor->wp_user->get('user_birthplace'),
                    "editable"=>$is_author
                ));

                DashboardUtility::create_field(array(
                    "id"=>"new_nationality",
                    "type"=>"select",
                    "label"=>"Nationalit&eacute;",
                    "value"=>$WDGAuthor->wp_user->get('user_nationality'),
                    "editable"=>$is_author,
                    "options_id"=>array_keys($country_list),
                    "options_names"=>array_values($country_list)
                ));

                DashboardUtility::create_field(array(
                    "id"=>"new_mobile_phone",
                    "type"=>"text",
                    "label"=>"T&eacute;l&eacute;phone mobile",
                    "value"=>$WDGAuthor->wp_user->get('user_mobile_phone'),
                    "infobubble"=>"Ce num&eacute;ro sera celui utilis&eacute; pour vous contacter &agrave; propos de votre projet",
                    "editable"=>$is_author,
                    "left_icon"=>"mobile-phone"
                ));

                DashboardUtility::create_field(array(
                    "id"=>"new_mail",
                    "type"=>"text",
                    "label"=>"Adresse &eacute;lectronique",
                    "value"=>$WDGAuthor->wp_user->get('user_email'),
                    "infobubble"=>"Pour modifier votre adresse e-mail de contact, rendez-vous dans vos param&egrave;tres de compte",
                    "left_icon"=>"at"
                ));

                DashboardUtility::create_field(array(
                    "id"=>"new_address",
                    "type"=>"text",
                    "label"=>"Adresse",
                    "value"=>$WDGAuthor->wp_user->get('user_address'),
                    "editable"=>$is_author
                ));

                DashboardUtility::create_field(array(
                    "id"=>"new_postal_code",
                    "type"=>"text",
                    "label"=>"Code postal",
                    "value"=>$WDGAuthor->wp_user->get('user_postal_code'),
                    "editable"=>$is_author
                ));

                DashboardUtility::create_field(array(
                    "id"=>"new_city",
                    "type"=>"text",
                    "label"=>"Ville",
                    "value"=>$WDGAuthor->wp_user->get('user_city'),
                    "editable"=>$is_author
                ));

                DashboardUtility::create_field(array(
                    "id"=>"new_country",
                    "type"=>"text",
                    "label"=>"Pays",
                    "value"=>$WDGAuthor->wp_user->get('user_country'),
                    "editable"=>$is_author
                ));?>
                <br/>

                <?php
                DashboardUtility::create_save_button("userinfo_form",$is_author); ?>
            </form>
        </div>

        <div class="tab-content" id="tab-organization">
            <form id="orgainfo_form" class="db-form" data-action="save_project_organisation">
                <ul class="errors">

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
                    <span class="field-value" data-type="select" data-id="new_project_organisation">
                        <select name="project-organisation" id="new_project_organisation">
                            <option value=""></option>
                            <?php echo $str_organisations; ?>
                        </select>
                    </span>
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
                <?php DashboardUtility::create_save_button("orgainfo_form"); ?>
            </form>
        </div>

        <div class="tab-content" id="tab-funding">
            <ul class="errors">

            </ul>
            <form id="projectfunding_form"  class="db-form" data-action="save_project_funding">
                <?php
                DashboardUtility::create_field(array(
                    "id"			=> "new_minimum_goal",
                    "type"			=> "number",
                    "label"			=> "Objectif",
                    "infobubble"	=> "C'est le seuil de validation de votre lev&eacute;e de fonds, vous pourrez ensuite viser le montant maximum !",
                    "value"			=> $campaign->minimum_goal(false),
                    "right_icon"	=> "eur",
                    "min"			=> 500,
					"editable"		=> $is_admin || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing
                ));

                DashboardUtility::create_field(array(
                    "id"			=> "new_maximum_goal",
                    "type"			=> "number",
                    "label"			=> "Montant maximum",
                    "value"			=> $campaign->goal(false),
                    "right_icon"	=> "eur",
                    "min"			=> 500,
					"editable"		=> $is_admin || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing
                ));

                DashboardUtility::create_field(array(
                    "id"			=> "new_funding_duration",
                    "type"			=> "number",
                    "label"			=> "Dur&eacute;e du financement",
                    "value"			=> $campaign->funding_duration(),
                    "suffix"		=> " ann&eacute;es",
                    "min"			=> 1,
                    "max"			=> 10,
					"editable"		=> $is_admin || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing
                ));

                DashboardUtility::create_field(array(
                    "id"			=> "new_roi_percent_estimated",
                    "type"			=> "number",
                    "label"			=> "Royalties",
                    "infobubble"	=> "Pourcentage de chiffre d'affaires correspondant au montant maximum.",
                    "value"			=> $campaign->roi_percent_estimated(),
                    "suffix"		=> "&nbsp;% du chiffre d'affaires",
                    "min"			=> 0,
                    "max"			=> 100,
                    "step"			=> 0.01,
					"editable"		=> $is_admin || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing
                ));

                DashboardUtility::create_field(array(
                    "id"			=> "new_first_payment",
                    "type"			=> "date",
                    "label"			=> "Première date de versement",
                    "value"			=> new DateTime($campaign->first_payment_date()),
                    "editable"		=> $is_admin,
                    "admin_theme"	=> $is_admin,
                    "visible"		=> $is_admin || ($campaign->first_payment_date()!="")
                ));

                ?>

                <div class="field">
                    <label>Chiffre d'affaires pr&eacute;visionnel</label>
                    <label style="margin-left: 320px"><?php _e('Montant des royalties reversés', 'yproject'); ?></label>
                </div>
                <ul id="estimated-turnover">
                    <?php
                    $estimated_turnover = $campaign->estimated_turnover();
                    if(!empty($estimated_turnover)){
                        $i=0;
                        foreach (($campaign->estimated_turnover()) as $year => $turnover) :?>
                            <li class="field">
                                <label>Année <span class="year"><?php echo ($i+1); ?></span></label>
                                <span class="field-container">
                                        <span class="field-value" data-type="number" data-id="new_estimated_turnover_<?php echo $i;?>">
                                                <?php if ( $is_admin || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing ): ?>
                                                <i class="right fa fa-eur" aria-hidden="true"></i>
                                                <input type="number" value="<?php echo $turnover?>" id="new_estimated_turnover_<?php echo $i;?>" class="right-icon" />                                         
                                                <?php else: ?>
                                                <?php echo $turnover; ?> 
                                                <?php endif; ?>
                                        </span>
                                        <?php if ( !$is_admin && $campaign->campaign_status() != ATCF_Campaign::$campaign_status_preparing ): ?>
                                        &euro;
                                        <?php endif; ?>
                                        <!--montant des royalties reversés par année-->
                                        <span class="like-input-center">
                                            <p id="roi-amount-<?php echo $i;?>">0 €</p>
                                            <!--<input class="input-center" type="text" id="new-estimated-roi-<?php echo $i;?>" disabled="disabled"/>-->
                                        </span>                                     
                                </span>
                            </li>
                        <?php
                            $i++;
                        endforeach;
                    }
                     ?>
                </ul>

                <?php DashboardUtility::create_save_button("projectfunding_form"); ?>
            </form>
            <!-- Résultats de la simulation -->
            <div class="field" id="calc-funding">
                <p id="info-roi-project" class="calc-result">
                    <?php _e('Avec ce pourcentage, mes investisseurs auront retrouvé','yproject');?>
                    <span id="total-roi">---</span> &euro; 
                    <?php _e('dans', 'yproject');?>
                    <span id="nb-years">---</span>&nbsp;ans,
                    <?php _e('pour' , 'yproject');?>
                    <span id="total-funding">---</span>&nbsp;&euro;*
                    <?php _e('investis', 'yproject');?>
                </p>
                <p id="annual-gain" class="calc-result">
                    <?php ?>
                    <strong class="uppercase"><?php _e('rendement annuel pour investisseur','yproject'); ?>&nbsp;:&nbsp;</strong>
                    <span id="medium-rend" class="lowercase" >---&nbsp;%</span>
                </p>
                <p class="calc-result">*<?php _e('montant de la collecte, incluant la commission de WE DO GOOD', 'yproject') ?></p>
            </div>
        </div>

        <div class="tab-content" id="tab-communication">
            <ul class="errors">

            </ul>
            <form id="communication_form" class="db-form" data-action="save_project_communication">
                <?php
                DashboardUtility::create_field(array(
                    "id"=>"new_website",
                    "type"=>"text",
                    "label"=>'Site web',
                    "value"=> $campaign->campaign_external_website(),
                    "right_icon"=>"link",
                ));

                DashboardUtility::create_field(array(
                    "id"=>"new_facebook",
                    "type"=>"text",
                    "label"=>'Page Facebook',
                    "value"=> $campaign->facebook_name(),
                    "prefix"=>"www.facebook.com/",
                    "placeholder"=>"PageFacebook",
                    "right_icon"=>"facebook",
                ));

                DashboardUtility::create_field(array(
                    "id"=>"new_twitter",
                    "type"=>"text",
                    "label"=>'Twitter',
                    "value"=> $campaign->twitter_name(),
                    "prefix"=>"@",
                    "placeholder"=>"CompteTwitter",
                    "right_icon"=>"twitter",
                ));

                DashboardUtility::create_save_button("communication_form");?>
            </form>
        </div>

        <div class="tab-content" id="tab-contract">
            <ul class="errors">

            </ul>
            <form id="contract_form" class="db-form" data-action="save_project_contract">
                <?php

                DashboardUtility::create_field(array(
                    "id"				=> "new_contract_url",
                    "type"				=> "link",
                    "label"				=> "Lien du contrat",
                    "value"				=> $campaign->contract_doc_url(),
                    "editable"			=> $is_admin,
                    "admin_theme"		=> $is_admin,
                    "placeholder"		=> "http://.....",
                    "default_display"	=> "Le contrat n'a pas encore &eacute;t&eacute; g&eacute;n&eacute;r&eacute;."
                ));

                DashboardUtility::create_save_button("contract_form", $is_admin);?>
            </form>
        </div>
    </div>
    <?php
}
