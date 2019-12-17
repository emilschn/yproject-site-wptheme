<?php
	$page_controler = WDG_Templates_Engine::instance()->get_controler();
	global $campaign_id, $organization_obj, $WDGOrganization;
?>

<div id="stat-subtab-orga-identitydocs" class="stat-subtab hidden">
	<?php 
		locate_template( array( 'pages/view/common/form-orga-identitydocs.php'  ), true );
    ?>
</div>

