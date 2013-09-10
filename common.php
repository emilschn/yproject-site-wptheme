<?php
/******************************************************************************/
/* PAGE PROJET */
/******************************************************************************/
function printPageTop($post) {
    ?>
    <div id="post_top_bg">
	<div id="post_top_title" class="center" style="background-image: url('<?php 
		if (WP_DEBUG) {$debug_src = 'http://localhost/taffe/wp-yproject-site/wp-content/themes/yproject/todo.jpg';} else {$debug_src = get_stylesheet_directory_uri();}
		if (isset($_GET["campaign_id"])) {
		    $post = get_post($_GET["campaign_id"]);
		}
		$attachments = get_posts(
		    array('post_type' => 'attachment',
		    'post_parent' => $post->ID,
		    'post_mime_type' => 'image')
		);
		$image_src = wp_get_attachment_image_src($attachments[0]->ID, "full");
		if (isset($image_src) && !empty($image_src[0])) echo $image_src[0]; else echo $debug_src;
		?>'); background-repeat: no-repeat; background-position: center;">
	    
		<h1><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></h1>

	    <?php /*
	    <div>
		<a href="#">[TODO: bouton "J'y crois"] <?php echo __('Jy crois', 'yproject'); ?></a>
	    </div>
	     * 
	     */ ?>

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
		$percent = min(100, $percent);
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
		    <?php if ($campaign->vote()=='vote') 
		    {
		    	echo  0;
		    }else
		    {
		     	echo $campaign->days_remaining(); 
			}
			?>
		</div>

		<div class="post_bottom_infos_item">
		    <img src="" width="40" height="40" />
		    <?php echo $campaign->current_amount() . ' / ' . $campaign->goal(); ?>
		</div>

		<div class="post_bottom_buttons">
		    <div class="dark">
			<?php 
			    /* Lien Investissez */ $page_invest = get_page_by_path('investir');
			    $campaign_id_param = '?campaign_id=';
			    if (isset($_GET['campaign_id'])) $campaign_id_param .= $_GET['campaign_id'];
			    else $campaign_id_param .= get_the_ID();
			    
			    if ($campaign->vote() == 'vote') {
			?>
			    <a href="javascript:void(0);" onclick="javascript:alert('<?php echo __('Bient&ocirc;t !', 'yproject'); ?>');"><?php echo __('Investissez', 'yproject'); ?></a>
			<?php	
			    } else {
			?>
			<a href="<?php echo get_permalink($page_invest->ID); ?><?php echo $campaign_id_param; ?>"><?php echo __('Investissez', 'yproject'); ?></a>
			<?php
			    }
			?>
		    </div>
		    <div id="share_btn" class="dark">
			<a href="javascript:void(0)"><?php echo __('Participer autrement', 'yproject'); ?></a>
		    </div>
		    <div class="light">
			<?php
			    $category_slug = $post->ID . '-blog-' . $post->post_title;
			    $category_obj = get_category_by_slug($category_slug);
			    $category_link = (!empty($category_obj)) ? get_category_link($category_obj->cat_ID) : '';
			?>
			<a href="<?php echo esc_url( $category_link ); ?>" title=""><?php echo __('Blog', 'yproject'); ?></a>
		    </div>
		     <div class="light">
			<?php /* Lien statistiques*/ $forum = get_page_by_path('forum'); ?>
      		<a href="<?php echo get_permalink($forum->ID); ?><?php echo $campaign_id_param; ?>"> <?php echo __('Forum', 'yproject'); ?></a>
		    </div>
		    <div class="light">
			<?php /* Lien forum*/ $statistiques = get_page_by_path('statistiques'); ?>
      		<a href="<?php echo get_permalink($statistiques->ID); ?><?php echo $campaign_id_param; ?>"> <?php echo __('Statistiques', 'yproject'); ?></a>
		    </div>
		   
		</div>
	    </div>

	    <div style="clear: both"></div>
	</div>
		    
	<div id="popup_share">
	    <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink( $post->ID )); ?>&amp;layout=button_count&amp;show_faces=true&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=30" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:20px; text-align: center" allowTransparency="true"></iframe>
	    <a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode(get_permalink( $post->ID )); ?>" target="_blank"><?php echo __('Partager sur Facebook', 'yproject'); ?></a>
	    <br />

	    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
	    <a href="https://twitter.com/share" class="twitter-share-button" data-via="yproject_co" data-lang="fr"><?php echo __('Partager sur Twitter', 'yproject'); ?></a>
	    <br />

	    <a id="popup_share_close" href="javascript:void(0)">[<?php echo __('Fermer', 'yproject'); ?>]</a>
	</div>
    </div>
    <?php
}


