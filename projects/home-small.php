<?php
function print_vote_post($vote_post,$is_right_project){
	global $post;
	$temp_post = $post;
	$post = $vote_post;
	$campaign = atcf_get_campaign( $vote_post );
        ?>
	<div class="home-small-project status-vote <?php if ($is_right_project){echo "home-small-project-right";$is_right_project=false;}else{ echo "home-small-project-left"; $is_right_project=true;}?>">
		<h2><a href="<?php echo get_permalink($vote_post->ID); ?>"><?php echo $vote_post->post_title; ?></a></h2>
		<div class="description-separator first-description-separator"></div>
		
			<a href="<?php echo get_permalink($vote_post->ID); ?>">
				<?php 
				if($is_right_project){//si a gauche
				?>
				<div class="vote-bubble-left" >
				<?php } else { ?>
				<div class="vote-bubble-right">
				<?php } ?>
					<p>Projet</p>
					<p class="big-text">En vote</p>
					<p class="small-text">jusqu'au <?php echo $campaign->end_vote_date_home() ?> </p>
				</div>
			</a>

		<div class="description-zone">
			<div class="description-summary ">
				<a href="<?php echo get_permalink($vote_post->ID);?>">
				<?php $content =$campaign->summary();echo $content;?>
				</a>
			</div>
		    
			<div class="description-logos">
				<div class="description-logos-item">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/france.png" alt="Logo France" /><br />
					<?php 
					$campaign_location = $campaign->location();
					$exploded = explode(' ', $campaign_location);
					if (count($exploded) > 1) $campaign_location = $exploded[0];
					echo (($campaign_location != '') ? $campaign_location : 'France'); 
					?>
				</div>
				<div class="description-logos-item" style="width: 45px;">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png" alt="Logo J'y crois" /><br />
					<?php do_shortcode('[yproject_crowdfunding_count_jcrois]'); ?>
				</div>
				<div class="description-logos-item">
				    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/horloge.png" alt="Logo Horloge" /><br />
				    <?php echo $campaign->end_vote_remaining(); ?>
				</div>
			</div>

		</div>
		<?php 
		$video_element = '';
		$img_src = '';
		if ($campaign->video() == '') {
			$attachments = get_posts( array(
				'post_type' => 'attachment',
				'post_parent' => $vote_post->ID,
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
			$video_element = wp_oembed_get($campaign->video(), array('width' => 440));
		}
		?>
<div class="video-zone" <?php if ($img_src != '') { ?>style="background-image: url('<?php echo $img_src; ?>');clear: both;"<?php } ?> >
			<?php echo $video_element;
				if ($video_element == '') { ?>
					<div class="vote-banner"></div>
				<?php }
			 ?>
		</div>
		<div class="description-separator " <?php if($video_element!='')echo "style='margin-top: 95px;'";?>></div>
		<a href="<?php echo get_permalink($vote_post->ID); ?>" class="description-discover"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle">Voter sur ce projet ici<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"></a>
	</div>

<?php
    $post = $temp_post;
    return $is_right_project; 
}
?>





		
<?php
 function print_preview_post($preview_post,$is_right_project){
	global $post;
	$temp_post = $post;
	$post = $preview_post;
	$campaign = atcf_get_campaign( $preview_post );
	?>

<div class="home-small-project status-preview<?php echo $container_class; ?> <?php if ($is_right_project){echo "home-small-project-right";$is_right_project=false;}else{ echo "home-small-project-left"; $is_right_project=true;}?>">

		<h2><a href="<?php echo get_permalink($preview_post->ID) ?>"><?php echo $preview_post->post_title; ?></a></h2>
		<div class="description-separator first-description-separator"></div>
		<div class="description-zone">
			<div class="description-summary">
				<a href="<?php echo get_permalink($preview_post->ID);?>">
				<?php $content =html_entity_decode($campaign->summary()); echo $content; ?> 
				</a>
			</div>
		    
			<div class="description-logos">
				<div class="description-logos-item">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/france.png" alt="Logo France" /><br />
					<?php 
					$campaign_location = $campaign->location();
					$exploded = explode(' ', $campaign_location);
					if (count($exploded) > 1) $campaign_location = $exploded[0];
					echo (($campaign_location != '') ? $campaign_location : 'France'); 
					?>
				</div>
				<div class="description-logos-item" style="width: 45px;">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png" alt="Logo J'y crois" /><br />
					<?php do_shortcode('[yproject_crowdfunding_count_jcrois]'); ?>
				</div>
			</div>
		</div>
		<?php 
		$video_element = '';
		$img_src = '';
		//Si aucune vidéo n'est définie, ou si on est encore en mode preview, on affiche l'image
		if ($campaign->video() == '') {
			$attachments = get_posts( array(
							    'post_type' => 'attachment',
							    'post_parent' => $preview_post->ID,
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
			$video_element = wp_oembed_get($campaign->video(), array('width' => 440));
		}
		?>
		<div class="video-zone" <?php if ($img_src != '') { ?>style="background-image: url('<?php echo $img_src; ?>')"<?php } ?> >
			<?php echo $video_element;
			if ($video_element == '') { ?>
					<div class="preview-banner"></div>
				<?php }
			 ?>
			<a href="<?php echo get_permalink($preview_post->ID); ?>">
				<?php
				if($is_right_project){//si a gauche
				?>
					<div class="preview-bubble-left">
				<?php } else { ?>
					<div class="preview-bubble-right">	
				<?php } ?>
				<p>Projet</p>
				<p>En avant</p>
				<p>Premiere</p>
				</div>
			</a>
		</div>
		<div class="description-separator"></div>
		<a href="<?php echo get_permalink($preview_post->ID); ?>" class="description-discover"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle">D&eacute;couvrir le projet ici<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"></a>
	</div>
	

<?php
    $post = $temp_post;
    return $is_right_project; 
}
?>

<?php             
function print_empty_post(){
        $page_propose_project = get_page_by_path('proposer-un-projet');?>
		<a href="<?php echo get_permalink($page_propose_project->ID); ?>">	
        <div class="home-small-project home-small-project-right home-small-project-empty"></div>
        </a>
<?php } ?>