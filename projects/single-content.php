<?php 
$images_folder=get_stylesheet_directory_uri().'/images/';
global $campaign, $client_context; 
$campaign_id_param = '?campaign_id=';
if (isset($_GET['campaign_id'])) {
	$campaign_id_param .= $_GET['campaign_id'];
	$post_campaign = get_post($_GET['campaign_id']);
	$campaign = atcf_get_campaign( $post_campaign );
} else  {
	$campaign_id_param .= $post_campaign->ID;
}
$vote_status = html_entity_decode($campaign->vote());
?>
<div id="projects-top-desc">
	<div id="projects-left-desc" class="left">		    
<?php
//*******************
//CACHE PROJECT CONTENT SUMMARY
$cache_content_summary = $WDG_cache_plugin->get_cache('project-content-summary-' . $post_campaign->ID, 2);
if ($cache_content_summary !== FALSE) { echo $cache_content_summary; }
else {
	ob_start();
?>
		<div id="project-summary-container">
			<div id="projects-summary"><?php echo html_entity_decode($campaign->summary()); ?></div>
		</div>
		<?php 
		$video_element = '';
		$img_src = '';
		//Si aucune vidéo n'est définie, ou si on est encore en mode preview, on affiche l'image
		if ($campaign->video() == '') {
			$attachments = get_posts( array(
				'post_type' => 'attachment',
				'post_parent' => $post_campaign->ID,
				'post_mime_type' => 'image'
				));
			$image_obj = '';
			//Si on en trouve bien une avec le titre "image_home" on prend celle-là
			foreach ($attachments as $attachment) {
				if ($attachment->post_title == 'image_home') $image_obj = wp_get_attachment_image_src($attachment->ID, "full");
			}
			//Sinon on prend la première image rattachée à l'article
			if ($image_obj == '' && count($attachments) > 0) $image_obj = wp_get_attachment_image_src($attachments[0]->ID, "full");
			if ($image_obj != '') $img_src = $image_obj[0];

		//Sinon on utilise l'objet vidéo fourni par wordpress
		} else {
			$video_element = wp_oembed_get($campaign->video(), array('width' => 580, 'height' => 325));
		}
		?>
		<div class="video-zone" <?php if ($img_src != '') { ?>style="background-image: url('<?php echo $img_src; ?>');"<?php } ?>>
			<?php echo $video_element; ?>
		</div>
		<div class="mobile-share only_on_mobile">
			<?php if ($vote_status != 'vote' && $vote_status != 'preparing') : ?>
    			<?php if ( is_user_logged_in() ) { 
				global $wpdb;
				$user_id = wp_get_current_user()->ID;
				$table_jcrois = $wpdb->prefix . "jycrois";
				$users = $wpdb->get_results( 'SELECT * FROM '.$table_jcrois.' WHERE campaign_id = '.$campaign->ID.' AND user_id='.$user_id );
				if ( !empty($users[0]->ID) ) { ?>
					<a class="jy-crois" href="javascript:WDGProjectPageFunctions.update_jycrois(0,<?php global $post;echo($post->ID); ?>,'<?php echo $stylesheet_directory_uri; ?>')">
					<div id="jy-crois-btn" style="background-image: url('<?php echo $stylesheet_directory_uri.'/images/jycrois_gris.png';?>')" class="stats_btn">
					<p id="jy-crois-txt"><p>    
					<p id="nb-jycrois"><?php echo $campaign->get_jycrois_nb(); ?></p>
					</div></a>
				<?php } else { ?>
					<a class="jy-crois" href="javascript:WDGProjectPageFunctions.update_jycrois(1,<?php global $post;echo($post->ID); ?>,'<?php echo $stylesheet_directory_uri; ?>')">
					<div id="jy-crois-btn" class="stats_btn">
					<p id="jy-crois-txt">J'y crois<p>
					<p id="nb-jycrois"><?php echo $campaign->get_jycrois_nb(); ?></p>
					</div></a>

				<?php }
			} else { ?>
				<a class="jy-crois wdg-button-lightbox-open" href="#connexion" data-lightbox="connexion">
				<div id="jy-crois-btn" class="stats_btn">
				<p id="jy-crois-txt">J'y crois<p>
				<p id="nb-jycrois"><?php echo $campaign->get_jycrois_nb(); ?></p>
				</div></a>
			<?php } ?>
			<?php endif; ?>
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>" target="_blank">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/facebook.jpg" alt="logo facebook" />
			</a>
			<a href="http://twitter.com/share?url=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>&text='WEDOGOOD'" target="_blank">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/twitter.jpg" alt="logo twitter" />
			</a>
			<a href="https://plus.google.com/share?url=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>" target="_blank">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/google+.jpg" alt="logo google" />
			</a>
		</div>
<?php
	$cache_content_summary = ob_get_contents();
	$WDG_cache_plugin->set_cache('project-content-summary-' . $post_campaign->ID, $cache_content_summary, 60*60*6, 1);
	ob_end_clean();
	echo $cache_content_summary;
}
//FIN CACHE PROJECT CONTENT SUMMARY
//*******************
?>
	</div>
    
        <div id="projects-right-desc" class="right" <?php echo 'data-link-project-settings="'.get_permalink(get_page_by_path('parametres-projet')->ID) . $campaign_id_param . $params_partial.'"'; ?>>
