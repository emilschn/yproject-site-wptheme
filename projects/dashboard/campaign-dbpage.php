<?php

function print_campaign_page()
{
    global $campaign_id, $campaign, $post_campaign,
           $WDGAuthor, $WDGUser_current,
           $is_admin, $is_author,

            $status, $collecte_or_after, $vote_or_after, $preview_or_after, $validated_or_after ;

    ?>

    <div class="head"><?php _e('Organisation de la campagne', 'yproject'); ?></div>
    <div class="tab-content">
        <?php if ($campaign->google_doc() != ''): ?>
            <h2><?php _e("Planning", 'yproject'); ?></h2>
            <div class="google-doc">
                <?php if (strpos($campaign->google_doc(), 'spreadsheets') !== FALSE) : ?>
                    <iframe data-src="<?php echo $campaign->google_doc(); ?>/edit?usp=sharing&embedded=true"></iframe>
                <?php else : ?>
                    <iframe data-src="<?php echo $campaign->google_doc(); ?>/pub?embedded=true"></iframe>
                <?php endif; ?>
            </div>
            <br/><br/>
        <?php endif; ?>

        <?php if ($campaign->logbook_google_doc() != ''): ?>
            <h2><?php _e("Journal de bord", 'yproject'); ?></h2>
            <div class="google-doc">
                <?php if (strpos($campaign->logbook_google_doc(), 'spreadsheets') !== FALSE) : ?>
                    <iframe data-src="<?php echo $campaign->logbook_google_doc(); ?>/edit?usp=sharing&embedded=true"></iframe>
                <?php else : ?>
                    <iframe data-src="<?php echo $campaign->logbook_google_doc(); ?>/pub?embedded=true"></iframe>
                <?php endif; ?>
            </div>
            <br/><br/>
        <?php endif; ?>

        <form id="campaign_form" class="db-form" data-action="save_project_campaigntab">
            <ul class="errors">

            </ul>
            <?php
            DashboardUtility::create_field(array(
                "id"=>"new_end_vote_date",
                "type"=>"datetime",
                "label"=>"Date de fin de vote",
                "value"=>new DateTime($campaign->end_vote_date()),
                "editable"=>$is_admin,
                "admin_theme"=>$is_admin,
                "warning"=>true,
                "visible"=>$is_admin || $vote_or_after
            ));

            DashboardUtility::create_field(array(
                "id"=>"new_begin_collecte_date",
                "type"=>"datetime",
                "label"=>"Date de d&eacute;but de collecte",
                "value"=>new DateTime($campaign->begin_collecte_date()),
                "editable"=>false,
                "admin_theme"=>$is_admin,
                "editable"=>$is_admin,
                "warning"=>true,
                "visible"=>$is_admin || $collecte_or_after
            ));

            DashboardUtility::create_field(array(
                "id"=>"new_end_collecte_date",
                "type"=>"datetime",
                "label"=>"Date de fin de collecte",
                "value"=>new DateTime($campaign->end_date()),
                "admin_theme"=>$is_admin,
                "editable"=>$is_admin,
                "warning"=>true,
                "visible"=>$is_admin || $collecte_or_after
            ));

            DashboardUtility::create_field(array(
                "id"				=> "new_planning_gdrive",
                "type"				=> "link",
                "label"				=> "Lien du google drive planning",
				"infobubble"		=> "Copier le lien jusqu'&apos; avant /edit",
                "value"				=> $campaign->google_doc(),
                "editable"			=> $is_admin,
                "admin_theme"		=> $is_admin,
                "placeholder"		=> "https://docs.google.com/document/d/.....",
                "default_display"	=> "Le planning n'a pas encore été défini"
            ));

            DashboardUtility::create_field(array(
                "id"				=> "new_logbook_gdrive",
                "type"				=> "link",
                "label"				=> "Lien du google drive journal de bord",
				"infobubble"		=> "Copier le lien jusqu'&apos; avant /edit",
                "value"				=> $campaign->logbook_google_doc(),
                "editable"			=> $is_admin,
                "admin_theme"		=> $is_admin,
                "placeholder"		=> "https://docs.google.com/document/d/.....",
                "default_display"	=> "Le journal de bord n'a pas encore été défini"
            ));

            DashboardUtility::create_save_button("campaign_form",$is_admin);
            ?>
        </form>
    </div>


    <div class="head"><?php _e('Equipe du projet', 'yproject'); ?></div>
    <div class="tab-content">

        <h2><?php _e('Administrateur du projet', 'yproject'); ?></h2>
        <div style="text-align:center">
            <span><?php echo $WDGAuthor->wp_user->user_firstname . ' ' . $WDGAuthor->wp_user->user_lastname.'</span><br/><span>'.
                bp_core_get_userlink($WDGAuthor->wp_user->ID)?></span>
        </div>

        <h2><?php _e('&Eacute;quipe projet', 'yproject'); ?></h2>
        <?php
        ypcf_debug_log('template-project-dashboard >> ' . $campaign_id);
        $project_api_id = BoppLibHelpers::get_api_project_id($campaign_id);
        if (isset($project_api_id)) $team_member_list = BoppLib::get_project_members_by_role($project_api_id, BoppLibHelpers::$project_team_member_role['slug']);

            ?>
            <ul id="team-list">
                <?php if (count($team_member_list) > 0):
                    foreach ($team_member_list as $team_member):
                        $team_member_wp = get_userdata($team_member->wp_user_id)?>
                        <li>
                            <?php echo $team_member_wp->user_firstname . ' ' . $team_member_wp->user_lastname . ' (' . bp_core_get_userlink($team_member_wp->ID).')'; ?>
                            <a class="project-manage-team button" data-action="yproject-remove-member" data-user="<?php echo $team_member->wp_user_id; ?>"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
                        </li>
                    <?php endforeach;
                else:
                    _e('Aucun membre dans l&apos;&eacute;quipe pour l&apos;instant.', 'yproject');
                endif;?>
            </ul>

        <div style="text-align:center">
            <input type="text" id="new_team_member_string" style="width: 295px;" placeholder="<?php _e('E-mail ou identifiant d&apos;un utilisateur WEDOGOOD.co', 'ypoject'); ?>" />
            <a class="project-manage-team button" data-action="yproject-add-member"><i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;<?php _e('Ajouter', 'ypoject'); ?></a>
            <?php DashboardUtility::get_infobutton("Les membres de l'&eacute;quipe peuvent acc&eacute;der au tableau de bord, modifier les param&egrave;tres et la page de projet",true); ?>
        </div>
    </div>

    <?php
}