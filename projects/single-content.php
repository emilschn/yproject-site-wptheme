<?php 
$cache_result=$WDG_cache_plugin->get_cache('project-'.$campaign_id.'-content-first');
if(false===$cache_result){
	ob_start();
$images_folder=get_stylesheet_directory_uri().'/images/';
global $campaign; 
$campaign_id_param = '?campaign_id=';
if (isset($_GET['campaign_id'])) {
	$campaign_id_param .= $_GET['campaign_id'];
	$post = get_post($_GET['campaign_id']);
	$campaign = atcf_get_campaign( $post );
} else  {
	$campaign_id_param .= $post->ID;
}
$vote_status = html_entity_decode($campaign->vote());
?>
<div id="projects-top-desc">
	<div id="projects-left-desc" class="left">
		<h1 class="projects-title">Le projet</h1>
		<div id="projects-summary"><?php echo html_entity_decode($campaign->summary()); ?></div>
		<?php 
		$video_element = '';
		$img_src = '';
		//Si aucune vidéo n'est définie, ou si on est encore en mode preview, on affiche l'image
		if ($campaign->video() == '' || $vote_status == 'preview') {
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
			print_user_avatar($author_id);
			$author=get_user_meta($author_id);
			?>
			<div id="project-owner-desc">
				<?php
				echo '<p>'.$author['last_name'][0].' '.$author['first_name'][0].'</p>';
				echo '<p>'.$author['user_address'][0].'</p>';
				echo '<p>'.$author['user_postal_code'][0].' '.$author['user_city'][0].'</p>';
				echo '<p>'.$author['user_mobile_phone'][0].'</p>';
				?>
			</div>
		</div>

		<div id="project-about">
			<p> A propos <p>
			<p> de <?php echo get_the_title(); ?> </p>
		</div>
		<div id="project-map">
			<?php $cursor_top_position=get_post_meta($post->ID,'campaign_cursor_top_position',TRUE); ?>
			<?php $cursor_left_position=get_post_meta($post->ID,'campaign_cursor_left_position',TRUE); ?>
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
<div class="part-title-separator" >
	<span class="part-title"> 
		Description du projet
	</span>
</div>
<div id="projects-bottom-desc">
	<?php 
	if ($vote_status == 'preview') : 
		$forum = get_page_by_path('forum');
	?>
	<br /><br /><center><a href="<?php echo get_permalink($forum->ID) . $campaign_id_param; ?>">Participez sur son forum !</a></center>
<?php endif; ?>

<div class="indent">
	<div class="projects-desc-item">
		<img class="vertical-align-middle" src="<?php echo $images_folder;?>projet.png"/>
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png"/>
		<div class="projects-desc-content">
		<h2 >En quoi consiste le projet ?</h2>
		<span><?php the_content(); ?></span>
		</div>
	</div>

	<?php if ($vote_status != 'preview'): ?>
	<div class="projects-desc-item">
		<img class="vertical-align-middle" src="<?php echo $images_folder;?>economie.png"/>
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png"/>
		<div class="projects-desc-content">
		<h2 >Quelle est l'opportunité économique du projet ?</h2>
		<div><?php 
		$added_value = html_entity_decode($campaign->added_value()); 
		echo apply_filters('the_content', $added_value);
		?>
		</div>
		</div>
	</div>
	<?php endif; ?>

	<a id="utilite-societale"></a>
	<div class="projects-desc-item">
		<img class="vertical-align-middle" src="<?php echo $images_folder;?>sociale.png"/>
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png"/>
		<div class="projects-desc-content">
		<h2 >Quelle est l'utilité sociétale du projet ?</h2>
		<div><?php 
		$societal_challenge = html_entity_decode($campaign->societal_challenge()); 
		echo apply_filters('the_content', $societal_challenge);
		?>
		</div>
		</div>
	</div>

	<?php if ($vote_status != 'preview'): ?>
	<div class="projects-desc-item">
		<img class="vertical-align-middle" src="<?php echo $images_folder;?>model.png"/>
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png"/>
		<div class="projects-desc-content">
		<h2 >Quel est le modèle économique du projet ?</h2>
		<div><?php 
		$economic_model = html_entity_decode($campaign->economic_model()); 
		echo apply_filters('the_content', $economic_model);
		?></div>
		</div>
	</div>
	<?php endif; ?>
    
	<div class="projects-desc-item">
		<img class="vertical-align-middle" src="<?php echo $images_folder;?>porteur.png"/>
		<img class="vertical-align-middle grey-triangle"src="<?php echo $images_folder;?>triangle_gris_projet.png"/>
		<div class="projects-desc-content">
		<h2>Qui porte le projet ?</h2>
		<div><?php 
		$implementation = html_entity_decode($campaign->implementation()); 
		echo apply_filters('the_content', $implementation);
		?></div>
		</div>
	</div>
</div>
</div>
</div>
<?php
$cache_result=ob_get_contents();
		$WDG_cache_plugin->set_cache('project-'.$campaign_id.'-content-second',$cache_result);
		ob_end_clean();
		}
		echo $cache_result;
?>
