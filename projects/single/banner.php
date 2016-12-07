<?php 
global $campaign, $current_user, $stylesheet_directory_uri, $can_modify;
$video_element = '';
$img_src = '';
//Si aucune vidéo n'est définie, on affiche l'image
if ($campaign->video() == '') {
	$img_src = $campaign->get_home_picture_src();

//Sinon on utilise l'objet vidéo fourni par wordpress
} else {
	$video_element = wp_oembed_get($campaign->video(), array('height' => 400));
}
$campaign_status = $campaign->campaign_status();
$campaign_categories_str = $campaign->get_categories_str();

$btn_follow_href = '#connexion';
$btn_follow_classes = 'wdg-button-lightbox-open';
$btn_follow_data_lightbox = 'connexion';
$btn_follow_text = __('Suivre', 'yproject');
$btn_follow_following = '0';
$has_voted = false;
if (is_user_logged_in()) {
	$btn_follow_classes = 'update-follow';
	$btn_follow_data_lightbox = $campaign->ID;
	global $wpdb;
	$table_jcrois = $wpdb->prefix . "jycrois";
	$users = $wpdb->get_results( 'SELECT * FROM '.$table_jcrois.' WHERE campaign_id = '.$campaign->ID.' AND user_id='.$current_user->ID );
	$btn_follow_text = (!empty($users[0]->ID)) ? __('Suivi !', 'yproject') : __('Suivre', 'yproject');
	$btn_follow_following = (!empty($users[0]->ID)) ? '1' : '0';
	if ($btn_follow_following == '1') { $btn_follow_classes .= ' btn-followed'; }
	if (!empty($users[0]->ID)) { $btn_follow_href = '#'; }
	
	if ($campaign_status == "vote") {
		$table_name = $wpdb->prefix . "ypcf_project_votes";
		$hasvoted_results = $wpdb->get_results( 'SELECT id FROM '.$table_name.' WHERE post_id = '.$campaign->ID.'. AND user_id = '.$current_user->ID );
		if ( !empty($hasvoted_results[0]->id) ) $has_voted = true;
	}
}

$owner_str = '';
$lightbox_content = '';
$api_project_id = BoppLibHelpers::get_api_project_id($campaign->ID);
$current_organisations = BoppLib::get_project_organisations_by_role($api_project_id, BoppLibHelpers::$project_organisation_manager_role['slug']);
if (count($current_organisations) > 0) {
	$current_organisation = $current_organisations[0];
	$page_edit_orga = get_permalink(get_page_by_path('editer-une-organisation')->ID) .'?orga_id='.$current_organisation->organisation_wpref;
	
	$owner_str = $current_organisation->organisation_name;
	$lightbox_content = '<div class="content align-center">'.$current_organisation->organisation_name.'</div>
		<div class="content align-left">
		<span>'.__('Forme juridique :', 'yproject').'</span>'.$current_organisation->organisation_legalform.'<br />
		<span>'.__('Num&eacute;ro SIREN :', 'yproject').'</span>'.$current_organisation->organisation_idnumber.'<br />
		<span>'.__('Code APE :', 'yproject').'</span>'.$current_organisation->organisation_ape.'<br />
		<span>'.__('Capital social :', 'yproject').'</span>'.$current_organisation->organisation_capital.'<br /><br />
		</div>
		<div class="content align-left">
		<span>'.__('Si&egrave;ge social :', 'yproject').'</span>'.$current_organisation->organisation_address.'<br />
		<span></span>'.$current_organisation->organisation_postalcode.' '.$current_organisation->organisation_city.'<br />
		<span></span>'.$current_organisation->organisation_country.'<br />
		</div>';
} else {
	$page_edit_orga = get_permalink(get_page_by_path('parametres-projet')->ID) . '?campaign_id=' . $campaign->ID;
	$author = get_userdata($campaign->data->post_author);
	$owner_str = $author->user_firstname . ' ' . $author->user_lastname;
	if ($owner_str == ' ') { $owner_str = $author->user_login; }
}
?>
	
