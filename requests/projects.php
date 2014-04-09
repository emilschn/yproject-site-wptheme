<?php
function queryHomePojects($nb, $temp = false) {
	global $wpdb;
	$query_options = array(
		'showposts' => $nb,
		'post_type' => 'download',
		'post_status' => 'publish',
		'meta_query' => array (
			array (
				'key' => 'campaign_end_date',
				'compare' => '>',
				'value' => date('Y-m-d H:i:s')
			)
		),
		'orderby' => 'post_date',
		'order' => 'asc'
	);
	if ($temp) {
	    $query_options['meta_query'] = array();
	    $query_options['order'] = 'desc';
	}
	query_posts( $query_options );
}
?>