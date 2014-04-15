<?php require_once("common.php"); ?>
<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php 

    // Articles du blog WDG
    $category = get_the_category(); 
    if ($category[0]->slug == 'wedogood' || $category[0]->slug == 'revue-de-presse') :
	$page_blog = ($category[0]->slug == 'wedogood') ? get_page_by_path('blog') : get_page_by_path('espace-presse');

?>
    <div id="content">
	<div class="padder">
	    <?php 
	    $page_title = ($category[0]->slug == 'wedogood') ? "Blog" : "Espace presse";
	    printMiscPagesTop($page_title); 
	    ?>
	    
	    <div id="post_bottom_bg">
		<div id="post_bottom_content" class="center">
		    <div class="left post_bottom_desc">

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			    <div class="post-content">
				<div>&lt;&lt; <a href="<?php echo get_permalink($page_blog->ID); ?>"><?php _e($page_title, 'yproject'); ?></a></div>
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

<?php 
    date_default_timezone_set("Europe/Paris");
    require_once("common.php");
    
    $this_category = get_the_category();
    $this_category = $this_category[0];
    $this_category_name = $this_category->name;
    $name_exploted = explode('cat', $this_category_name);
    $campaign_post = get_post($name_exploted[1]);
    $campaign = atcf_get_campaign( $campaign_post );
    $category_link = (!empty($this_category)) ? get_category_link($this_category->cat_ID) : '';
    global $post;
    $post = $campaign_post;
?>
	<div id="content">
		<div class="padder">

			<?php do_action( 'bp_before_blog_single_post' ); ?>

			<div class="page" id="blog-archives" role="main">
				<?php require_once('projects/single-admin-bar.php'); ?>
				<?php require_once('projects/single-header.php'); ?>

				<div id="post_bottom_bg">
					<div id="post_bottom_content" class="center">
						<div class="left post_bottom_desc">
							<a href="<?php echo esc_url($category_link); ?>">&lt;&lt; Revenir &agrave; la liste des actualit&eacute;s</a>

							<?php wp_reset_query(); ?>

							<div class="post-content">
							    <h2 class="posttitle"><?php the_title(); ?></h2>
							    <p class="date"><?php echo get_the_date(); ?></p>
							    <?php the_content(); ?>
							    <?php comments_template(); ?>
							</div>
						</div>

						<div class="left post_bottom_infos">
							<?php $post = $campaign_post; ?>
							<?php require_once('projects/single-sidebar.php'); ?>
						</div>

						<div style="clear: both"></div>
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