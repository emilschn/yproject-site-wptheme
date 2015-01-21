<?php

/******************************************************************************/
/* PREVIEW DES PROJETS */
/******************************************************************************/
function printPreviewProjectsVote($nb) {
    global $wpdb, $print_project_count;
    $print_project_count = 0;
    query_posts( array(
	'showposts' => $nb,
	'post_type' => 'download',
	'meta_query' => array (
	    array (
		'key' => 'campaign_vote',
		'compare' => '=',
		'value' => 'vote'
	    ),
	    array (
		'key' => 'campaign_end_date',
		'compare' => '>',
		'value' => date('Y-m-d H:i:s')
	    )
	)
    ) );
    printProjectsPreview(true);
}

function printHomePreviewProjects($nb) {
    global $print_project_count;
    $print_project_count = 0;
    printPreviewProjectsTop($nb);
    printPreviewProjectsNew($nb);
}

function printPreviewProjectsTop($nb) {
    global $wpdb, $print_project_count;
    $print_project_count = 0;
    query_posts( array(
	'showposts' => $nb,
	'post_type' => 'download',
	'meta_query' => array (
	    array (
		'key' => 'campaign_vote',
		'compare' => 'NOT LIKE',
		'value' => 'vote'
	    ),
	    array (
		'key' => 'campaign_end_date',
		'compare' => '>',
		'value' => date('Y-m-d H:i:s')
	    )
	),
	'orderby' => '_edd_download_sales',
	'order' => 'desc'
    ) );
    printProjectsPreview(false);
    
}

function printPreviewProjectsNew($nb) {
    global $wpdb, $print_project_count;
    $print_project_count = 0;
    query_posts( array(
	'showposts' => $nb,
	'post_type' => 'download',
	'meta_query' => array (
	    array (
		'key' => 'campaign_vote',
		'compare' => 'NOT LIKE',
		'value' => 'vote'
	    ),
	    array (
		'key' => 'campaign_end_date',
		'compare' => '>',
		'value' => date('Y-m-d H:i:s')
	    )
	),
	'orderby' => 'post_date',
	'order' => 'desc'
    ) );
    printProjectsPreview(false);
}

function printPreviewProjectsFinished($nb) {
    global $wpdb, $print_project_count;
    $print_project_count = 0;
    $successreq = "SELECT $wpdb->posts.ID FROM $wpdb->posts";
    $successreq .= " LEFT JOIN $wpdb->postmeta AS downloadearnings ON ($wpdb->posts.ID = downloadearnings.post_id AND downloadearnings.meta_key = '_edd_download_earnings')";
    $successreq .= " LEFT JOIN $wpdb->postmeta AS downloadgoal ON ($wpdb->posts.ID = downloadgoal.post_id AND downloadgoal.meta_key = 'campaign_goal')";
    $successreq .= " WHERE $wpdb->posts.post_type = 'download'";
    $successreq .= " AND CAST(downloadearnings.meta_value AS SIGNED) >= CAST(downloadgoal.meta_value AS SIGNED)";
    $successreq .= " LIMIT " . $nb;
    $successproj = $wpdb->get_results($successreq);
    if (isset($successproj)) : 
	foreach ($successproj as $temppost) {
	    query_posts('p='.$temppost->ID.'&post_type=download');
	    printProjectsPreview(false);
	}
    endif;
}

function printProjectsPreview($vote) {
    global $print_project_count;
    while (have_posts()) {
	the_post();
	global $post;
	$vote = (get_post_meta($post->ID, 'campaign_vote', true) == 'vote');
	printSinglePreview($print_project_count, $vote);
	$print_project_count++;
    } 
    wp_reset_query();
}




