<?php
/**
 * Template Name: Template Vide
 *
 */
?>

<?php get_header( 'empty' ); ?>

<?php date_default_timezone_set("Europe/Paris"); ?>

<div id="content">
	
	<div class="padder">
		
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
			<?php the_content(); ?>
		
		<?php endwhile; endif; ?>
		
	</div><!-- .padder -->
	
</div><!-- #content -->

<?php get_footer( 'empty' );
