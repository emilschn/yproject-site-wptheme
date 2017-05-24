<?php
	global $campaign, $stylesheet_directory_uri;
	if (isset($_GET['campaign_id'])) {
		$post = get_post($_GET['campaign_id']);
		$campaign = atcf_get_campaign( $post );
	}
	$btn_follow_href = '#connexion';
	$btn_follow_classes = 'wdg-button-lightbox-open';
	$btn_follow_data_lightbox = 'connexion';
	$btn_follow_text = __('Suivre', 'yproject');
	$btn_follow_following = '0';
	$directory_facebook=$stylesheet_directory_uri."/images/facebook.jpg";
	$directory_twitter=$stylesheet_directory_uri."/images/twitter.jpg";
	$directory_google=$stylesheet_directory_uri."/images/google+.jpg";

	echo do_shortcode('[yproject_lightbox id="voteform-validated"]'."
		<div id='thanks'> 
			<span class='block_thanks_1'>Merci d'avoir voté sur ce projet !</span>
			</br>
			<span class='block_thanks_2'>Pensez à en parler autour de vous !</span>
			</br>
			
			<span class='block_thanks_3'>		
				<a style='background: url(".$directory_facebook."); background-repeat:no-repeat; background-position:center; background-size: cover;' alt='logo facebook' href='https://www.facebook.com/sharer/sharer.php?u=".get_permalink($post->ID)."' target='_blank'>
				</a>
				<a style='background: url(".$directory_twitter."); background-repeat:no-repeat; background-position:center; background-size: cover;' alt='logo twitter' href='http://twitter.com/share?url=".get_permalink($post->ID)."&text=".'"WEDOGOOD"'." target='_blank'>
				</a>
				<a style='background: url(".$directory_google."); background-repeat:no-repeat; background-position:center; background-size: cover;' alt='logo google' href='https://plus.google.com/share?url=".get_permalink($post->ID)."' target='_blank'>
				</a>
			</span>
		</div>
	".'[/yproject_lightbox]');
?>

