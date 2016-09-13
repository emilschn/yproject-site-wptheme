<?php
locate_template( array("projects/dashboard/dashboardutility.php"), true );
$WDGUser_current = WDGUser::current();
?>

<?php if (!is_user_logged_in()): ?>
    <p class="align-center"><?php _e('Afin de cr&eacute;er un projet, vous devez &ecirc;tre inscrit et connect&eacute;.', 'yproject'); ?></p>
    <?php locate_template('common/connexion-lightbox.php', true); ?>
	
<?php else: ?>

<form id="newproject_form" class="db-form" method="post" action="<?php echo admin_url( 'admin-post.php?action=create_project_form'); ?>">
    <h2 style="text-align: center;"><?php _e('D&eacute;pot de dossier','yproject');?></h2><?php

    DashboardUtility::create_field(array(
        "id"		=> "firstname",
        "type"		=> "text",
        "label"		=> "Mon prénom",
        "value"		=> $WDGUser_current->wp_user->user_firstname,
        "left_icon"	=> "user",
    ));

    DashboardUtility::create_field(array(
        "id"		=> "lastname",
        "type"		=> "text",
        "label"		=> "Mon nom",
        "value"		=> $WDGUser_current->wp_user->user_lastname,
        "left_icon"	=> "user",
    ));

    DashboardUtility::create_field(array(
        "id"		=> "email",
        "type"		=> "text",
        "label"		=> "Mon e-mail",
        "value"		=> $WDGUser_current->wp_user->get('user_email'),
        "left_icon"	=> "at",
    ));

    DashboardUtility::create_field(array(
        "id"		=> "phone",
        "type"		=> "text",
        "label"		=> "Mon t&eacute;l&eacute;phone mobile",
        "value"		=> $WDGUser_current->wp_user->get('user_mobile_phone'),
        "infobubble"=> "Ce num&eacute;ro sera celui utilis&eacute; pour vous contacter &agrave; propos de votre projet",
        "left_icon"	=> "mobile-phone",
    ));

    echo '<hr class="form-separator"/>';

    DashboardUtility::create_field(array(
        "id"		=> "company-name",
        "type"		=> "text",
        "label"		=> "Nom de mon entreprise",
        "value"		=> "",
        "left_icon"	=> "building",
    ));

    DashboardUtility::create_field(array(
        "id"		=> "project-name",
        "type"		=> "text",
        "label"		=> "Nom du projet",
        "value"		=> "",
        "left_icon"	=> "lightbulb-o",
    ));

    DashboardUtility::create_field(array(
        "id"		=> "project-description",
        "type"		=> "textarea",
        "label"		=> "Description du projet",
        "value"		=> "",
    ));

    DashboardUtility::create_field(array(
        "id"		=> "project-WDGnotoriety",
        "type"		=> "textarea",
        "label"		=> "Comment avez-vous <br/>connu WE DO GOOD ?",
        "value"		=> "",
    ));

    DashboardUtility::create_save_button('newProject', true, "Enregistrer", "Enregistrement en cours");

    ?></form>
<?php endif;