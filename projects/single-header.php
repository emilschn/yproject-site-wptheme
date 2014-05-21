<?php 
global $campaign_id_param,$campaign_id;
date_default_timezone_set("Europe/London");
$campaign_id_param = '?campaign_id=';
if (isset($_GET['campaign_id'])) {
	$campaign_id_param .= $_GET['campaign_id'];
	$post = get_post($_GET['campaign_id']);
	$campaign = atcf_get_campaign( $post );
} else  {
	$campaign_id_param .= $post->ID;
}
get_header();
$vote_status = $campaign->campaign_status(); 

?>
	<section id="projects-banner">
		<div id="projects-stats" class="center">
			<?php 
			if ($vote_status !='preview'){ 
				$cache_result=$WDG_cache_plugin->get_cache('project-'.$campaign_id.'-header-first');
				if(false===$cache_result){
					ob_start();
				?>

					<div id="projects-stats-content">
						<div class="projects-description-separator"></div>
						 	<?php
							if($vote_status=='collecte'||$vote_status=='funded') {
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
		   					}else if($vote_status=='vote') {
			    				$nbvoters = $campaign->nb_voters();
								$remaining_vote_days = $campaign->end_vote_remaining();?>
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
						<?php 
							}
						?>
							<div class="post_bottom_buttons">
						<?php
					   			if ($vote_status == 'collecte' && ypcf_check_user_is_complete($post->post_author) && $campaign->days_remaining() > 0) { ?> 
									<div id="invest-button" >
										<?php $page_invest = get_page_by_path('investir'); ?>
										<a href="<?php echo get_permalink($page_invest->ID); ?><?php echo $campaign_id_param; ?>&invest_start=1" class="description-discover"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle">Investir sur ce projet<img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"></a>
									</div>
					<?php

								}else if($vote_status == 'vote' && ypcf_check_user_is_complete($post->post_author) && $campaign->days_remaining() > 0) { ?>
									<div id="invest-button" >
										<a href="javascript:print_vote_form()" class="description-discover"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_droite.png" alt="triangle">Voter pour ce projet<img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"><img src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"></a>
									</div>
					<?php
								}else if($vote_status=='funded'){ ?>
				    				<div id="funded-button" >
										<a class="description-discover">Projet financ&eacute;</a>
									</div>
					<?php 		}
							$cache_result=ob_get_contents();
							$WDG_cache_plugin->set_cache('project-'.$campaign_id.'-header-first',$cache_result);
							ob_end_clean();
						}
					echo $cache_result;
					?>
		
		
		<!-- Zone de partage et j'y crois -->
		<?php if ( is_user_logged_in() ) { 
				global $wpdb, $campaign_id;
				$user_id = wp_get_current_user()->ID;
				$table_jcrois = $wpdb->prefix . "jycrois";
				$users = $wpdb->get_results( "SELECT * FROM $table_jcrois WHERE campaign_id = $campaign_id AND user_id=$user_id" );
				if ( !empty($users[0]->ID) ) {?>

					<a class="jy-crois" href="javascript:WDGProjectPageFunctions.update_jycrois(0,<?php global $post;echo($post->ID); ?>,'<?php echo $stylesheet_directory_uri; ?>')">
					<div id="jy-crois-btn" style="background-image: url('<?php echo $stylesheet_directory_uri.'/images/jycrois_gris.png';?>')" class="stats_btn" class="dark">
					<p id="jy-crois-txt"><p>
				<?php }
				else{ ?>
				<a class="jy-crois" href="javascript:WDGProjectPageFunctions.update_jycrois(1,<?php global $post;echo($post->ID); ?>,'<?php echo $stylesheet_directory_uri; ?>')">
					<div id="jy-crois-btn" class="stats_btn" class="dark">
					<p id="jy-crois-txt">J'y crois <p>

				<?php }
				
			}
		
		else{
			$page_connexion = get_page_by_path('connexion'); ?>
			<a class="jy-crois" href="<?php echo get_permalink($page_connexion->ID); ?>">

					<div id="jy-crois-btn" class="stats_btn" class="dark">
					<p id="jy-crois-txt">J'y crois <p>
         <?php 
        	}
		  ?>
		  <p id="nb-jycrois"><?php do_shortcode('[yproject_crowdfunding_count_jcrois]') ?></p>
		</div></a>
		<a id="share-btn-a" href="javascript:WDGProjectPageFunctions.share_btn_click();">
		<div id="share-btn-div" class="stats_btn" class="dark">
			<p id="share-txt">Partager</p>	
		</div>
		</a>
		</div>
		<?php if (class_exists('Sharing_Service')) {
	    //Liens pour partager
	    $buffer = ypcf_fake_sharing_display();
	    $buffer .= '</center>';
		$buffer .= '<br /><br />&lt;&lt; <a href="'.$campaign_url.'">Retour au projet</a>';
		echo $buffer;
		} else {?>
		<div id="dialog" title="Partager ce projet">
			
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/facebook.jpg" alt="Logo Facebook" />
			</a>
			<a href="http://twitter.com/share?url=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>&text='test'">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/twitter.jpg" alt="Logo twitter" />
			</a>
			<a href="https://plus.google.com/share?url=<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?>">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/google+.jpg" alt="Logo google" />
			</a>
		</div> 
		<?php } ?>
		<div id="white-background">
		</div>
		
		<!-- FIN Zone de partage -->
		<?php if ($vote_status=='vote') { ?>
		<div id="vote-form">
			<?php require_once('single-voteform.php'); ?>
		<!--	<h1>Votez !</h1>
			<h2>Impacts et cohérence du projet</h2>
			<h3>Cette question détermine la publication du projet sur le site</h3>
		-->
		</div> 
		<?php } 
		
		?>
	</div>
</div>
<?php }
?>
</div>
<?php
$cache_result=$WDG_cache_plugin->get_cache('project-'.$campaign_id.'-header-second');
		if(false===$cache_result){
		ob_start();
 ?>
		

		<div id="head-image">
			<div class="center">
				<div id="head-content">
					<p id="title"> <?php echo get_the_title(); ?></p>
					<p id="subtitle"> <?php echo $campaign->subtitle(); ?> </p>
					<img src="<?php echo $stylesheet_directory_uri;?>/images/fond_projet.png"></img>
					<?php
				$category_slug = $post->ID . '-blog-' . $post->post_title;
				$category_obj = get_category_by_slug($category_slug);
				if (!empty($category_obj)) {
				    $category_link = get_category_link($category_obj->cat_ID);
				    $posts_in_category = get_posts(array('category'=>$category_obj->cat_ID));
				} else {
				    $category_link = '';
				}
				$nb_cat = (isset($posts_in_category)) ? ' ('.count($posts_in_category).')' : '';
				?>
				<?php $forum = get_page_by_path('forum'); ?>
				<?php $statistiques = get_page_by_path('statistiques'); ?>
				<?php
                                $category_slug = $post->ID . '-blog-' . $post->post_name;
                                echo $category_slug;
                                $category_obj = get_category_by_slug($category_slug);
                                if (!empty($category_obj)) {
                                    $category_link = get_category_link($category_obj->cat_ID);
                                    $posts_in_category = get_posts(array('category'=>$category_obj->cat_ID));
                                    $test='if';
                                     } else {
                                    $category_link = '';
                                    $test='else';
                                }
                                $nb_cat = (isset($posts_in_category)) ? ' ('.count($posts_in_category).')' : '';

                        ?>

					<nav>
						<ul>
							<?php 
							 $current_page = 'http';
							 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
							 $current_page .= "://";
							 $current_page .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
							 $project_link=get_permalink($campaign_id);
							 $news_link=esc_url($category_link);
							 $forum_link=get_permalink($forum->ID).$campaign_id_param;
							 $stats_link=get_permalink($statistiques->ID).$campaign_id_param;
							 ?>
							<li><a href="<?php echo $project_link; ?>" <?php if($current_page==$project_link) echo 'class="current"'; ?>>Le projet</a></li>
							<li><a href="<?php echo $news_link; ?>" <?php if($current_page==$news_link) echo 'class="current"'; ?>>Actualit&eacute;<?php echo  $nb_cat; ?></a></li>
							<li><a href="<?php echo $forum_link; ?>" <?php if($current_page==$forum_link) echo 'class="current"'; ?>>Forum</a></li>
							<li><a href="<?php echo $stats_link; ?>" <?php if($current_page==$stats_link) echo 'class="current"'; ?>>Statistiques</a></li>
						</ul>
					<nav>
				</div>
			</div>
            <div id='img-container' style="top:<?php $cover_position=get_post_meta($post->ID,'campaign_cover_position',TRUE); if($cover_position!='') echo $cover_position;?>">
            	<?php 
					$img_src = '';			
					$attachments = get_posts( array(
					    'post_type' => 'attachment',
					    'post_parent' => $post->ID,
					    'post_mime_type' => 'image'
					));
				$image_obj = '';
				//Si on en trouve bien une avec le titre "image_home" on prend celle-là
				foreach ($attachments as $attachment) {
	    			if ($attachment->post_title == 'image_header') $image_obj = wp_get_attachment_image_src($attachment->ID, "full");
				}
				//Sinon on prend la première image rattachée à l'article
				if ($image_obj == '' && count($attachments) > 0) $image_obj = wp_get_attachment_image_src($attachments[0]->ID, "full");
				if ($image_obj != '') $img_src = $image_obj[0];?>
            	   <img id="moved-img" src="<?php echo $img_src; ?>" />
            </div>
            <?php 
			$cache_result=ob_get_contents();
			$WDG_cache_plugin->set_cache('project-'.$campaign_id.'-header-second',$cache_result);
			ob_end_clean();
			}
			echo $cache_result;
			
            if ($can_modify) {?>
            	<a href="#" id="reposition-cover" onclick='javascript:WDGProjectPageFunctions.move_picture(<?php if(isset($_GET['campaign_id'])){echo $_GET['campaign_id'];}else{global $post;echo($post->ID); } ?>)'>Repositionner</a>
            <?php }
            ?>
			
		</div>
		
</section>