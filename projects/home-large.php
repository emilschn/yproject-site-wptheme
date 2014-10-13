
<?php while (have_posts()): the_post(); ?>
	<?php 
	global $post;
	$campaign = atcf_get_campaign( $post );
	$campaign_status = $campaign->campaign_status();
	
	$days_remaining = $campaign->days_remaining();
	if ($campaign_status == 'vote') {
		$days_remaining = $campaign->end_vote_remaining();
	}
	
	$percent = min(100, $campaign->percent_minimum_completed(false));
	$width = 240 * $percent / 100;
	$width_min = 0;
	/*if ($percent >= 100 && $campaign->is_flexible()) {
		$percent_min = $campaign->percent_minimum_to_total();
		$width_min = 150 * $percent_min / 100;
	}*/
	
	$container_class = 'status-' . $campaign_status;
	?>

	<div class="home-large-project <?php echo $container_class; ?>">

		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" border="0" /></a></h2>
		
		<?php 
		$video_element = '';
		$img_src = '';
		//Si aucune vidéo n'est définie, ou si on est encore en mode preview, on affiche l'image
		if ($campaign->video() == '' || $campaign_status == 'preview' || ($campaign->days_remaining() == 0 && $campaign->is_funded())) {
			$attachments = get_posts( array(
							    'post_type' => 'attachment',
							    'post_parent' => $post->ID,
							    'post_mime_type' => 'image'
					));
			$image_obj = '';
			//Si on en trouve bien une avec le titre "image_home" on prend celle-là
			foreach ($attachments as $attachment) {
			    if ($attachment->post_title == 'image_home') $image_obj = wp_get_attachment_image_src($attachment->ID, "full");
			}
			//Sinon on prend la première image rattachée à l'article
			if ($image_obj == '') $image_obj = wp_get_attachment_image_src($attachments[0]->ID, "full");
			if ($image_obj != '') $img_src = $image_obj[0];
			
		//Sinon on utilise l'objet vidéo fourni par wordpress
		} else {
			$video_element = wp_oembed_get($campaign->video(), array('width' => 610));
		}
		?>
		<?php if ($img_src != ''): ?>
		<a href="<?php the_permalink(); ?>" style="display: block;">
		<?php endif; ?>
		<div class="video-zone" <?php if ($img_src != '') { ?>style="background-image: url('<?php echo $img_src; ?>')"<?php } ?>>
			<?php echo $video_element;
			if ($video_element == '' && $campaign_status== 'funded' ) { ?>
					<div class="funded-banner"></div>
				<?php }
			 ?>
		</div>
		<?php if ($img_src != ''): ?>
		</a>
		<?php endif; ?>
		
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
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png" alt="Logo J'y crois" />
						<?php echo $campaign->get_jycrois_nb(); ?>
					</div>
					<div class="description-logos-item">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/horloge.png" alt="Logo Horloge" />
						<?php echo $days_remaining; ?>
					</div>
					<div class="description-logos-item" style="width: 60px;">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cible.png" alt="Logo Cible" />
						<?php echo $campaign->minimum_goal(true); ?>
					</div>	
					
				</div>

				<?php if ($campaign_status == 'collecte'): ?>
				<div class="description-progress">
					<div class="project_preview_item_progressbg">
						<div class="project_preview_item_progressbar" style="width:<?php echo $width; ?>px">
							<?php if ($width_min > 0): ?>
							<div style="width: <?php echo $width_min; ?>px; height: 25px; border: 0px; border-right: 1px solid white;">&nbsp;</div>
							<?php else: ?>
							&nbsp;
							<?php endif; ?>
						</div>
					</div>
					<span class="project_preview_item_progressprint"><?php echo $campaign->percent_minimum_completed(); ?></span>
				</div>
				<?php endif; ?>
				
				<div class="description-status"><a href="<?php the_permalink(); ?>">
				<?php if ($campaign_status == 'preview'): ?>D&eacute;couvrez ce projet et participez &agrave; sa pr&eacute;paration.<?php endif; ?>
				<?php if ($campaign_status == 'vote'): ?>&Eacute;valuez l&apos;impact du projet et d&eacute;cidez de sa pr&eacute;sence sur WEDOGOOD.co.<?php endif; ?>
				</a></div>

				<div class="description-separator"></div>
			</div>
			
			<a href="<?php the_permalink(); ?>" class="description-discover"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle" /><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle" />D&eacute;couvrir le projet<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /></a>
		</div>
		
		<div class="clear"></div>
	</div>
	
<?php endwhile; ?>
    
<?php wp_reset_query(); ?>