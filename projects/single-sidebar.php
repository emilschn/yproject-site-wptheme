<?php
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

<?php 
switch ($vote_status) {
    // Affichage des stats quand on est en financement
    case 'collecte' :
	$percent = min(100, $campaign->percent_minimum_completed(false));
	$width = 250 * $percent / 100;
	$width_min = 0;
	/*if ($percent >= 100 && $campaign->is_flexible()) {
		$percent_min = $campaign->percent_minimum_to_total();
		$width_min = 150 * $percent_min / 100;
	}*/
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
		Il reste <strong><?php echo $campaign->days_remaining(); ?></strong> jours pour participer à ce projet.
	</div>

	<div class="post_bottom_infos_item">
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cible.png" alt="Logo cible" />
		<?php echo $campaign->current_amount() . ' / ' . $campaign->minimum_goal(true) . ' (maximum : ' . $campaign->goal() . ')'; ?>
	</div>

<?php 
    break;
    
    // Affichage des stats quand on est en vote
    case 'vote':
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

<?php 
    break;
}

//Les boutons
?>

	<div class="post_bottom_buttons">
		<?php 
		    // Affichage du bouton investir : Statut du projet == 'collecte' && Fiche du porteur de projet complète
		    if ($vote_status == 'collecte' && ypcf_check_user_is_complete($post->post_author) && $campaign->days_remaining() > 0) :
		?> 
		<div class="dark">
			<?php $page_invest = get_page_by_path('investir'); ?>
			<a href="<?php echo get_permalink($page_invest->ID); ?><?php echo $campaign_id_param; ?>&invest_start=1"><?php echo __('Investir', 'yproject'); ?></a>
		</div>
		<?php
		    endif; 
		?>
	    
		<?php
		    // Affichage du bouton voter et de la zone de vote : Statut du projet == 'vote'
		    if ($vote_status == 'vote') require_once('single-voteform.php');
		?>

		<!-- Zone de partage -->
		<div id="share_btn" class="dark">
			<a href="javascript:void(0)">Partager</a>
		</div>
		<div id="share_btn_zone" style="display: none;" class="light">
			<a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode(get_permalink( $post->ID )); ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook_bouton_partager.png" alt="Bouton Facebook" /></a>
			<br />

			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
			<a href="https://twitter.com/share" class="twitter-share-button" data-via="wedogood_co" data-lang="fr"><?php _e('Partager sur Twitter', 'yproject'); ?></a>
			<br />
		</div>
		<!-- FIN Zone de partage -->
		
		<div class="light">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/actu.png" alt="Logo actu" />&nbsp;
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
			<a href="<?php echo esc_url( $category_link ); ?>" title=""><?php echo __('Actualit&eacute;s', 'yproject') . $nb_cat; ?></a>
		</div>
		
		<div class="light">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/com.png" alt="Logo forum" />&nbsp;
			<?php $forum = get_page_by_path('forum'); ?>
			<a href="<?php echo get_permalink($forum->ID); ?><?php echo $campaign_id_param; ?>">Forum</a>
		</div>
		
		<?php if ($vote_status != 'preview'): ?>
		<?php
		    $upload_dir = wp_upload_dir();
		    if (file_exists($upload_dir['basedir'] . '/projets/' . $post->post_name . '-stats.jpg')):
		?>
		<div class="light">
			<?php $statistiques = get_page_by_path('statistiques'); ?>
			<a href="<?php echo get_permalink($statistiques->ID); ?><?php echo $campaign_id_param; ?>"><?php echo __('Statistiques', 'yproject'); ?></a>
		</div>
		<?php endif; ?>
		<?php endif; ?>
	</div>