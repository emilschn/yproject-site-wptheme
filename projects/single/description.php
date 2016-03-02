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
?>

<div class="project-description center">
	<div class="project-description-title separator-title">
		<span> 
			<?php _e('Pr&eacute;sentation', 'yproject'); ?>
		</span>
	</div>
    
	<?php if (is_user_logged_in() || $campaign->funding_type() == 'fundingdonation') : ?>
	
	<div class="project-description-item" data-content="description">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/projet<?php echo $file_complement; ?>.png" alt="project" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_gris_projet.png" alt="grey triangle"/>
		<div id="project-content-description" class="projects-desc-content">
			<h2><?php _e('Pitch', 'yproject'); ?></h2>
			<div class="zone-content">
				<?php 
				$description = html_entity_decode($campaign->description());
				echo apply_filters('the_content', $description);
				?>
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
		<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/sociale<?php echo $file_complement; ?>.png" alt="impacts" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_gris_projet.png" alt="grey triangle" />
		<div id="project-content-societal_challenge" class="projects-desc-content">
			<h2><?php _e('Impacts positifs', 'yproject'); ?></h2>
			<div class="zone-content">
				<?php 
				$societal_challenge = html_entity_decode($campaign->societal_challenge());
				echo apply_filters('the_content', $societal_challenge);
				?>
			</div>
			<?php if ($can_modify) { ?>
			<div class="zone-edit hidden">
				<?php wp_editor( $societal_challenge, 'wdg-input-societal_challenge', $editor_params ); ?>
			</div>
			<?php } ?>
		</div>
	</div>
	
	<?php if ($campaign_status != 'preview'): ?>
	<div class="project-description-item" data-content="added_value">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/economie<?php echo $file_complement; ?>.png" alt="strategy" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_gris_projet.png" alt="grey triangle"/>
		<div id="project-content-added_value" class="projects-desc-content">
			<h2><?php _e('Strat&eacute;gie', 'yproject'); ?></h2>
			<div class="zone-content">
				<?php 
				$added_value = html_entity_decode($campaign->added_value()); 
				echo apply_filters('the_content', $added_value);
				?>
			</div>
			<?php if ($can_modify) { ?>
			<div class="zone-edit hidden">
				<?php wp_editor( $added_value, 'wdg-input-added_value', $editor_params ); ?>
			</div>
			<?php } ?>
		</div>
	</div>
	
	<div class="project-description-item" data-content="economic_model">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/model<?php echo $file_complement; ?>.png" alt="model" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_gris_projet.png" alt="grey triangle"/>
		<div id="project-content-economic_model" class="projects-desc-content">
			<h2><?php _e('Donn&eacute;es financi&egrave;res', 'yproject'); ?></h2>
			<div class="zone-content">
				<?php 
				$economic_model = html_entity_decode($campaign->economic_model()); 
				echo apply_filters('the_content', $economic_model);
				?>
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
		<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/porteur<?php echo $file_complement; ?>.png" alt="team" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_gris_projet.png" alt="grey triangle"/>
		<div id="project-content-implementation" class="projects-desc-content">
			<h2><?php _e('&Eacute;quipe', 'yproject'); ?></h2>
			<div class="zone-content">
				<?php 
				$implementation = html_entity_decode($campaign->implementation()); 
				echo apply_filters('the_content', $implementation);
				?>
			</div>
			<?php if ($can_modify) { ?>
			<div class="zone-edit hidden">
				<?php wp_editor( $implementation, 'wdg-input-implementation', $editor_params ); ?>
			</div>
			<?php } ?>
		</div>
	</div>
    
	<?php if ($campaign_status != 'preview'): ?>
	<div class="project-description-item" data-content="statistics">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/statistiques<?php echo $file_complement; ?>.png" alt="stats" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_gris_projet.png" alt="grey triangle"/>
		<div id="project-content-statistics" class="projects-desc-content">
			<h2><?php _e('Statistiques', 'yproject'); ?></h2>
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
		<a href="#register" id="register" class="wdg-button-lightbox-open button" data-lightbox="register" data-redirect="<?php echo get_permalink(); ?>"><?php _e("Inscription", 'yproject'); ?></a>
		<a href="#connexion" id="connexion" class="wdg-button-lightbox-open button" data-lightbox="connexion" data-redirect="<?php echo get_permalink(); ?>"><?php _e("Connexion", 'yproject'); ?></a>
	</div>
	
	<?php endif; ?>
</div>