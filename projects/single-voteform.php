<?php
if (isset($_GET['campaign_id'])) {
	$post = get_post($_GET['campaign_id']);
	$campaign = atcf_get_campaign( $post );
}
?>
<div id="project_vote_link" class="dark" style="color: #FFF">Voter</div>
<div id="project_vote_zone">
<?php
if ($campaign->end_vote_remaining() > 0) {
	do_shortcode('[yproject_crowdfunding_printPageVoteForm remaining_days='.$campaign->end_vote_remaining().']');
} else {
	do_shortcode('[yproject_crowdfunding_printPageVoteDeadLine]');
}
?>
</div>