<?php
//*******************
//CACHE PROJECT CONTENT SUMMARY
$cache_content_about = $WDG_cache_plugin->get_cache('project-content-about-' . $post_campaign->ID, 1);
if ($cache_content_about !== FALSE) { echo $cache_content_about; }
else {
	ob_start();
?>
		<div id="project-owner">
			<?php 
			$owner_str = '';
			$api_project_id = BoppLibHelpers::get_api_project_id($post_campaign->ID);
			$current_organisations = BoppLib::get_project_organisations_by_role($api_project_id, BoppLibHelpers::$project_organisation_manager_role['slug']);
			if (count($current_organisations) > 0) {
				$current_organisation = $current_organisations[0];
                                $page_edit_orga = get_page_by_path('editer-une-organisation');
				$owner_str = '<div id="orga-edit" data-link-edit="'
                                        .get_permalink($page_edit_orga->ID) .'?orga_id='.$current_organisation->organisation_wpref
                                        .'">'
                                        . '<a href="#project-organisation" class="wdg-button-lightbox-open" data-lightbox="project-organisation">' . $current_organisation->organisation_name
                                        . '</a>'
                                        . '</div><br />';
				$owner_str .= '<div id="wdg-lightbox-project-organisation" class="wdg-lightbox hidden">
				    <div class="wdg-lightbox-click-catcher"></div>
				    <div class="wdg-lightbox-padder">
					<div class="wdg-lightbox-button-close">
					    <a href="#" class="button">X</a>
					</div>
					<div class="content align-center">'.$current_organisation->organisation_name.'</div>
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
					</div>
				    </div>
				</div>';
			} else {
//				UIHelpers::print_user_avatar($author_id);
				$author = get_userdata($post_campaign->post_author);
				$owner_str = '<div id="orga-edit" data-link-edit="'
                                        . get_permalink(get_page_by_path('parametres-projet')->ID) . $campaign_id_param . $params_partial
                                        . '">'
                                        .$author->user_firstname . ' ' . $author->user_lastname 
                                        .'</div><br />';
//				$owner_str .= '@' . $author->user_nickname;
			}
			?>
			<div id="project-owner-desc">
				<?php echo $owner_str; ?>
			</div>
		</div>

		<div id="project-about">
			<p>A propos de<p>
			<p><?php echo get_the_title(); ?></p>
		</div>
		<div id="project-map">
			<?php $cursor_top_position=get_post_meta($post_campaign->ID,'campaign_cursor_top_position',TRUE); ?>
			<?php $cursor_left_position=get_post_meta($post_campaign->ID,'campaign_cursor_left_position',TRUE); ?>
			<div id="map-cursor" style="<?php if($cursor_top_position!='') echo 'top:'.$cursor_top_position.';'; if($cursor_left_position!='') echo 'left:'.$cursor_left_position; ?> ">
				<p><?php echo $campaign->location(); ?></p>
			</div>
		</div>
<?php
	$cache_content_about = ob_get_contents();
	$WDG_cache_plugin->set_cache('project-content-about-' . $post_campaign->ID, $cache_content_about, 60*60*6, 1);
	ob_end_clean();
	echo $cache_content_about;
}
//FIN CACHE PROJECT CONTENT SUMMARY
//*******************
?>
			
		<?php if($can_modify){ ?>
			<div id="wdg-move-picture-location" class="move-button"></div>
		<?php } ?>
			
		<div class="project-rewards">
			<span>En &eacute;change de votre <?php echo $campaign->funding_type_vocabulary()['investor_action'];?></span>
		</div>
			
		<div class="project-rewards">
			<?php if ($campaign->funding_type() == 'fundingdevelopment'): ?>
			Vous recevrez une part de capital de cette entreprise.
			<?php elseif ($campaign->funding_type() == 'fundingproject'): ?>
			Vous recevrez une partie du chiffre d'affaires de ce projet.
			<?php endif; ?>
		</div>
		
		<div id="project-rewards-custom" class="project-rewards"><?php echo $campaign->rewards(); ?></div>
	</div>
