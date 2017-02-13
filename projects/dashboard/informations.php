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


                $terms_category = get_terms('download_category', array('slug' => 'categories', 'hide_empty' => false));
                $term_category_id = $terms_category[0]->term_id;
                $terms_activity = get_terms('download_category', array('slug' => 'activities', 'hide_empty' => false));
                $term_activity_id = $terms_activity[0]->term_id;
                ?>

                <div class="field">
					<label for="categories"><?php _e("Cat&eacute;gorie", 'yproject'); ?></label>
					<span class="field-value" data-type="multicheck" data-id="new_project_categories"><?php
					include ABSPATH . 'wp-admin/includes/template.php';
						wp_terms_checklist(
							$campaign_id, 
							array(
								'taxonomy' => 'download_category',
								'descendants_and_self' => $term_category_id,
								'checked_ontop' => false
						) );
					?></span>
				</div>

                <div class="field">
					<label for="activities"><?php _e("Secteur d&apos;activit&eacute;", 'yproject'); ?></label>
					<span class="field-value" data-type="multicheck" data-id="new_project_activities"><?php
						wp_terms_checklist(
							$campaign_id, 
							array(
								'taxonomy' => 'download_category',
								'descendants_and_self' => $term_activity_id,
								'checked_ontop' => false
						) );
					?></span>
				</div>

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
                global $current_user, $current_organisation;
                
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
                    <?php if ($current_organisation!=null): ?>                   
                        <!--bouton d'édition de l'organisation-->
                        <a href="#informations" id="edit-orga-button" class="wdg-button-lightbox-open button" data-lightbox="editOrga">
                            <?php _e("&Eacute;diter", "yproject"); echo '&nbsp;'.$current_organisation->organisation_name ?></a>
                    <?php endif; ?>

                <?php else: ?>
                    <?php _e('Le porteur de projet n&apos;est li&eacute; &agrave; aucune organisation.', 'yproject'); ?>
                    <input type="hidden" name="project-organisation" value="" />
                <?php endif; ?>

                <!--bouton de création de l'organisation visible dans tous les cas -->
                <a href="#informations" id="btn-new-orga" class="wdg-button-lightbox-open button" data-lightbox="newOrga"><?php _e("Cr&eacute;er une organisation","yproject") ?></a>               
                <br />
                <?php
                DashboardUtility::create_save_button("orgainfo_form"); ?>

            </form> 
            <?php
                if ($current_organisation!=null){
                    ob_start();
                    locate_template( array("projects/dashboard/informations/lightbox-organisation-edit.php"), true );                  
                    $lightbox_content = ob_get_clean();
                    echo do_shortcode('[yproject_widelightbox id="editOrga" scrolltop="1"]'.$lightbox_content.'[/yproject_widelightbox]');
                }
            ?>
            <?php 
                ob_start();
		locate_template( array("projects/dashboard/informations/lightbox-organisation-new.php"), true );
                $lightbox_content = ob_get_clean();
                echo do_shortcode('[yproject_lightbox id="newOrga" scrolltop="1"]'.$lightbox_content.'[/yproject_lightbox]');
            ?>
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
                    "suffix"            => "<span>&nbsp;&euro;</span>",
                    "min"			=> 500,
					"editable"		=> $is_admin || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing
                ));

                DashboardUtility::create_field(array(
                    "id"			=> "new_maximum_goal",
                    "type"			=> "number",
                    "label"			=> "Montant maximum",
                    "infobubble"	=> "C'est le montant maximum de votre lev&eacute;e de fonds, incluant la commission de WE DO GOOD.",
                    "value"			=> $campaign->goal(false),
                    "suffix"            => "<span>&nbsp;&euro;</span>",
                    "min"			=> 500,
					"editable"		=> $is_admin || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing
                ));

                DashboardUtility::create_field(array(
                    "id"			=> "new_funding_duration",
                    "type"			=> "number",
                    "label"			=> "Dur&eacute;e du financement",
					"infobubble"	=> "Indiquez 5 ans pour un projet entrepreneurial, sauf cas particulier à valider avec l’équipe WE DO GOOD.",
                    "value"			=> $campaign->funding_duration(),
                    "suffix"		=> "<span>&nbsp;ann&eacute;es</span>",
                    "min"			=> 1,
                    "max"			=> 20,
					"editable"		=> $is_admin || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing
                ));

                DashboardUtility::create_field(array(
                    "id"			=> "new_roi_percent_estimated",
                    "type"			=> "number",
                    "label"			=> "Royalties",
                    "infobubble"	=> "Indiquez le pourcentage de chiffre d’affaires que vous souhaitez reverser à vos investisseurs. Vérifiez dans le prévisionnel ci-dessous que le retour sur investissement est suffisant.",
                    "value"			=> $campaign->roi_percent_estimated(),
                    "suffix"		=> "<span>&nbsp;% du chiffre d'affaires</span>",
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
                    <label class="column-title" style="margin-left: 270px">Chiffre d'affaires pr&eacute;visionnel</label>
                    <label class="column-title" style="margin-left: 10px; width: 260px">
                        <?php echo __('Montant des Royalties reversées', 'yproject')."&nbsp;".__("pour","yproject")?>
                        <span id="total-funding">---</span>&nbsp;&euro;
                        <?php echo "&nbsp;".__("investis"); ?>
                    </label>
                </div>
                <ul id="estimated-turnover">
                    <?php
                    $estimated_turnover = $campaign->estimated_turnover();
                    if(!empty($estimated_turnover)){
                        $i=0;
                        foreach (($campaign->estimated_turnover()) as $year => $turnover) :?>
                            <li class="field">
                                <label>Année <span class="year"><?php echo ($i+1); ?></span></label>                           
                                <span class="field-container" <?php if ( !$is_admin && $campaign->campaign_status() != ATCF_Campaign::$campaign_status_preparing ): ?> style="padding-left: 80px;" <?php endif; ?>>
                                        <span class="field-value" data-type="number" data-id="new_estimated_turnover_<?php echo $i;?>">
                                                <?php if ( $is_admin || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing ): ?>
                                                <i class="right fa fa-eur" aria-hidden="true"></i>
                                                <input type="number" value="<?php echo $turnover?>" id="new_estimated_turnover_<?php echo $i;?>" class="right-icon" />                                         
                                                <?php else: ?>
                                                <?php echo $turnover; ?>
                                                <?php endif; ?>
                                        </span>
                                        <?php if ( !$is_admin && $campaign->campaign_status() != ATCF_Campaign::$campaign_status_preparing ): ?>
                                            <span style="padding-right: 70px;">&euro;</span>
                                        <?php endif; ?>
                                        <!--montant des royalties reversées par année-->
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
                <!-- Total de royalties reversées -->
                <div id="total-roi-container" class="field">
                    <label><?php _e("TOTAL", "yproject")?></label><span class="like-input-center"><p id="total-roi">0&nbsp;€</p></span>
					<label><?php _e("Retour sur investissement", "yproject")?></label><span class="like-input-center"><p id="gain"></p></span>
                </div>
                <!-- Rendement annuel moyen pour les investisseurs -->
                <div id="annual-gain-container" class="field">
					<label><?php _e("Pour vos investisseurs", "yproject")?>&nbsp;:</label>
                    <label><?php _e("Rendement annuel moyen", "yproject") ?></label><span class="like-input-center"><p id="medium-rend">---&nbsp;%</p></span>
                </div>
                <?php if ( $is_admin || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing ): ?>
                <?php DashboardUtility::create_save_button("projectfunding_form"); ?>
                <?php endif; ?>
            </form>
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
			
			<h3>Attention : si vous envoyez un document grâce au formulaire ci-dessous, 
				la page se rafraichira et les modifications qui ne sont pas enregistrées seront perdues.</h3>
			
            <form action="<?php echo admin_url( 'admin-post.php?action=upload_contract_files'); ?>" method="post" id="contract_files_form" enctype="multipart/form-data">
                <ul class="errors">

                </ul>

				<?php
				$file_name_contract_user = $campaign->backoffice_contract_user();
				if (!empty($file_name_contract_user)) {
					$file_name_exploded = explode('.', $file_name_contract_user);
					$ext = $file_name_exploded[count($file_name_exploded) - 1];
					$file_name_contract_user = home_url() . '/wp-content/plugins/appthemer-crowdfunding/includes/contracts/' . $file_name_contract_user;
				}
                DashboardUtility::create_field(array(
                    "id"				=> "new_backoffice_contract_user",
                    "type"				=> "upload",
                    "label"				=> "Contrat d'investissement au nom d'une personne physique",
                    "value"				=> $file_name_contract_user,
                    "editable"			=> $is_admin,
					"download_label"	=> $post_campaign->post_title . " - Contrat personne physique." . $ext
                ));
				
				$file_name_contract_orga = $campaign->backoffice_contract_orga();
				if (!empty($file_name_contract_orga)) {
					$file_name_exploded = explode('.', $file_name_contract_orga);
					$ext = $file_name_exploded[count($file_name_exploded) - 1];
					$file_name_contract_orga = home_url() . '/wp-content/plugins/appthemer-crowdfunding/includes/contracts/' . $file_name_contract_orga;
				}
                DashboardUtility::create_field(array(
                    "id"				=> "new_backoffice_contract_orga",
                    "type"				=> "upload",
                    "label"				=> "Contrat d'investissement au nom d'une organisation",
                    "value"				=> $file_name_contract_orga,
                    "editable"			=> $is_admin,
					"download_label"	=> $post_campaign->post_title . " - Contrat organisation." . $ext
                ));
				
                DashboardUtility::create_save_button("projectinfo_form");
				?>
				
				<input type="hidden" name="campaign_id" value="<?php echo $campaign_id; ?>" />
			</form>
        </div>
    </div>
    <?php
}
