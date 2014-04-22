<?php
function query_projects_preview($nb=0){
	return queryHomeProjects($nb,'preview');
}
function query_projects_vote($nb=0){
	return queryHomeProjects($nb,'vote');
}
function query_projects_collecte($nb=0){
	return queryHomeProjects($nb,'collecte');
}
function query_projects_funded($nb=0){
	return queryHomeProjects($nb,'funded');
}
function query_projects_archive($nb=0){
	return queryHomeProjects($nb,'archive');
}

function queryHomeProjects($nb,$type) {
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
				'compare' => '>',
				'value' => date('Y-m-d H:i:s')
			)
		),
		'orderby' => 'post_date',
		'order' => 'asc'
	);
	return query_posts( $query_options );

}
?>