<?php require_once("common.php"); ?>
<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php 

    // Articles du blog WDG
    $category = get_the_category(); 
    if ($category[0]->slug == 'wedogood') :
	$page_blog = get_page_by_path('blog');

?>
    <div id="content">
	<div class="padder">
	    <?php printMiscPagesTop("Blog"); ?>
	    
	    <div id="post_bottom_bg">
		<div id="post_bottom_content" class="center">
		    <div class="left post_bottom_desc">

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			    <div class="post-content">
				<div>&lt;&lt; <a href="<?php echo get_permalink($page_blog->ID); ?>"><?php _e('Blog', 'yproject'); ?></a></div>
				<h2 class="posttitle"><?php the_title(); ?></h2>
				<p class="date"><?php echo get_the_date(); ?></p>
				<?php the_content(); ?>
				<?php comments_template(); ?>
			    </div>
			</div>

		    </div>
		</div>
		<?php printCommunityMenu(); ?>
		<div style="clear: both"></div>
	    </div>

	</div><!-- .padder -->
    </div><!-- #content -->

<?php

    //Autres articles
    else:
?>
	<div id="content">
		<div class="padder">

			<?php do_action( 'bp_before_blog_single_post' ); ?>

			<div class="page" id="blog-single" role="main">

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				    <div class="post-content">
					<div class="entry center">
					    <h1><?php the_title(); ?></h1>
					    <?php the_content(); ?>
					</div>
				    </div>
				</div>

			</div>

			<?php do_action( 'bp_after_blog_single_post' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->
<?php
    endif;
?>


<?php endwhile; else: ?>
	<div id="content">
	    <div class="padder center">
		<p><?php _e( 'Sorry, no posts matched your criteria.', 'buddypress' ); ?></p>
	    </div><!-- .padder -->
	</div><!-- #content -->
<?php endif; ?>
	
<?php get_footer(); ?>