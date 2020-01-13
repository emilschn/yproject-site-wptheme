<?php
    $page_controler = WDG_Templates_Engine::instance()->get_controler();
    global $campaign_id, $organization_obj;
?>

<div id="stat-subtab-identitydocs" class="stat-subtab hidden">
    <?php 
   		locate_template( array( 'pages/view/common/form-identitydocs.php'  ), true );
    ?>	
</div>
