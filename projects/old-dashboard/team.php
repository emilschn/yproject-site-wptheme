<?php function print_block_team() { 
    global $campaign,
            $author_data;
            ?>

<div id="block-team" class="block" data-campaign="<?php echo $campaign->ID?>">
    <div class="head">&Eacute;quipe</div>
    <div class="body" style="text-align:center">
        <h2><?php _e('Administrateur du projet', 'yproject'); ?></h2>
        <?php echo $author_data->first_name . ' ' . $author_data->last_name; ?>

        <h2><?php _e('&Eacute;quipe projet', 'yproject'); ?></h2>
        <?php 
		$project_api_id = $campaign->get_api_id();
		if (isset($project_api_id)) $team_member_list = WDGWPREST_Entity_Project::get_users_by_role( $project_api_id, WDGWPREST_Entity_Project::$link_user_type_team );
		if (count($team_member_list) > 0):
        ?>
                <ul id="team-list">
        <?php foreach ($team_member_list as $team_member): ?>
                    <li>
                        <?php echo $team_member->user_name . ' ' . $team_member->user_surname; ?>
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
    </div>
</div>
    
<?php } ?>