<?php
global $campaign, $can_modify, $stylesheet_directory_uri;
$campaign_status = $campaign->campaign_status();
$file_complement = '';
if (!empty($client_context)) { $file_complement .= '-' . $client_context; }
if ($can_modify) { 
	$editor_params = array( 
		'media_buttons' => true,
		'quicktags'     => false,
		'editor_height' => 500,
		'tinymce'       => array(
			'plugins' => 'paste, wplink, textcolor',
			'paste_remove_styles' => true
		)
	);
}
$description = html_entity_decode($campaign->description());
$description_content = apply_filters('the_content', $description);
$societal_challenge = html_entity_decode($campaign->societal_challenge());
$societal_challenge_content = apply_filters('the_content', $societal_challenge);
$added_value = html_entity_decode($campaign->added_value()); 
$added_value_content = apply_filters('the_content', $added_value);
$economic_model = html_entity_decode($campaign->economic_model()); 
$economic_model_content = apply_filters('the_content', $economic_model);
$implementation = html_entity_decode($campaign->implementation()); 
$implementation_content = apply_filters('the_content', $implementation);
?>

<div class="project-description">
	
	<h2 class="standard">/ <?php _e('Pr&eacute;sentation', 'yproject'); ?> /</h2>
	
	<div class="center">
    
	<?php if (is_user_logged_in() || $campaign->funding_type() == 'fundingdonation') : ?>

		<div class="project-description-item" data-content="description">
			<div class="projects-desc-content-picto">
				<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/description-pitch.png" alt="project" />
			</div>
			<div id="project-content-description" class="projects-desc-content">
				<h3><?php _e('Pitch', 'yproject'); ?></h3>
				<div class="zone-content">
					<?php echo $description_content; ?>
				</div>
				<?php if ($can_modify) { ?>
				<div class="zone-edit hidden">
					<?php 
					$editor_description_content = str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', $campaign->data->post_content ));
					global $post, $post_id; $post_ID = $post = 0;
					wp_editor( $editor_description_content, 'wdg-input-description', $editor_params );
					?>
				</div>
				<?php } ?>
			</div>
		</div>

		<div class="project-description-item" data-content="societal_challenge">
			<div class="projects-desc-content-picto">
				<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/description-impacts.png" alt="impacts" />
			</div>
			<div id="project-content-societal_challenge" class="projects-desc-content">
				<h3><?php _e('Impacts positifs', 'yproject'); ?></h3>
				<div class="zone-content">
					<?php echo $societal_challenge_content; ?>
				</div>
				<?php if ($can_modify) { ?>
				<div class="zone-edit hidden">
					<?php wp_editor( $societal_challenge, 'wdg-input-societal_challenge', $editor_params ); ?>
				</div>
				<?php } ?>
			</div>
		</div>

		<?php if ($campaign_status != ATCF_Campaign::$campaign_status_preview): ?>
		<div class="project-description-item" data-content="added_value">
			<div class="projects-desc-content-picto">
				<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/description-strategie.png" alt="strategy" />
			</div>
			<div id="project-content-added_value" class="projects-desc-content">
				<h3><?php _e('Strat&eacute;gie', 'yproject'); ?></h3>
				<div class="zone-content">
					<?php echo $added_value_content; ?>
				</div>
				<?php if ($can_modify) { ?>
				<div class="zone-edit hidden">
					<?php wp_editor( $added_value, 'wdg-input-added_value', $editor_params ); ?>
				</div>
				<?php } ?>
			</div>
		</div>

		<div class="project-description-item" data-content="economic_model">
			<div id="top-economic_model" class="projects-desc-content-picto">
				<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/description-financier.png" alt="model" />
			</div>
			<div id="project-content-economic_model" class="projects-desc-content">
				<h3><?php _e('Donn&eacute;es financi&egrave;res', 'yproject'); ?></h3>
				<div class="zone-content">
					<?php echo $economic_model_content; ?>
				</div>
				<?php if ($can_modify) { ?>
				<div class="zone-edit hidden">
					<?php wp_editor( $economic_model, 'wdg-input-economic_model', $editor_params ); ?>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php endif; ?>

		<div class="project-description-item" data-content="implementation">
			<div class="projects-desc-content-picto">
				<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/description-equipe.png" alt="team" width="74px"/>
			</div>
			<div id="project-content-implementation" class="projects-desc-content">
				<h3><?php _e('&Eacute;quipe', 'yproject'); ?></h3>
				<div class="zone-content">
					<?php echo $implementation_content; ?>
				</div>
				<?php if ($can_modify) { ?>
				<div class="zone-edit hidden">
					<?php wp_editor( $implementation, 'wdg-input-implementation', $editor_params ); ?>
				</div>
				<?php } ?>
			</div>
		</div>

		<?php if ($campaign_status != ATCF_Campaign::$campaign_status_preparing
				&& $campaign_status != ATCF_Campaign::$campaign_status_preview
				&& $campaign_status != ATCF_Campaign::$campaign_status_vote): ?>
		<div class="project-description-item" data-content="statistics">
			<div class="projects-desc-content-picto">
				<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/description-statistiques.png" alt="stats" />
			</div>
			<div id="project-content-statistics" class="projects-desc-content">
				<h3><?php _e('Statistiques', 'yproject'); ?></h3>
				<div class="zone-content">
					<p><?php _e('Les statistiques de vote et d&apos;investissement du projet', 'yproject'); ?></p>
					<?php locate_template( array("projects/common/stats-public.php"), true ); ?>
				</div>
			</div>
		</div>
		<?php endif; ?>

	<?php else: ?>

		<div class="align-center">
			<p>
				<?php _e("Afin de r&eacute;pondre aux recommandations des autorit&eacute;s financi&egrave;res sur la pr&eacute;vention du risque repr&eacute;sent&eacute; par l&apos;investissement participatif,", 'yproject'); ?><br />
				<?php _e("vous devez &ecirc;tre inscrit et connect&eacute; pour acc&eacute;der à la totalit&eacute; du projet.", 'yproject'); ?>
			</p>
			<a href="#register" id="register" class="wdg-button-lightbox-open button red" data-lightbox="register" data-redirect="<?php echo get_permalink(); ?>"><?php _e("Inscription", 'yproject'); ?></a>
			<a href="#connexion" id="connexion" class="wdg-button-lightbox-open button red" data-lightbox="connexion" data-redirect="<?php echo get_permalink(); ?>"><?php _e("Connexion", 'yproject'); ?></a>
		</div>

	<?php endif; ?>
	</div>
</div>