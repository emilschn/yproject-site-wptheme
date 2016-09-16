<?php
global $campaign;
$video_element = '';
$img_src = '';
//Si aucune vidéo n'est définie, on affiche l'image
if ($campaign->video() == '') {
	$img_src = $campaign->get_home_picture_src();

//Sinon on utilise l'objet vidéo fourni par wordpress
} else {
	$video_element = wp_oembed_get($campaign->video(), array('width' => 580, 'height' => 325));
}

?>
<div class="project-pitch center">
	<div class="project-pitch-title separator-title">
		<span><?php _e('R&eacute;sum&eacute; du projet', 'yproject'); ?></span>
	</div>
    
	<div class="clearfix">
		<div class="project-pitch-text"><?php echo html_entity_decode($campaign->summary()); ?></div>

		<?php echo "<input type='hidden' href='".$campaign->get_home_picture_src()."' id='url_image_link' style='display:none;'/>"; ?>
		<?php echo "<input type='hidden' href='".$campaign->video()."' id='url_video_link' style='display:none;'/>"; ?>

		<div class="project-pitch-video" <?php if ($img_src != '') { ?>style="background-image: url('<?php echo $img_src; ?>');"<?php } ?>>
			<?php echo $video_element; ?>
		</div>
	</div>
</div>