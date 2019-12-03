<?php
    $page_controler = WDG_Templates_Engine::instance()->get_controler();
    global $campaign_id, $organization_obj;
    if ( isset( $organization_obj ) ) {
        $WDGOrganization = $organization_obj;
?>

<div id="stat-subtab-orga-bank" class="stat-subtab hidden">
    <?php 
        locate_template( array( 'pages/view/common/form-orga-bank.php'  ), true );
    ?>	
</div>
<?php
}