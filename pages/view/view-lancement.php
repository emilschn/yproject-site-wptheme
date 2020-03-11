<?php
    $page_controler = WDG_Templates_Engine::instance()->get_controler();

    // on récupère le composant Vue
    $WDG_Vue_Components = WDG_Vue_Components::instance();
    $WDG_Vue_Components->enqueue_component( WDG_Vue_Components::$component_launch_project );

    $user = WDGUser::current();
    // on récupère la liste des projets de cet utilisateur
    $existingprojects = $page_controler->get_user_projects();
    // on récupère la liste des organisations de cet utilisateur
    $user_organisations = $page_controler->get_user_organisations();
?>

<div id="content" class="lancement-page-container">
	<div class="padder_more">
        <div class="center_small margin-height">
            <?php if (!is_user_logged_in()): ?>
                <p class="align-center"><?php _e('Connectez-vous afin de cr&eacute;er un projet.', 'yproject'); ?></p>            
                <?php locate_template( array("common/connexion-lightbox.php"), true, false ); ?>            
                <?php global $signup_errors; $has_register_errors = ($signup_errors->get_error_message() != ""); ?>
                <div id="newproject-register-user" class="<?php if (!$has_register_errors): ?>hidden<?php endif; ?>">
                    <?php locate_template( array("common/register-lightbox.php"), true, false ); ?>
                </div>
            <?php else: ?>
                <!-- le composant Vue récupéré plus tôt sera injecté dans cette div -->
                <div id="app" 
                data-ajaxurl='<?php echo home_url('/wp-admin/admin-ajax.php'); ?>'
                data-firstname="<?php echo $user->get_firstname(); ?>" 
                data-lastname="<?php echo $user->get_lastname(); ?>"
                data-phonenumber="<?php echo $user->get_phone_number(); ?>"
                data-organame=""
                data-email=""
                data-projectname=""
                data-projectdescription=""
                data-existingprojects='<?php echo $existingprojects; ?>'
                data-existingorganisations='<?php echo $user_organisations; ?>'
                data-urlcgu='<?php echo home_url('/a-propos/cgu/'); ?>'                
                ></div> 
            <?php endif; ?>
        </div>
    </div>
</div>