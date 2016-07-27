<?php

function print_campaign_page()
{
    global $campaign_id, $campaign, $post_campaign,
           $WDGAuthor, $WDGUser_current;
    ?>
    <div class="head">Organisation de la campagne</div>
    <div class="tab-content" style="text-align:center">
        <h2><?php _e('Administrateur du projet', 'yproject'); ?></h2>
        <span><?php echo $WDGAuthor->wp_user->user_firstname . ' ' . $WDGAuthor->wp_user->user_lastname.'</span><br/><span>'.
            bp_core_get_userlink($WDGAuthor->wp_user->ID)?></span>

        <h2><?php _e('&Eacute;quipe projet', 'yproject'); ?></h2>
        <?php
        ypcf_debug_log('template-project-dashboard >> ' . $campaign_id);
        $project_api_id = BoppLibHelpers::get_api_project_id($campaign_id);
        if (isset($project_api_id)) $team_member_list = BoppLib::get_project_members_by_role($project_api_id, BoppLibHelpers::$project_team_member_role['slug']);
        if (count($team_member_list) > 0):
            ?>
            <ul id="team-list">
                <?php foreach ($team_member_list as $team_member):
                    $team_member_wp = get_userdata($team_member->wp_user_id)?>
                    <li>
                        <?php echo $team_member_wp->user_firstname . ' ' . $team_member_wp->user_lastname . ' (' . bp_core_get_userlink($team_member_wp->ID).')'; ?>
                        <a class="project-manage-team button" data-action="yproject-remove-member" data-user="<?php echo $team_member->wp_user_id; ?>">x</a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php
        else:
            _e('Aucun membre dans l&apos;&eacute;quipe pour l&apos;instant.', 'yproject');
        endif;
        ?>
        <input type="text" id="new_team_member_string" style="width: 295px;" placeholder="<?php _e('E-mail ou identifiant d&apos;un utilisateur WEDOGOOD.co', 'ypoject'); ?>" />
        <a class="project-manage-team button" data-action="yproject-add-member">Ajouter</a>
        <?php echo DashboardUtility::get_infobutton("Les membres de l'&eacute;quipe peuvent acc&eacute;der au tableau de bord et modifier les param&egrave;tres et la page de projet"); ?>
    </div>
<?php
}