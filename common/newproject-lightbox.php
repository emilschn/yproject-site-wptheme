<?php
locate_template( array("projects/dashboard/dashboardutility.php"), true );
$WDGUser_current = WDGUser::current();
$organizations_list = $WDGUser_current->get_organizations_list();
$first_organization_email = '';

if ($organizations_list) {
	foreach ($organizations_list as $organization_item) {
		$organizations_options_id[] = $organization_item->wpref;
		$organizations_options_names[] = $organization_item->name;
		if ( empty( $first_organization_email ) ) {
			$first_organization = new WDGOrganization( $organization_item->wpref );
			$first_organization_email = $first_organization->get_creator()->user_email;
		}
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

<form id="newproject_form" class="db-form v3 full form-register" method="post" action="<?php echo admin_url( 'admin-post.php?action=create_project_form'); ?>" <?php if (!is_user_logged_in()){ ?>style="display: none;"<?php } ?>>
    <h2 style="text-align: center;"><?php _e('Lancement de campagne','yproject');?></h2>
	
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
			"label"			=> "Mon entreprise",
			"value"			=> $organizations_list,
			"options_id"	=> array_values($organizations_options_id),
			"options_names"	=> array_values($organizations_options_names),
		));

		DashboardUtility::create_field(array(
			"id"		=> "new-company-name",
			"type"		=> "text",
			"label"		=> "Nom de mon entreprise",
			"value"		=> "",
		));
		
	} else {
		DashboardUtility::create_field(array(
			"id"		=> "company-name",
			"type"		=> "text",
			"label"		=> "Nom de mon entreprise",
			"value"		=> "",
		));
	}

    DashboardUtility::create_field(array(
        "id"		=> "email-organization",
        "type"		=> "text",
        "label"		=> "E-mail de contact",
        "value"		=> $first_organization_email,
        "infobubble"=> __( "Cet e-mail ne doit pas &ecirc;tre utilis&eacute; par un compte existant.", 'yproject' )
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
	?>

	<div class="align-left">
	<label for="project-terms"><input type="checkbox" id="project-terms" name="project-terms" /><span></span> Je valide les <a href="'.home_url('/conditions-particulieres').'" target="_blank">conditions particuli&egrave;res</a></label><br />
	</div>
	<br /><br />
	
	<button class="button save red" type="submit"><?php _e( "Valider", 'yproject' ); ?></button>

</form>