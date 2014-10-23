<?php 
$cache_result=$WDG_cache_plugin->get_cache('project-'.$campaign_id.'-content-first');
if(false===$cache_result){
	ob_start();
$images_folder=get_stylesheet_directory_uri().'/images/';
global $campaign; 
$campaign_id_param = '?campaign_id=';
if (isset($_GET['campaign_id'])) {
	$campaign_id_param .= $_GET['campaign_id'];
	$post_campaign = get_post($_GET['campaign_id']);
	$campaign = atcf_get_campaign( $post_campaign );
} else  {
	$campaign_id_param .= $post_campaign->ID;
}
$vote_status = html_entity_decode($campaign->vote());
?>
<div id="projects-top-desc">
	<div id="projects-left-desc" class="left">
		<div id="projects-summary"><?php echo html_entity_decode($campaign->summary()); ?></div>
		<?php 
		$video_element = '';
		$img_src = '';
		//Si aucune vidéo n'est définie, ou si on est encore en mode preview, on affiche l'image
		if ($campaign->video() == '' || $vote_status == 'preview') {
			$attachments = get_posts( array(
				'post_type' => 'attachment',
				'post_parent' => $post_campaign->ID,
				'post_mime_type' => 'image'
				));
			$image_obj = '';
			//Si on en trouve bien une avec le titre "image_home" on prend celle-là
			foreach ($attachments as $attachment) {
				if ($attachment->post_title == 'image_home') $image_obj = wp_get_attachment_image_src($attachment->ID, "full");
			}
			//Sinon on prend la première image rattachée à l'article
			if ($image_obj == '' && count($attachments) > 0) $image_obj = wp_get_attachment_image_src($attachments[0]->ID, "full");
			if ($image_obj != '') $img_src = $image_obj[0];

		//Sinon on utilise l'objet vidéo fourni par wordpress
		} else {
			$video_element = wp_oembed_get($campaign->video(), array('width' => 580));
		}
		?>
		<div class="video-zone" <?php if ($img_src != '') { ?>style="background-image: url('<?php echo $img_src; ?>');"<?php } ?>>
			<?php echo $video_element; ?>
		</div>
	</div>
	<div id="projects-right-desc" class="right" >
		<div id="project-owner">
			<?php 
			$author_id=get_the_author_meta('ID');
//			UIHelpers::print_user_avatar($author_id);
			$author=get_user_meta($author_id);
			?>
			<div id="project-owner-desc" style="width: 100%; text-align: center;">
				<?php echo $author['last_name'][0] . ' ' . $author['first_name'][0]; ?><br />
				<?php echo '@'.$author['nickname'][0]; ?>
				<?php
				
//				echo '<p>'.$author['last_name'][0].' '.$author['first_name'][0].'</p>';
//				echo '<p>@'.$author['nickname'][0].'</p>';
				?>
			</div>
		</div>

		<div id="project-about">
			<p> A propos de<p>
			<p>  <?php echo get_the_title(); ?> </p>
		</div>
		<div id="project-map">
			<?php $cursor_top_position=get_post_meta($post_campaign->ID,'campaign_cursor_top_position',TRUE); ?>
			<?php $cursor_left_position=get_post_meta($post_campaign->ID,'campaign_cursor_left_position',TRUE); ?>
			<div id="map-cursor" style="<?php if($cursor_top_position!='') echo 'top:'.$cursor_top_position.';'; if($cursor_left_position!='') echo 'left:'.$cursor_left_position; ?> ">
				<p><?php echo $campaign->location(); ?></p>
			</div>
			
		</div>
		<?php
		$cache_result=ob_get_contents();
		$WDG_cache_plugin->set_cache('project-'.$campaign_id.'-content-first',$cache_result);
		ob_end_clean();
		}
		echo $cache_result;
			
			if($can_modify){ ?>
				<a id="move-cursor" href="JavaScript:void(0);" onclick='javascript:WDGProjectPageFunctions.move_cursor(<?php if(isset($_GET['campaign_id'])){echo $_GET['campaign_id'];}else{global $post;echo($post->ID); } ?>)'>Modifier la position du curseur</a>
			<?php } 

			$cache_result=$WDG_cache_plugin->get_cache('project-'.$campaign_id.'-content-second');
			if(false===$cache_result){
				ob_start();
				?>
	</div>
</div>
<div id="project-description-title-padding"></div>

<div class="part-title-separator">
	<span class="part-title"> 
		Description du projet
	</span>
</div>

<div class="indent">
	<div class="projects-desc-item project-item">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>projet.png" data-content="project"/>
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png"/>
		<div id="project-content-project" class="projects-desc-content">
			<h2>En quoi consiste le projet ?</h2>
			<div><?php the_content(); ?></div>
		</div>
	</div>

	<div class="projects-desc-item">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>sociale.png" data-content="social" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png" />
		<div id="project-content-social" class="projects-desc-content">
			<h2>Quelle est l&apos;utilit&eacute; soci&eacute;tale du projet ?</h2>
				<div>
				<?php 
				$societal_challenge = html_entity_decode($campaign->societal_challenge()); 
				echo apply_filters('the_content', $societal_challenge);
				?>
			</div>
		</div>
	</div>
	
	<?php if ($vote_status != 'preview'): ?>
	<div class="projects-desc-item">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>economie.png" data-content="economic" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png"/>
		<div id="project-content-economic" class="projects-desc-content">
			<h2>Quelle est l&apos;opportunit&eacute; &eacute;conomique du projet ?</h2>
			<div>
				<?php 
				$added_value = html_entity_decode($campaign->added_value()); 
				echo apply_filters('the_content', $added_value);
				?>
			</div>
		</div>
	</div>
	
	<div class="projects-desc-item">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>model.png" data-content="model" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png"/>
		<div id="project-content-model" class="projects-desc-content">
			<h2>Quel est le mod&egrave;le &eacute;conomique du projet ?</h2>
			<div>
				<?php 
				$economic_model = html_entity_decode($campaign->economic_model()); 
				echo apply_filters('the_content', $economic_model);
				?>
			</div>
		</div>
	</div>
	<?php endif; ?>
    
	<div class="projects-desc-item">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>porteur.png" data-content="porteur"/>
		<img class="vertical-align-middle grey-triangle"src="<?php echo $images_folder;?>triangle_gris_projet.png"/>
		<div id="project-content-porteur" class="projects-desc-content">
			<h2>Qui porte le projet ?</h2>
			<div>
				<?php 
				$implementation = html_entity_decode($campaign->implementation()); 
				echo apply_filters('the_content', $implementation);
				?>
			</div>
		</div>
	</div>
</div>
</div>
</div>
<?php
		$cache_result = ob_get_contents();
		$WDG_cache_plugin->set_cache('project-'.$campaign_id.'-content-second', $cache_result);
		ob_end_clean();
		}
		echo $cache_result;
?>
