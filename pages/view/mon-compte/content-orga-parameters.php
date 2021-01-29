<?php 
    global $WDGOrganization;
?>


<h2><?php _e( 'account.parameters.orga.TITLE', 'yproject' ); ?> <?php echo $WDGOrganization->get_name(); ?></h2>

<?php 
    locate_template( array( 'pages/view/common/form-orga-parameters.php'  ), true, false );
?>