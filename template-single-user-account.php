<?php 
/**
 * Template Name: Single User Account
 *
 */
?>

<?php get_header(); ?>

<div id="content">
    
	<div class="padder">

		<div class="page">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
				<?php if (!is_user_logged_in()): ?>
			
					<div class="center margin-height">
						<?php _e("Vous devez vous identifier pour acc&eacute;der &agrave; cette page.", 'yproject'); ?>
					</div>
			
				<?php else: ?>
			
					<?php locate_template( array("members/single/header-short.php"), true ); ?>

					<div id="page-single-user-roi" class="center margin-height">

						Coucou !

					</div>
			
				<?php endif; ?>

			<?php endwhile; endif; ?>

		</div>

	</div><!-- .padder -->
</div><!-- #content -->

<?php get_footer();