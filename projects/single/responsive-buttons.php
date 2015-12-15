<?php
global $campaign, $stylesheet_directory_uri;
$page_invest = get_page_by_path('investir');
$invest_url = get_permalink($page_invest->ID) . '?campaign_id=' . $campaign->ID . '&amp;invest_start=1#invest-start';
$invest_url_href = "#connexion";
$btn_invest_classes = 'wdg-button-lightbox-open';
$btn_invest_data_lightbox = 'connexion';
$btn_invest_text = ($campaign->funding_type() == 'fundingdonation') ? __('Soutenir', 'yproject') : __('Investir', 'yproject');
if (is_user_logged_in()) {
	$invest_url_href = $invest_url;
	$btn_invest_classes = '';
	$btn_invest_data_lightbox = '';
}
?>
<div class="project-responsive-buttons hidden">
	
	<?php
	$campaign_status = $campaign->campaign_status();
	switch ($campaign_status) {
		case 'vote': ?>
		<a href="#" class="button-action">
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/goodvote.png" alt="<?php _e('Voter', 'yproject'); ?>" title="<?php _e('Voter', 'yproject'); ?>" />
			<?php _e('Voter', 'yproject'); ?>
		</a>

		<?php
		break;
		case 'collecte':
		?>
		<a href="<?php echo $invest_url_href; ?>" class="button-action <?php echo $btn_invest_classes; ?>" data-lightbox="<?php echo $btn_invest_data_lightbox; ?>" data-redirect="<?php echo $invest_url; ?>">
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/sous.png" alt="<?php echo $btn_invest_text; ?>" title="<?php echo $btn_invest_text; ?>" />
		</a>
		<?php break;
	} ?>
	
	<a href="#" class="trigger-menu button-share" data-target="shareresponsive">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/hautparleur.png" alt="<?php _e('Partager', 'yproject'); ?>" title="<?php _e('Partager', 'yproject'); ?>" />
	</a>
	
	
	<div id="triggered-menu-shareresponsive" class="triggered-menu hidden">
		<span>
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>" target="_blank">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/facebook.jpg" alt="logo facebook" />
			</a>
			<a href="http://twitter.com/share?url=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>&text='WEDOGOOD'" target="_blank">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/twitter.jpg" alt="logo twitter" />
			</a>
			<a href="https://plus.google.com/share?url=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>" target="_blank">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/google+.jpg" alt="logo google" />
			</a>
		</span>
	</div>
</div>