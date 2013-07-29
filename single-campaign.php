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
					    <?php printPageTop($post); ?>
					    
					    <?php printPageBottomStart($post, $campaign); ?>
					    
					    <div><?php echo html_entity_decode($campaign->summary()); ?></div>

					    <h2>En quoi consiste le projet ?</h2>
					    <span><?php the_content(); ?></span>
					    <?php 
						global $wp_embed; 
						echo $wp_embed->run_shortcode( '[embed]' . $campaign->video() . '[/embed]' ); 
					    ?>

					    <h2>Quelle est l'opportunité économique du projet ?</h2>
					    <div><?php echo html_entity_decode($campaign->added_value()); ?></div>
					    <h2>Quelle est l'utilité sociétale du projet ?</h2>
					    <div><?php echo html_entity_decode($campaign->measuring_impact()); ?></div>
					    <div><?php echo html_entity_decode($campaign->implementation()); ?></div>

					    <h2>Quel est le modèle économique du projet ?</h2>
					    <div><?php echo html_entity_decode($campaign->economic_model()); ?></div>
					    <div><?php echo html_entity_decode($campaign->development_strategy()); ?></div>

					    <h2>Qui porte le projet ?</h2>
					    <div><?php echo html_entity_decode($campaign->impact_area()); ?></div>
					    
					    <?php printPageBottomEnd($post, $campaign); ?>
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