<?php
/******************************************************************************/
/* PAGE PROJET */
/******************************************************************************/
function printPageTop($post) {
    ?>
    <div id="post_top_bg">
	<div id="post_top_title" class="center" style="background-image: url('<?php 
		if (WP_DEBUG) {$debug_src = 'http://localhost/taffe/wp-yproject-site/wp-content/themes/yproject/todo.jpg';} else {$debug_src = get_stylesheet_directory_uri();}
		$attachments = get_posts('post_type=attachment');
		$image_src = wp_get_attachment_image_src($attachments[0]->ID, "full");
		if (isset($image_src) && !empty($image_src[0])) echo $image_src[0]; else echo $debug_src;
		?>'); background-repeat: no-repeat; background-position: center;">
	    <h1><?php the_title(); ?></h1>

	    <div>
		<a href="#">[TODO: bouton "J'y crois"] <?php echo __('Jy crois', 'yproject'); ?></a>
	    </div>

	    <div id="post_top_infos">
		<img src="" width="40" height="40" />
		<?php echo ((isset($post->campaign_location) && $post->campaign_location != '') ? $post->campaign_location : 'France'); ?>

		<?php echo get_avatar( get_the_author_meta( 'user_email' ), '40' ); ?>
		<?php echo str_replace( '<a href=', '<a rel="author" href=', bp_core_get_userlink( $post->post_author ) ); ?>
	    </div>
	</div>
    </div>
    <?php
}

function printPageBottomStart($post, $campaign) {
    ?>
    <div id="post_bottom_bg">
	<div id="post_bottom_content" class="center">
	    <div class="left post_bottom_desc">
    <?php
}

function printPageBottomEnd($post, $campaign) {
    ?>
	    </div>

	    <div class="left post_bottom_infos">
		<?php 
		$percent = $campaign->percent_completed(false);
		$width = 250 * $percent / 100;
		?>
		<div>
		    <div class="project_full_progressbg"><div class="project_full_progressbar" style="width:<?php echo $width; ?>px"></div></div>
		    <span class="project_full_percent"><?php echo $campaign->percent_completed(); ?></span>
		</div>

		<div class="post_bottom_infos_item">
		    <img src="" width="40" height="40" />
		    <?php echo $campaign->backers_count(); ?>
		</div>

		<div class="post_bottom_infos_item">
		    <img src="" width="40" height="40" />
		    <?php echo $campaign->days_remaining(); ?>
		</div>

		<div class="post_bottom_infos_item">
		    <img src="" width="40" height="40" />
		    <?php echo $campaign->current_amount() . ' / ' . $campaign->goal(); ?>
		</div>

		<div class="post_bottom_buttons">
		    <div class="dark">
			<a href="#">[TODO: ] <?php echo __('Investissez', 'yproject'); ?></a>
		    </div>
		    <div id="share_btn" class="dark">
			<a href="javascript:void(0)"><?php echo __('Participer autrement', 'yproject'); ?></a>
		    </div>
		    <div class="light">
			<?php
			    $category_slug = 'cat' . $post->ID;
			    $category_obj = get_category_by_slug($category_slug);
			    $category_link = get_category_link($category_obj->cat_ID);
			?>
			<a href="<?php echo esc_url( $category_link ); ?>" title=""><?php echo __('Blog', 'yproject'); ?></a>
		    </div>
		    <div class="light">
			<a href="#">[TODO: ] <?php echo __('Forum', 'yproject'); ?></a>
		    </div>
		    <div class="light">
			<a href="#">[TODO: ] <?php echo __('Statistiques', 'yproject'); ?></a>
		    </div>
		</div>
	    </div>

	    <div style="clear: both"></div>
	</div>
    </div>
    <?php
}

/******************************************************************************/
/* PREVIEW DES PROJETS */
/******************************************************************************/

function printPreviewProjectsVote($nb) {
    global $wpdb, $print_project_count;
    $print_project_count = 0;
    query_posts('showposts=' . $nb . '&post_type=download');
    //TODO : requête pour prendre seulement les status "en cours de vote"
    printProjectsPreview(true);
}

function printHomePreviewProjects($nb) {
    global $print_project_count;
    $print_project_count = 0;
    printPreviewProjectsTop($nb);
    printPreviewProjectsNew($nb);
}

