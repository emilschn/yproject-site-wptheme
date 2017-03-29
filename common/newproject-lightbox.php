<?php
locate_template( array("projects/dashboard/dashboardutility.php"), true );
$WDGUser_current = WDGUser::current();
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
	
	<?php global $signup_errors; $has_register_errors = ($signup_errors->get_error_message() != ""); ?>
    <?php locate_template( array("common/connexion-lightbox.php"), true, false ); ?>
	
	<div id="newproject-register-user" class="<?php if (!$has_register_errors): ?>hidden<?php endif; ?>">
    <?php locate_template( array("common/register-lightbox.php"), true, false ); ?>
	</div>
<?php endif; ?>

<form id="newproject_form" class="db-form form-register" method="post" action="<?php echo admin_url( 'admin-post.php?action=create_project_form'); ?>" <?php if (!is_user_logged_in()){ ?>style="display: none;"<?php } ?>>
    <h2 style="text-align: center;"><?php _e('Lancement de campagne','yproject');?></h2>
		
	<?php
    DashboardUtility::create_field(array(
        "id"		=> "firstname",
        "type"		=> "text",
        "label"		=> "Mon prénom",
        "value"		=> $WDGUser_current->wp_user->user_firstname,
    ));

    DashboardUtility::create_field(array(
        "id"		=> "lastname",
        "type"		=> "text",
        "label"		=> "Mon nom",
        "value"		=> $WDGUser_current->wp_user->user_lastname,
    ));

    DashboardUtility::create_field(array(
        "id"		=> "email",
        "type"		=> "text",
        "label"		=> "Mon e-mail",
        "value"		=> $WDGUser_current->wp_user->get('user_email'),
    ));

    DashboardUtility::create_field(array(
        "id"		=> "phone",
        "type"		=> "text",
        "label"		=> "Mon t&eacute;l&eacute;phone mobile",
        "value"		=> $WDGUser_current->wp_user->get('user_mobile_phone'),
        "infobubble"=> "Ce num&eacute;ro sera celui utilis&eacute; pour vous contacter &agrave; propos de votre projet",
    ));

    echo '<hr class="form-separator"/>';

	if(!empty($organizations_list)){
		DashboardUtility::create_field(array(
			"id"			=> "company-name",
			"type"			=> "select",
			"label"			=> "Nom de mon entreprise",
			"value"			=> $organizations_list,
			"options_id"	=> array_values($organizations_options_id),
			"options_names"	=> array_values($organizations_options_names),
		));
	}
	else{
		DashboardUtility::create_field(array(
			"id"		=> "company-name",
			"type"		=> "text",
			"label"		=> "Nom de mon entreprise",
			"value"		=> "",
		));
	}

	DashboardUtility::create_field(array(
		"id"		=> "new-company-name",
		"type"		=> "text",
		"label"		=> "Nom de l'organisation",
		"value"		=> "",
	));

    DashboardUtility::create_field(array(
        "id"		=> "project-name",
        "type"		=> "text",
        "label"		=> "Nom du projet",
        "value"		=> "",
    ));

    DashboardUtility::create_field(array(
        "id"		=> "project-description",
        "type"		=> "textarea",
        "label"		=> "Description du projet",
        "value"		=> "",
    ));

    DashboardUtility::create_field(array(
        "id"		=> "project-terms",
        "type"		=> "check",
        "label"		=> 'Je valide les <a href="'.home_url('/conditions-particulieres').'" target="_blank">conditions particuli&egrave;res</a>',
        "value"		=> "",
    ));


    DashboardUtility::create_save_button('newProject', true, "Enregistrer", "Enregistrement en cours");
	?>
	
</form>