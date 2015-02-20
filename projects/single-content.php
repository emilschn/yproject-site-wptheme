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
		<div id="project-summary-container">
			<div id="projects-summary"><?php echo html_entity_decode($campaign->summary()); ?></div>
		</div>
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
			$video_element = wp_oembed_get($campaign->video(), array('width' => 580, 'height' => 325));
		}
		?>
		<div class="video-zone" <?php if ($img_src != '') { ?>style="background-image: url('<?php echo $img_src; ?>');"<?php } ?>>
			<?php echo $video_element; ?>
		</div>
	</div>
	<div id="projects-right-desc" class="right" >
		<div id="project-owner">
			<?php 
			$owner_str = '';
			$api_project_id = BoppLibHelpers::get_api_project_id($post_campaign->ID);
			$current_organisations = BoppLib::get_project_organisations_by_role($api_project_id, BoppLibHelpers::$project_organisation_manager_role['slug']);
			if (count($current_organisations) > 0) {
				$current_organisation = $current_organisations[0];
				$owner_str = $current_organisation->organisation_name . '<br />';
			} else {
//				UIHelpers::print_user_avatar($author_id);
				$author = get_userdata($post_campaign->post_author);
				$owner_str = $author->user_firstname . ' ' . $author->user_lastname . '<br />';
//				$owner_str .= '@' . $author->user_nickname;
			}
			?>
			<div id="project-owner-desc" style="width: 100%; text-align: center;">
				<?php echo $owner_str; ?>
			</div>
		</div>

		<div id="project-about">
			<p>A propos de<p>
			<p><?php echo get_the_title(); ?></p>
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
		<?php } ?>
			
		<div class="project-rewards">
			<span>En &eacute;change de votre investissement</span>
		</div>
			
		<div class="project-rewards">
			<?php if ($campaign->funding_type() == 'fundingdevelopment'): ?>
			Vous recevrez une part de capital de cette entreprise.
			<?php else: ?>
			Vous recevrez une partie du chiffre d'affaires de ce projet.
			<?php endif; ?>
		</div>
		
		<div id="project-rewards-custom" class="project-rewards"><?php echo $campaign->rewards(); ?></div>
	</div>
</div>
<div id="project-description-title-padding"></div>

<div class="part-title-separator">
	<span class="part-title"> 
		Description du projet
	</span>
</div>

<?php
$editor_params = array( 
	'media_buttons' => true,
	'quicktags'     => false,
	'editor_height' => 500,
	'tinymce'       => array(
		'plugins' => 'paste',
		'paste_remove_styles' => true
	)
);
?>

<div class="indent">
	<div class="projects-desc-item">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>projet.png" alt="logo projet" data-content="description"/>
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png" alt="triangle projet"/>
		<div id="project-content-description" class="projects-desc-content">
			<h2>En quoi consiste le projet ?</h2>
			<div class="zone-content"><?php the_content(); ?></div>
			<?php if ($can_modify): ?>
				<div class="zone-edit hidden">
				<?php 
				$editor_description_content = str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', $campaign->data->post_content ));
				wp_editor( $editor_description_content, 'wdg-input-description', $editor_params );
				?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<a id="anchor-societal_challenge"></a>
	<div class="projects-desc-item">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>sociale.png" alt="logo social" data-content="societal_challenge" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png" alt="triangle gris" />
		<div id="project-content-societal_challenge" class="projects-desc-content">
			<h2>Quelle est l&apos;utilit&eacute; soci&eacute;tale du projet ?</h2>
			<div class="zone-content">
				<?php 
				$societal_challenge = html_entity_decode($campaign->societal_challenge()); 
				echo apply_filters('the_content', $societal_challenge);
				?>
			</div>
			<?php if ($can_modify): ?>
				<div class="zone-edit hidden">
				<?php wp_editor( $societal_challenge, 'wdg-input-societal_challenge', $editor_params ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	
	<?php if ($vote_status != 'preview'): ?>
	<div class="projects-desc-item">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>economie.png" alt="logo economie" data-content="added_value" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png" alt="triangle gris"/>
		<div id="project-content-added_value" class="projects-desc-content">
			<h2>Quelle est l&apos;opportunit&eacute; &eacute;conomique du projet ?</h2>
			<div class="zone-content">
				<?php 
				$added_value = html_entity_decode($campaign->added_value()); 
				echo apply_filters('the_content', $added_value);
				?>
			</div>
			<?php if ($can_modify): ?>
				<div class="zone-edit hidden">
				<?php wp_editor( $added_value, 'wdg-input-added_value', $editor_params ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	
	<div class="projects-desc-item">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>model.png" alt="logo modele" data-content="economic_model" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png" alt="triangle gris"/>
		<div id="project-content-economic_model" class="projects-desc-content">
			<h2>Quel est le mod&egrave;le &eacute;conomique du projet ?</h2>
			<div class="zone-content">
				<?php 
				$economic_model = html_entity_decode($campaign->economic_model()); 
				echo apply_filters('the_content', $economic_model);
				?>
			</div>
			<?php if ($can_modify): ?>
				<div class="zone-edit hidden">
				<?php wp_editor( $economic_model, 'wdg-input-economic_model', $editor_params ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
    
	<div class="projects-desc-item">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>porteur.png" alt="logo porteur" data-content="implementation"/>
		<img class="vertical-align-middle grey-triangle"src="<?php echo $images_folder;?>triangle_gris_projet.png" alt="triangle gris"/>
		<div id="project-content-implementation" class="projects-desc-content">
			<h2>Qui porte le projet ?</h2>
			<div class="zone-content">
				<?php 
				$implementation = html_entity_decode($campaign->implementation()); 
				echo apply_filters('the_content', $implementation);
				?>
			</div>
			<?php if ($can_modify): ?>
				<div class="zone-edit hidden">
				<?php wp_editor( $implementation, 'wdg-input-implementation', $editor_params ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
</div>
</div>