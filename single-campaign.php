<?php 
    date_default_timezone_set("Europe/Paris");
    require_once("common.php");
    
    global $campaign, $post;
    if ( ! is_object( $campaign ) )
	    $campaign = atcf_get_campaign( $post );
?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="content">
		<div class="padder">

			<?php do_action( 'bp_before_blog_single_post' ); ?>

			<div class="page" id="blog-single" role="main">

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				    <div class="post-content">
					<div class="entry">
					    <div id="post_top_bg">
						<div id="post_top_title" class="center" style="background-image: url('<?php if (WP_DEBUG) echo 'http://localhost/taffe/wp-yproject-site/wp-content/themes/yproject'; else echo get_stylesheet_directory_uri(); ?>/todo.jpg'); background-repeat: no-repeat; background-position: center;">
						    <h1><?php the_title(); ?></h1>

						    <div>
							<a href="#">[TODO: bouton "J'y crois"] <?php echo __('Jy crois', 'yproject'); ?></a>
						    </div>

						    <div id="post_top_infos">
							<img src="" width="40" height="40" />
							<?php echo ((isset($post->campaign_location) && $post->campaign_location != '') ? $post->campaign_location : 'France'); ?>

							<?php echo get_avatar( get_the_author_meta( 'user_email' ), '40' ); ?>
							<?php echo str_replace( '<a href=', '<a rel="author" href=', bp_core_get_userlink( $post->post_author ) ); ?>
						    </div>
						</div>
					    </div>

					    <div id="post_bottom_bg">
						<div id="post_bottom_content" class="center">
						    <div class="left post_bottom_desc">
							<div>
							    <?php 
								/*
								 * La fonction the_content ajoute automatiquement des morceaux prévus par Easy Digital Download (bouton Acheter, mention "vous avez déjà acheté cet article",..)
								 * Nous ne voulons pas ces données dans cette partie mais il faut :
								 * - le résumé
								 * - les différentes parties explicatives (description, ...)
								 */
								the_content();
							    ?>
							    [TODO : résumé, description, etc.]
							</div>
							<div>
							    <?php echo $post->campaign_video; ?>
							    [TODO : video => quel format ? Player youtube ? autre ?]
							</div>
							<div>
							    <?php echo $post->campaign_images; ?>
							    [TODO : images => quel format ? Quelles sont ces images ?]
							</div>
						    </div>
						    
						    <div class="left post_bottom_infos">
							<?php 
							$percent = $campaign->percent_completed(false);
							$width = 309 * $percent / 100;
							?>
							<div class="post_bottom_infos_item">
							    <div class="project_full_progressbg"><div class="project_full_progressbar" style="width:<?php echo $width; ?>px"><?php echo $campaign->current_amount(); ?></div></div>
							</div>
							
							<div class="post_bottom_infos_item">
							    <img src="" width="40" height="40" />
							    <?php echo $campaign->backers_count(); ?>
							</div>
							
							<div class="post_bottom_infos_item">
							    <img src="" width="40" height="40" />
							    <?php echo $campaign->days_remaining(); ?>
							</div>
							
							<div class="post_bottom_infos_item">
							    <img src="" width="40" height="40" />
							    <?php echo $campaign->current_amount() . ' / ' . $campaign->goal(); ?>
							</div>
							
							<div class="post_bottom_buttons">
							    <div class="dark">
								<a href="#">[TODO: lien vers "Investir"] <?php echo __('Investissez', 'yproject'); ?></a>
							    </div>
							    <div class="dark">
								<a href="#">[TODO: lien vers "Participer autrement"] <?php echo __('Participer autrement', 'yproject'); ?></a>
							    </div>
							    <div class="light">
								<a href="#">[TODO: lien vers le blog du projet] <?php echo __('Blog', 'yproject'); ?></a>
							    </div>
							    <div class="light">
								<a href="#">[TODO: lien vers le forum du projet] <?php echo __('Forum', 'yproject'); ?></a>
							    </div>
							    <div class="light">
								<a href="#">[TODO: lien vers le forum du projet] <?php echo __('Statistiques', 'yproject'); ?></a>
							    </div>
							</div>
						    </div>

						    <div style="clear: both"></div>
						</div>
					    </div>
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