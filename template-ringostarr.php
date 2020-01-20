<?php
/**
 * Template Name: Ringo Starr
 *
 */

    // on récupère le composant Vue
    $WDG_Vue_Components = WDG_Vue_Components::instance();
    $WDG_Vue_Components->enqueue_component( WDG_Vue_Components::$component_launch_project );

    // on récupère les données envoyées en GET
    
    $input_first_name = filter_input( INPUT_GET, 'firstname' );
    
    echo $input_first_name;

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
    <div id="app" data-test="hehehe"></div> 
	
</div><!-- #content -->

<?php get_footer( ATCF_CrowdFunding::get_platform_context() );