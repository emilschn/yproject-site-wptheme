<?php
global $post;
if (isset($_GET["campaign_id"])) $post_campaign = get_post($_GET["campaign_id"]);
else $post_campaign = $post;
$save_post = $post;
$post = $post_campaign;

$attachments = get_posts( array(
				    'post_type' => 'attachment',
				    'post_parent' => $post->ID,
				    'post_mime_type' => 'image'
		));
$image_obj = wp_get_attachment_image_src($attachments[0]->ID, "large");
$image_src = '';
if (isset($image_obj) && !empty($image_obj[0])) $image_src = $image_obj[0];
?>
<div id="post_top_bg">
	<div id="post_top_title" class="center" style="background-image: url('<?php echo $image_src; ?>');">  

		<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/blanc_bandeau_projet.png" alt="bandeau blanc" />

		<h1><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></h1>

		<div id="tab-count-jycrois"><?php do_shortcode('[yproject_crowdfunding_jcrois]'); ?></div>

		<div id="post_top_infos">
			<?php echo get_avatar( get_the_author_meta( 'user_email' ), '20' ); ?>
			<?php echo str_replace( '<a href=', '<a rel="author" href=', bp_core_get_userlink( $post->post_author ) ); ?>

			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/france_blc.png" alt="Logo France" width="20" height="20" />

			<?php echo ((isset($post->campaign_location) && $post->campaign_location != '') ? $post->campaign_location : 'France'); ?>
		</div>
	</div>
</div>
<?php $post = $save_post; ?>