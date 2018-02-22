<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>
<h2><?php _e( "Equipe du projet", 'yproject' ); ?></h2>

<div class="db-form v3 center">
    <div class="tab-content">

        <h3><?php _e('Administrateur du projet', 'yproject'); ?></h3>
        <div style="text-align:center">
            <span><?php echo $page_controler->get_campaign_author()->wp_user->user_firstname . ' ' . $page_controler->get_campaign_author()->wp_user->user_lastname; ?></span>
        </div>

        <h3><?php _e('&Eacute;quipe projet', 'yproject'); ?></h3>
        <?php $team_member_list = WDGWPREST_Entity_Project::get_users_by_role( $page_controler->get_campaign()->get_api_id(), WDGWPREST_Entity_Project::$link_user_type_team ); ?>
        <ul id="team-list">
            <?php if (count($team_member_list) > 0):
                foreach ($team_member_list as $team_member):
                    $team_member_wp = get_userdata($team_member->wpref);
					$team_member_name = ($team_member_wp->user_firstname != "") ? $team_member_wp->user_firstname . ' ' . $team_member_wp->user_lastname : $team_member_wp->user_login;
			?>
                    <li>
                        <?php echo $team_member_name; ?>
                        <a class="project-manage-team button" data-action="yproject-remove-member" data-user="<?php echo $team_member->wpref; ?>"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
                    </li>
                <?php endforeach;
            else:
                _e('Aucun membre dans l&apos;&eacute;quipe pour l&apos;instant.', 'yproject');
            endif;?>
        </ul>

        <div style="text-align:center">
            <input type="text" id="new_team_member_string" style="width: 295px;" placeholder="<?php _e('E-mail d&apos;un utilisateur', 'ypoject'); echo ' ' . ATCF_CrowdFunding::get_platform_name(); ?>" />
            <a class="project-manage-team button" data-action="yproject-add-member"><i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;<?php _e('Ajouter', 'ypoject'); ?></a>
            <?php DashboardUtility::get_infobutton("Les membres de l'&eacute;quipe peuvent acc&eacute;der au tableau de bord, modifier les param&egrave;tres et la page de projet",true); ?>
        </div>
    </div>
</div>