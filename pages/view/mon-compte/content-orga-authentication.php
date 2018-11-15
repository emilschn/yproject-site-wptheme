<?php
global $WDGOrganization;
?>

<h2><?php _e( 'Authentification de ', 'yproject' ); echo $WDGOrganization->get_name(); ?></h2>

<?php locate_template( array( 'pages/view/mon-compte/partial-authentication.php' ), true, false ); ?>

<br><br>
<?php $edd_settings = get_option( 'edd_settings' ); ?>
<?php echo wpautop( $edd_settings[ 'lemonway_generalities' ] );