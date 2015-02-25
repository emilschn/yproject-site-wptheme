<?php 
global $campaign_id_param, $campaign_id, $stylesheet_directory_uri;

$bopp_campaign_id = BoppLibHelpers::get_api_project_id($campaign_id);
$bopp= BoppLib::get_project($bopp_campaign_id);

$stylesheet_directory_uri = get_stylesheet_directory_uri();
date_default_timezone_set("Europe/London");
$campaign_id_param = '?campaign_id=';
if (isset($_GET['campaign_id'])) {
	$campaign_id_param .= $_GET['campaign_id'];
	$post = get_post($_GET['campaign_id']);
	$campaign = atcf_get_campaign( $post );
	
} else if (isset($campaign_id)) {
	$campaign_id_param .= $campaign_id;
	$post = get_post($campaign_id);
	$campaign = atcf_get_campaign( $post );

} else {
	$campaign_id_param .= $post->ID;
}
$vote_status = $campaign->campaign_status(); 

?>

<section id="projects-banner">
	<div id="projects-stats" class="center">
		<div id="projects-stats-content" <?php if($vote_status=='preview')echo 'style="background:transparent !important;"'?>>
				<div class="projects-description-separator" <?php if($vote_status=='preview')echo 'style="opacity:0;"'?>></div>

				<?php
				if ($vote_status == 'collecte' || $vote_status == 'funded') {
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
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/personnes.png" alt="Logo personnes" />
						<?php echo $campaign->backers_count(); ?> personnes ont dèjà financé ce projet
					</div>
					<div class="post_bottom_infos_item" <?php if($vote_status=='funded'){echo "style=opacity:0.5";}?>>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/horloge.png" alt="Logo horloge" />
						Plus que <strong><?php echo $campaign->days_remaining(); ?></strong> jours !
					</div>
					<div class="post_bottom_infos_item">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/cible.png" alt="Logo cible" />
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
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/personnes.png" alt="Logo personnes" />
						<?php if ($nbvoters == 1): ?>
							1 personne a d&eacute;j&agrave; vot&eacute;
						<?php elseif ($nbvoters > 1): echo $nbvoters; ?>
							personnes ont d&eacute;j&agrave; vot&eacute;
						<?php else: ?>
							Personne n'a vot&eacute;. Soyez le premier !
						<?php endif; ?>
					</div>
					<div class="post_bottom_infos_item">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/horloge.png" alt="Logo personnes" />
						<?php if ($remaining_vote_days > 0) : ?>
							Il reste <strong><?php echo $campaign->end_vote_remaining(); ?></strong> jours pour voter
						<?php else: ?>
							Le vote est termin&eacute;
						<?php endif; ?>
					</div>
					<div class="post_bottom_infos_item">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/cible.png" alt="Logo cible" />
						<?php echo 'Ce projet a besoin de '.$campaign->minimum_goal(true) ; ?>
					</div>
					<div class="projects-description-separator"></div>

					<?php } else if ($vote_status== 'preview'){ ?>

					<div style="opacity:0">
						<div class="project_full_progressbg"></div>
						<span class="project_full_percent"><?php echo $campaign->percent_minimum_completed(); ?></span>
					</div>
					<div class="post_bottom_infos_item" style="opacity:0">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/personnes.png" alt="Logo personnes" />
						<?php if ($nbvoters == 1): ?>
							1 personne a d&eacute;j&agrave; vot&eacute;
						<?php elseif ($nbvoters > 1): echo $nbvoters; ?>
							personnes ont d&eacute;j&agrave; vot&eacute;
						<?php else: ?>
							Personne n'a vot&eacute;. Soyez le premier !
						<?php endif; ?>
					</div>
					<div class="post_bottom_infos_item" style="opacity:0">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/horloge.png" alt="Logo personnes" />
						<?php if ($remaining_vote_days > 0) : ?>
							Il reste <strong><?php echo $campaign->end_vote_remaining(); ?></strong> jours pour voter
						<?php else: ?>
							Le vote est termin&eacute;
						<?php endif; ?>
					</div>
					<div class="post_bottom_infos_item" style="opacity:0">
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/cible.png" alt="Logo cible" />
						<?php echo 'Ce projet a besoin de '.$campaign->minimum_goal(true) ; ?>
					</div>
					<div class="projects-description-separator" style="opacity:0"></div>

					<?php } ?>
				<div class="post_bottom_buttons">
					<?php if ($vote_status == 'collecte' && ypcf_check_user_is_complete($post->post_author) && $campaign->days_remaining() > 0) { ?> 
					<div id="invest-button">
						<?php $page_invest = get_page_by_path('investir'); ?>
						<a href="<?php echo get_permalink($page_invest->ID) . $campaign_id_param; ?>&invest_start=1" class="description-discover"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle">Investir sur ce projet<img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"></a>
					</div>

					<?php } else if ($vote_status == 'preview'){ ?>
					<div id="participate-button">
						<?php $page_forum = get_page_by_path('forum'); ?>
						<a href="<?php echo get_permalink($page_forum->ID) . $campaign_id_param; ?>" class="description-discover"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle">Participer au forum<img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"></a>
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
								<a href="javascript:WDGProjectPageFunctions.print_vote_form();" class="description-discover"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle">Voter sur ce projet<img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"></a>
							<?php endif; ?>
						</div>

						<?php } else if($vote_status=='funded') { ?>
						<div id="funded-button">
							<a class="description-discover">Projet financ&eacute;</a>
						</div>

						<?php } ?>



						<?php if ( is_user_logged_in() ) { 
							global $wpdb, $campaign_id;
							$user_id = wp_get_current_user()->ID;
							$table_jcrois = $wpdb->prefix . "jycrois";
							$users = $wpdb->get_results( "SELECT * FROM $table_jcrois WHERE campaign_id = $campaign_id AND user_id=$user_id" );
							if ( !empty($users[0]->ID) ) { ?>
							<a class="jy-crois" href="javascript:WDGProjectPageFunctions.update_jycrois(0,<?php global $post;echo($post->ID); ?>,'<?php echo $stylesheet_directory_uri; ?>')">
								<div id="jy-crois-btn" style="background-image: url('<?php echo $stylesheet_directory_uri.'/images/jycrois_gris.png';?>')" class="stats_btn" class="dark">
									<p id="jy-crois-txt"><p>    
										<p id="nb-jycrois"><?php do_shortcode('[yproject_crowdfunding_count_jcrois]') ?></p>
									</div></a>
									<?php } else { ?>
									<a class="jy-crois" href="javascript:WDGProjectPageFunctions.update_jycrois(1,<?php global $post;echo($post->ID); ?>,'<?php echo $stylesheet_directory_uri; ?>')">
										<div id="jy-crois-btn" class="stats_btn" class="dark">
											<p id="jy-crois-txt">J'y crois<p>
												<p id="nb-jycrois"><?php do_shortcode('[yproject_crowdfunding_count_jcrois]') ?></p>
											</div></a>

											<?php }
										} else {
											$page_connexion = get_page_by_path('connexion'); ?>
											<a class="jy-crois" href="<?php echo get_permalink($page_connexion->ID); ?>">
												<div id="jy-crois-btn" class="stats_btn" class="dark">
													<p id="jy-crois-txt">J'y crois<p>
														<p id="nb-jycrois"><?php do_shortcode('[yproject_crowdfunding_count_jcrois]') ?></p>
													</div></a>
													<?php } ?>

													<a id="share-btn-a" href="javascript:WDGProjectPageFunctions.share_btn_click();">
														<div id="share-btn-div" class="stats_btn" class="dark">
															<p id="share-txt">Partager</p>	
														</div>
													</a>
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
													<img src="<?php echo $stylesheet_directory_uri; ?>/images/facebook.jpg" alt="Logo Facebook" />
												</a>
												<a href="http://twitter.com/share?url=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>&text='test'">
													<img src="<?php echo $stylesheet_directory_uri; ?>/images/twitter.jpg" alt="Logo twitter" />
												</a>
												<a href="https://plus.google.com/share?url=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>">
													<img src="<?php echo $stylesheet_directory_uri; ?>/images/google+.jpg" alt="Logo google" />
												</a>
												<?php } ?>
											</div>
										</div>


										<div class="edit_project">
											<div id="head-image"<?php global $can_modify; if ($can_modify) { echo ' style="margin-top: 36px"'; } ?>>
												<div class="center">
													<div id="head-content">
															<div class="head_project_name">
															<?php if($can_modify){ ?>
																<div class="edit_name">
																	<a class="edit-button" href="#" data-action="edit_name">Editer</a>
																	<span class="cancel_save">
																		<a class="cancel-button" href="#" data-action="cancel_name">Annuler</a>
																		<a class="save-button" href="#" data-campaign="<?= $campaign_id ?>" data-action="save_name">Enregistrer</a> 
																	</span>
																</div>
																<?php } ?>
	
																<div class="control-group">
																	<div class="controls">
																		<?php if($can_modify){ ?>
																		<input type="text" id="projectName" class="edit-name-field" value="<?php if ($bopp->project_name) { echo $bopp->project_name;}?>" data-placeholder="Titre du projet" id="projectName" name="projectName">
																		<?php } ?>
																		<h1 id="title" class="project_name view-name-content"><?php if ($bopp->project_name) { echo $bopp->project_name;} else { echo "&nbsp"; } ?></h1>
																	</div>
																</div>
																<div class="control-group">
																	<div class="controls">
																		<?php if($can_modify){ ?>
																		<input type="text" id="projectSlogan" class="edit-name-field" value="<?php if ($bopp->project_slogan) { echo $bopp->project_slogan;}?>" placeholder="Slogan de la campagne" id="projectSlogan" name="projectSlogan">
																		<?php } ?>
																		<h2 id="subtitle" class="project_slogan view-name-content"><?php if ($bopp->project_slogan) { echo $bopp->project_slogan;} else { echo "&nbsp"; } ?></h2>	
																	</div>
																</div>
															</div>
															<img src="<?php echo $stylesheet_directory_uri;?>/images/fond_projet.png" alt="Fond projet" />

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

												<?php if ($can_modify) { ?>
													<form id="image_upload" method="post" action="#" enctype="multipart/form-data" >
													   <span class="btn_file"><input type="file" name="image" id="image"></span>
													  <input type='hidden' value='<?php wp_create_nonce( 'upload' ); ?>' name='_nonce' />
													  <input type="hidden" name="post_id" id="post_id" value="<?= $campaign_id ?>">
													  <input type="hidden" name="action" id="action" value="save_image">
													  <span id="loading_image_cover"></span>
													  <span id="ok_image_cover"></span>
													  <input id="submit_image_cover" name="submit-ajax-cover" type="submit" value="upload">
													</form>
													<?php } ?>
													<div id='img-container' style="top:<?php $cover_position=get_post_meta($post->ID,'campaign_cover_position',TRUE); if($cover_position!='') echo $cover_position;?>">
														<?php 
														$img_src = '';			
														$attachments = get_posts( array(
															'post_type' => 'attachment',
															'post_parent' => $post->ID,
															'post_mime_type' => 'image'
															));
														$image_obj = array();
														//Si on en trouve bien une avec le titre "image_home" on prend celle-là
														// foreach ($attachments as $attachment) {
														// 	if ($attachment->post_title == 'image_header') $image_obj = wp_get_attachment_image_src($attachment->ID, "full");
														// }


														foreach ($attachments as $attachment) {
															if ($attachment->post_title == 'image_header') array_push($image_obj, wp_get_attachment_image_src($attachment->ID, "full"));

														}
													    //Sinon on prend la première image rattachée à l'article
														if ($image_obj_header != '') $image_src_header = $image_obj_header[0];
														//echo $image_src_header; 


														//Sinon on prend la première image rattachée à l'article
														if ($image_obj == '' && count($attachments) > 0) $image_obj = wp_get_attachment_image_src($attachments[0]->ID, "full");
														if ($image_obj != '') $img_src = $image_obj[0];
														?>
														<img id="moved-img" class="cover-img" src="<?php echo $image_obj[0][0];; ?>" alt="Image du projet" />
													</div>

												<?php 
												global $can_modify;
												if ($can_modify) { 
													global $post;
													$post_id_echo = (isset($_GET['campaign_id'])) ? $_GET['campaign_id'] : $post->ID;
													?>
													<a href="#" id="reposition-cover" onclick='javascript:BOPPFunctions.move_picture(<?php echo $post_id_echo; ?>)'>Repositionner</a>


		
													<?php } ?>
												</div>
												<div style="clear:both"></div>
												<div id="output2"></div>
											</div>

										</section>
