<?php
//Redirection vers nouveau blog wedogood
global $post;
$post_category_list = get_the_category($post->ID);
$post_category = $post_category_list[0]->slug;
if ($post_category == "wedogood") {
	$post_name = $post->post_name;
	$post_date = get_the_date( 'Y/m/d', $post->ID );
	$new_url = 'http://blog.wedogood.co/' . $post_date . '/' . $post_name;
	wp_redirect($new_url);
	exit();
}
//Blocage pour actualités de projets pas encore publiés
global $campaign_id, $is_campaign, $is_campaign_page;
$this_category = get_the_category();
$this_category = $this_category[0];
$this_category_name = $this_category->name;
$name_exploded = explode('cat', $this_category_name);
if (count($name_exploded) > 1) {
	$campaign_id = $name_exploded[1];
}
if (isset($campaign_id)) {
	$is_campaign = true;
	$is_campaign_page = true;
	$campaign_post = get_post($campaign_id);
	$campaign = atcf_get_campaign($campaign_post);
	if ( ($campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_validated) && !$campaign->current_user_can_edit()) {
		wp_redirect(home_url());
	}
}

?>

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
	    $page_title = ($category[0]->slug == 'wedogood') ? "Actualit&eacute;s" : "Espace presse";
//	    locate_template( array( 'common/basic-header.php' ), true );
	    ?>
	    
	    <div id="post_bottom_bg">
		<div id="post_bottom_content" class="center">
		    <div class="left two-thirds" style="margin-left: 175px;">

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			    <div class="post-content">
				<div><br />&lt;&lt; <a href="<?php echo get_permalink($page_blog->ID); ?>"><?php _e($page_title, 'yproject'); ?></a></div>
				<h2 class="posttitle"><?php the_title(); ?></h2>
				<p class="date"><?php echo get_the_date(); ?></p>
				<?php the_content(); ?>
				<?php if ($category[0]->slug == 'wedogood') comments_template(); ?>
			    </div>
			</div>

		    </div>
		</div>
		<div style="clear: both"></div>
	    </div>

	</div><!-- .padder -->
    </div><!-- #content -->

<?php

    //Autres articles
    else:
?>

<?php 
    global $campaign_id, $post;
    date_default_timezone_set("Europe/Paris");
    $this_category = get_the_category();
    $this_category = $this_category[0];
    $this_category_name = $this_category->name;
    $name_exploded = explode('cat', $this_category_name);
    if (count($name_exploded) > 1) {
	    $campaign_id = $name_exploded[1];
    }
    if (isset($campaign_id)) {
	    $campaign_post = get_post($campaign_id);
	    $campaign = atcf_get_campaign($campaign_post);
    }
	$edit_version = $campaign->edit_version();
	$classes = 'version-' . $edit_version;
    $post = $campaign_post;
?>
	<div id="content" style="margin-top: -15px;" class="<?php echo $classes; ?>">
		<div class="padder">

			<div class="page" id="blog-archives" role="main">
				
				<?php locate_template( array("projects/single/banner.php"), true ); ?>

				<div id="post_bottom_bg">
					<div id="post_bottom_content" class="center margin-height">
						<?php $postlist_link = get_permalink($campaign->ID); ?>
						<a href="<?php echo esc_url($postlist_link); ?>">&lt;&lt; Revenir &agrave; la page du projet</a>
						
						<?php wp_reset_query(); ?>
						
						<div class="post-content">
						    <h2 class="posttitle"><?php the_title(); ?></h2>
						    <p class="date"><?php echo get_the_date(); ?></p>
						    <?php the_content(); ?>
						    <?php comments_template(); ?>
						</div>
					</div>
				</div>
			</div>

		</div><!-- .padder -->
	</div><!-- #content -->
<?php
    endif;
?>


<?php endwhile; else: ?>
	<div id="content">
	    <div class="padder center">
		<p><?php _e( 'D&eacute;sol&eacute;, aucun article correspondant.', 'yproject' ); ?></p>
	    </div><!-- .padder -->
	</div><!-- #content -->
<?php endif; ?>
	
<?php get_footer(); ?>