function printSinglePreview($i, $vote) {
    global $post;
    $campaign = atcf_get_campaign( $post );
    
    $days_remaining = $campaign->days_remaining();
    if ($vote) {
	$days_remaining = $campaign->end_vote_remaining();
    }
    ?>
    <div class="projects_preview<?php if ($vote) { ?> projects_vote<?php } else { ?> projects_current projects_current_temp<?php } ?>">
	<div class="preview_item_<?php echo $post->ID; ?> project_preview_item<?php if (($vote && $i > 0) || (!$vote && $i > 2)) echo ' mobile_hidden'; ?>">
	    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	    <span><a href="<?php the_permalink(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" border="0" alt="signe plus"/></a></span>

	    <?php
	    if (WP_DEBUG) {$debug_src = 'http://localhost/taffe/wp-yproject-site/wp-content/themes/yproject/todo.jpg';}
	    if (isset($_GET["campaign_id"])) {
		$post = get_post($_GET["campaign_id"]);
	    }
	    $attachments = get_posts(
		array('post_type' => 'attachment',
		'post_parent' => $post->ID,
		'post_mime_type' => 'image')
	    );
	    if ($attachments) $image_src = wp_get_attachment_image_src($attachments[0]->ID, "thumbnail");
	    ?>

	    <div class="project_preview_item_part">
		<a href="<?php the_permalink(); ?>"><img src="<?php if (isset($image_src) && !empty($image_src[0])) echo $image_src[0]; else echo $debug_src; ?>" class="project_preview_item_img" alt="<?php the_title(); ?>" /></a><br />

		<div class="project_preview_item_desc"><a href="<?php the_permalink(); ?>"><?php echo html_entity_decode($campaign->summary()); ?></a></div>
	    </div>

		<div class="project_preview_item_part">
		    <div class="project_preview_item_pictos">
		    <div class="project_preview_item_picto">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/france.png" alt="logo france" />
			<?php 
			    $campaign_location = $campaign->location();
			    $exploded = explode(' ', $campaign_location);
			    if (count($exploded) > 1) $campaign_location = $exploded[0];
			    echo (($campaign_location != '') ? $campaign_location : 'France'); 
			?>
		    </div>
		    <div class="project_preview_item_picto">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/horloge.png" alt="logo horloge" />
			<?php echo $days_remaining; ?>
		    </div>
		    <div class="project_preview_item_picto">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cible.png" alt="logo cible" />
			<?php echo $campaign->minimum_goal(true); ?>
		    </div>
		    <div class="project_preview_item_picto">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png" alt="logo jy crois" />
			<?php echo $campaign->get_jycrois_nb(); ?>
		    </div>
		    <div style="clear: both"></div>
		    </div>


		<?php if ($vote) : ?>
		    <div class="project_preview_item_progress" style="text-align: center; text-transform: uppercase; padding-top: 10px;">
			<a href="<?php the_permalink(); ?>">Votez sur ce projet</a>
		    </div>
		<?php else: ?>
		    <div class="project_preview_item_progress">
		    <?php
			$percent = min(100, $campaign->percent_minimum_completed(false));
			$width = 150 * $percent / 100;
			$width_min = 0;
			if ($percent >= 100 && $campaign->is_flexible()) {
			    $percent_min = $campaign->percent_minimum_to_total();
			    $width_min = 150 * $percent_min / 100;
			}
			?>
			<a href="<?php the_permalink(); ?>">
			<div class="project_preview_item_progressbg">
			    <div class="project_preview_item_progressbar" style="width:<?php echo $width; ?>px">
				<?php if ($width_min > 0): ?>
				<div style="width: <?php echo $width_min; ?>px; height: 20px; border: 0px; border-right: 1px solid white;">&nbsp;</div>
				<?php else: ?>
				&nbsp;
				<?php endif; ?>
			    </div>
			</div>
			<span class="project_preview_item_progressprint"><?php echo $campaign->percent_minimum_completed(); ?></span>
			</a>
		    </div>
		<?php endif; ?>
		</div>
	</div>
	<div style="clear: both"></div>
    </div>
    <?php
}
?>
