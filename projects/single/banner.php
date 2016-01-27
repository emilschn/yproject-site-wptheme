<?php 
global $campaign, $current_user, $stylesheet_directory_uri, $can_modify;
$img_src = $campaign->get_header_picture_src();

$btn_follow_href = '#connexion';
$btn_follow_classes = 'wdg-button-lightbox-open';
$btn_follow_data_lightbox = 'connexion';
$btn_follow_text = __('Suivre', 'yproject');
$btn_follow_following = '0';
if (is_user_logged_in()) {
	$btn_follow_classes = 'update-follow';
	$btn_follow_data_lightbox = $campaign->ID;
	global $wpdb;
	$table_jcrois = $wpdb->prefix . "jycrois";
	$users = $wpdb->get_results( 'SELECT * FROM '.$table_jcrois.' WHERE campaign_id = '.$campaign->ID.' AND user_id='.$current_user->ID );
	$btn_follow_text = (!empty($users[0]->ID)) ? __('Suivi !', 'yproject') : __('Suivre', 'yproject');
	$btn_follow_following = (!empty($users[0]->ID)) ? '1' : '0';
	if (!empty($users[0]->ID)) { $btn_follow_href = '#'; }
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
		<span>'._('Forme juridique :', 'yproject').'</span>'.$current_organisation->organisation_legalform.'<br />
		<span>'._('Num&eacute;ro SIREN :', 'yproject').'</span>'.$current_organisation->organisation_idnumber.'<br />
		<span>'._('Code APE :', 'yproject').'</span>'.$current_organisation->organisation_ape.'<br />
		<span>'._('Capital social :', 'yproject').'</span>'.$current_organisation->organisation_capital.'<br /><br />
		</div>
		<div class="content align-left">
		<span>'._('Si&egrave;ge social :', 'yproject').'</span>'.$current_organisation->organisation_address.'<br />
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
<?php if ($can_modify): ?>
	<div id="wdg-move-picture-head" class="move-button"></div>
<?php endif; ?>
	
<div class="project-banner">
	<div class="project-banner-img" style="<?php echo $campaign->get_header_picture_position_style(); ?>">
		<?php if ($img_src != ''): ?>
		<img id="project-banner-src" src="<?php echo $img_src; ?>" alt="banner <?php echo $post->post_title; ?>" />
		<?php endif; ?>
	</div>
    
	<div class="project-banner-deco">
		<div class="center">
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/fond_projet3.png" alt="bg decoration" />
		</div>
	</div>

	<div class="project-banner-content">
		<div class="center">
			<div class="left">
				<h1><?php echo $campaign->data->post_title; ?></h1>
				<div class="subtitle"><?php echo $campaign->subtitle(); ?></div>
			</div>

			<div class="right">
				<?php locate_template( array("projects/single/timeline.php"), true ); ?>
				
				<div class="separator"></div>
				
				<?php locate_template( array("projects/common/progressbar.php"), true ); ?>
				
				<div class="project-banner-logos">
					<div class="project-banner-info-item">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/cible.png" alt="logo cible" />
						<span class="campaign-mobile-hidden"><?php _e('Objectif :', 'yproject'); ?>
							<b><?php echo $campaign->minimum_goal(true); ?></b>
							<?php if ($campaign->minimum_goal(false) < $campaign->goal(false)): ?>
								<?php _e('&agrave;', 'yproject'); ?> <b><?php echo $campaign->goal(true); ?></b>
							<?php endif; ?>
						</span>
						<span class="hidden"><b><?php echo $campaign->minimum_goal(true); ?></b></span>
					</div>

					<div class="project-banner-info-item">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/personnes.png" alt="logo personnes" />
						<?php $backers_count = $campaign->backers_count(); ?>
						<span class="campaign-mobile-hidden"><b><?php echo $backers_count; ?></b> <?php
							if ($backers_count > 1) {
								_e('personnes ont d&eacute;j&agrave;', 'yproject');
							} else {
								_e('personne a d&eacute;j&agrave;', 'yproject');
							}
							echo ' ';
							if ($campaign->funding_type() == 'fundingdonation') { 
								_e('soutenu ce projet', 'yproject');
							} else {
								_e('investi sur ce projet', 'yproject');
							}
						?></span>
						<span class="hidden"><b><?php echo $backers_count; ?></b></span>
					</div>

					<div class="project-banner-info-item">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/horloge.png" alt="logo horloge" />
						<span class="campaign-mobile-hidden"><?php echo $campaign->time_remaining_fullstr(); ?></span>
						<span class="hidden"><?php echo $campaign->time_remaining_str(); ?></span>
					</div>
				</div>
				
				<div class="project-banner-info-item align-center author-info" data-link-edit="<?php echo $page_edit_orga; ?>">
					<p><?php _e("Un projet port&eacute; par", 'yproject'); ?> <?php echo $owner_str; ?></p>
					<p>(<a href="#project-organisation" class="wdg-button-lightbox-open" data-lightbox="project-organisation"><?php _e('Voir les informations', 'yproject'); ?></a>)</p>
					<?php echo do_shortcode('[yproject_lightbox id="project-organisation"]'.$lightbox_content.'[/yproject_lightbox]'); ?>
				</div>
				
				<div class="separator"></div>
				
				<div class="project-banner-info-actions">
					<div>
						<a href="<?php echo $btn_follow_href; ?>" class="<?php echo $btn_follow_classes; ?>" data-lightbox="<?php echo $btn_follow_data_lightbox; ?>" data-textfollow="<?php _e('Suivre', 'yproject'); ?>" data-textfollowed="<?php _e('Suivi', 'yproject'); ?>" data-following="<?php echo $btn_follow_following; ?>">
							<?php echo $btn_follow_text; ?>
						</a>
					</div>
					<div>
						<a href="#" class="trigger-menu" data-target="share">
							<?php _e('Partager', 'yproject'); ?>
						</a>
					</div>
				</div>
			</div>

			<div id="triggered-menu-share" class="triggered-menu">
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
	</div>
</div>