<?php
global $campaign, $stylesheet_directory_uri;
$campaign_status = $campaign->campaign_status();
$status_list = array(
    "vote"		=> __("En vote", "yproject"),
    "collecte"	=> __("En financement", "yproject"),
    "funded"	=> __("R&eacute;alisation", "yproject"),
    "archive"	=> __("Collecte termin&eacute;e", "yproject"),
);
?>
<div class="project-timeline center">
	<?php foreach ($status_list as $status_key => $status_label): ?>
		<?php if (
			($status_key != 'archive' && $status_key != 'funded')
			|| ($status_key == 'funded' && $campaign_status != 'archive')
			|| ($status_key == 'archive' && $campaign_status == 'archive')
			):
		?>
		<span class="project-status-<?php echo $status_key; ?> <?php if ($status_key == $campaign_status) { echo 'selected'; } ?>"><?php echo $status_label; ?></span>
		<?php endif; ?>
		
		<?php if ($status_key != 'archive' && $status_key != 'funded'): ?><span class="greater">&gt;</span><?php endif; ?>
	<?php endforeach; ?>
</div>