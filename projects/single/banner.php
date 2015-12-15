<?php 
global $campaign, $stylesheet_directory_uri;
$img_src = $campaign->get_header_picture_src();

$owner_str = '';
$lightbox_content = '';
$api_project_id = BoppLibHelpers::get_api_project_id($campaign->ID);
$current_organisations = BoppLib::get_project_organisations_by_role($api_project_id, BoppLibHelpers::$project_organisation_manager_role['slug']);
if (count($current_organisations) > 0) {
	$current_organisation = $current_organisations[0];
	$page_edit_orga = get_page_by_path('editer-une-organisation');
	
	$owner_str = $current_organisation->organisation_name;
	$lightbox_content = '<div class="content align-center">'.$current_organisation->organisation_name.'</div>
		<div class="content align-left">
		<span>Forme juridique :</span>'.$current_organisation->organisation_legalform.'<br />
		<span>Num&eacute;ro SIREN :</span>'.$current_organisation->organisation_idnumber.'<br />
		<span>Code APE :</span>'.$current_organisation->organisation_ape.'<br />
		<span>Capital social :</span>'.$current_organisation->organisation_capital.'<br /><br />
		</div>
		<div class="content align-left">
		<span>Si&egrave;ge social :</span>'.$current_organisation->organisation_address.'<br />
		<span></span>'.$current_organisation->organisation_postalcode.' '.$current_organisation->organisation_city.'<br />
		<span></span>'.$current_organisation->organisation_country.'<br />
		</div>';
} else {
	$author = get_userdata($campaign->data->post_author);
	$owner_str = $author->user_firstname . ' ' . $author->user_lastname;
	if ($owner_str == ' ') { $owner_str = $author->user_login; }
}
?>

<div class="project-banner">
	<div class="project-banner-img" style="<?php echo $campaign->get_header_picture_position_style(); ?>">
		<?php if ($img_src != ''): ?>
		<img id="project-banner-src" src="<?php echo $img_src; ?>" alt="banner <?php echo $post->post_title; ?>" />
		<?php endif; ?>
	</div>
    
	<div class="project-banner-deco">
		<div class="center">
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/fond_projet.png" alt="bg decoration" />
		</div>
	</div>

	<div class="project-banner-content">
		<div class="center">
			<div class="left">
				<h1><?php echo $campaign->data->post_title; ?></h1>
				<div class="subtitle"><?php echo $campaign->subtitle(); ?>aa</div>
			</div>

			<div class="right">
				<div class="separator"></div>
				
				<?php locate_template( array("projects/common/progressbar.php"), true ); ?>
				
				<div class="project-banner-logos">
					<div class="project-banner-info-item">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/cible.png" alt="logo cible" />
						<span class="campaign-mobile-hidden"><?php 
							echo __('Objectif : ', 'yproject') . $campaign->minimum_goal(true);
							if ($campaign->minimum_goal(false) < $campaign->goal(false)) {
							echo __(' &agrave; ', 'yproject') . $campaign->goal(true);
							}
						?></span>
						<span class="hidden"><?php echo $campaign->minimum_goal(true); ?></span>
					</div>

					<div class="project-banner-info-item">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/personnes.png" alt="logo personnes" />
						<?php $backers_count = $campaign->backers_count(); ?>
						<span class="campaign-mobile-hidden"><?php
							echo $backers_count . ' ';
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
						<span class="hidden"><?php echo $backers_count; ?></span>
					</div>

					<div class="project-banner-info-item">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/horloge.png" alt="logo horloge" />
						<span class="campaign-mobile-hidden"><?php echo $campaign->time_remaining_fullstr(); ?></span>
						<span class="hidden"><?php echo $campaign->time_remaining_str(); ?></span>
					</div>
				</div>
				
				<div class="separator"></div>
				
				<div class="project-banner-info-item align-center author-info">
					<p><?php _e("Un projet port&eacute; par"); ?> <?php echo $owner_str; ?></p>
					<p>(<a href="#project-organisation" class="wdg-button-lightbox-open" data-lightbox="project-organisation"><?php _e('Voir les informations', 'yproject'); ?></a>)</p>
					<?php echo do_shortcode('[yproject_lightbox id="project-organisation"]'.$lightbox_content.'[/yproject_lightbox]'); ?>
				</div>
			</div>
		</div>
	</div>
</div>