function printAdminBar() {
    // La barre d'admin n'apparait que pour l'admin du site et pour l'admin de la page
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;
    $author_id = get_the_author_meta('ID');
    if ($current_user_id == $author_id || current_user_can('manage_options')) {
	$campaign_id_param = '?campaign_id=';
	if (isset($_GET['campaign_id'])) $campaign_id = $_GET['campaign_id'];
	else $campaign_id = get_the_ID();
	$campaign_id_param .= $campaign_id; 
    ?>
	<div id="yp_admin_bar" class="center">
	    <?php /* Lien page projet */ ?>
	    <a href="<?php echo get_permalink($campaign_id); ?>"><?php echo __('Page projet', 'yproject'); ?></a>
	    .:|:.
	    <?php /* Lien gerer un projet */ $page_manage = get_page_by_path('gerer'); ?>
	    <a href="<?php echo get_permalink($page_manage->ID); ?><?php echo $campaign_id_param; ?>"><?php echo __('G&eacute;rer vos informations', 'yproject'); ?></a>
	    .:|:.
	    <?php /* Lien ajouter une actu */ $page_add_news = get_page_by_path('ajouter-une-actu'); ?>
	    <a href="<?php echo get_permalink($page_add_news->ID); ?><?php echo $campaign_id_param; ?>"><?php echo __('Ajouter une actualit&eacute', 'yproject'); ?></a>
	     .:|:.
        <?php /* Lien resultats des votes*/ $vote = get_page_by_path('vote'); ?>
        <a href="<?php echo get_permalink($vote->ID); ?><?php echo $campaign_id_param; ?>"><?php echo __('Stats des votes', 'yproject'); ?></a>

    </div>
    <?php }
}

/******************************************************************************/
/* PREVIEW DES PROJETS */
/******************************************************************************/

