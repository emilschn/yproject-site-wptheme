<?php
function print_vote_post($vote_post, $is_right_project){
	global $post;
	$temp_post = $post;
	$post = $vote_post;
	$campaign = atcf_get_campaign( $vote_post );
	$position_str = ($is_right_project) ? 'right' : 'left';
	$is_right_project = !$is_right_project;
        ?>
	<div class="home-small-project status-vote <?php echo "home-small-project-" . $position_str; ?>">
	    
		<h2><a href="<?php echo get_permalink($vote_post->ID); ?>"><?php echo $vote_post->post_title; ?></a></h2>
		
		<div class="description-separator first-description-separator"></div>
		
		<a href="<?php echo get_permalink($vote_post->ID); ?>">
			<div class="vote-bubble-<?php echo $position_str; ?>" >
				    
				<?php if ($campaign->end_vote_remaining() > 0) : ?>
					<p>Projet</p>
					<p class="big-text">En vote</p>
					<p class="small-text">jusqu'au <?php echo $campaign->end_vote_date_home() ?> </p>
				<?php else: ?>
					<p style="margin-top: 5px;">Projet</p>
					<p class="big-text">valid&eacute;</p>
				<?php endif; ?>
					
			</div>
		</a>
		
		<div class="description-zone">
			<div class="description-summary">
				<a href="<?php echo get_permalink($vote_post->ID);?>" style="height: 75px;">
				<?php echo $campaign->summary();?>
				</a>
			</div>
		    
			<div class="description-logos">
				<div class="description-logos-item">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/france.png" alt="Logo France" /><br />
					<?php 
					$campaign_location = $campaign->location();
					$exploded = explode(' ', $campaign_location);
					$campaign_location_str = (count($exploded) > 1) ? $exploded[0] : 'France';
					echo $campaign_location_str; 
					?>
				</div>
				<div class="description-logos-item" style="width: 45px;">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png" alt="Logo J'y crois" /><br />
					<?php echo $campaign->get_jycrois_nb(); ?>

				</div>
				<div class="description-logos-item">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/horloge.png" alt="Logo Horloge" /><br />
					<?php echo $campaign->end_vote_remaining(); ?>
				</div>
			</div>

		</div>
		
		<?php $img_src = $campaign->get_home_picture_src(); ?>
		
		<?php if ($img_src != ''): ?>
		<a href="<?php the_permalink(); ?>" style="display: block;">
			<div class="video-zone" style="background-image: url('<?php echo $img_src; ?>'); clear: both;">
				<?php if ($campaign->end_vote_remaining() > 0) : ?>
				<div class="vote-banner"></div>
				<?php endif; ?>
			</div>
		</a>
		<?php endif; ?>
		
		<div class="description-separator"></div>
		
		<?php if ($campaign->end_vote_remaining() > 0) : ?>
		<a href="<?php echo get_permalink($vote_post->ID); ?>" class="description-discover"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle">Voter sur ce projet ici<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"></a>
		<?php else: ?>
		<a href="<?php echo get_permalink($vote_post->ID); ?>" class="description-discover"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle">Voir le projet<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"></a>
		<?php endif; ?>
	</div>

<?php
    $post = $temp_post;
    return $is_right_project; 
}
?>





		
<?php
 function print_preview_post($preview_post, $is_right_project) {
	global $post;
	$temp_post = $post;
	$post = $preview_post;
	$campaign = atcf_get_campaign( $preview_post );
	$position_str = ($is_right_project) ? 'right' : 'left';
	$is_right_project = !$is_right_project;
	?>
	<div class="home-small-project status-preview home-small-project-<?php echo $position_str; ?>">

		<h2><a href="<?php echo get_permalink($preview_post->ID) ?>"><?php echo $preview_post->post_title; ?></a></h2>
		
		<div class="description-separator first-description-separator"></div>
		
		<div class="description-zone">
		    
			<div class="description-summary">
				<a href="<?php echo get_permalink($preview_post->ID);?>">
					<?php echo html_entity_decode($campaign->summary()); ?> 
				</a>
			</div>
		    
			<div class="description-logos">
				<div class="description-logos-item">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/france.png" alt="Logo France" /><br />
					<?php 
					$campaign_location = $campaign->location();
					$exploded = explode(' ', $campaign_location);
					$campaign_location_str = (count($exploded) > 1) ? $exploded[0] : 'France';
					echo $campaign_location_str; 
					?>
				</div>
				<div class="description-logos-item" style="width: 45px;">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png" alt="Logo J'y crois" /><br />
					<?php echo $campaign->get_jycrois_nb(); ?>
				</div>
			</div>
		</div>
		
		<?php $img_src = $campaign->get_home_picture_src(); ?>
		
		<?php if ($img_src != ''): ?>
		<div class="video-zone" style="background-image: url('<?php echo $img_src; ?>')">
			<div class="preview-banner"></div>
			<a href="<?php echo get_permalink($preview_post->ID); ?>">
				<div class="preview-bubble-<?php echo $position_str; ?>">
					<p>Projet</p>
					<p>En avant</p>
					<p>Premiere</p>
				</div>
			</a>
		</div>
		<?php endif; ?>
			    
		<div class="description-separator"></div>
<<<<<<< HEAD
		<a href="<?php echo get_permalink($preview_post->ID); ?>" class="description-discover"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle" /><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle" />D&eacute;couvrir le projet ici<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /></a>
=======
		
		<a href="<?php echo get_permalink($preview_post->ID); ?>" class="description-discover"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle">D&eacute;couvrir le projet ici<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"></a>
>>>>>>> emilien
	</div>
	

<?php
    $post = $temp_post;
    return $is_right_project; 
}
?>

<?php             
function print_empty_post() {
        $page_propose_project = get_page_by_path('proposer-un-projet'); ?>
	<a href="<?php echo get_permalink($page_propose_project->ID); ?>">	
		<div class="home-small-project home-small-project-right home-small-project-empty"></div>
        </a>
<?php } ?>