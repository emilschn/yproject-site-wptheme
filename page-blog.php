<?php require_once("common.php"); ?>
<?php get_header(); ?>

<div id="content">
    <div class="padder">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	    <?php printMiscPagesTop("Blog"); ?>
	    <div id="post_bottom_bg">
		<div id="post_bottom_content" class="center">
		    <div class="left post_bottom_desc">
			<?php $page_community = get_page_by_path('communaute'); // Menu Communauté ?>
			&lt;&lt; <a href="<?php echo get_permalink($page_community->ID); ?>"><?php echo __('Communaute', 'yproject'); ?></a>
			
			<?php 
			query_posts( array(
			    'post_status' => 'publish',
			    'category_name' => 'wedogood',
			    'orderby' => 'post_date',
			    'order' => 'desc'
			) );
			
			if ( have_posts() ) : ?>

				<?php bp_dtheme_content_nav( 'nav-above' ); ?>

				<?php while (have_posts()) : the_post(); ?>

					<?php do_action( 'bp_before_blog_post' ); ?>

					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<div class="post-content">
							<?php /* <h2 class="posttitle"><?php the_title(); ?></h2> */ ?>
							<h2 class="posttitle"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'buddypress' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
							
							<p class="date"><?php echo get_the_date(); ?></p>

							<div class="entry">
							    <?php /* the_content( __( 'Read the rest of this entry &rarr;', 'buddypress' ) ); */ ?>
							    <?php the_excerpt(); ?>
							    <br />
							    <a href="<?php the_permalink(); ?>">Lire la suite...</a>
							</div>
						</div>

					</div>

					<?php do_action( 'bp_after_blog_post' ); ?>

				<?php endwhile; ?>

				<?php bp_dtheme_content_nav( 'nav-below' ); ?>

			<?php else : ?>
			    <div>Retrouvez bient&ocirc;t les actualit&eacute;s de l&apos;&eacute;quipe !</div>

			<?php endif; 
			
			wp_reset_query();
			?>
			
		    </div>

		    <?php printCommunityMenu(); ?>
		    <div style="clear: both"></div>
		</div>
	    </div>

	<?php endwhile; endif; ?>

    </div><!-- .padder -->
</div><!-- #content -->
	
<?php get_footer(); ?>