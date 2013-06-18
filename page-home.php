<?php date_default_timezone_set("Europe/Paris"); ?>
<div id="content">
    <div class="padder">
	<div id="projects_vote" class="projects_preview">
	    <h1><?php echo __('Votez !', 'yproject'); ?></h1>
	    
	    <div class="project_preview_item">
		<h2>La ferme verte</h2>
		<img src="" width="100%" height="100" /><br />
		<div class="project_preview_item_desc">Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un peintre anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte.</div>
		<div class="project_preview_item_pictos">
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		</div>
		<img src="" width="100%" height="20" /><br />
		<div class="project_preview_item_votebtn">
		    <strong><?php echo __('voter', 'yproject'); ?></strong><br />
		    <?php echo __('pour ce projet', 'yproject'); ?> 
		</div>
	    </div>
	</div>
	
	<div id="projects_current" class="projects_preview">
	    <h1><?php echo __('Les projets en cours', 'yproject'); ?></h1>
	    
	    <div class="project_preview_item">
		<h2>La ferme verte</h2>
		<img src="" width="100%" height="100" /><br />
		<div class="project_preview_item_desc">Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un peintre anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte.</div>
		<div class="project_preview_item_pictos">
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		</div>
		<img src="" width="100%" height="20" /><br />
		<div class="project_preview_item_votebtn">
		    <strong><?php echo __('en savoir', 'yproject'); ?></strong><br />
		    <?php echo __('plus', 'yproject'); ?> 
		</div>
	    </div>
	    
	    <div class="project_preview_item">
		<h2>La ferme verte</h2>
		<img src="" width="100%" height="100" /><br />
		<div class="project_preview_item_desc">Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un peintre anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte.</div>
		<div class="project_preview_item_pictos">
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		</div>
		<img src="" width="100%" height="20" /><br />
		<div class="project_preview_item_votebtn">
		    <strong><?php echo __('en savoir', 'yproject'); ?></strong><br />
		    <?php echo __('plus', 'yproject'); ?> 
		</div>
	    </div>
	    
	    <div class="project_preview_item">
		<h2>La ferme verte</h2>
		<img src="" width="100%" height="100" /><br />
		<div class="project_preview_item_desc">Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un peintre anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte.</div>
		<div class="project_preview_item_pictos">
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		    <div class="project_preview_item_picto">
			<img src="" width="40" height="40" />
			66
		    </div>
		</div>
		<img src="" width="100%" height="20" /><br />
		<div class="project_preview_item_votebtn">
		    <strong><?php echo __('en savoir', 'yproject'); ?></strong><br />
		    <?php echo __('plus', 'yproject'); ?> 
		</div>
	    </div>
	</div>
	
	<div style="clear: both">
	TODO: Deux projets en cours de vote<br />
	<?php 
	    echoProject('');
	?>
	
	TODO: Trois projets nouveau<br />
	<?php 
	    echoProject('');
	?>
	
	TODO: Trois projets les plus proches de réussir<br />
	<?php 
	    echoProject('');
	?>
	
	<strong>Participer à un projet | Proposer un projet | Différenciations</strong><br />
	TODO : Diviser en deux et inclure les pages
	<?php 
	    $page_show_proj = get_page_by_title('Projets'); 
	    $page_input_proj = get_page_by_title('Proposer un projet');
	    $page_how_it_works = get_page_by_title('Comment ça marche');
	?>
	<a href="<?php echo get_permalink($page_show_proj->ID); ?>">Lien vers participer à un projet</a> (<a href="<?php echo get_permalink($page_how_it_works->ID); ?>#everyone">Lien vers Comment participer à un projet ?</a>)<br />
	<a href="<?php echo get_permalink($page_input_proj->ID); ?>">Lien vers proposer un projet</a> (<a href="<?php echo get_permalink($page_how_it_works->ID); ?>#newproject">Lien vers Comment proposer un projet ?</a>)<br />
	Schéma de ce qui fait la particularité de notre plateforme<br /><br />
	
	
	<strong>Trouver les projets</strong><br />
	TODO: une carte de France qui a un roll sur chacune des régions. Quand on clique sur une région, ça filtre les projets.<br />
	TODO: recherche des projets par centres d'intérêts<br />
	<br />
	
	
	<strong>Fil d'actualité</strong><br />
	<ul>
	    <li>TODO: une liste d'activité</li>
	</ul>
	Voir plus (TODO: un lien vers la page communauté)<br />
	Derniers projets financés ?<br />
	<br />
	
	<strong>Les 10 derniers investisseurs</strong><br />
	<?php
	    $lastinvestreq = "SELECT DISTINCT $wpdb->posts.post_author FROM $wpdb->posts";
	    $lastinvestreq .= " WHERE $wpdb->posts.post_type = 'edd_payment'";
	    $lastinvestreq .= " ORDER BY $wpdb->posts.post_modified DESC LIMIT 10";
	    $lastinvestproj = $wpdb->get_results($lastinvestreq);
	    if (isset($lastinvestproj)) : 
	?>
		<ul>
		<?php
		    foreach ($lastinvestproj as $temppost) {
			echoUser($temppost->post_author);
		    }
		?>
		</ul>
	<?php 
	    endif;
	?>
	</div>
    </div><!-- .padder -->
</div><!-- #content -->


<?php get_sidebar(); ?>



<?php 

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

/*
 * SAUVEGARDE
 * 
 * 
	
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



?>