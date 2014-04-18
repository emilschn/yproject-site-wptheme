<?php
function query_projects_preview($nb){
	queryHomePojects($nb,'preview');
}
function query_projects_vote($nb){
	 queryHomePojects($nb,'vote');
}
function query_projects_collecte($nb){
	 queryHomePojects($nb,'collecte');
}
function query_projects_funded($nb){
	 queryHomePojects($nb,'funded');
}
function query_projects_archive($nb){
	 queryHomePojects($nb,'archive');
}

function queryHomePojects($nb,$type) {
	global $wpdb;
	$query_options = array(
		'showposts' => $nb,
		'post_type' => 'download',
		'post_status' => 'publish',
		'meta_query' => array (

			array (
				'key' => 'campaign_vote',
				'value' => $type
				),
			array (
				'key' => 'campaign_end_date',
				'compare' => '<',
				'value' => date('Y-m-d H:i:s')
			)
		),
		'orderby' => 'post_date',
		'order' => 'asc'
	);
	query_posts( $query_options );

}
?>