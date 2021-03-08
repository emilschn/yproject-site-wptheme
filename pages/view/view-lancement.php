<?php
    $page_controler = WDG_Templates_Engine::instance()->get_controler();

    // on récupère le composant Vue
    $WDG_Vue_Components = WDG_Vue_Components::instance();
    $WDG_Vue_Components->enqueue_component( WDG_Vue_Components::$component_launch_project );

    // on récupère la liste des projets de cet utilisateur
    $existingprojects = $page_controler->get_user_projects();
    // on récupère la liste des organisations de cet utilisateur
    $user_organisations = $page_controler->get_user_organisations();
?>

<div id="content" class="lancement-page-container">
	<div class="padder_more">
		<div class="center_small margin-height">
			<?php if ( !is_user_logged_in() ): ?>
				<p class="align-center"><?php _e('F&eacute;licitations, votre dossier a &eacute;t&eacute; valid&eacute; par WE DO GOOD !', 'yproject'); ?></p>
				<p class="align-center"><?php _e('Attention, la lev&eacute;e de fonds doit &ecirc;tre configur&eacute;e par le repr&eacute;sentant l&eacute;gal de l’entreprise avec son compte personnel, qui pourra ensuite inviter le reste de l’&eacute;quipe.', 'yproject'); ?></p>
				<p class="align-center"><?php _e('Vous devez disposer d’une adresse mail personnelle pour votre compte et d’une seconde adresse mail pour l’entreprise (adresse g&eacute;n&eacute;rique ou d’entreprise).', 'yproject'); ?></p>
				<p class="align-center"><?php _e('Connectez-vous afin de cr&eacute;er un projet.', 'yproject'); ?></p>
				<?php locate_template( array("common/connexion-lightbox.php"), true, false ); ?>
				<?php global $signup_errors; $has_register_errors = ($signup_errors->get_error_message() != ""); ?>
				<div id="newproject-register-user" class="<?php if (!$has_register_errors): ?>hidden<?php endif; ?>">
					<?php locate_template( array("common/register-lightbox.php"), true, false ); ?>
				</div>

			<?php else: ?>
				<!-- le composant Vue récupéré plus tôt sera injecté dans cette div -->
				<div id="app"
				  data-ajaxurl='<?php echo admin_url('admin-ajax.php'); ?>'
				  data-firstname="<?php echo $page_controler->get_user_firstname(); ?>"
				  data-lastname="<?php echo $page_controler->get_user_lastname(); ?>"
				  data-phonenumber="<?php echo $page_controler->get_user_phone(); ?>"
				  data-organame="<?php echo $page_controler->get_organization_name(); ?>"
				  data-orgaemail="<?php echo $page_controler->get_organization_email(); ?>"
				  data-projectname="Projet de <?php echo $page_controler->get_organization_name(); ?>"
				  data-existingprojects='<?php echo $existingprojects; ?>'
				  data-existingorganisations='<?php echo $user_organisations; ?>'
				  data-urlcgu='<?php echo home_url('/a-propos/cgu/'); ?>'
				  ></div> 
			<?php endif; ?>
		</div>
	</div>
</div>