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

    // on récupère la liste des projets de cet utilisateur
    global $WDG_cache_plugin;
    if ( $WDG_cache_plugin == null ) {
      $WDG_cache_plugin = new WDG_Cache_Plugin();
    }
    $cache_project_list = $WDG_cache_plugin->get_cache( 'WDGUser::get_projects_by_id(' .$user->get_wpref(). ', TRUE)', 1 );
    if ( $cache_project_list !== FALSE ) {
      $project_list = json_decode( $cache_project_list );
      
    } else {
      $project_list = WDGUser::get_projects_by_id( $user->get_wpref(), TRUE );
      $WDG_cache_plugin->set_cache( 'WDGUser::get_projects_by_id(' .$user->get_wpref(). ', TRUE)', json_encode( $project_list ), 60*10, 1 ); //MAJ 10min
    }
    
    if ( !empty( $project_list ) ){
      $existingprojects = array();
      $existingprojects["projects"] = array();
      $page_dashboard = home_url( '/tableau-de-bord/' );
      $project_string = '';
      foreach ( $project_list as $project_id ) {
        if ( !empty( $project_id ) ){
          $project_campaign = new ATCF_Campaign( $project_id );
          if ( isset( $project_campaign ) && $project_campaign->get_name() != '' ){
            $campaign_dashboard_url = $page_dashboard. '?campaign_id=' .$project_id;
            $project = array('name' => $project_campaign->get_name() , 'url' => $campaign_dashboard_url );
            $existingprojects["projects"][] = $project;

          }
        }
      }
      $existingprojects = json_encode($existingprojects, JSON_HEX_APOS );


    }

    // on récupère la liste des organisations de cet utilisateur
    $organizations_list = $user->get_organizations_list();    
    $user_organisations = array();
    $user_organisations["organisations"] = array();
    if ($organizations_list) {
      foreach ($organizations_list as $organization_item) {
        $orga = array('Id' => $organization_item->wpref , 'Text' => $organization_item->name );
        $user_organisations["organisations"][] = $orga;
      }
      $user_organisations["organisations"][] = array('Id' => "new_orga" , 'Text' => "Une nouvelle organisation..." );
      $user_organisations = json_encode($user_organisations, JSON_HEX_APOS );

    }
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
    data-ajaxurl="http://wedogood.local/wp-admin/admin-ajax.php"
    data-firstname="<?php echo $user->get_firstname(); ?>" 
    data-lastname="<?php echo $user->get_lastname(); ?>"
    data-phonenumber="<?php echo $user->get_phone_number(); ?>"
    data-organame="<?php echo $metadata->orga_name; ?>"
    data-email="<?php echo $email; ?>"
    data-projectname=""
    data-projectdescription=""
    data-existingprojects='<?php echo $existingprojects; ?>'
    data-existingorganisations='<?php echo $user_organisations; ?>'
    data-urlcgu='<?php echo home_url('/a-propos/cgu/conditions-particulieres/'); ?>'
    
    ></div> 

	
</div><!-- #content -->

<?php get_footer( ATCF_CrowdFunding::get_platform_context() );