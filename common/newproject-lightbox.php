<?php
locate_template( array("projects/dashboard/dashboardutility.php"), true );
$WDGUser_current = WDGUser::current();

global $WDG_cache_plugin;
if ( $WDG_cache_plugin == null ) {
	$WDG_cache_plugin = new WDG_Cache_Plugin();
}
$cache_project_list = $WDG_cache_plugin->get_cache( 'WDGUser::get_projects_by_id(' .$WDGUser_current->get_wpref(). ', TRUE)', 1 );
if ( $cache_project_list !== FALSE ) {
	$project_list = json_decode( $cache_project_list );
} else {
	$project_list = WDGUser::get_projects_by_id( $WDGUser_current->get_wpref(), TRUE );
	$WDG_cache_plugin->set_cache( 'WDGUser::get_projects_by_id(' .$WDGUser_current->get_wpref(). ', TRUE)', json_encode( $project_list ), 60*10, 1 ); //MAJ 10min
}

$organizations_list = $WDGUser_current->get_organizations_list();

if ($organizations_list) {
	foreach ($organizations_list as $organization_item) {
		$organizations_options_id[] = $organization_item->wpref;
		$organizations_options_names[] = $organization_item->name;
	}
	array_push($organizations_options_id, "new_orga");
	array_push($organizations_options_names, "Une nouvelle organisation...");
}
?>

<?php if (!is_user_logged_in()): ?>
    <p class="align-center"><?php _e('Connectez-vous afin de cr&eacute;er un projet.', 'yproject'); ?></p>
	
    <?php locate_template( array("common/connexion-lightbox.php"), true, false ); ?>
	
	<?php global $signup_errors; $has_register_errors = ($signup_errors->get_error_message() != ""); ?>
	<div id="newproject-register-user" class="<?php if (!$has_register_errors): ?>hidden<?php endif; ?>">
    <?php locate_template( array("common/register-lightbox.php"), true, false ); ?>
	</div>
<?php endif; ?>

<form id="newproject_form" class="db-form v3 full form-register" method="post" action="<?php echo admin_url( 'admin-post.php?action=create_project_form'); ?>" <?php if (!is_user_logged_in()) { ?>style="display: none;"<?php } ?>>
    <?php
	$input_error = filter_input( INPUT_GET, 'error' );
	$errors_submit_new = $_SESSION[ 'newproject-errors-submit' ];
	$errors_create_orga = $_SESSION[ 'newproject-errors-orga' ];
	?>
	<?php if ( !empty( $errors_submit_new ) ): ?>
		<div class="errors">
		<?php foreach ( $errors_submit_new as $error ): ?>
			<?php echo $error; ?>
		<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php if ( !empty( $errors_create_orga ) ): ?>
		<div class="errors">
		<?php foreach ( $errors_create_orga as $error ): ?>
			<?php echo $error; ?>
		<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php if ( !empty( $input_error ) ): ?>
		<div class="errors">
		<?php if ( $input_error == 'creation' ): ?>
			Erreur de création, merci de nous contacter.
		<?php elseif ( $input_error == 'field_empty' ): ?>
			Certains champs n'ont pas été remplis. Chaque champ est obligatoire.
		<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( !empty( $project_list ) ): ?>
		<?php
		$page_dashboard = WDG_Redirect_Engine::override_get_page_url( 'tableau-de-bord' );
		$project_string = '';
		?>
		<?php foreach ( $project_list as $project_id ): ?>
			<?php if ( !empty( $project_id ) ): ?>
				<?php $project_campaign = new ATCF_Campaign( $project_id ); ?>
				<?php if ( isset( $project_campaign ) && $project_campaign->get_name() != '' ): ?>
					<?php
					$campaign_dashboard_url = $page_dashboard. '?campaign_id=' .$project_id;
					$project_string .= '- <a href="' . $campaign_dashboard_url . '">' . $project_campaign->get_name() . '</a><br>';
					?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>

		<?php if ( !empty( $project_string ) ): ?>
			<div class="align-justify">
				<?php _e( "Vous avez d&eacute;j&agrave; cr&eacute;&eacute; un (ou des) projet(s) sur la plateforme pr&eacute;c&eacute;demment :", 'yproject' ); ?><br>
				<?php echo $project_string; ?><br><br>
				<?php _e( "Si vous souhaitez tout de m&ecirc;me cr&eacute;er un nouveau projet, veuillez remplir le formulaire ci-dessous.", 'yproject' ); ?><br><br>
			</div>
		<?php endif; ?>
	<?php endif; ?>
		
	<?php
    DashboardUtility::create_field(array(
        "id"		=> "firstname",
        "type"		=> "text",
        "label"		=> "Mon prénom *",
        "value"		=> $WDGUser_current->wp_user->user_firstname,
    ));

    DashboardUtility::create_field(array(
        "id"		=> "lastname",
        "type"		=> "text",
        "label"		=> "Mon nom *",
        "value"		=> $WDGUser_current->wp_user->user_lastname,
    ));

    DashboardUtility::create_field(array(
        "id"		=> "phone",
        "type"		=> "text",
        "label"		=> "Mon t&eacute;l&eacute;phone mobile *",
        "value"		=> $WDGUser_current->wp_user->get('user_mobile_phone'),
        "infobubble"=> "Ce num&eacute;ro sera celui utilis&eacute; pour vous contacter &agrave; propos de votre projet",
    ));

    echo '<hr class="form-separator"/>';

	if (!empty($organizations_list)) {
		DashboardUtility::create_field(array(
			"id"			=> "company-name",
			"type"			=> "select",
			"label"			=> "Mon entreprise *",
			"value"			=> $organizations_list,
			"options_id"	=> array_values($organizations_options_id),
			"options_names"	=> array_values($organizations_options_names),
		));

		DashboardUtility::create_field(array(
			"id"		=> "new-company-name",
			"type"		=> "text",
			"label"		=> "Nom de mon entreprise *",
			"value"		=> "",
		));
	} else {
		DashboardUtility::create_field(array(
			"id"		=> "company-name",
			"type"		=> "text",
			"label"		=> "Nom de mon entreprise *",
			"value"		=> "",
		));
	}

	DashboardUtility::create_field(array(
		"id"		=> "email-organization",
		"type"		=> "text",
		"label"		=> "E-mail de contact *",
		"value"		=> "",
		"description"	=> __( "Cette adresse doit &ecirc;tre diff&eacute;rente de celle de votre compte personnel, utilisez une adresse telle que contact@votre-entreprise.fr", 'yproject' ),
		"infobubble"	=> __( "Cet e-mail ne doit pas &ecirc;tre utilis&eacute; par un compte existant.", 'yproject' )
	));

    DashboardUtility::create_field(array(
        "id"		=> "project-name",
        "type"		=> "text",
        "label"		=> "Nom du projet *",
        "value"		=> "",
    ));

    DashboardUtility::create_field(array(
        "id"		=> "project-description",
        "type"		=> "textarea",
        "label"		=> "Description du projet *",
        "value"		=> "",
    ));
	?>

	<div class="align-left">
	<label for="project-terms"><input type="checkbox" id="project-terms" name="project-terms" /><span></span> Je valide les <a href="<?php echo WDG_Redirect_Engine::override_get_page_url('a-propos/cgu/conditions-particulieres'); ?>" target="_blank">conditions particuli&egrave;res</a></label><br />
	</div>
	<br /><br />
	
	<button class="button save red" type="submit"><?php _e( "Valider", 'yproject' ); ?></button>

</form>