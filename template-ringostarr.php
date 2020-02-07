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
   /* 
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

    if ( !empty( $project_list ) ) {
      $page_dashboard = home_url( '/tableau-de-bord/' );
      $project_string = '';
      foreach ( $project_list as $project_id ){
        if (!empty( $project_id )) {
          $project_campaign = new ATCF_Campaign( $project_id );
          if ( isset( $project_campaign ) && $project_campaign->get_name() != '' ){
            $campaign_dashboard_url = $page_dashboard. '?campaign_id=' .$project_id;
            $project_string .= '- <a href="' . $campaign_dashboard_url . '">' . $project_campaign->get_name() . '</a><br>';

          }
        }
      }

    }
*/



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
    
    <!-- todo ; prévoir la redirection si l'utilisateur n'est pas loggé -->
    <?php if (!is_user_logged_in()): ?>
        <p class="align-center"><?php _e('Connectez-vous afin de cr&eacute;er un projet.', 'yproject'); ?></p>
      
        <?php locate_template( array("common/connexion-lightbox.php"), true, false ); ?>
      
      <?php global $signup_errors; $has_register_errors = ($signup_errors->get_error_message() != ""); ?>
      <div id="newproject-register-user" class="<?php if (!$has_register_errors): ?>hidden<?php endif; ?>">
        <?php locate_template( array("common/register-lightbox.php"), true, false ); ?>
      </div>
    <?php endif; ?>

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