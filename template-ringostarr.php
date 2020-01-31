<?php
/**
 * Template Name: Ringo Starr
 *
 */

    // on récupère le composant Vue
    $WDG_Vue_Components = WDG_Vue_Components::instance();
    $WDG_Vue_Components->enqueue_component( WDG_Vue_Components::$component_launch_project );

    // on récupère le guid envoyé en GET    
    $input_guid = filter_input( INPUT_GET, 'guid' );
    // grâce à ce guid, on récupère les données du brouillon de projet    
    $project_draft_data = WDGWPREST_Entity_Project_Draft::get( $input_guid );
    $id_user = $project_draft_data->id_user;
    $email = $project_draft_data->email;
    $status = $project_draft_data->status;
    $step = $project_draft_data->step;
    $authorization = $project_draft_data->authorization;
    $metadata = json_decode( $project_draft_data->metadata) ;

    $user = new WDGUser( $id_user);
    // TODO : récupérer la liste des projets de cet utilisateur
    // à envoyer sous forme de json dans    data-existingprojects="<?php echo $existingprojects; 
    // data-existingprojects='{"projects":[{"name":"Le roi de la betterave","url":"http://wedogood.local/le-roi-de-la-betterave/"},{"name":"Projet de catatak","url":"http://wedogood.local/projet-de-catatak/"}]}'
    
    $existingprojects = 'test';
?>

<?php get_header( ATCF_CrowdFunding::get_platform_context() ); ?>

<?php date_default_timezone_set("Europe/Paris"); ?>

<div id="content">

	<div class="padder">
		
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
			<?php the_content(); ?>
		
		<?php endwhile; endif; ?>
		
    </div><!-- .padder -->
    
    <!-- le composant Vue récupéré plus tôt sera injecté dans cette div -->
    <div id="app" 
    data-firstname="<?php echo $user->get_firstname(); ?>" 
    data-lastname="<?php echo $user->get_lastname(); ?>"
    data-phonenumber="<?php echo $user->get_phone_number(); ?>"
    data-organame="<?php echo $metadata->orga_name; ?>"
    data-email="<?php echo $email; ?>"
    data-projectname=""
    data-projectdescription=""
    
    ></div> 

	
</div><!-- #content -->

<?php get_footer( ATCF_CrowdFunding::get_platform_context() );