<div class="project-banner">
	<div class="project-banner-title padder">
		<?php if (!empty($lang_list)): ?>
			<form method="GET" action="<?php the_permalink(); ?>">
				<select name="lang">
					<option value="fr_FR" <?php selected($current_lang , "fr_FR"); ?>>Fran&ccedil;ais</option>
					<?php foreach ($lang_list as $lang): ?>
					<option value="<?php echo $lang; ?>" <?php selected($current_lang, $lang); ?>><?php echo $language_list[$lang]; ?></option>
					<?php endforeach; ?>
				</select>
			</form>
		<?php endif; ?>
		
		<h1><?php echo $campaign->data->post_title; ?></h1>
		
		<div class="project-banner-info-item align-center author-info" data-link-edit="<?php echo $page_edit_orga; ?>">
			<p>
				<?php _e("Un projet port&eacute; par", 'yproject'); ?> <a href="#project-organisation" class="wdg-button-lightbox-open" data-lightbox="project-organisation"><?php echo $owner_str; ?></a>
			</p>
			<?php echo do_shortcode('[yproject_lightbox id="project-organisation"]'.$lightbox_content.'[/yproject_lightbox]'); ?>
		</div>
	</div>

	<div class="project-banner-content">
		<div class="padder">
			
			<div class="banner-half left">
				<div id="project-banner-picture">
					<?php if ($img_src != ''): ?>
					<img id="project-banner-src" src="<?php echo $img_src; ?>" alt="banner <?php echo $post->post_title; ?>" />
					<?php else: ?>
					<?php echo $video_element; ?>
					<?php endif; ?>
				</div>
				<input type="hidden" id="url_image_link" href="<?php echo $campaign->get_home_picture_src(); ?>" />
				<input type="hidden" id="url_video_link" href="<?php echo $campaign->video(); ?>" />
			</div>
			
			<div class="banner-half right">
				
				<div class="project-banner-info-actions">
					<div class="impacts-container" id="impacts-<?php echo $project_id ?>">
						<?php if (strpos($campaign_categories_str, 'environnemental') != FALSE): ?>
						<span class="impact-logo impact-ecologic" id="impact-ecologic-<?php echo $project_id ?>"><p>ecl</p></span>
						<?php endif; ?>
						<?php if (strpos($campaign_categories_str, 'social') != FALSE): ?>
						<span class="impact-logo impact-social" id="impact-social-<?php echo $project_id ?>"><p>soc</p></span>
						<?php endif; ?>
						<?php if (strpos($campaign_categories_str, 'economique') != FALSE): ?>
						<span class="impact-logo impact-economic" id="impact-economic-<?php echo $project_id ?>"><p>ecn</p></span>
						<?php endif; ?>
					</div>

					<a href="<?php echo $btn_follow_href; ?>" class="button blue <?php echo $btn_follow_classes; ?>" data-lightbox="<?php echo $btn_follow_data_lightbox; ?>" data-textfollow="<?php _e('Suivre', 'yproject'); ?>" data-textfollowed="<?php _e('Suivi !', 'yproject'); ?>" data-following="<?php echo $btn_follow_following; ?>">
						<span><?php echo $btn_follow_text; ?></span>
					</a>
					<a href="#" class="button blue trigger-menu" data-target="share">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/partage/picto-partage.png" alt="Partager" />
					</a>
				</div>
				
				<div class="project-pitch-text"><?php echo html_entity_decode($campaign->summary()); ?></div>
				
				<?php locate_template( array("projects/common/progressbar.php"), true ); ?>
				
				
				<?php // cas d'un projet en cours de vote ?>
				<?php if ($campaign_status == ATCF_Campaign::$campaign_status_vote): ?>
					<?php $nbvoters = $campaign->nb_voters(); ?>
				
					<div class="left">
						<?php
						$number = $nbvoters;
						$text = __("votant", 'yproject');
						if ($nbvoters == 0) {
							$number = __("aucun", 'yproject');
						} elseif ($nbvoters > 1) {
							$text = __("votants", 'yproject');
						}
						?>
						<span><?php echo $number; ?></span><br />
						<span><?php echo $text; ?></span>
					</div>
					<div class="left bordered">
						<span><?php echo $campaign->minimum_goal(true); ?></span><br />
						<span><?php _e('Objectif', 'yproject'); ?></span>
					</div>
					<div class="left">
						<?php
						$time_remaining_str = $campaign->time_remaining_str();
						if ($time_remaining_str != '-'):
							$time_remaining_str_split = explode('-', $time_remaining_str);
							$time_remaining_str = $time_remaining_str_split[1] . ' ';
							$time_remaining_str_unit = $time_remaining_str_split[0];
							switch ($time_remaining_str_split[0]) {
								case 'J': $time_remaining_str .= 'jours'; break;
								case 'H': $time_remaining_str .= 'heures'; break;
								case 'M': $time_remaining_str .= 'minutes'; break;
							}
						?>
							<span><?php echo $time_remaining_str; ?></span><br />
							<?php if ($time_remaining_str_unit == 'J'): ?>
							<span><?php _e('Restants', 'yproject'); ?></span>
							<?php else: ?>
							<span><?php _e('Restantes', 'yproject'); ?></span>
							<?php endif; ?>
						<?php
						else:
						?>
							<span><?php echo $time_remaining_str; ?></span>
						<?php	
						endif;
						?>
					</div>
				
				
					<div class="clear">
						
						<?php if ($campaign->time_remaining_str() != '-'): ?>
						<?php if (!is_user_logged_in()): ?>
							<a href="#connexion" class="button red wdg-button-lightbox-open" data-lightbox="connexion" 
								data-redirect="<?php echo get_permalink($page_invest->ID) . $campaign_id_param; ?>&amp;invest_start=1#invest-start">
								<?php _e('Voter', 'yproject'); ?>
							</a>

						<?php elseif ($has_voted): ?>
							<div style="-webkit-filter: grayscale(100%); text-transform: uppercase;">
								<?php _e('Merci pour votre vote !', 'yproject'); ?>
							</div>

						<?php else: ?>
							<a href="#lightbox_voter" class="button red wdg-button-lightbox-open" data-lightbox="vote">
								<?php _e('Voter', 'yproject'); ?>
							</a>
						<?php endif; ?>

						<?php endif; ?>
						
					</div>
				
				
				<?php // cas d'un projet en financement ?>
				<?php elseif($campaign_status == ATCF_Campaign::$campaign_status_collecte): ?>
					<?php
					$nbinvestors = $campaign->backers_count();
					$page_invest = get_page_by_path('investir');
					$campaign_id_param = '?campaign_id=' . $campaign->ID;
					$invest_url = get_permalink($page_invest->ID) . $campaign_id_param . '&amp;invest_start=1';
					$invest_url_href = "#connexion";
					$btn_invest_classes = 'button red wdg-button-lightbox-open';
					$btn_invest_data_lightbox = 'connexion';
					$btn_invest_text = ($campaign->funding_type() == 'fundingdonation') ? __('Soutenir', 'yproject') : __('Investir', 'yproject');
					if (is_user_logged_in()) {
						$invest_url_href = $invest_url;
						$btn_invest_classes = 'button red';
						$btn_invest_data_lightbox = '';
					}
					?>
				
					<div class="left">
						<?php
						$number = $nbinvestors;
						$text = __("investisseur", 'yproject');
						if ($nbinvestors == 0) {
							$number = __("aucun", 'yproject');
						} elseif ($nbinvestors > 1) {
							$text = __("investisseurs", 'yproject');
						}
						?>
						<span><?php echo $number; ?></span><br />
						<span><?php echo $text; ?></span>
					</div>
					<div class="left bordered">
						<span><?php echo $campaign->minimum_goal(true); ?></span><br />
						<span><?php _e('Objectif', 'yproject'); ?></span>
					</div>
					<div class="left">
						<?php
						$time_remaining_str = $campaign->time_remaining_str();
						if ($time_remaining_str != '-'):
							$time_remaining_str_split = explode('-', $time_remaining_str);
							$time_remaining_str = $time_remaining_str_split[1] . ' ';
							$time_remaining_str_unit = $time_remaining_str_split[0];
							switch ($time_remaining_str_split[0]) {
								case 'J': $time_remaining_str .= 'jours'; break;
								case 'H': $time_remaining_str .= 'heures'; break;
								case 'M': $time_remaining_str .= 'minutes'; break;
							}
						?>
							<span><?php echo $time_remaining_str; ?></span><br />
							<?php if ($time_remaining_str_unit == 'J'): ?>
							<span><?php _e('Restants', 'yproject'); ?></span>
							<?php else: ?>
							<span><?php _e('Restantes', 'yproject'); ?></span>
							<?php endif; ?>
						<?php
						else:
						?>
							<span><?php echo $time_remaining_str; ?></span>
						<?php	
						endif;
						?>
					</div>

					<a href="<?php echo $invest_url_href; ?>" class="<?php echo $btn_invest_classes; ?>" data-lightbox="<?php echo $btn_invest_data_lightbox; ?>" data-redirect="<?php echo $invest_url; ?>">
						<?php echo $btn_invest_text; ?>
					</a>
				
				
				<?php // cas d'un projet terminé ?>
				<?php else: ?>
				
				<?php endif; ?>
				
			</div>

		</div>
	</div>
	<div class="clear padder"><div class="subtitle"><?php echo $campaign->subtitle(); ?></div></div>
</div>
	
<div class="padder">
	<div id="triggered-menu-share" class="triggered-menu">
		<?php locate_template( 'projects/common/share-buttons.php', true, false ); ?>
	</div>
</div>