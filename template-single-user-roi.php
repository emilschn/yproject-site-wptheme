<?php 
/**
 * Template Name: Single User ROI
 *
 */
?>

<?php get_header(); ?>

<div id="content">
    
	<div class="padder padder-top">

		<div class="page">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
				<?php _e( "Le d&eacute;tail de vos royalties se trouve &agrave; pr&eacute;sent sur votre compte personnel.", 'yproject' ); ?><br><br>
				<a href="<?php echo home_url( '/mon-compte/' ); ?>" class="button red"><?php _e( "Acc&eacute;der &agrave; mon compte", 'yproject' ); ?></a>

			<?php endwhile; endif; ?>

		</div>

	</div><!-- .padder -->
</div><!-- #content -->

<?php get_footer();