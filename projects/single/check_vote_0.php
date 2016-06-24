<?php
	global $campaign, $stylesheet_directory_uri;
	if (isset($_GET['campaign_id'])) {
		$post = get_post($_GET['campaign_id']);
		$campaign = atcf_get_campaign( $post );
	}

	echo do_shortcode('
		[yproject_lightbox id="vote_check_0"]'."
		<div id='Erreur'> 
			<span>Vous avez mal remplis le formulaire de vote, veuillez réssayer s'il vous plait !</span>
		</div>
	".'[/yproject_lightbox]'); 
?>
<script type="text/javascript">
	document.getElementById('wdg-lightbox-vote_check_0').style.display = 'block';
</script>