function printHomePreviewProjectsTemp($nb) {
    global $wpdb, $print_project_count;
    $print_project_count = 0;
    query_posts( array(
	'showposts' => $nb,
	'post_type' => 'download',
	'meta_query' => array (
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
    ?>
    <div class="projects_preview<?php if ($vote) { ?> projects_vote<?php } else { ?> projects_current projects_current_temp<?php } ?>">
	<div class="preview_item_<?php echo $post->ID; ?> project_preview_item<?php if (($vote && $i > 0) || (!$vote && $i > 2)) echo ' mobile_hidden'; ?>">
	    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

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
	    if ($attachments) $image_src = wp_get_attachment_image_src($attachments[0]->ID, "medium");
	    ?>

	    <div class="project_preview_item_part">
		<img src="<?php if (isset($image_src) && !empty($image_src[0])) echo $image_src[0]; else echo $debug_src; ?>" class="project_preview_item_img" /><br />

		<div class="project_preview_item_desc"><?php echo html_entity_decode($campaign->summary()); ?></div>
	    </div>

	    <?php if ($vote) : ?>
		<a href="<?php the_permalink(); ?>"><?php _e('Soutenez le projet en votant !', 'yproject'); ?></a>
	    <?php else: ?>
		<div class="project_preview_item_part">
		    <div class="project_preview_item_pictos">
		    <div class="project_preview_item_picto">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/france.png" />
			<?php echo ((isset($post->campaign_location) && $post->campaign_location != '') ? $post->campaign_location : 'France'); ?>
		    </div>
		    <div class="project_preview_item_picto">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/horloge.png" />
			<?php echo $campaign->days_remaining(); ?>
		    </div>
		    <div class="project_preview_item_picto">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cible.png" />
			<?php echo $campaign->goal(); ?>
		    </div>
		    <div class="project_preview_item_picto">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png" />
			<?php echo $campaign->backers_count(); ?>
		    </div>
		    <div style="clear: both"></div>
		    </div>


		    <div class="project_preview_item_progress">
		    <?php
			$percent = $campaign->percent_completed(false);
			$percent = min(100, $percent);
			$width = 150 * $percent / 100;
			?>
			<div class="project_preview_item_progressbg"><div class="project_preview_item_progressbar" style="width:<?php echo $width; ?>px">&nbsp;</div></div>
			<span class="project_preview_item_progressprint"><?php echo $campaign->percent_completed(); ?></span>
		    </div>
		</div>
	    <?php endif; ?>
	</div>
	<div style="clear: both"></div>
    </div>
    <?php
}

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

		
/******************************************************************************/		
/* PAGE PROFILS UTILISATEURS */		
/******************************************************************************/		
function printUserProfileAdminBar($skip_controls = false) {		
    // La barre d'admin n'apparait que pour l'admin du site et si on est l'utilisateur qu'on affiche		
    $current_user = wp_get_current_user();		
    $current_user_id = $current_user->ID;		
    $displayed_user_id = bp_displayed_user_id();		
    if ($skip_controls || $current_user_id == $displayed_user_id) {		
    ?>		
	<div id="yp_admin_bar">		
		<div class="center">		
		<?php // Lien page profil ?>		
		<a href="<?php echo bp_loggedin_user_domain(); ?>"><?php echo __('Mon profil', 'yproject'); ?></a>		
			.:|:.		
		<?php $page_investments = get_page_by_path('mes-investissements'); // Lien page investissements  ?>		
		<a href="<?php echo get_permalink($page_investments->ID); ?>"><?php echo __('Mes investissements', 'yproject'); ?></a>		
		.:|:.		
		<?php $page_update = get_page_by_path('modifier-mon-compte'); // Lien page paramètres ?>		
		<a href="<?php echo get_permalink($page_update->ID); ?>"><?php echo __('Param&egrave;tres', 'yproject'); ?></a>		
	    </div>		
    </div>		
    <?php }		
}

function printUserInvest($post_invest, $post_campaign) {
    $campaign = atcf_get_campaign( $post_campaign );
    //Date de l'investissement : echo date_i18n( get_option('date_format'), strtotime( get_post_field( 'post_date', $post->ID ) ) );
    //Statut de l'investissement : echo edd_get_payment_status( $post, true );
    //echo '<a href="' . get_permalink($campaign->ID) . '">' . $post_camp->post_title . '</a>';
    ?>
    <li>
	<div class="user_history_title left">
	    <a href="<?php echo get_permalink($campaign->ID); ?>"><?php echo $post_campaign->post_title; ?></a><br />
	    <div class="project_preview_item_progress">
	    <?php
		$percent = $campaign->percent_completed(false);
		$percent = min(100, $percent);
		$width = 150 * $percent / 100;
		?>
		<div class="project_preview_item_progressbg"><div class="project_preview_item_progressbar" style="width:<?php echo $width; ?>px">&nbsp;</div></div>
		<span class="project_preview_item_progressprint"><?php echo $campaign->percent_completed(); ?></span>
	    </div>
	</div>
	<div class="user_history_pictos left">
	    <div class="project_preview_item_pictos">
		<div class="project_preview_item_picto">
		    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/france.png" />
		    <?php 
			$campaign_location = $campaign->location();
			$exploded = explode(' ', $campaign_location);
			if (count($exploded) > 1) $campaign_location = $exploded[0];
			echo (($campaign_location != '') ? $campaign_location : 'France'); 
		    ?>
		</div>
		<div class="project_preview_item_picto">
		    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/horloge.png" />
		    <?php echo $campaign->days_remaining(); ?>
		</div>
		<div class="project_preview_item_picto">
		    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cible.png" />
		    <?php echo $campaign->goal(); ?>
		</div>
		<div class="project_preview_item_picto">
		    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png" />
		    <?php echo $campaign->backers_count(); ?>
		</div>
		<div class="project_preview_item_infos">
		    <?php echo date_i18n( get_option('date_format'), strtotime( get_post_field( 'post_date', $post_invest->ID ) ) ); ?>
		</div>
		<div class="project_preview_item_infos">
		    <?php echo __("Paiement", "yproject") . ' ' . edd_get_payment_status( $post_invest, true ); ?>
		</div>
		
		<?php
		    //N'est que visible si la collecte est toujours en cours
		    if ($campaign->is_active() && !$campaign->is_collected() && !$campaign->is_funded() && $campaign->vote() == "collecte") :
			$page_cancel_invest = get_page_by_path('annuler-un-investissement');
		?>
		<div class="project_preview_item_cancel">
		    <a href="<?php echo get_permalink($page_cancel_invest->ID); ?>"><?php _e("Annuler mon investissement", "yproject"); ?></a>
		</div>
		<?php
		    endif;
		?>
		
		<div style="clear: both"></div>
	    </div>
	</div>
	<div style="clear: both"></div>
    </li>
    <?php
}

/******************************************************************************/
/* PAGE COMMUNAUTE */
/******************************************************************************/
function printCommunityTop($title) {
    ?>
    <div id="post_top_bg">
	<div id="post_top_title" class="center" style="background-image: url('<?php 
		if (WP_DEBUG) {$debug_src = 'http://localhost/taffe/wp-yproject-site/wp-content/themes/yproject/todo.jpg';} else {$debug_src = get_stylesheet_directory_uri();}
		$attachments = get_posts(
		    array('post_type' => 'attachment',
		    'post_parent' => $post->ID,
		    'post_mime_type' => 'image')
		);
		$image_src = wp_get_attachment_image_src($attachments[0]->ID, "full");
		if (isset($image_src) && !empty($image_src[0])) echo $image_src[0]; else echo $debug_src;
		?>'); background-repeat: no-repeat; background-position: center;">
	    
		<h1><?php 
		$result = count_users();
		$user_count = $result['total_users'];
		
		if ($user_count > 50) echo 'WE ARE ' . $user_count;
		else _e($title, "yproject");
		?></h1>
	</div>
    </div>
    <?php
}

function printCommunityMenu() {
    ?>
    <div class="left post_bottom_infos">
	<div class="post_bottom_buttons">
	    <div class="dark">
		<?php $page_team = get_page_by_path('lequipe'); //Lien vers l'équipe ?>
		<a href="<?php echo get_permalink($page_team->ID); ?>"><?php _e("L&apos;&eacute;quipe", "yproject"); ?></a>
	    </div>
	    <div class="dark">
		<?php $page_makesense = get_page_by_path('makesense'); //Lien vers MakeSense ?>
		<a href="<?php echo get_permalink($page_makesense->ID); ?>">MakeSense</a>
	    </div>
	    <div class="dark">
		<?php $page_partners = get_page_by_path('partenaires'); //Lien vers Partenaires ?>
		<a href="<?php echo get_permalink($page_partners->ID); ?>"><?php _e("Partenaires", "yproject"); ?></a>
	    </div>
	    <div class="light">
		<?php $page_blog = get_page_by_path('blog'); //Lien vers l'équipe ?>
		<a href="<?php echo get_permalink($page_blog->ID); ?>"><?php _e('Blog', 'yproject'); ?></a>
	    </div>
	</div>
	
    </div>
    <?php
}

?>

<?php



// la partie droite  sur la page comment ca marche
function printCommentcamarcheright(){
	?>
		<div class="post_bottom_buttons">
							    		<div class="dark" style="color:white;">
							    			<?php /* Lien page faq */ $page_manage = get_page_by_path('faq-2'); ?>
	    									<a href="<?php echo get_permalink($page_manage->ID); ?>"><?php echo __('FAQ', 'yproject'); ?></a>
	    							    </div>
							    
							    	</div>
							   
							       	<div class="post_bottom_buttons">
							       		
							    		<div class="light" >
							    			<?php /* forum questions */  $forum = get_page_by_path('Forum Questions'); ?>
      										<a href="<?php echo get_permalink($forum->ID); ?>"> <?php echo __('FORUM Questions', 'yproject'); ?></a>
	    							    </div>
							    		
							    	</div>

							    	<div class="post_bottom_buttons">
							    		<div class="light" >
							    			<?php /* forum idees */  $forum = get_page_by_path('Forum Idees'); ?>
      										<a href="<?php echo get_permalink($forum->ID); ?>"> <?php echo __('FORUM Idées', 'yproject'); ?></a>
	    							    </div>
							    		
							    	</div>

							    	<div class="post_bottom_buttons">
							    		<div class="light" >
							    			<?php /* forum idees */  $forum = get_page_by_path('Forum Bugs'); ?>
      										<a href="<?php echo get_permalink($forum->ID); ?>"> <?php echo __('FORUM Bugs', 'yproject'); ?></a>
	    							    </div>
							    		
							    	</div>
	<?php
}

/**RENVOIE LES FAQs LES PLUS RECENTS****/
function showFaq($nb){
	global $wpdb;
	$lastfaq = "SELECT DISTINCT $wpdb->posts.post_title, $wpdb->posts.post_name FROM $wpdb->posts";
	$lastfaq .= " WHERE $wpdb->posts.post_type = 'qa_faqs'";
	$lastfaq .= " ORDER BY $wpdb->posts.post_modified DESC LIMIT " . $nb;
	$lastfaqproj = $wpdb->get_results($lastfaq);
	?>
	<div class="post_bottom_buttons">
	<div class="dark" id="tab-faq-dark">
	<?php /* Lien page faq */ $page_manage = get_page_by_path('faq-2'); ?>
		<a href="<?php echo get_permalink($page_manage->ID); ?>"><?php echo __('FAQ', 'yproject'); ?></a>
	</div>
	</div>
	<div class="post_bottom_buttons">
	
	
    <?php
	 	if (isset($lastfaqproj)) :

	 	 	foreach ($lastfaqproj as $temppost) {
	 	 		?>
	 	 		<div class="light" id="tab-faq-light">
	 	 		<?php /* Lien page faq */$page_faq = get_page_by_path('faq'); ?>
	 	 		<a href ="<?php echo get_permalink($page_faq->post_name); ?>"> <?php echo ($temppost->post_title);	?> </a>
	 	 		</div>
	 	 		<?php
	 	 	}
	 	endif;
	?>
	

	</div>
<?php
}
?>