</div>

<a name="description"></a>
<div id="project-description-title-padding"></div>

<div id="description_du_projet" class="part-title-separator mobile_hidden">
	<span class="part-title"> 
		Description du projet
	</span>
</div>

<?php if (is_user_logged_in() || $campaign->funding_type() == 'fundingdonation') {
    
    $file_complement = '';
    if (!empty($client_context)) { $file_complement .= '-' . $client_context; }
    
    /*$check = $check = yproject_check_user_warning(get_current_user_id());
    if(!$check){
        ob_start();
            locate_template('common/warning-lightbox.php',true);
            $content = ob_get_contents();
	ob_end_clean();
	echo do_shortcode('[yproject_lightbox id="warning"]' .$content . '[/yproject_lightbox]');
        echo "<div class='align-center'>Il est nécessaire pour continuer que vous prenniez connaisances des risques liés à l'investissement <a href='#warning' class='wdg-button-lightbox-open button' data-lightbox='warning'>ici</a></div></br>";

    } else {*/
	
	$editor_params = array( 
		'media_buttons' => true,
		'quicktags'     => false,
		'editor_height' => 500,
		'tinymce'       => array(
			'plugins' => 'paste, wplink, textcolor',
			'paste_remove_styles' => true
		)
	);
?>

<div class="indent">
	<div class="projects-desc-item">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>projet<?php echo $file_complement; ?>.png" alt="logo projet" data-content="description"/>
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png" alt="triangle projet"/>
		<div id="project-content-description" class="projects-desc-content">
			<h2>Pitch</h2>
			<div class="zone-content">
				<?php the_content(); ?>
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

	<a id="anchor-societal_challenge"></a>
	<div class="projects-desc-item">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>sociale<?php echo $file_complement; ?>.png" alt="logo social" data-content="societal_challenge" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png" alt="triangle gris" />
		<div id="project-content-societal_challenge" class="projects-desc-content">
			<h2>Impacts positifs</h2>
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
	
	<?php if ($vote_status != 'preview' || $can_modify): ?>
	<div class="projects-desc-item">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>economie<?php echo $file_complement; ?>.png" alt="logo economie" data-content="added_value" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png" alt="triangle gris"/>
		<div id="project-content-added_value" class="projects-desc-content">
			<h2>Strat&eacute;gie</h2>
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
	
	<div class="projects-desc-item">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>model<?php echo $file_complement; ?>.png" alt="logo modele" data-content="economic_model" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png" alt="triangle gris"/>
		<div id="project-content-economic_model" class="projects-desc-content">
			<h2>Donn&eacute;es financi&egrave;res</h2>
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
    
	<div class="projects-desc-item">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>porteur<?php echo $file_complement; ?>.png" alt="logo porteur" data-content="implementation"/>
		<img class="vertical-align-middle grey-triangle"src="<?php echo $images_folder;?>triangle_gris_projet.png" alt="triangle gris"/>
		<div id="project-content-implementation" class="projects-desc-content">
			<h2>&Eacute;quipe</h2>
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
	
<?php
//    }
} else {
	$page_connexion_register = get_page_by_path('register');
	$page_connexion = get_page_by_path('connexion');
	?>

	<div class="align-center">
		<p>
		    Afin de r&eacute;pondre aux recommandations des autorit&eacute;s financi&egrave;res sur la limite du risque repr&eacute;sent&eacute; par l&apos;investissement participatif,<br />
		    vous devez &ecirc;tre inscrit et connect&eacute; pour acc&eacute;der à la totalit&eacute; du projet.
		</p>
		<a href="#register" id="register" class="wdg-button-lightbox-open button" data-lightbox="register">Inscription</a>
		<a href="#connexion" id="connexion" class="wdg-button-lightbox-open button" data-lightbox="connexion" data-redirect="<?php echo get_permalink() . '#description'; ?>">Connexion</a><br /><br />
	</div>
<?php } ?>
	
