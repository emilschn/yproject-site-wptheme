<?php 
global $campaign_id_param, $campaign_id, $WDG_cache_plugin, $stylesheet_directory_uri, $can_modify, $show_admin_bar; 
$stylesheet_directory_uri = get_stylesheet_directory_uri();
date_default_timezone_set("Europe/London");
$campaign_id_param = '?campaign_id=';
if (isset($_GET['campaign_id'])) {
	$campaign_id_param .= $_GET['campaign_id'];
	$post = get_post($_GET['campaign_id']);
	$campaign_id = $_GET['campaign_id'];
	
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
//*******************
//CACHE PROJECT HEADER RIGHT
$cache_header_right = $WDG_cache_plugin->get_cache('project-header-right-' . $campaign_id, 1);
if ($cache_header_right !== FALSE) { echo $cache_header_right; }
else {
	ob_start();
?>
			<div class="projects-description-separator mobile_hidden" <?php if($vote_status=='preview')echo 'style="opacity:0;"'?>></div>

			<?php
			if ($vote_status == 'collecte' || $vote_status == 'funded' || $vote_status == 'archive') {
				$percent = min(100, $campaign->percent_minimum_completed(false));
				$width = 250 * $percent / 100;
			?>
				<div class="progress_zone">
					<div class="project_full_progressbg">
						<span class="project_full_percent" style="min-width:<?php echo $width; ?>px">&nbsp;<?php echo $campaign->current_amount(); ?>&nbsp;</span>
					</div>
					<span class="progress_percent tablet_hidden"><?php echo $campaign->percent_minimum_completed(); ?></span>
				</div>
				<div class="logos_zone">
					<div class="post_bottom_infos_item only_on_mobile">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/france.png" alt="logo france" /><br />
						<?php 
						$campaign_location = $campaign->location();
						$exploded = explode(' ', $campaign_location);
						if (count($exploded) > 1) $campaign_location = $exploded[0];
						echo (($campaign_location != '') ? $campaign_location : 'France'); 
						?>
					</div>
					<div class="post_bottom_infos_item">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/personnes.png" alt="logo personnes" />
						<?php $backers_count = $campaign->backers_count(); ?>
						<span class="mobile_hidden"><?php echo $backers_count; ?> personne<?php if ($backers_count > 1) { echo 's ont'; } else { echo ' a'; } ?> d&eacute;j&agrave; investi sur ce projet</span>
						<span class="only_on_mobile"><?php echo $backers_count; ?></span>
					</div>
					<div class="post_bottom_infos_item" <?php if($vote_status=='funded' || $vote_status == 'archive'){echo "style=opacity:0.5";}?>>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/horloge.png" alt="logo horloge" />
						<span class="mobile_hidden"><?php echo $campaign->time_remaining_fullstr(); ?></span>
						<span class="only_on_mobile"><?php echo $campaign->time_remaining_str(); ?></span>
					</div>
					<div class="post_bottom_infos_item">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/cible.png" alt="logo cible" />
						<span class="mobile_hidden"><?php 
						    echo __('Objectif : ', 'yproject') . $campaign->minimum_goal(true);
						    if ($campaign->minimum_goal(false) < $campaign->goal(false)) {
							echo __(' &agrave; ', 'yproject') . $campaign->goal(true);
						    }
						?></span>
						<span class="only_on_mobile"><?php echo $campaign->minimum_goal(true); ?></span>
					</div>
					<div class="post_bottom_infos_item only_on_mobile">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/good.png" alt="logo main" /><br />
						<span><?php echo $campaign->get_jycrois_nb(); ?></span>
					</div>
					<div class="projects-description-separator mobile_hidden"></div>
				</div>

			<?php 
			} else if ($vote_status == 'vote') {
				$nbvoters = $campaign->nb_voters();
			?>
				<div class="logos_zone vote">
					<div class="post_bottom_infos_item only_on_mobile">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/france.png" alt="logo france" /><br />
						<?php 
						$campaign_location = $campaign->location();
						$exploded = explode(' ', $campaign_location);
						if (count($exploded) > 1) $campaign_location = $exploded[0];
						echo (($campaign_location != '') ? $campaign_location : 'France'); 
						?>
					</div>
					<div class="post_bottom_infos_item">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/personnes.png" alt="logo personnes" />
						<span class="mobile_hidden">
						    <?php if ($nbvoters == 1): ?>
						    1 personne a d&eacute;j&agrave; vot&eacute;
						    <?php elseif ($nbvoters > 1): echo $nbvoters; ?>
						    personnes ont d&eacute;j&agrave; vot&eacute;
						    <?php else: ?>
						    Personne n'a vot&eacute;. Soyez le premier !
						    <?php endif; ?>
						</span>
						<span class="only_on_mobile"><?php echo $nbvoters; ?></span>
					</div>
					<div class="post_bottom_infos_item">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/horloge.png" alt="logo horloge" />
						<span class="mobile_hidden"><?php echo $campaign->time_remaining_fullstr(); ?></span>
						<span class="only_on_mobile"><?php echo $campaign->time_remaining_str(); ?></span>
					</div>
					<div class="post_bottom_infos_item">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/cible.png" alt="logo cible" />
						<span class="mobile_hidden"><?php 
						    echo __('Objectif : ', 'yproject') . $campaign->minimum_goal(true);
						    if ($campaign->minimum_goal(false) < $campaign->goal(false)) {
							echo __(' &agrave; ', 'yproject') . $campaign->goal(true);
						    }
						?></span>
						<span class="only_on_mobile"><?php echo $campaign->minimum_goal(true); ?></span>
					</div>
					<div class="post_bottom_infos_item only_on_mobile">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/good.png" alt="logo main" /><br />
						<span><?php echo $campaign->get_jycrois_nb(); ?></span>
					</div>
					<div class="projects-description-separator mobile_hidden"></div>
				</div>

			<?php } else if ($vote_status== 'preview'){ ?>

				<div class="logos_zone">
					<div class="post_bottom_infos_item only_on_mobile">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/france.png" alt="logo france" /><br />
						<?php 
						$campaign_location = $campaign->location();
						$exploded = explode(' ', $campaign_location);
						if (count($exploded) > 1) $campaign_location = $exploded[0];
						echo (($campaign_location != '') ? $campaign_location : 'France'); 
						?>
					</div>
					<div class="post_bottom_infos_item">
	-					<img src="<?php echo $stylesheet_directory_uri; ?>/images/cible.png" alt="logo cible" />
						<span class="mobile_hidden"><?php 
						    echo __('Objectif : ', 'yproject') . $campaign->minimum_goal(true);
						    if ($campaign->minimum_goal(false) < $campaign->goal(false)) {
							echo __(' &agrave; ', 'yproject') . $campaign->goal(true);
						    }
						?></span>
						<span class="only_on_mobile"><?php echo $campaign->minimum_goal(true); ?></span>
					</div>
					<div class="projects-description-separator mobile_hidden"></div>
				</div>

			<?php } ?>
<?php
	$cache_header_right = ob_get_contents();
	$WDG_cache_plugin->set_cache('project-header-right-' . $campaign_id, $cache_header_right, 60*10, 1);
	ob_end_clean();
	echo $cache_header_right;
}
//FIN CACHE MENU
//*******************
?>
			<div class="post_bottom_buttons mobile_hidden">
				<?php if ($vote_status == 'collecte' && ypcf_check_user_is_complete($post->post_author) && $campaign->is_remaining_time() > 0) { ?> 
					<div id="invest-button">
						<?php if ( is_user_logged_in() ): ?> 
							<?php $page_invest = get_page_by_path('investir'); ?>
							<a href="<?php echo get_permalink($page_invest->ID) . $campaign_id_param; ?>&amp;invest_start=1" class="description-discover"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" />Investir sur ce projet<img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /></a>
						<?php else: ?>
							<a href="#connexion" id="investir" class="wdg-button-lightbox-open description-discover" data-lightbox="connexion"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" />Investir sur ce projet<img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /></a>
						<?php endif; ?>
					</div>

				<?php } else if ($vote_status == 'preview'){ ?>
						<div id="participate-button">
						<?php $page_forum = get_page_by_path('forum'); ?>
						<a href="<?php echo get_permalink($page_forum->ID) . $campaign_id_param; ?>" class="description-discover"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" />Participer au forum<img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /></a>
					</div>

				<?php } else if ($vote_status == 'vote') { ?>
					<?php if ( is_user_logged_in() ): ?> 
						<?php 
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
					<?php else: ?>
						<div id="invest-button">
							<a href="#connexion" class="description-discover wdg-button-lightbox-open" data-lightbox="connexion"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" />Voter sur ce projet<img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /></a>
						</div>
					<?php endif; ?>

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
				} else { ?>
					<a class="jy-crois wdg-button-lightbox-open" href="#connexion" data-lightbox="connexion">
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
			
			<div id="invest-button" class="only_on_mobile responsive-fixed">
				<?php if ($has_voted): ?>
				<span class="description-discover" style="background-color:#333;">Merci pour votre vote</span>
				<?php else : ?>
				<a href="javascript:WDGProjectPageFunctions.print_vote_form();" class="description-discover"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle" />Voter sur ce projet<img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle" /></a>
				<?php endif; ?>
			</div>

			<div id="white-background" class="mobile_hidden" <?php if($vote_status=='preview')echo 'style="background:transparent !important;"'?>></div>

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
	
	<div id="head-image"<?php if ($show_admin_bar) { echo ' style="margin-top: 46px"'; } ?>>
		<div class="center">
			<div id="head-content">
				<div id="title">
                                    <a href="<?php echo get_permalink(get_page_by_path('descriptif')->ID)?>" target="_blank">
                                    <?php 
                                        if ($campaign->funding_type() == 'fundingproject'): ?><img src="<?php echo $stylesheet_directory_uri;?>/images/macarons/macaron-R.png" alt="Picto Royalties" /><br /><?php endif;
					if ($campaign->funding_type() == 'fundingdevelopment'): ?><img src="<?php echo $stylesheet_directory_uri;?>/images/macarons/macaron-K.png" alt="Picto Capital" /><br /><?php endif;
					if ($campaign->funding_type() == 'fundingdonation'): ?><img src="<?php echo $stylesheet_directory_uri;?>/images/macarons/macaron-D.png" alt="Picto Donc" /><br /><?php endif; 
                                    ?>
                                    </a>
                                    <p><?php $title = get_the_title(); if (strpos($title, 'span') === FALSE) { $title = '<span>' . $title . '</span>'; } echo $title; ?></p>
				</div>
				<p id="subtitle"><?php echo $campaign->subtitle(); ?></p>
				<img src="<?php echo $stylesheet_directory_uri;?>/images/fond_projet.png" alt="fond projet" class="bg-project mobile_hidden" />
				
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
				<nav class="mobile_hidden">
					<ul>
						<li><a href="<?php echo $project_link; ?>" <?php if($current_page==$project_link) echo 'class="current"'; ?>>Le projet</a></li>
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
				</nav>
			</div>
		</div>
	    
<?php
//*******************
//CACHE PROJECT HEADER IMAGE
$cache_header_image = $WDG_cache_plugin->get_cache('project-header-image-' . $campaign_id, 1);
if ($cache_header_image !== FALSE) { echo $cache_header_image; }
else {
	ob_start();
?>
		<div id="img-container" style="<?php echo $campaign->get_header_picture_position_style(); ?>">
			<?php 
			$img_src = $campaign->get_header_picture_src();
			if ($img_src != ''):
			?>
			<img id="moved-img" src="<?php echo $img_src; ?>" alt="banniere <?php echo $post->post_title; ?>" class="mobile_hidden" />
			<?php endif; ?>
		</div>
<?php
	$cache_header_image = ob_get_contents();
	$WDG_cache_plugin->set_cache('project-header-image-' . $campaign_id, $cache_header_image, 60*30, 1);
	ob_end_clean();
	echo $cache_header_image;
}
//FIN CACHE PROJECT HEADER IMAGE
//*******************
?>

		<?php 
		global $can_modify, $is_campaign_page;
		if ($can_modify && $is_campaign_page) { 
		?>
		    <div id="wdg-move-picture-head" class="move-button"></div>
		<?php } ?>
	</div>
	<div style="clear:both"></div>
</section>
