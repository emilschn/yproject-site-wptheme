<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="content">
		<div class="padder">

			<?php do_action( 'bp_before_blog_single_post' ); ?>

			<div class="page" id="blog-single" role="main">

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				    <div class="post-content">
					<div class="entry center">
					    <div>
						<?php $category = get_the_category(); if ($category[0]->slug == 'wedogood') { $page_blog = get_page_by_path('blog'); ?>&lt;&lt; <a href="<?php echo get_permalink($page_blog->ID); ?>"><?php echo __('Blog', 'yproject'); ?></a><?php } ?>
					    </div>
					    <h1><?php the_title(); ?></h1>
					    <?php the_content(); ?>
					</div>
				    </div>
				</div>

			</div>

			<?php do_action( 'bp_after_blog_single_post' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->


<?php endwhile; else: ?>
	<div id="content">
	    <div class="padder center">
		<p><?php _e( 'Sorry, no posts matched your criteria.', 'buddypress' ); ?></p>
	    </div><!-- .padder -->
	</div><!-- #content -->
<?php endif; ?>
	
<?php get_footer(); ?>