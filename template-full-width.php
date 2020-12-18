<?php
/**
 * Template Name: Template Pleine Largeur
 *
 */
?>

<?php get_header( ATCF_CrowdFunding::get_platform_context() ); ?>

<?php date_default_timezone_set("Europe/Paris"); ?>

<div id="content">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<?php the_content(); ?>

	<?php endwhile; endif; ?>

</div><!-- #content -->

<?php get_footer( ATCF_CrowdFunding::get_platform_context() );
