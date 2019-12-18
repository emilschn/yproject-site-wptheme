<?php
    global $WDGOrganization;
?>


<h2><?php _e( "Coordonn&eacute;es bancaires de", 'yproject' ); ?> <?php echo $WDGOrganization->get_name(); ?></h2>


<?php 
    locate_template( array( 'pages/view/common/form-orga-bank.php'  ), true );
?>