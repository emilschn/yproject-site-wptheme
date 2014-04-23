<?php 
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

<div style="padding-top: 25px"><?php echo html_entity_decode($campaign->summary()); ?></div>


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
	$video_element = wp_oembed_get($campaign->video(), array('width' => 610));
}
?>
<div class="padding-top video-zone" <?php if ($img_src != '') { ?>style="background-image: url('<?php echo $img_src; ?>'); height: 330px; margin-top: 45px; padding-top: 0px;"<?php } ?>>
	<?php echo $video_element; ?>
</div>

<?php 
if ($vote_status == 'preview') : 
	$forum = get_page_by_path('forum');
?>
<br /><br /><center><a href="<?php echo get_permalink($forum->ID) . $campaign_id_param; ?>">Participez sur son forum !</a></center>
<?php endif; ?>

<h2 class="padding-top">En quoi consiste le projet ?</h2>
<span><?php the_content(); ?></span>

<?php if ($vote_status != 'preview'): ?>
<h2 class="padding-top">Quelle est l'opportunité économique du projet ?</h2>
<div><?php 
    $added_value = html_entity_decode($campaign->added_value()); 
    echo apply_filters('the_content', $added_value);
?></div>
<?php endif; ?>

<h2 class="padding-top">Quelle est l'utilité sociétale du projet ?</h2>
<div><?php 
    $societal_challenge = html_entity_decode($campaign->societal_challenge()); 
    echo apply_filters('the_content', $societal_challenge);
?></div>

<?php if ($vote_status != 'preview'): ?>
<h2 class="padding-top">Quel est le modèle économique du projet ?</h2>
<div><?php 
    $economic_model = html_entity_decode($campaign->economic_model()); 
    echo apply_filters('the_content', $economic_model);
?></div>
<?php endif; ?>

<h2 class="padding-top">Qui porte le projet ?</h2>
<div><?php 
    $implementation = html_entity_decode($campaign->implementation()); 
    echo apply_filters('the_content', $implementation);
?></div>