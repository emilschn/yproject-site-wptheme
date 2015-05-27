<?php 
global $disable_logs; $disable_logs = TRUE;
$campaign = atcf_get_current_campaign();

if ($campaign->current_user_can_edit()) {
?>
		
<h2>Liste des investisseurs</h2>
<i>Si vous envoyez un mail group&eacute; &agrave; vos investisseurs, pensez &agrave; les mettre dans le champ CCI, pour qu&apos;ils n&apos;aient pas acc&egrave;s aux adresses des autres.</i><br /><br />

<div id="ajax-investors-load" class="center" style="text-align: center;" data-value="<?php echo $campaign->ID?>"><img id="ajax-loader-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>

<?php
}
?>