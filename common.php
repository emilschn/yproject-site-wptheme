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
			    $category_slug = $post->ID . '-blog-' . $post->post_title;
			    $category_obj = get_category_by_slug($category_slug);
			    $category_link = (!empty($category_obj)) ? get_category_link($category_obj->cat_ID) : '';
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
		    
	<div id="popup_share">
	    <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink( $post->ID )); ?>&amp;layout=button_count&amp;show_faces=true&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=30" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:20px; text-align: center" allowTransparency="true"></iframe>
	    <?php /*<script>function fbs_click() {u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;}</script>
	    <a rel="nofollow" href="http://www.facebook.com/share.php?u=<;url>" onclick="return fbs_click()" target="_blank"><?php echo __('Partager sur Facebook', 'yproject'); ?></a> */ ?>
	    <?php /*<a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode(get_permalink( $post->ID )); ?>%2F&t=<?php echo urlencode(get_the_title()); ?>" target="_blank"><?php echo __('Partager sur Facebook', 'yproject'); ?></a>*/ ?>
	    <a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode(get_permalink( $post->ID )); ?>" target="_blank"><?php echo __('Partager sur Facebook', 'yproject'); ?></a>
	    <br />

	    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
	    <a href="https://twitter.com/share" class="twitter-share-button" data-via="yproject_co" data-lang="fr"><?php echo __('Partager sur Twitter', 'yproject'); ?></a>
	    <?php /*<a href=""><?php echo __('Partager sur Twitter', 'yproject'); ?></a>*/ ?>
	    <br />

	    <a id="popup_share_close" href="javascript:void(0)">[<?php echo __('Fermer', 'yproject'); ?>]</a>
	</div>
    </div>
    <?php
}


function printPageVoteForm($post, $campaign) {
    ?>
	
	    </div>

	    <div class="left post_bottom_infos">
		<form action="" method="post">
		    <fieldset>
			<legend>Votez sur ce projet</legend>
			
			<input type="radio" name="radios1" id="impact_positif" value="impact_positif">
			Je pense que ce projet va avoir un impact positif
			</input>
			<div id="impact_positif_choix">
			<input type="checkbox" name="checkboxes0" id="local" value="local">
			  Local
			</input></br>
			<input type="checkbox" name="checkboxes0" id="environnemental" value="environnemental">
			  Environnemental
			</input></br>
			<input type="checkbox" name="checkboxes0" id="economique" value="economique">
			  Economique
			</input></br>
			<input type="checkbox" name="checkboxes0" id="social" value="social">
			  Social
			</label></br>
			<input type="checkbox" name="checkboxes0" id="autre" value="autre">
			  Autre
			</input>
			<input id="precision" name="checkboxes0" type="text" placeholder="précisez ici" />
			</div>
			
			<input type="radio" name="radios1" value="impact_negatif" checked="checked">
			  Je désapprouve ce projet car son impact prévu n'est pas significatif
			</input></br></br>
			
			<input type="radio" name="radios2" value="pret_collect">
			 Je pense que ce projet est prêt pour la collecte
			</input></br>
			<div>
			<input type="checkbox" id="investir" name="checkboxes1" value="investir">
			  Je serais prêt à investir
			</input>
			<input id="sum" name="sum" type="text" placeholder="200€" /></br>
			<input type="checkbox" id="risque" name="checkboxes1" value="risque">
			  Risque
			</input></br>
			<select id="liste_risque" name="liste_risque" >
			  <option id="tres_faible">Le risque très faible</option>
			  <option id="plutot_faible">Le risque plutôt faible</option>
			  <option id="modere">Le risque modéré</option>
			  <option id="plutot_eleve">Le risque plutôt élevé</option>
			  <option id="tres_eleve">Le risque très élevé</option>
			</select>
			<input type="radio" name="radios2" value="pret_collect">
			 Je pense que ce projet doit être retravaillé avant de pouvoir être financé. Sur quels points 
			</input>
			<div>
			<input type="checkbox" id="responsable" name="checkboxes" value="responsable">
			  Pas d’impact responsable
			</input></br>
			<input type="checkbox" id="mal_explique" name="checkboxes" value="mal_expliqué">
			  Projet mal expliqué  
			</input></br>
			<input type="checkbox" id="service" name="checkboxes" value="service">
			  Qualité du produit/service
			</input></br>
			<input type="checkbox" id="equipe" name="checkboxes" value="equipe">
			  Qualité de l’équipe
			</input></br>
			<input type="checkbox" id="plan" name="checkboxes" value="plan">
			  Qualité du business plan
			</input></br>
			<input type="checkbox" id="innovation" name="checkboxes" value="innovation">
			  Qualité d’innovation
			</input></br>
			<input type="checkbox" name="checkboxes" value="porteur" id="porteur">
			  Qualité du marché, porteur
			</input></br>
			<textarea type="text" name="checkboxes" id="expliquer" value="expliquer">
			  Expliquer pourquoi
			</textarea></br>
			</div>
			<input type="submit" name="valider" value="Valider"/>
			</div>
			
		    </fieldset>
		</form>
	</div>
    </div>
<?php
}

function printPageVoteForm_submit($campaign) {
	global  $post;
	
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;
	
	if ( empty( $_POST['action' ] ) || ( 'valider' !== $_POST[ 'action' ] ) )
		return;


	$impact_positif     		= $_POST[ 'impact_positif' ];
	$local            	        = $_POST[ 'local' ];
	$environnemental            = $_POST[ 'environnemental' ];
	$economique                 = $_POST[ 'economique' ];
	$social                     = $_POST[ 'social' ];
	$autre                      = $_POST[ 'autre' ];
	$precision      		    = $_POST[ 'precision' ];
	$impact_negatif    		    = $_POST[ 'impact_negatif' ];
	$pret_collect       		= $_POST[ 'pret_collect' ];
	$investir   		        = $_POST[ 'investir' ];
	$sum                		= $_POST[ 'sum' ];
	$risque                     = $_POST[ 'risque' ];
	$tres_faible                = $_POST[ 'tres_faible' ];
    $plutot_faible              = $_POST[ 'development_strategy' ];
	$modere                     = $_POST[ 'modere' ];
	$plutot_eleve               = $_POST[ 'plutot_eleve' ];
	$tres_eleve         		= $_POST[ 'tres_eleve' ];
	$responsable                = $_POST[ 'responsable' ];
	$mal_explique               = $_POST[ 'mal_explique' ];
	$service                    = $_POST[ 'service                ' ];
    $equipe                     = $_POST[ 'equipe' ];
	$plan                       = $_POST[ 'plan' ];
	$porteur                    = $_POST[ 'porteur' ];
	$expliquer          		= $_POST[ 'expliquer' ];
	
	
}

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
    printProjectsPreview(true);
    
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
    printProjectsPreview(true);
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