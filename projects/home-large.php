
<?php while (have_posts()): the_post(); ?>
	<?php 
	date_default_timezone_set("Europe/London");
	global $post;
	$campaign = atcf_get_campaign( $post );
	$campaign_status = $campaign->campaign_status();
	
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

		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" class="mobile_hidden" alt="signe plus" border="0" /></a></h2>
		
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
		<div class="video-zone" <?php if ($img_src != '') { ?>style="background-image: url('<?php echo $img_src; ?>')"<?php } ?>>
			<?php echo $video_element;
			if ($video_element == '' && $campaign_status== 'funded' ) { ?>
					<div class="funded-banner mobile_hidden"></div>
				<?php }
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
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/france.png" alt="logo france" />
						<?php 
						$campaign_location = $campaign->location();
						$exploded = explode(' ', $campaign_location);
						if (count($exploded) > 1) $campaign_location = $exploded[0];
						echo (($campaign_location != '') ? $campaign_location : 'France'); 
						?>
					</div>
					<div class="description-logos-item">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png" alt="logo jy crois" />
						<?php echo $campaign->get_jycrois_nb(); ?>
					</div>
					<div class="description-logos-item">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/horloge.png" alt="logo horloge" />
						<?php echo $campaign->time_remaining_str(); ?>
					</div>
					<div class="description-logos-item" style="width: 60px;">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cible.png" alt="logo cible" />
						<?php echo $campaign->minimum_goal(true); ?>
					</div>	
					
				</div>

				<?php if ($campaign_status == 'collecte' || $campaign_status == 'funded' || $campaign_status == 'archive'): ?>
				<div class="progress_zone">
					<div class="project_full_progressbg">
						<span class="project_full_percent" style="min-width:<?php echo $width; ?>px">&nbsp;<?php echo $campaign->current_amount(); ?>&nbsp;</span>
					</div>
					<span class="progress_percent"><?php echo $campaign->percent_minimum_completed(); ?></span>
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