function printPreviewProjectsTop($nb) {
    global $wpdb;
    $popularreq = "SELECT $wpdb->posts.ID FROM $wpdb->posts";
    $popularreq .= " LEFT JOIN $wpdb->postmeta AS enddate ON ($wpdb->posts.ID = enddate.post_id AND enddate.meta_key = 'campaign_end_date')";
    $popularreq .= " LEFT JOIN $wpdb->postmeta AS downloadsales ON ($wpdb->posts.ID = downloadsales.post_id AND downloadsales.meta_key = '_edd_download_sales')";
    $popularreq .= " WHERE $wpdb->posts.post_type = 'download'";
    $popularreq .= " AND STR_TO_DATE(enddate.meta_value, '%Y-%m-%d %H:%i:%s')>'" . date('Y-m-d H:i:s')."'";
    $popularreq .= " ORDER BY downloadsales.meta_value DESC LIMIT ". $nb;
    $popularproj = $wpdb->get_results($popularreq);
    if (isset($popularproj)) : 
	foreach ($popularproj as $temppost) {
	    query_posts('p='.$temppost->ID.'&post_type=download');
	    printProjectsPreview(false);
	}
    endif;
}

function printPreviewProjectsNew($nb) {
    global $wpdb;
    $popularreq = "SELECT $wpdb->posts.ID FROM $wpdb->posts";
    $popularreq .= " LEFT JOIN $wpdb->postmeta AS enddate ON ($wpdb->posts.ID = enddate.post_id AND enddate.meta_key = 'campaign_end_date')";
    $popularreq .= " WHERE $wpdb->posts.post_type = 'download'";
    $popularreq .= " AND STR_TO_DATE(enddate.meta_value, '%Y-%m-%d %H:%i:%s')>'" . date('Y-m-d H:i:s')."'";
    $popularreq .= " ORDER BY ".$wpdb->posts.".post_date DESC LIMIT ". $nb;
    $popularproj = $wpdb->get_results($popularreq);
    if (isset($popularproj)) : 
	foreach ($popularproj as $temppost) {
	    query_posts('p='.$temppost->ID.'&post_type=download');
	    printProjectsPreview(false);
	}
    endif;
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
	printSinglePreview($print_project_count, $vote);
	$print_project_count++;
    } 
    wp_reset_query();
}



function printSinglePreview($i, $vote) {
    global $campaign, $post;
    if ( ! is_object( $campaign ) )
	    $campaign = atcf_get_campaign( $post );
    ?>
    <div class="project_preview_item<?php if (($vote && $i > 0) || (!$vote && $i > 2)) echo ' mobile_hidden'; ?>">
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	
	<div class="project_preview_item_part">
	    <img src="" class="project_preview_item_img" /><br />

	    <div class="project_preview_item_desc"><?php the_excerpt(); ?></div>
	</div>
	
	<div class="project_preview_item_part">
	    <div class="project_preview_item_pictos">
		<div class="project_preview_item_picto">
		    <img src="" />
		    <?php echo ((isset($post->campaign_location) && $post->campaign_location != '') ? $post->campaign_location : 'France'); ?>
		</div>
		<div class="project_preview_item_picto">
		    <img src="" />
		    <?php echo $campaign->days_remaining(); ?>
		</div>
		<div class="project_preview_item_picto">
		    <img src="" />
		    <?php echo $campaign->goal(); ?>
		</div>
		<div class="project_preview_item_picto">
		    <img src="" />
		    <?php echo $campaign->backers_count(); ?>
		</div>
		<div style="clear: both"></div>
	    </div>


	    <?php 
	    $percent = $campaign->percent_completed(false);
	    $width = 150 * $percent / 100;
	    ?>
	    <div class="project_preview_item_progress">
		<div class="project_preview_item_progressbg"><div class="project_preview_item_progressbar" style="width:<?php echo $width; ?>px">&nbsp;</div></div>
		<span class="project_preview_item_progressprint"><?php echo $campaign->percent_completed(); ?></span>
	    </div>


	    <div class="project_preview_item_btn mobile_hidden">
		<a href="<?php the_permalink(); ?>">
		    <?php if ($vote) : ?>
			<strong><?php echo __('voter', 'yproject'); ?></strong><br />
			<?php echo __('pour ce projet', 'yproject'); ?> 
		    <?php else : ?>
			<strong><?php echo __('en savoir', 'yproject'); ?></strong><br />
			<?php echo __('plus', 'yproject'); ?> 
		    <?php endif; ?>
		</a>
	    </div>
	</div>
    </div>
    <?php
}



