<?php 
global $campaign, $current_user, $stylesheet_directory_uri, $can_modify, $language_list;
$video_element = '';
$img_src = '';
//Si aucune vidéo n'est définie, on affiche l'image
if ($campaign->video() == '') {
	$img_src = $campaign->get_home_picture_src();

//Sinon on utilise l'objet vidéo fourni par wordpress
} else {
	$video_element = wp_oembed_get( $campaign->video(), array( 'height' => 400 ) );
}
$campaign_status = $campaign->campaign_status();
$campaign_categories_str = $campaign->get_categories_str();

$btn_follow_href = home_url( '/connexion/' ) . '?source=project';
$btn_follow_classes = 'wdg-button-lightbox-open';
$btn_follow_data_lightbox = 'connexion';
$btn_follow_text = __('Suivre', 'yproject');
$btn_follow_following = '0';
$has_voted = false;
if (is_user_logged_in()) {
	$WDGUser_current = WDGUser::current();
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
		$has_voted = $WDGUser_current->has_voted_on_campaign( $campaign->ID );
	}
}

$owner_str = '';
$lightbox_title = '';
$lightbox_content = '';
$current_organization = $campaign->get_organization();
if (!empty($current_organization)) {
	$wdg_organization = new WDGOrganization( $current_organization->wpref, $current_organization );
	
	$lightbox_title = $wdg_organization->get_name();
	$owner_str = $wdg_organization->get_name();
	$lightbox_content = '<div class="lightbox-organization-separator"></div>
		<div class="content align-left"><br />
		<span>'.__('Forme juridique : ', 'yproject').'</span>'.$wdg_organization->get_legalform().'<br />
		<span>'.__('Num&eacute;ro SIRET : ', 'yproject').'</span>'.$wdg_organization->get_idnumber().'<br />
		<span>'.__('Code APE : ', 'yproject').'</span>'.$wdg_organization->get_ape().'<br />';
	if ( $wdg_organization->get_vat() != "" && $wdg_organization->get_vat() != '---' ) {
		$lightbox_content .= '<span>'.__('Num&eacute;ro de TVA : ', 'yproject').'</span>'.$wdg_organization->get_vat().'<br />';
	}
	$lightbox_content .= 
		'<span>'.__('Capital social : ', 'yproject').'</span>'.$wdg_organization->get_capital().' &euro;'.'<br /><br />
		</div>
		<div class="lightbox-organization-separator"></div>'.'<br/>
		<div class="content align-left">
		<span>'.__('Si&egrave;ge social : ', 'yproject').'<br/>'.'</span>'.$wdg_organization->get_full_address_str().'<br />
		<span></span>'.$wdg_organization->get_postal_code().' '.$wdg_organization->get_city().'<br />
		<span></span>'.$wdg_organization->get_nationality().'<br />
		</div>';
} else {
	$author = get_userdata($campaign->data->post_author);
	$owner_str = $author->user_firstname . ' ' . $author->user_lastname;
	if ($owner_str == ' ') { $owner_str = $author->user_login; }
}

