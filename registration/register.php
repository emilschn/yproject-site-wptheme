<?php get_header(); ?>

    <div id="content" class="page-register">
	<div class="padder">
	
	    <?php global $page_register; $page_register = TRUE; ?>
	    <div class="center_small"><?php locate_template( array( 'common/register-lightbox.php' ), true ); ?></div>

	</div><!-- .padder -->
    </div><!-- #content -->

<?php get_footer(); ?>