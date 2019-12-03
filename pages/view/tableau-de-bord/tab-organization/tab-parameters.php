<?php
	$page_controler = WDG_Templates_Engine::instance()->get_controler();
	global $country_list;
?>

<div id="stat-subtab-parameters" class="stat-subtab hidden">
    <?php 
   		locate_template( array( 'pages/view/common/form-parameters.php'  ), true );
    ?>	
</div>