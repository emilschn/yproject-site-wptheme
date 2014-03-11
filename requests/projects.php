<?php
function queryHomePojects($nb) {
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
}
?>