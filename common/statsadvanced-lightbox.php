
<h2 class="expandator" data-target="general">G&eacute;n&eacute;ral <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" alt="signe plus"/></h2>
<div id="extendable-general" class="expandable">
    <?php locate_template( array("projects/single-stats-advanced-main.php"), true );?>
</div>

<h2 class="expandator" data-target="votes">Votes <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" alt="signe plus"/></h2>
<div id="extendable-votes" class="expandable">
<?php
    locate_template( array("projects/single-stats-advanced-votes.php"), true );
    /*
	$post_campaign = get_post($_GET["campaign_id"]);
	$upload_dir = wp_upload_dir();
	if (file_exists($upload_dir['basedir'] . '/projets/' . $post_campaign->post_name . '-stats.jpg')) { 
		echo '<img src="'.$upload_dir['baseurl'] . '/projets/' . $post_campaign->post_name . '-stats.jpg" alt="Statistiques du projet" />';
	} else {
		locate_template( array("requests/votes.php"), true );
		locate_template( array("projects/stats-votes-public.php"), true );
		$vote_results = wdg_get_project_vote_results($_GET['campaign_id']);
		print_vote_results($vote_results);
	}*/
?>
</div>

<h2 class="expandator" data-target="investments">Investissements <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" alt="signe plus" /></h2>
<div id="extendable-investments" class="expandable">
<?php 
    locate_template( array("projects/single-stats-advanced-investments.php"), true );
/*
	locate_template( array("requests/investments.php"), true );
	locate_template( array("projects/stats-investments-public.php"), true );
	$investments_list = wdg_get_project_investments($_GET['campaign_id']);
	print_investments($investments_list);*/
?>
</div>