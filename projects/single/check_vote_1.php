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



	echo do_shortcode('
		[yproject_lightbox id="vote_check_1"]'."
		<div id='remerciement'> 
			<span>Nous vous remercions d'avoir voter pour ce projet.</span>
			</br>
			<span> Pensez à partager ce projet ! </span>
			</br>
			<span>
							
				<a style='background:none !important;' href='https://www.facebook.com/sharer/sharer.php?u=".get_permalink($post->ID)."' target='_blank'>
					<img src='".$directory_facebook."' alt='logo facebook' />
				</a>
				<a style='background:none !important;' href='http://twitter.com/share?url=".get_permalink($post->ID)."&text=".'"WEDOGOOD"'." target='_blank'>
					<img src='".$directory_twitter."' alt='logo twitter' />
				</a>
				<a style='background:none !important;' href='https://plus.google.com/share?url=".get_permalink($post->ID)."' target='_blank'>
					<img src='".$directory_google."' alt='logo google' />
				</a>
			</span>

		</div>
	".'[/yproject_lightbox]'); 
?>
<script type="text/javascript">
	document.getElementById('wdg-lightbox-vote_check_1').style.display = 'block';
</script>

