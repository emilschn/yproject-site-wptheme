<?php
    global $WDGOrganization;
?>


<h2><?php _e( 'account.bank.orga.BANK_DETAILS_OF', 'yproject' ); ?> <?php echo $WDGOrganization->get_name(); ?></h2>


<?php 
    locate_template( array( 'pages/view/common/form-orga-bank.php'  ), true, false );
?>