</div>

<div class="only_on_mobile">
	<?php if ($vote_status == 'collecte'): ?>
	<div class="reward-zone">
		<div class="project-rewards">
			<span>En &eacute;change de votre <?php echo $campaign->funding_type_vocabulary()['investor_action'];?></span>
		</div>
			
		<div class="project-rewards">
			<?php if ($campaign->funding_type() == 'fundingdevelopment'): ?>
			Vous recevrez une part de capital de cette entreprise.
			<?php elseif ($campaign->funding_type() == 'fundingproject') : ?>
			Vous recevrez une partie du chiffre d'affaires de ce projet.
			<?php endif; ?>
		</div>
		
		<?php if ($campaign->rewards() != ""): ?>
		<div id="project-rewards-custom" class="project-rewards"><?php echo $campaign->rewards(); ?></div>
		<?php endif; ?>
		
		<div id="invest-button">
			<?php $page_invest = get_page_by_path('investir'); ?>
			<a href="<?php echo get_permalink($page_invest->ID) . $campaign_id_param; ?>&amp;invest_start=1" class="description-discover">
                            <?php if (($campaign->funding_type() == 'fundingdevelopment')||($campaign->funding_type() == 'fundingproject')): ?>
                            Investir sur ce projet
                            <?php elseif ($campaign->funding_type() == 'fundingdonation') : ?>
                            Soutenir ce projet
                            <?php endif; ?>
                            </a>
		</div>
	</div>
	<?php endif; ?>
	<div class="part-title-separator">
		<span class="part-title"> 
			Partager
		</span>
	</div>
	<div class="mobile-share">
		<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>" target="_blank">
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/facebook.jpg" alt="logo facebook" />
		</a>
		<a href="http://twitter.com/share?url=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>&text='WEDOGOOD'" target="_blank">
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/twitter.jpg" alt="logo twitter" />
		</a>
		<a href="https://plus.google.com/share?url=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>" target="_blank">
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/google+.jpg" alt="logo google" />
		</a>
	</div>
	<ul class="secondary-menu only_on_mobile">
		<li><a href="<?php echo $news_link; ?>" <?php if($current_page==$news_link) echo 'class="current"'; ?>>Actualit&eacute;<?php echo $nb_cat; ?></a></li>

		<?php if ( is_user_logged_in() ): ?> 
			<?php $forum = get_page_by_path('forum');
				$forum_link = get_permalink($forum->ID).$campaign_id_param;
				$forum_link_2 = site_url('forums/forum') . '/' . $campaign_id . '-2/'; 
			?>
			<li><a href="<?php echo $forum_link; ?>" <?php if($current_page==$forum_link || $current_page==$forum_link_2) echo 'class="current"'; ?>>Forum</a></li>

		<?php else: ?>
			<li><a href="#connexion" id="forum" class="wdg-button-lightbox-open description-discover" data-lightbox="connexion">Forum</a></li>
		<?php endif; ?>
		<?php if ($show_stat_button) { ?>
		<li><a href="<?php echo $stats_link; ?>" <?php if($current_page==$stats_link) echo 'class="current"'; ?>>Statistiques</a></li>
		<?php } ?>
	</ul>
</div>

</div>
</div>