<?php queryHomePojects(1); ?>

<?php while (have_posts()): the_post(); ?>

	<?php 
	global $post; $campaign = atcf_get_campaign( $post );
	$vote = (get_post_meta($post->ID, 'campaign_vote', true) == 'vote');
	
	$days_remaining = $campaign->days_remaining();
	if ($vote) {
		$days_remaining = $campaign->end_vote_remaining();
	}
	
	$percent = min(100, $campaign->percent_minimum_completed(false));
	$width = 240 * $percent / 100;
	$width_min = 0;
	if ($percent >= 100 && $campaign->is_flexible()) {
		$percent_min = $campaign->percent_minimum_to_total();
		$width_min = 150 * $percent_min / 100;
	}
	?>

	<div class="home-large-project">

		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" border="0" /></a></h2>
		
		<div class="video-zone">
			<?php 
			if ($campaign->video() != '') {
				echo wp_oembed_get($campaign->video(), array('width' => 610));
			}
			?>
		</div>
		
		<div class="description-zone">
			<div class="description-summary">
				<?php echo html_entity_decode($campaign->summary()); ?>
			</div>
		    
			<div class="description-middle">
		    
				<div class="description-separator"></div>

				<div class="description-logos">
					<div class="description-logos-item">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/france.png" alt="Logo France" />
						<?php 
						$campaign_location = $campaign->location();
						$exploded = explode(' ', $campaign_location);
						if (count($exploded) > 1) $campaign_location = $exploded[0];
						echo (($campaign_location != '') ? $campaign_location : 'France'); 
						?>
					</div>
					<div class="description-logos-item">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/horloge.png" alt="Logo Horloge" />
						<?php echo $days_remaining; ?>
					</div>
					<div class="description-logos-item">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cible.png" alt="Logo Cible" />
						<?php echo $campaign->minimum_goal(true); ?>
					</div>
					<div class="description-logos-item">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png" alt="Logo J'y crois" />
						<?php do_shortcode('[yproject_crowdfunding_count_jcrois]'); ?>
					</div>
				</div>

				<?php if (!$vote): ?>
				<div class="description-progress">
					<div class="project_preview_item_progressbg">
						<div class="project_preview_item_progressbar" style="width:<?php echo $width; ?>px">
							<?php if ($width_min > 0): ?>
							<div style="width: <?php echo $width_min; ?>px; height: 20px; border: 0px; border-right: 1px solid white;">&nbsp;</div>
							<?php else: ?>
							&nbsp;
							<?php endif; ?>
						</div>
					</div>
					<span class="project_preview_item_progressprint"><?php echo $campaign->percent_minimum_completed(); ?></span>
				</div>
				<?php endif; ?>

				<div class="description-separator"></div>
			
			</div>
			
			<a href="<?php the_permalink(); ?>" class="description-discover"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle">D&eacute;couvrir le projet<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"></a>
		</div>
		
		<div class="clear"></div>
	</div>
	
<?php endwhile; ?>
    
<?php wp_reset_query(); ?>