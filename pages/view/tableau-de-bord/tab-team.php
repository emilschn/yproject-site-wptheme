<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>
<h2><?php _e( "Equipe du projet", 'yproject' ); ?></h2>

<div class="db-form v3 full center bg-white">
    <div class="tab-content align-left">

        <h3><?php _e('Administrateur du projet', 'yproject'); ?></h3>
        <div class="align-center">
            <span><?php echo $page_controler->get_campaign_author()->wp_user->user_firstname . ' ' . $page_controler->get_campaign_author()->wp_user->user_lastname; ?></span>
        </div>

        <h3><?php _e('&Eacute;quipe projet', 'yproject'); ?></h3>
        <?php $team_member_list = WDGWPREST_Entity_Project::get_users_by_role( $page_controler->get_campaign()->get_api_id(), WDGWPREST_Entity_Project::$link_user_type_team ); ?>
        <?php if (count($team_member_list) > 0): ?>
			<ul id="team-list">
                <?php foreach ($team_member_list as $team_member):
                    $team_member_wp = get_userdata($team_member->wpref);
					$team_member_name = ($team_member_wp->user_firstname != "") ? $team_member_wp->user_firstname . ' ' . $team_member_wp->user_lastname : $team_member_wp->user_login;
					?>
					<li>
						<?php echo $team_member_name; ?>
						<?php if ($team_member->notifications == '1'): ?>
							<a class="project-manage-notifications button red" title="Désactiver les notifications" data-action="yproject-remove-notification" data-user="<?php echo $team_member->wpref; ?>"><i class="fa fa-bell fa-fw" aria-hidden="true"></i></a>
						<?php else: ?>
							<a class="project-manage-notifications button disabled" title="Activer les notifications" data-action="yproject-add-notification" data-user="<?php echo $team_member->wpref; ?>"><i class="fa fa-bell fa-fw" aria-hidden="true"></i></a>
						<?php endif; ?>
						<a class="project-manage-team button red" title="Supprimer de l'équipe" data-action="yproject-remove-member" data-user="<?php echo $team_member->wpref; ?>"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>
					</li>
                <?php endforeach; ?>
			</ul>
		
		<?php else: ?>
			<ul id="team-list"></ul>
			<div id="team-list-empty" class="align-center">
				<?php _e('Aucun membre dans l&apos;&eacute;quipe pour l&apos;instant', 'yproject'); ?>
			</div>
		<?php endif; ?>

		<hr>
        <h3><?php _e( "Ajouter un utilisateur", 'yproject' ); ?></h3>
		<div class="field">
			<label for="new_team_member_string"><?php _e( "E-mail d&apos;un utilisateur", 'yproject' ); ?></label>
			<div class="field-description">
				<?php _e( "Les membres de l'&eacute;quipe peuvent acc&eacute;der au tableau de bord, modifier les param&egrave;tres et la page de projet.", 'yproject' ); ?>
				<?php _e( "Seul le porteur de projet peut &eacute;diter l'onglet Organisation.", 'yproject' ); ?><br>
				<?php _e( "Attention : pour ajouter un membre, il faut que celui-ci ait d&eacute;j&agrave; un compte sur la plateforme.", 'yproject' ); ?>
			</div>
			<div class="field-container">
				<span class="field-value">
					<input type="text" id="new_team_member_string">
				</span>
			</div>
		</div>
		
		<a class="project-manage-team button red align-center" data-action="yproject-add-member"><?php _e('Ajouter', 'ypoject'); ?></a>
    </div>
</div>