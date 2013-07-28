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
						<div id="post_top_title" class="center" style="background-image: url('<?php 
							if (WP_DEBUG) {$debug_src = 'http://localhost/taffe/wp-yproject-site/wp-content/themes/yproject/todo.jpg';} else {$debug_src = get_stylesheet_directory_uri();}
							$attachments = get_posts('post_type=attachment');
							$image_src = wp_get_attachment_image_src($attachments[0]->ID, "full");
							if (isset($image_src) && !empty($image_src[0])) echo $image_src[0]; else echo $debug_src;
							?>'); background-repeat: no-repeat; background-position: center;">
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
							<div><?php echo $campaign->summary(); ?></div>
							
							<h2>En quoi consiste le projet ?</h2>
							<span><?php the_content(); ?></span>
							<?php 
							    global $wp_embed; 
							    echo $wp_embed->run_shortcode( '[embed]' . $campaign->video() . '[/embed]' ); 
							?>

							<h2>Quelle est l'opportunité économique du projet ?</h2>
							<div><?php echo $campaign->added_value(); ?></div>
							<h2>Quelle est l'utilité sociétale du projet ?</h2>
							<div><?php echo $campaign->measuring_impact(); ?></div>
							
							<div><?php echo $campaign->implementation(); ?></div>
							
							<?php
							$categories = get_the_category();
							$separator = ' ';
							$output = '';
							if($categories=='category'.' '.$campaign->title){
								foreach($categories as $category) {
									$output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
								}
							echo trim($output, $separator);
							}
							?>
							
							<h2>Quel est le modèle économique du projet ?</h2>
							<div><?php echo $campaign->economic_model(); ?></div>
							<div><?php echo $campaign->development_strategy(); ?></div>

							<h2>Qui porte le projet ?</h2>
							<div><?php echo $campaign->impact_area(); ?></div>
						    </div>
						    
						    <div class="left post_bottom_infos">
							<?php 
							$percent = $campaign->percent_completed(false);
							$width = 250 * $percent / 100;
							?>
							<div>
							    <div class="project_full_progressbg"><div class="project_full_progressbar" style="width:<?php echo $width; ?>px"></div></div>
							    <span class="project_full_percent"><?php echo $campaign->percent_completed(); ?></span>
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
								<a href="#">[TODO: ] <?php echo __('Investissez', 'yproject'); ?></a>
							    </div>
							    <div id="share_btn" class="dark">
								<a href="#"><?php echo __('Participer autrement', 'yproject'); ?></a>
							    </div>
							    <div class="light">
								<?php
								    $category_slug = 'cat' . $post->ID;
								    $category_obj = get_category_by_slug($category_slug);
								    $category_link = get_category_link($category_obj->cat_ID);
								?>
								<a href="<?php echo esc_url( $category_link ); ?>" title=""><?php echo __('Blog', 'yproject'); ?></a>
							    </div>
							    <div class="light">
								<a href="#">[TODO: ] <?php echo __('Forum', 'yproject'); ?></a>
							    </div>
							    <div class="light">
								<a href="#">[TODO: ] <?php echo __('Statistiques', 'yproject'); ?></a>
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
		    
			<div id="popup_share">
			    <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink( $post->ID )); ?>&amp;layout=button_count&amp;show_faces=true&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=30" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:20px; text-align: center" allowTransparency="true"></iframe>
			    <?php /*<script>function fbs_click() {u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;}</script>
			    <a rel="nofollow" href="http://www.facebook.com/share.php?u=<;url>" onclick="return fbs_click()" target="_blank"><?php echo __('Partager sur Facebook', 'yproject'); ?></a> */ ?>
			    <?php /*<a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode(get_permalink( $post->ID )); ?>%2F&t=<?php echo urlencode(get_the_title()); ?>" target="_blank"><?php echo __('Partager sur Facebook', 'yproject'); ?></a>*/ ?>
			    <a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode(get_permalink( $post->ID )); ?>" target="_blank"><?php echo __('Partager sur Facebook', 'yproject'); ?></a>
			    <br />
			    
			    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
			    <a href="https://twitter.com/share" class="twitter-share-button" data-via="yproject_co" data-lang="fr"><?php echo __('Partager sur Twitter', 'yproject'); ?></a>
			    <?php /*<a href=""><?php echo __('Partager sur Twitter', 'yproject'); ?></a>*/ ?>
			    <br />
			    
			    <a id="popup_share_close" href="javascript:void(0)">[<?php echo __('Fermer', 'yproject'); ?>]</a>
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