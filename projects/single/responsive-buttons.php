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

		<?php if(!is_user_logged_in()){ ?>
		<div id="vote-form-v3-button">
		    <a href="#connexion" class="button-action" data-lightbox="connexion" 
				data-redirect="<?php echo get_permalink($page_invest->ID) . $campaign_id_param; ?>&amp;invest_start=1#invest-start"
	 					>
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/goodvote.png" alt="<?php _e('Voter', 'yproject'); ?>" title="<?php _e('Voter', 'yproject'); ?>" />
			</a>
		</div>
		<?php }else	{
			$table_name = $wpdb->prefix . "ypcf_project_votes";
			$campaign_id=$campaign->ID;
			$user_id = wp_get_current_user()->ID;

			$hasvoted_results = $wpdb->get_results( 'SELECT id FROM '.$table_name.' WHERE post_id = '.$campaign_id.' AND user_id = '.$user_id );
			$has_voted = false;
			if ( !empty($hasvoted_results[0]->id) ) $has_voted = true;
			if (!$has_voted){ ?>
			<div id="vote-form-v3-button">
				<a href="#lightbox_voter" class="wdg-button-lightbox-open" data-lightbox="vote" 
					style=""
					id="vote-form-v3-link-responsive"
					>		
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/goodvote.png" alt="<?php _e('Voter', 'yproject'); ?>" title="<?php _e('Voter', 'yproject'); ?>" />
				</a>
			</div>
			<?php } 
		}
		?>


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
		<?php locate_template( 'projects/common/share-buttons.php', true, false ); ?>
	</div>
</div>