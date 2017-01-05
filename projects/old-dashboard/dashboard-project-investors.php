<?php 
global $disable_logs; $disable_logs = TRUE;
$current_wdg_user = WDGUser::current();
$campaign = atcf_get_current_campaign();

if ($campaign->current_user_can_edit()) {
?>
		
<h2>Liste des <?php echo $campaign->funding_type_vocabulary()['investor_name'];?>s</h2>
<em>Si vous envoyez un mail group&eacute; &agrave; vos <?php echo $campaign->funding_type_vocabulary()['investor_name'];?>s, pensez &agrave; les mettre dans le champ CCI, pour qu&apos;ils n&apos;aient pas acc&egrave;s aux adresses des autres.</em><br /><br />


<div id="ajax-investors-load" class="ajax-investments-load" style="text-align: center;" data-value="<?php echo $campaign->ID?>">
	<img id="ajax-loader-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" />
</div>

<?php
}