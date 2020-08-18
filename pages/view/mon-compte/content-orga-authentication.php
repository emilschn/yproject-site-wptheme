<?php
global $WDGOrganization;
?>

<h2><?php _e( 'account.authentication.orga.AUTHENTICATION_OF', 'yproject' ); ?> <? echo $WDGOrganization->get_name(); ?></h2>

<?php locate_template( array( 'pages/view/mon-compte/partial-authentication.php' ), true, false ); ?>

<br><br>
<?php $edd_settings = get_option( 'edd_settings' ); ?>

<div class="center">
	<?php echo wpautop( $edd_settings[ 'lemonway_generalities' ] ); ?>
</div>
