<?php 
get_header();
date_default_timezone_set("Europe/London");
$campaign_id_param = '?campaign_id=';
if (isset($_GET['campaign_id'])) {
	$campaign_id_param .= $_GET['campaign_id'];
	$post = get_post($_GET['campaign_id']);
	$campaign = atcf_get_campaign( $post );
} else  {
	$campaign_id_param .= $post->ID;
}
	$vote_status = $campaign->campaign_status(); 
?>

<section id="projects-banner">
	<div id="projects-stats" class="center">
		<?php if ($vote_status !='preview'){ ?>
			<div id="projects-stats-content">
				<div class="projects-description-separator"></div>
				 <?php
					if($vote_status=='collecte') {
    // Affichage des stats quand on est en financement
					$percent = min(100, $campaign->percent_minimum_completed(false));
					$width = 250 * $percent / 100;
					$width_min = 0;
					
					?>
					<div>
						<div class="project_full_progressbg">
							<div class="project_full_progressbar" style="width:<?php echo $width; ?>px">
								<?php if ($width_min > 0): ?>
									<div style="width: <?php echo $width_min; ?>px; height: 100%; border: 0px; border-right: 1px solid white;">&nbsp;</div>
								<?php else: ?>
										&nbsp;
								<?php endif; ?>
							</div>
						</div>
						<span class="project_full_percent"><?php echo $campaign->percent_minimum_completed(); ?></span>
						</div>
						<div class="post_bottom_infos_item">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/personnes.png" alt="Logo personnes" />
							<?php echo $campaign->backers_count(); ?> personnes ont dèjà financé ce projet
						</div>
						<div class="post_bottom_infos_item">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/horloge.png" alt="Logo horloge" />
							Plus que <strong><?php echo $campaign->days_remaining(); ?></strong> jours !
						</div>
						<div class="post_bottom_infos_item">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cible.png" alt="Logo cible" />
							<?php echo $campaign->current_amount() . ' financés sur ' . $campaign->minimum_goal(true) ; ?>
						</div>
						<div class="projects-description-separator"></div>

						<?php 
   					 }
    
    // Affichage des stats quand on est en vote
    				else if($vote_status=='vote') {
	$nbvoters = $campaign->nb_voters();
	$remaining_vote_days = $campaign->end_vote_remaining();
?>
	<div class="post_bottom_infos_item">
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/personnes.png" alt="Logo personnes" />

		<?php if ($nbvoters == 1): ?>
		1 personne a d&eacute;j&agrave; vot&eacute;
		<?php elseif ($nbvoters > 1): echo $nbvoters; ?>
		personnes ont d&eacute;j&agrave; vot&eacute;
		<?php else: ?>
		Personne n'a vot&eacute;. Soyez le premier !
		<?php endif; ?>
	</div>

	<div class="post_bottom_infos_item">
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/horloge.png" alt="Logo personnes" />
		
		<?php if ($remaining_vote_days > 0) : ?>
		Il reste <strong><?php echo $campaign->end_vote_remaining(); ?></strong> jours pour voter
		<?php else: ?>
		Le vote est termin&eacute;
		<?php endif; ?>
	</div>
	<div class="projects-description-separator"></div>
<?php 
}

//Les boutons
?>

	<div class="post_bottom_buttons">
		<?php 
		    // Affichage du bouton investir : Statut du projet == 'collecte' && Fiche du porteur de projet complète
		    if ($vote_status == 'collecte' && ypcf_check_user_is_complete($post->post_author) && $campaign->days_remaining() > 0) {
		?> 
		<div id="invest-button" >
			<?php $page_invest = get_page_by_path('investir'); ?>
			<a href="<?php echo get_permalink($vote_post->ID); ?>" class="description-discover"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle">Investir sur ce projet<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"></a>
		</div>
		<?php
			}else if($vote_status == 'vote' && ypcf_check_user_is_complete($post->post_author) && $campaign->days_remaining() > 0) {
		?>		<div id="invest-button" >
					<a href="javascript:print_vote_form()" class="description-discover"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_droite.png" alt="triangle">Voter pour ce projet<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blanc_vers_gauche.png" alt="triangle"></a>
				</div>
		<?php
			}
		?>
	    
		<?php
		    // Affichage du bouton voter et de la zone de vote : Statut du projet == 'vote'
		  //  if ($vote_status == 'vote') require_once('single-voteform.php');
		?>

		<!-- Zone de partage -->
		<div class="stats_btn" class="dark">
			<a href="javascript:void(0)">J'y crois</a>
		</div>
		<div class="stats_btn" class="dark">
			<a href="javascript:void(0)">Partager</a>
		</div>
		<?php do_shortcode('[yproject_crowdfunding_jcrois]'); ?>
		
		<div id="share_btn_zone" style="display: none;" class="light">
			<a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode(get_permalink( $post->ID )); ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook_bouton_partager.png" alt="Bouton Facebook" /></a>
			<br />

			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
			<a href="https://twitter.com/share" class="twitter-share-button" data-via="wedogood_co" data-lang="fr"><?php _e('Partager sur Twitter', 'yproject'); ?></a>
			<br />
		</div>
		<!-- FIN Zone de partage -->
	</div>

</div>
<?php } ?>
		</div>
		<div id="head-image">
			<div class="center">
				<div id="head-content">
					<p id="title"> <?php echo get_the_title(); ?></p>
					<img src="<?php echo get_stylesheet_directory_uri();?>/images/fond_projet.png"></img>
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
					<nav>
						<ul>
							<li><a href="<?php echo get_permalink($campaign_id) . $params_full; ?>">Le projet</a></li>
							<li><a href="<?php echo esc_url( $category_link ); ?>">Actualités</a></li>
							<li><a href="<?php echo get_permalink($forum->ID); ?>">Forum</a></li>
							<li><a href="<?php echo get_permalink($statistiques->ID); ?>">Statistiques</a></li>
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
            <?php global $current_user_id;
            		global $post;
            if ($can_modify) {?>
            	<a href="#" id="reposition-cover" onclick='javascript:move_picture(<?php if(isset($_GET['campaign_id'])){echo $_GET['campaign_id'];}else{global $post;echo($post->ID); } ?>)'>Repositionner</a>
            <?php }
            ?>
			
		</div>
		
</section>