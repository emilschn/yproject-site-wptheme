<?php 
global $campaign_id_param, $campaign_id, $WDG_cache_plugin, $stylesheet_directory_uri, $can_modify; 
$stylesheet_directory_uri = get_stylesheet_directory_uri();
date_default_timezone_set("Europe/London");
$campaign_id_param = '?campaign_id=';
if (isset($_GET['campaign_id'])) {
	$campaign_id_param .= $_GET['campaign_id'];
	$post = get_post($_GET['campaign_id']);
	
} else if (isset($campaign_id)) {
	$campaign_id_param .= $campaign_id;
	$post = get_post($campaign_id);
	    
} else {
	$campaign_id_param .= $post->ID;
}
$campaign = atcf_get_campaign( $post );
$vote_status = $campaign->campaign_status(); 
?>

<section id="projects-banner">
	<div id="projects-stats" class="center">
		<div id="projects-stats-content" <?php if($vote_status=='preview')echo 'style="background:transparent !important;"'?>>
		<?php 
		$cache_result = $WDG_cache_plugin->get_cache('project-'.$campaign_id.'-header-first');
		if (false === $cache_result) {
			ob_start();
		?>
			<div class="projects-description-separator" <?php if($vote_status=='preview')echo 'style="opacity:0;"'?>></div>

			<?php
			if ($vote_status == 'collecte' || $vote_status == 'funded' || $vote_status == 'archive') {
				$percent = min(100, $campaign->percent_minimum_completed(false));
				$width = 250 * $percent / 100;
			?>
				<div>
					<div class="project_full_progressbg">
						<div class="project_full_progressbar" style="width:<?php echo $width; ?>px">
							&nbsp;
						</div>
					</div>
					<span class="project_full_percent"><?php echo $campaign->percent_minimum_completed(); ?></span>
				</div>
				<div class="post_bottom_infos_item">
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/personnes.png" alt="logo personnes" />
					<?php $backers_count = $campaign->backers_count(); ?>
					<?php echo $backers_count; ?> personne<?php if ($backers_count > 1) { echo 's ont'; } else { echo ' a'; } ?> d&eacute;j&agrave; financ&eacute; ce projet
				</div>
				<div class="post_bottom_infos_item" <?php if($vote_status=='funded' || $vote_status == 'archive'){echo "style=opacity:0.5";}?>>
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/horloge.png" alt="logo horloge" />
					Plus que <strong><?php echo $campaign->days_remaining(); ?></strong> jours !
				</div>
				<div class="post_bottom_infos_item">
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/cible.png" alt="logo cible" />
					<?php echo $campaign->current_amount() . ' financés sur ' . $campaign->minimum_goal(true) ; ?>
				</div>
				<div class="projects-description-separator"></div>

			<?php 
			} else if ($vote_status == 'vote') {
				$nbvoters = $campaign->nb_voters();
				$remaining_vote_days = $campaign->end_vote_remaining(); 
			?>
				<div style="opacity:0.5">
					<div class="project_full_progressbg"></div>
					<span class="project_full_percent"><?php echo $campaign->percent_minimum_completed(); ?></span>
				</div>
				<div class="post_bottom_infos_item">
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/personnes.png" alt="logo personnes" />
					<?php if ($nbvoters == 1): ?>
					1 personne a d&eacute;j&agrave; vot&eacute;
					<?php elseif ($nbvoters > 1): echo $nbvoters; ?>
					personnes ont d&eacute;j&agrave; vot&eacute;
					<?php else: ?>
					Personne n'a vot&eacute;. Soyez le premier !
					<?php endif; ?>
				</div>
				<div class="post_bottom_infos_item">
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/horloge.png" alt="logo personnes" />
					<?php if ($remaining_vote_days > 0) : ?>
					Il reste <strong><?php echo $campaign->end_vote_remaining(); ?></strong> jours pour voter
					<?php else: ?>
					Le vote est termin&eacute;
					<?php endif; ?>
				</div>
				<div class="post_bottom_infos_item">
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/cible.png" alt="logo cible" />
					<?php echo 'Ce projet a besoin de '.$campaign->minimum_goal(true) ; ?>
				</div>
				<div class="projects-description-separator"></div>

			<?php } else if ($vote_status== 'preview'){ ?>

				<div style="opacity:0">
					<div class="project_full_progressbg"></div>
					<span class="project_full_percent"><?php echo $campaign->percent_minimum_completed(); ?></span>
				</div>
				<div class="post_bottom_infos_item" style="opacity:0">
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/personnes.png" alt="logo personnes" />
					<?php if ($nbvoters == 1): ?>
					1 personne a d&eacute;j&agrave; vot&eacute;
					<?php elseif ($nbvoters > 1): echo $nbvoters; ?>
					personnes ont d&eacute;j&agrave; vot&eacute;
					<?php else: ?>
					Personne n'a vot&eacute;. Soyez le premier !
					<?php endif; ?>
				</div>
				<div class="post_bottom_infos_item" style="opacity:0">
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/horloge.png" alt="logo personnes" />
					<?php if ($remaining_vote_days > 0) : ?>
					Il reste <strong><?php echo $campaign->end_vote_remaining(); ?></strong> jours pour voter
					<?php else: ?>
					Le vote est termin&eacute;
					<?php endif; ?>
				</div>
				<div class="post_bottom_infos_item" style="opacity:0">
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/cible.png" alt="logo cible" />
					<?php echo 'Ce projet a besoin de '.$campaign->minimum_goal(true) ; ?>
				</div>
				<div class="projects-description-separator" style="opacity:0"></div>

			<?php } 
			$cache_result=ob_get_contents();
			$WDG_cache_plugin->set_cache('project-'.$campaign_id.'-header-first',$cache_result);
			ob_end_clean();
		}
		echo $cache_result;
		?>
			<div class="post_bottom_buttons">
				<?php if ($vote_status == 'collecte' && ypcf_check_user_is_complete($post->post_author) && $campaign->days_remaining() > 0) { ?> 
					<div id="invest-button">
						<?php $page_invest = get_page_by_path('investir'); ?>
						<a href="<?php echo get_permalink($page_invest->ID) . $campaign_id_param; ?>&amp;invest_start=1" class="description-discover"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" />Investir sur ce projet<img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /></a>
					</div>

				<?php } else if ($vote_status == 'preview'){ ?>
						<div id="participate-button">
						<?php $page_forum = get_page_by_path('forum'); ?>
						<a href="<?php echo get_permalink($page_forum->ID) . $campaign_id_param; ?>" class="description-discover"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" />Participer au forum<img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /></a>
					</div>

				<?php } else if ($vote_status == 'vote') {
					global $wpdb;
					$table_name = $wpdb->prefix . "ypcf_project_votes";
					$hasvoted_results = $wpdb->get_results( 'SELECT id FROM '.$table_name.' WHERE post_id = '.$campaign->ID.' AND user_id = '.wp_get_current_user()->ID );
					$has_voted = false;
					if ( !empty($hasvoted_results[0]->id) ) $has_voted = true;
					?>
					<div id="invest-button">
						<?php if ($has_voted): ?>
						<span class="description-discover" style="background-color:#333;">Merci pour votre vote</span>
						<?php else : ?>
						<a href="javascript:WDGProjectPageFunctions.print_vote_form();" class="description-discover"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" />Voter sur ce projet<img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /></a>
						<?php endif; ?>
					</div>

				<?php } else if($vote_status=='funded') { ?>
					<div id="funded-button">
						<a class="description-discover">Projet financ&eacute;</a>
					</div>

				<?php } ?>


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
				} else {
					$page_connexion = get_page_by_path('connexion'); ?>
					<a class="jy-crois" href="<?php echo get_permalink($page_connexion->ID); ?>">
					<div id="jy-crois-btn" class="stats_btn">
					<p id="jy-crois-txt">J'y crois<p>
					<p id="nb-jycrois"><?php echo $campaign->get_jycrois_nb(); ?></p>
					</div></a>
				<?php } ?>
				<?php endif; ?>

				<?php if ($vote_status != 'preparing') : ?>
				<a id="share-btn-a" href="javascript:WDGProjectPageFunctions.share_btn_click();">
				<div id="share-btn-div" class="stats_btn">
					<p id="share-txt">Partager</p>	
				</div>
				</a>
				<?php endif; ?>
			</div>

			<div id="white-background" <?php if($vote_status=='preview')echo 'style="background:transparent !important;"'?>></div>

			<?php if ($vote_status == 'vote') { ?>
			<div id="vote-form">
				<?php require_once('single-voteform.php'); ?>
			</div> 
			<?php } ?>
		</div>
				    
		<div id="dialog" title="Partager ce projet">
			<?php if (class_exists('Sharing_Service')) {
				echo ypcf_fake_sharing_display();

			} else { ?>

				<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>">
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/facebook.jpg" alt="logo facebook" />
				</a>
				<a href="http://twitter.com/share?url=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>&text='test'">
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/twitter.jpg" alt="logo twitter" />
				</a>
				<a href="https://plus.google.com/share?url=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>">
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/google+.jpg" alt="logo google" />
				</a>
			<?php } ?>
		</div>
	</div>
	
	<div id="head-image"<?php if ($can_modify) { echo ' style="margin-top: 46px"'; } ?>>
		<div class="center">
			<div id="head-content">
				<?php
				$cache_result = $WDG_cache_plugin->get_cache('project-'.$campaign_id.'-header-title');
				if (false === $cache_result) {
					ob_start();
				?>
					<p id="title">
						<?php if ($campaign->funding_type() == 'fundingdevelopment'): ?><img src="<?php echo $stylesheet_directory_uri;?>/images/capital.png" alt="Picto Capital" /><br /><?php endif; ?>
						<?php echo get_the_title(); ?>
					</p>
					<p id="subtitle"><?php echo $campaign->subtitle(); ?></p>
					<img src="<?php echo $stylesheet_directory_uri;?>/images/fond_projet.png" alt="fond projet" class="bg-project" />
				<?php 
					$cache_result = ob_get_contents();
					$WDG_cache_plugin->set_cache('project-'.$campaign_id.'-header-title', $cache_result);
					ob_end_clean();
				}
				echo $cache_result;
				?>
				
				<?php
					$current_page = 'http';
					if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$current_page .= "s";}
					$current_page .= "://";
					$current_page .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
					
					$project_link = get_permalink($campaign_id);
					
					$category_slug = $post->ID . '-blog-' . $post->post_name;
					$category_obj = get_category_by_slug($category_slug);
					if (!empty($category_obj)) {
						$category_link = get_category_link($category_obj->cat_ID);
						$posts_in_category = get_posts(array('category'=>$category_obj->cat_ID));
					} else {
						$category_link = '';
					}
					$nb_cat = (isset($posts_in_category)) ? ' ('.count($posts_in_category).')' : '';
					$news_link = esc_url($category_link);
					
					$forum = get_page_by_path('forum');
					$forum_link = get_permalink($forum->ID).$campaign_id_param;
					$forum_link_2 = site_url('forums/forum') . '/' . $campaign_id . '-2/';
					
					$stats_page = get_page_by_path('statistiques');
					$stats_link = get_permalink($stats_page->ID).$campaign_id_param;
					
					$show_stat_button = false;
					if ($vote_status != 'preview') { 
						if ($vote_status != 'vote' || $campaign->end_vote_remaining() <= 0) {
							$show_stat_button = true;
						}
					}
					
				?>
				<nav>
					<ul>
						<li><a href="<?php echo $project_link; ?>" <?php if($current_page==$project_link) echo 'class="current"'; ?>>Le projet</a></li>
						<li><a href="<?php echo $news_link; ?>" <?php if($current_page==$news_link) echo 'class="current"'; ?>>Actualit&eacute;<?php echo $nb_cat; ?></a></li>
						<li><a href="<?php echo $forum_link; ?>" <?php if($current_page==$forum_link || $current_page==$forum_link_2) echo 'class="current"'; ?>>Forum</a></li>
						<?php if ($show_stat_button) { ?>
						<li><a href="<?php echo $stats_link; ?>" <?php if($current_page==$stats_link) echo 'class="current"'; ?>>Statistiques</a></li>
						<?php } ?>
					</ul>
				</nav>
			</div>
		</div>
	    
		<?php
		$cache_result = $WDG_cache_plugin->get_cache('project-'.$campaign_id.'-header-img');
		if (false === $cache_result) {
		ob_start();
		?>
		<div id='img-container' style="<?php echo $campaign->get_header_picture_position_style(); ?>">
			<?php 
			$img_src = $campaign->get_header_picture_src();
			if ($img_src != ''):
			?>
			<img id="moved-img" src="<?php echo $img_src; ?>" alt="banniere <?php echo $post->post_title; ?>" />
			<?php endif; ?>
		</div>
		<?php 
		    $cache_result = ob_get_contents();
		    $WDG_cache_plugin->set_cache('project-'.$campaign_id.'-header-img', $cache_result);
		    ob_end_clean();
		}
		echo $cache_result;
		?>

		<?php 
		global $can_modify, $is_campaign_page;
		if ($can_modify && $is_campaign_page) { 
		    global $post;
		    $post_id_echo = (isset($_GET['campaign_id'])) ? $_GET['campaign_id'] : $post->ID;
		?>
		    <div id="reposition-cover">
			    <a href="javascript:void(0);" class="button" onclick="javascript:BOPPFunctions.move_picture(<?php echo $post_id_echo; ?>)">Repositionner</a>
		    </div>
		<?php } ?>
	</div>
	<div style="clear:both"></div>
</section>
