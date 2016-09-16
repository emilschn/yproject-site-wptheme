<?php
global $campaign, $stylesheet_directory_uri;
$campaign_status = $campaign->campaign_status();
$status_names = ATCF_Campaign::get_campaign_status_list();
$status_list = array(
	ATCF_Campaign::$campaign_status_vote		=> __("En vote", "yproject"),
	ATCF_Campaign::$campaign_status_collecte	=> __("En financement", "yproject"),
	ATCF_Campaign::$campaign_status_funded		=> __("Financ&eacute;", "yproject"),
	ATCF_Campaign::$campaign_status_archive		=> __("Termin&eacute;", "yproject"),
);
?>
<div class="project-timeline">
	<?php foreach ($status_list as $status_key => $status_label): ?>
		<?php if (
			($status_key != ATCF_Campaign::$campaign_status_archive && $status_key != ATCF_Campaign::$campaign_status_funded)
			|| ($status_key == ATCF_Campaign::$campaign_status_funded && $campaign_status != ATCF_Campaign::$campaign_status_archive)
			|| ($status_key == ATCF_Campaign::$campaign_status_archive && $campaign_status == ATCF_Campaign::$campaign_status_archive)
			):
		?>
		<span class="project-status-<?php echo $status_key; ?> <?php if ($status_key == $campaign_status) { echo 'selected'; } ?>"><?php echo $status_label; ?></span>
		<?php endif; ?>
		
		<?php if ($status_key != ATCF_Campaign::$campaign_status_archive && $status_key != ATCF_Campaign::$campaign_status_funded): ?><span class="greater">&gt;</span><?php endif; ?>
	<?php endforeach; ?>
</div>