$current_lang = get_locale();
$campaign->set_current_lang($current_lang);
$lang_list = $campaign->get_lang_list();
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
		
		<div class="project-banner-info-item align-center author-info">
			<p>
				<?php _e("Un projet port&eacute; par", 'yproject'); ?> <a href="#project-organization" class="wdg-button-lightbox-open" data-lightbox="project-organization"><?php echo $owner_str; ?></a>
			</p>
			<?php echo do_shortcode('[yproject_lightbox_cornered id="project-organization" title="'.$lightbox_title.'"]'.$lightbox_content.'[/yproject_lightbox_cornered]'); ?>
		</div>
	</div>

	<div class="project-banner-content">
		<div class="padder">
			
			<div class="banner-half left">
				<div id="project-banner-picture">
					<?php if ($img_src != ''): ?>
						<img id="project-banner-src" src="<?php echo $img_src; ?>" alt="banner <?php echo $post->post_title; ?>" />
					<?php else: ?>
						<div class="video-element"><?php echo $video_element; ?></div>
					<?php endif; ?>
				</div>
				<input type="hidden" id="url_image_link" value="<?php echo $campaign->get_home_picture_src(); ?>" />
				<input type="hidden" id="url_video_link" value="<?php echo $campaign->video(); ?>" />
			</div>
			
			<div class="banner-half right">
				
				<div class="project-banner-info-actions">
					<div class="impacts-container" id="impacts-<?php echo $post->ID; ?>">
						<?php if (strpos($campaign_categories_str, 'environnemental') !== FALSE): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/common/impact-env.png" alt="impact environnemental" width="42" height="42" class="impact-logo" /><span class="info-bulle invisible"><?php _e('impact environnemental', 'yproject')?></span>
						<?php endif; ?>
						<?php if (strpos($campaign_categories_str, 'social') !== FALSE): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/common/impact-social.png" alt="impact social" width="42" height="42" class="impact-logo" /><span class="info-bulle invisible"><?php _e('impact social', 'yproject')?></span>
						<?php endif; ?>
						<?php if (strpos($campaign_categories_str, 'economique') !== FALSE): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/common/impact-eco.png" alt="impact économique" width="42" height="42" class="impact-logo" /><span class="info-bulle invisible"><?php _e('impact &eacute;conomique', 'yproject')?></span>
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
				<?php date_default_timezone_set("Europe/London"); ?>
				
				<?php // cas d'un projet en cours de vote ?>
				<?php if ($campaign_status == ATCF_Campaign::$campaign_status_vote): ?>
					<?php $nbvoters = $campaign->nb_voters(); ?>
				
					<div class="left">
						<?php
						$number = $nbvoters;
						$text = __("&eacute;valuateur", 'yproject');
						if ($nbvoters == 0) {
							$number = __("aucun", 'yproject');
						} elseif ($nbvoters > 1) {
							$text = __("&eacute;valuateurs", 'yproject');
						}
						?>
						<span><?php echo $number; ?></span><br />
						<span><?php echo $text; ?></span>
					</div>
					<div class="left bordered">
						<?php if ( $campaign->get_minimum_goal_display() == ATCF_Campaign::$key_minimum_goal_display_option_minimum_as_step ): ?>
							<span><?php echo YPUIHelpers::display_number( $campaign->minimum_goal() ); ?> &euro; MIN<br />
							<?php echo YPUIHelpers::display_number( $campaign->goal( false ) ); ?> &euro; MAX</span>
							<span></span>
						<?php else: ?>
							<span><?php echo YPUIHelpers::display_number( $campaign->minimum_goal() ); ?> &euro;</span><br />
							<span><?php _e('Objectif minimum', 'yproject'); ?></span>
						<?php endif; ?>
					</div>
					<div class="left">
						<?php
						$time_remaining_str = $campaign->time_remaining_str();
						if ($time_remaining_str != '-'):
							$time_remaining_str_split = explode('-', $time_remaining_str);
							$time_remaining_str = ($time_remaining_str_split[1] + 1) . ' ';
							$time_remaining_str_unit = $time_remaining_str_split[0];
							switch ($time_remaining_str_split[0]) {
								case 'J': $time_remaining_str .= __('jours', 'yproject'); break;
								case 'H': $time_remaining_str .= __('heures', 'yproject'); break;
								case 'M': $time_remaining_str .= __('minutes', 'yproject'); break;
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
					<?php if ( $campaign->time_remaining_str() != '-' ): ?>
						
						<?php if ( !is_user_logged_in() ): ?>
							<a href="<?php echo home_url( '/connexion/' ); ?>?source=project" class="button red">
								<?php _e('&Eacute;valuer', 'yproject'); ?>
							</a>

						<?php elseif ( $has_voted ): ?>
							<a href="#preinvest-warning" class="button red wdg-button-lightbox-open" data-lightbox="preinvest-warning"><?php _e( "Pr&eacute;-investir", 'yproject' ); ?></a>

						<?php else: ?>
							<a href="#vote" class="button red wdg-button-lightbox-open" data-lightbox="vote" data-thankyoumsg="<?php _e( "Merci pour votre &eacute;valuation !", 'yproject' ); ?>">
								<?php _e('&Eacute;valuer', 'yproject'); ?>
							</a>
						<?php endif; ?>
					<?php else: ?>
						<div class="end-sentence">
							<?php if ( $campaign->end_vote_pending_message() == '' ): ?>
								<?php _e( "Cette lev&eacute;e de fonds passera bient&ocirc;t en phase d'investissement !", 'yproject' ); ?>
							<?php else: ?>
								<?php echo $campaign->end_vote_pending_message(); ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					</div>
				
				
				<?php // cas d'un projet en financement ?>
				<?php elseif($campaign_status == ATCF_Campaign::$campaign_status_collecte): ?>
					<?php
					$invest_url = home_url( '/investir/?campaign_id=' .$campaign->ID. '&amp;invest_start=1' );
					$invest_url_href = home_url( '/connexion/' ) . '?source=project';
					if ( is_user_logged_in() ) {
						$invest_url_href = $invest_url;
					}
					$time_remaining_str = $campaign->time_remaining_str();
					?>
					<?php if ( $time_remaining_str == '-' ): ?>
						<?php if ( $campaign->is_remaining_time() ): ?>
							<div class="end-sentence">
								<?php $datetime_end = $campaign->get_end_date_when_can_invest_until_contract_start_date(); ?>
								<?php echo __( "L'investissement est possible jusqu'au premier versement de royalties", 'yproject' ). " (" .$datetime_end->format( 'd/m/Y' ). ")."; ?>
							</div>
							<a href="<?php echo $invest_url_href; ?>" class="button red"><?php _e( "Investir", 'yproject' ); ?></a>
						<?php endif; ?>
							
							
					<?php else: ?>
						<?php
						$nbinvestors = $campaign->backers_count();
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
							<?php if ( $campaign->get_minimum_goal_display() == ATCF_Campaign::$key_minimum_goal_display_option_minimum_as_step ): ?>
								<span></span>
								<span style="font-weight: bold;"><?php echo YPUIHelpers::display_number( $campaign->minimum_goal() ); ?> &euro; MIN<br />
								<?php echo YPUIHelpers::display_number( $campaign->goal( false ) ); ?> &euro; MAX</span>
							<?php else: ?>
								<span><?php echo YPUIHelpers::display_number( $campaign->minimum_goal() ); ?> &euro;</span><br />
								<span><?php _e('Objectif minimum', 'yproject'); ?></span>
							<?php endif; ?>
						</div>
						<div class="left">
							<?php
							if ($time_remaining_str != '-'):
								$time_remaining_str_split = explode('-', $time_remaining_str);
								$time_remaining_str = ($time_remaining_str_split[1] + 1) . ' ';
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

						<?php if ( $campaign->percent_completed( false ) < 100 ): ?>
							<a href="<?php echo $invest_url_href; ?>" class="button red"><?php _e( "Investir", 'yproject' ); ?></a>
						<?php else: ?>
							<div class="end-sentence">
								<?php if ( $campaign->maximum_complete_message() == '' ): ?>
									<?php _e( "Cette lev&eacute;e de fonds est en cours de cl&ocirc;ture !", 'yproject' ); ?>
								<?php else: ?>
									<?php echo $campaign->maximum_complete_message(); ?>
								<?php endif; ?>
							</div>
						<?php endif; ?>
				
					<?php endif; ?>
				
				
				
				
				<?php // cas d'un projet terminé et financé ?>
				<?php elseif($campaign_status == ATCF_Campaign::$campaign_status_funded || $campaign_status == ATCF_Campaign::$campaign_status_closed): ?>
					<?php
					$nbinvestors = $campaign->backers_count();
					$invest_amount = $campaign->current_amount();
					?>
					<div class="end-sentence">
						<?php echo $nbinvestors. " " .__("personnes","yproject"). " " .__("ont investi","yproject"). " " .$invest_amount. " " .__("pour propulser cette lev&eacute;e de fonds","yproject"); ?>
					</div>
					<a href="<?php echo home_url( '/les-projets/' ); ?>" class="button red"><?php _e("D&eacute;couvrir d'autres projets","yproject" ) ?></a>
				
                                        
				<?php // cas d'un projet terminé et non financé ?>
				<?php elseif($campaign_status == ATCF_Campaign::$campaign_status_archive): ?>            
					<div class="end-sentence">
						<?php if ( $campaign->archive_message() == '' ): ?>
							<?php _e( "Malheureusement, cette lev&eacute;e de fonds n'a pas &eacute;t&eacute; propuls&eacute;e", 'yproject' ); ?>
						<?php else: ?>
							<?php echo $campaign->archive_message(); ?>
						<?php endif; ?>
					</div>
					<a href="<?php echo home_url( '/les-projets/' ); ?>" class="button red"><?php _e("D&eacute;couvrir d'autres projets","yproject" ) ?></a>

				<?php endif; ?>
                                      				
			</div>

		</div>
	</div>
	
	<div class="clear padder">
		<div class="hashtags">
			<strong><?php _e( "Impacts", 'yproject' ); ?></strong> (<a href="<?php echo home_url( '/investissement/impact-investing/evaluation-des-impacts/' ); ?>" target="_blank"><?php _e( "en savoir plus", 'yproject' ); ?></a>) : <?php echo $campaign->get_subcategories_hashtags(); ?>
		</div>
		<div class="subtitle">
			<?php echo $campaign->subtitle(); ?>
		</div>
	</div>
</div>
	
<div class="padder">
	<div id="triggered-menu-share" class="triggered-menu">
		<?php locate_template( 'projects/common/share-buttons.php', true, false ); ?>
	</div>
</div>