<?php get_header(); ?>

<?php 
    $this_category = get_category($cat);
    $this_category_name = $this_category->name;
    $name_exploted = explode('cat', $this_category_name);
    $campaign_post = get_post($name_exploted[1]);
    $campaign = atcf_get_campaign( $campaign_post );
    global $post;
    $post = $campaign_post;
?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_archive' ); ?>

		<div class="page" id="blog-archives" role="main">
			<?php require_once('projects/single-admin-bar.php'); ?>
			<?php require_once('projects/single-header.php'); ?>
			
			<div id="post_bottom_bg">
				<div id="post_bottom_content" class="center">
					<div class="left post_bottom_desc">
						<a href="<?php echo get_permalink($campaign_post->ID); ?>">&lt;&lt; <?php echo __('Revenir à la description du projet', 'yproject'); ?></a>

						<h3 class="pagetitle"><?php printf( __( 'Derni&egrave;res actualit&eacute;s du projet %1$s', 'yproject' ), $campaign_post->post_title); ?></h3>

						<?php if ( have_posts() ) : ?>

							<?php bp_dtheme_content_nav( 'nav-above' ); ?>

							<?php while (have_posts()) : the_post(); ?>

								<?php do_action( 'bp_before_blog_post' ); ?>

								<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

									<div class="post-content">
										<h2 class="posttitle"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'buddypress' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

										<p class="date"><?php echo get_the_date(); ?></p>

										<div class="entry">
											<?php the_content( __( 'Read the rest of this entry &rarr;', 'buddypress' ) ); ?>
										</div>

										<span class="comments"><?php comments_popup_link( __( 'No Comments &#187;', 'buddypress' ), __( '1 Comment &#187;', 'buddypress' ), __( '% Comments &#187;', 'buddypress' ) ); ?></span></p>
									</div>

								</div>

								<?php do_action( 'bp_after_blog_post' ); ?>

							<?php endwhile; ?>

							<?php bp_dtheme_content_nav( 'nav-below' ); ?>

						<?php else : ?>

						    <?php if ($campaign_post) : ?>
							Retrouvez bient&ocirc;t les actualit&eacute;s de ce projet !
						    <?php else : ?>
							<h2 class="center"><?php _e( 'Not Found', 'buddypress' ); ?></h2>
							<?php get_search_form(); ?>
						    <?php endif; ?>

						<?php endif; ?>
					</div>

					<div class="left post_bottom_infos">
						<?php $post = $campaign_post; ?>
						<?php require_once('projects/single-sidebar.php'); ?>
					</div>

					<div style="clear: both"></div>
				</div>
			</div>
		    
		</div>

		<?php do_action( 'bp_after_archive' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer(); ?>
