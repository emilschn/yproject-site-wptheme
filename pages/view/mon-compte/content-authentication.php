<?php $edd_settings = get_option( 'edd_settings' ); ?>

<h2><?php _e( 'account.menu.MY_AUTHENTICATION', 'yproject' ); ?></h2>

<?php locate_template( array( 'pages/view/mon-compte/partial-authentication.php' ), true, false ); ?>

<br>

<div class="center">
	<?php echo wpautop( $edd_settings[ 'lemonway_generalities' ] ); ?>
</div>