<?php
    global $WDGOrganization;
?>

<h2><?php _e( "Justificatifs d'identitification de", 'yproject' ); ?> <?php echo $WDGOrganization->get_name(); ?></h2>


<?php 
    locate_template( array( 'pages/view/common/form-orga-identitydocs.php'  ), true );
?>