<?php
global $campaign, $stylesheet_directory_uri;
$campaign_status = $campaign->campaign_status();
$status_list = array(
    "preparing"	=> __("Pr&eacute;paration", "yproject"),
    "preview"	=> __("Avant-premi&egrave;re", "yproject"),
    "vote"	=> __("Vote", "yproject"),
    "collecte"	=> __("Collecte", "yproject"),
    "funded"	=> __("R&eacute;alisation", "yproject"),
    "archive"	=> __("Collecte termin&eacute;e", "yproject"),
);
?>
<div class="project-timeline center align-center">
	<img src="<?php echo $stylesheet_directory_uri; ?>/images/frise-preview.png" alt="Timeline" />
	<ul>
		<?php foreach ($status_list as $status_key => $status_label): ?>
			<?php if (
				($status_key != 'archive' && $status_key != 'funded')
				|| ($status_key == 'funded' && $campaign_status != 'archive')
				|| ($status_key == 'archive' && $campaign_status == 'archive')
				):
			?>
			<li class="status-<?php echo $campaign_status; ?> <?php if ($status_key == $campaign_status) { echo 'selected'; } ?>"><?php echo $status_label; ?></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>