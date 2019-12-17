<?php 
    global $WDGOrganization;
?>


<h2><?php _e( "Informations de", 'yproject' ); ?> <?php echo $WDGOrganization->get_name(); ?></h2>

<?php 
    locate_template( array( 'pages/view/common/form-orga-parameters.php'  ), true );
?>