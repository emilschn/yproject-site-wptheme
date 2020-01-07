<?php
    $page_controler = WDG_Templates_Engine::instance()->get_controler();
    global $campaign_id, $organization_obj, $WDGOrganization;

    if ( isset( $organization_obj ) ) {
    $WDGOrganization = $organization_obj;
?>

<div id="stat-subtab-orga-parameters" class="stat-subtab">
    <?php 
        locate_template( array( 'pages/view/common/form-orga-parameters.php'  ), true );
    ?>	
</div>

<?php 
}