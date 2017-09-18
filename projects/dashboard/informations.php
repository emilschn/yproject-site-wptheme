<?php

function print_informations_page()
{
    global $country_list;
    global $campaign_id, $campaign, $post_campaign,
           $WDGAuthor, $WDGUser_current,
           $is_admin, $is_author;

    ?>

    <div class="head"><?php _e("Informations","yproject");?></div>
    <div id="tab-informations-subtabs" class="tab-subtabs bloc-grid">
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

    <div id="tab-informations-subtabs-container" class="tab-container">      
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
				
				if ( $is_admin ) {
					DashboardUtility::create_field(array(
						'id'			=> 'new_project_url',
						'type'			=> 'text',
						'label'			=> __( "URL du projet", 'yproject' ),
						'value'			=> $post_campaign->post_name,
						'admin_theme'	=> true
					));
					
					DashboardUtility::create_field(array(
						'id'			=> 'new_is_hidden',
						'type'			=> 'check',
						'label'			=> __( "Masquée du public", 'yproject' ),
						'value'			=> $campaign->is_hidden(),
						'admin_theme'	=> true
					));
					
					DashboardUtility::create_field(array(
						'id'			=> 'new_skip_vote',
						'type'			=> 'check',
						'label'			=> __( "Passer la phase de vote", 'yproject' ),
						'value'			=> $campaign->skip_vote(),
						'admin_theme'	=> true,
						"editable"		=> $campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing
					));
				}


                $terms_category = get_terms('download_category', array('slug' => 'categories', 'hide_empty' => false));
                $term_category_id = $terms_category[0]->term_id;
                $terms_activity = get_terms('download_category', array('slug' => 'activities', 'hide_empty' => false));
                $term_activity_id = $terms_activity[0]->term_id;
                $terms_type = get_terms('download_category', array('slug' => 'types', 'hide_empty' => false));
				if ( $terms_type ) {
					$term_type_id = $terms_type[0]->term_id;
				}
                ?>

                <div class="field">
					<label for="categories"><?php _e("Cat&eacute;gorie", 'yproject'); ?></label>
					<span class="field-value" data-type="multicheck" data-id="new_project_categories"><?php
                        include_once ABSPATH . 'wp-admin/includes/template.php';
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

				<?php if ( $terms_type ): ?>
                <div class="field">
					<label for="types"><?php _e("Type de projet", 'yproject'); ?></label>
					<span class="field-value" data-type="multicheck" data-id="new_project_types"><?php
						wp_terms_checklist(
							$campaign_id,
							array(
								'taxonomy' => 'download_category',
								'descendants_and_self' => $term_type_id,
								'checked_ontop' => false
						) );
					?></span>
				</div>
				<?php endif; ?>

                <?php
                $locations = atcf_get_locations();
                DashboardUtility::create_field(array(
                    "id"			=> "new_project_location",
                    "type"			=> "select",
                    "label"			=> __( "Localisation", 'yproject' ),
                    "value"			=> $campaign->location(),
                    "options_id"	=> array_keys($locations),
                    "options_names"	=> array_values($locations)
                ));
				?>

				<?php
				// Champs personnalisés
				$nb_custom_fields = $WDGAuthor->wp_user->get('wdg-contract-nb-custom-fields');
				if ( $nb_custom_fields > 0 ) {
					for ( $i = 1; $i <= $nb_custom_fields; $i++ ) {
						DashboardUtility::create_field(array(
							"id"	=> 'custom_field_' . $i,
							"type"	=> 'text',
							"label"	=> __( "Champ personnalis&eacute; " , 'yproject') .$i,
							"value"	=> get_post_meta( $campaign->ID, 'custom_field_' . $i, TRUE)
						));
					}
				}
				?>

                <?php DashboardUtility::create_save_button("projectinfo_form"); ?>
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
            <form id="orgainfo_form" class="db-form" data-action="save_project_organization">
                <ul class="errors">

                </ul>

                <?php
                // Gestion des organisations
                $str_organizations = '';
                global $current_user, $current_organization;
				$current_organization = $campaign->get_organization();
                $organizations_list = $WDGAuthor->get_organizations_list();
                if ($organizations_list) {
                    foreach ($organizations_list as $organization_item) {
                        $selected_str = ($organization_item->id == $current_organization->id) ? 'selected="selected"' : '';
                        $str_organizations .= '<option ' . $selected_str . ' value="'.$organization_item->wpref.'">' .$organization_item->name. '</option>';
                    }
                }			
                ?>
                <label for="project-organization"><?php _e("Organisation li&eacute;e au projet"); ?> :</label>
                <?php if ($str_organizations != ''): ?>
                    <span class="field-value" data-type="select" data-id="new_project_organization">
                        <select name="project-organization" id="new_project_organization">
                            <?php echo $str_organizations; ?>
                        </select>
                    </span>
					<!--bouton d'édition de l'organisation-->
					<a href="#informations" id="edit-orga-button" class="wdg-button-lightbox-open button" data-lightbox="editOrga" style="display: none;">
						<?php _e("&Eacute;diter", "yproject"); echo '&nbsp;'.$current_organization->name ?></a>
					<?php DashboardUtility::create_save_button("orgainfo_form"); ?>
					<p id="save-mention" class="hidden"><?php _e("Veuillez enregistrer l'organisation choisie pour la lier à votre projet", "yproject"); ?></p>
                <?php else: ?>
                    <?php _e('Le porteur de projet n&apos;est li&eacute; &agrave; aucune organisation.', 'yproject'); ?>
                    <input type="hidden" name="project-organization" value="" />
                <?php endif; ?>

                <!--bouton de création de l'organisation visible dans tous les cas -->
                <a href="#informations" id="btn-new-orga" class="wdg-button-lightbox-open button" data-lightbox="newOrga"><?php _e("Cr&eacute;er une organisation","yproject") ?></a>               
                <br />
				<br />

            </form> 
            <?php
                if ($current_organization!=null){
                    ob_start();
                    locate_template( array("projects/dashboard/informations/lightbox-organization-edit.php"), true );                  
                    $lightbox_content = ob_get_clean();
                    echo do_shortcode('[yproject_widelightbox id="editOrga" scrolltop="1"]'.$lightbox_content.'[/yproject_widelightbox]');
                }
            ?>
            <?php 
                ob_start();
                locate_template( array("projects/dashboard/informations/lightbox-organization-new.php"), true );
                $lightbox_content = ob_get_clean();
                echo do_shortcode('[yproject_lightbox id="newOrga" scrolltop="1"]'.$lightbox_content.'[/yproject_lightbox]');
            ?>

			<?php
			$msg_valid_changeOrga = __("L'organisation a bien &eacute;t&eacute; li&eacute;e au projet", "yproject");
			echo do_shortcode('[yproject_msglightbox id="valid-changeOrga" scrolltop="1" type="valid"]'.$msg_valid_changeOrga.'[/yproject_msglightbox]');

			$msg_valid_newOrga = __("Votre nouvelle organisation a bien &eacute;t&eacute; cr&eacute;&eacute;e", "yproject");
			echo do_shortcode('[yproject_msglightbox id="valid-newOrga" scrolltop="1" type="valid"]'.$msg_valid_newOrga.'[/yproject_msglightbox]');

			$msg_valid_editOrga = __("Les informations ont bien &eacute;t&eacute; enregistr&eacute;es", "yproject");
			echo do_shortcode('[yproject_msglightbox id="valid-editOrga" scrolltop="1" type="valid"]'.$msg_valid_editOrga.'[/yproject_msglightbox]');
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
				
				if ( $is_admin ) {

					DashboardUtility::create_field(array(
						"id"			=> "new_maximum_profit",
						"type"			=> "number",
						"label"			=> "Gain maximum",
						"value"			=> $campaign->maximum_profit(),
						"prefix"		=> '*',
						"min"			=> 1,
						"admin_theme"	=> true,
						"editable"		=> $campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing
					));
					
				}

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
					"id"			=> "new_roi_percent",
					"type"			=> "number",
					"label"			=> "Royalties r&eacute;els (selon montant collect&eacute;)",
					"value"			=> $campaign->roi_percent(),
					"suffix"		=> "<span>&nbsp;% du chiffre d'affaires</span>",
					"min"			=> 0,
					"max"			=> 100,
					"step"			=> 0.01,
					"visible"		=> $is_admin || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_funded || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_closed,
					"editable"		=> $is_admin
				));

				$contract_start_date_editable = ( $campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing || $is_admin );
				$contract_start_date_values = array();
				$contract_start_date_list = array();
				if ( $contract_start_date_editable ) {
					// Trouver la prochaine date possible : janvier, avril, juillet, octobre
					$current_date = new DateTime();
					$next_date = new DateTime();
					switch ( $current_date->format('m') ) {
						case 1:
						case 2:
						case 3:
							$next_date = new DateTime( $current_date->format( 'Y' ) . '-04-01' );
							break;
						case 4:
						case 5:
						case 6:
							$next_date = new DateTime( $current_date->format( 'Y' ) . '-07-01' );
							break;
						case 7:
						case 8:
						case 9:
							$next_date = new DateTime( $current_date->format( 'Y' ) . '-10-01' );
							break;
						case 10:
						case 11:
						case 12:
							$next_date = new DateTime( ( $current_date->format( 'Y' ) + 1 ) . '-01-01' );
							break;
					}
					// Ensuite on ajoute (arbitrairement) 10 dates
					for ( $i = 0; $i < 10; $i++ ) {
						array_push( $contract_start_date_values, $next_date->format( 'Y-m-d H:i:s' ) );
						array_push( $contract_start_date_list, $next_date->format( 'd/m/Y' ) );
						$next_date->add( new DateInterval( 'P3M' ) );
					}
				}
                DashboardUtility::create_field(array(
                    "id"			=> "new_contract_start_date",
                    "type"			=> "select",
                    "label"			=> "Date de d&eacute;marrage du contrat",
                    "value"			=> $campaign->contract_start_date(),
                    "editable"		=> $contract_start_date_editable,
                    "options_id"	=> $contract_start_date_values,
                    "options_names"	=> $contract_start_date_list
                ));
				
				if ( $is_admin ) {
					DashboardUtility::create_field(array(
						"id"			=> "new_turnover_per_declaration",
						"type"			=> "select",
						"label"			=> "Nb d&eacute;claration CA par versement",
						"value"			=> $campaign->get_turnover_per_declaration(),
						"options_id"	=> array(1, 3),
						"options_names"	=> array(1, 3),
						"editable"		=> $is_admin,
						"admin_theme"	=> true
					));
					DashboardUtility::create_field(array(
						"id"			=> "new_costs_to_organization",
						"type"			=> "number",
						"label"			=> "Pourcentage de frais appliqués au PP",
						"value"			=> $campaign->get_costs_to_organization(),
						"suffix"		=> "<span>&nbsp;%</span>",
						"min"			=> 0,
						"max"			=> 100,
						"step"			=> 0.01,
						"editable"		=> $is_admin,
						"admin_theme"	=> true
					));
					DashboardUtility::create_field(array(
						"id"			=> "new_costs_to_investors",
						"type"			=> "number",
						"label"			=> "Pourcentage de frais appliqués aux investisseurs",
						"value"			=> $campaign->get_costs_to_investors(),
						"suffix"		=> "<span>&nbsp;%</span>",
						"min"			=> 0,
						"max"			=> 100,
						"step"			=> 0.01,
						"editable"		=> $is_admin,
						"admin_theme"	=> true
					));
				}

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
                    <label><?php _e("Rendement final", "yproject") ?></label><span class="like-input-center"><p id="medium-rend">---&nbsp;%</p></span>
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
				
				
				if ( $is_admin ) {
					
					DashboardUtility::create_field(array(
						"id"			=> "new_override_contract",
						"type"			=> "editor",
						"label"			=> "Surcharger le contrat standard",
						"infobubble"	=> "Le contrat ne sera pas surcharg&eacute; si ce champ reste vide.",
						"value"			=> $campaign->override_contract()
					));
				
				}
				
				
                DashboardUtility::create_save_button("projectinfo_form");
				?>
				
				<input type="hidden" name="campaign_id" value="<?php echo $campaign_id; ?>" />
			</form>
        </div>
    </div>
    <?php
}