/*
 * SAUVEGARDE
 * 
 * 
function echoProject($tempid) {
    if (isset($tempid)) query_posts('p='.$tempid.'&post_type=download');
    else query_posts('showposts=4&post_type=download');
	
    while (have_posts()) : the_post();
	global $post;
    ?>
	<li><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'campaignify' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a> -> <?php echo $post->_edd_download_earnings; ?> récoltés sur <?php echo $post->campaign_goal ?> .:. Nombre de participants : <?php echo $post->_edd_download_sales ?> .:. Fin de la récolte : <?php echo $post->campaign_end_date; ?></li>
    <?php 
    endwhile;
    wp_reset_query();
}
	
	<strong>Les 4 derniers projets</strong><br />
	<?php 
	    echoProject('');
	?>
	
	
	<strong>Les projets en cours</strong><br />
	<?php 
	    $currentproj = $wpdb->get_results("SELECT `post_id` FROM wp_postmeta WHERE meta_key='campaign_end_date' AND STR_TO_DATE(meta_value, '%Y-%m-%d %H:%i:%s')>'" . date('Y-m-d H:i:s')."'");
	    if (isset($currentproj)) : 
	?>
		<ul>
		<?php
		    foreach ($currentproj as $postitem => $temppost) {
			echoProject($temppost->post_id);
		    }
		?>
		</ul>
	<?php 
	    endif;
	?>
	
	
	<strong>Les projets terminés</strong><br />
	<?php 
	    $endproj = $wpdb->get_results("SELECT `post_id` FROM wp_postmeta WHERE meta_key='campaign_end_date' AND STR_TO_DATE(meta_value, '%Y-%m-%d %H:%i:%s')<='" . date('Y-m-d H:i:s')."'");
	    if (isset($endproj)) : 
	?>
		<ul>
		<?php
		    foreach ($endproj as $postitem => $temppost) {
			echoProject($temppost->post_id);
		    }
		?>
		</ul>
	<?php 
	    endif;
	?>
	
	
	<strong>4 projets réussis</strong><br />
	<?php 
	    $successreq = "SELECT $wpdb->posts.ID FROM $wpdb->posts";
	    $successreq .= " LEFT JOIN $wpdb->postmeta AS downloadearnings ON ($wpdb->posts.ID = downloadearnings.post_id AND downloadearnings.meta_key = '_edd_download_earnings')";
	    $successreq .= " LEFT JOIN $wpdb->postmeta AS downloadgoal ON ($wpdb->posts.ID = downloadgoal.post_id AND downloadgoal.meta_key = 'campaign_goal')";
	    $successreq .= " WHERE $wpdb->posts.post_type = 'download'";
	    $successreq .= " AND CAST(downloadearnings.meta_value AS SIGNED) >= CAST(downloadgoal.meta_value AS SIGNED)";
	    $successproj = $wpdb->get_results($successreq);
	    if (isset($successproj)) : 
	?>
		<ul>
		<?php
		    foreach ($successproj as $postitem => $temppost) {
			echoProject($temppost->ID);
		    }
		?>
		</ul>
	<?php 
	    endif;
	?>
	
	<strong>Les 4 projets en cours avec le plus d'investisseurs</strong>
	<?php 
	    $popularreq = "SELECT $wpdb->posts.ID FROM $wpdb->posts";
	    $popularreq .= " LEFT JOIN $wpdb->postmeta AS enddate ON ($wpdb->posts.ID = enddate.post_id AND enddate.meta_key = 'campaign_end_date')";
	    $popularreq .= " LEFT JOIN $wpdb->postmeta AS downloadsales ON ($wpdb->posts.ID = downloadsales.post_id AND downloadsales.meta_key = '_edd_download_sales')";
	    $popularreq .= " WHERE $wpdb->posts.post_type = 'download'";
	    $popularreq .= " AND STR_TO_DATE(enddate.meta_value, '%Y-%m-%d %H:%i:%s')>'" . date('Y-m-d H:i:s')."'";
	    $popularreq .= " ORDER BY downloadsales.meta_value DESC LIMIT 4";
	    $popularproj = $wpdb->get_results($popularreq);
	    if (isset($popularproj)) : 
	?>
		<ul>
		<?php
		    foreach ($popularproj as $temppost) {
			echoProject($temppost->ID);
		    }
		?>
		</ul>
	<?php 
	    endif;
	?>
 * 
 * 
 * 
 */

/******************************************************************************/
/* PREVIEW DES UTILISATEURS */
/******************************************************************************/
function printPreviewUsersLastInvestors($nb) {
    global $wpdb;
    $lastinvestreq = "SELECT DISTINCT $wpdb->posts.post_author FROM $wpdb->posts";
    $lastinvestreq .= " WHERE $wpdb->posts.post_type = 'edd_payment'";
    $lastinvestreq .= " ORDER BY $wpdb->posts.post_modified DESC LIMIT " . $nb;
    $lastinvestproj = $wpdb->get_results($lastinvestreq);
    if (isset($lastinvestproj)) : 
	foreach ($lastinvestproj as $temppost) {
	    echoUser($temppost->post_author);
	}
    endif;
}

function echoUser($tempid) {
    $args = array('include' => $tempid);
    if (bp_has_members($args)) {
	while ( bp_members() ) : bp_the_member();
    ?>
    <li style="clear:both"><a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?></a><a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a></li>
    <?php
	endwhile;
    }
}

?>