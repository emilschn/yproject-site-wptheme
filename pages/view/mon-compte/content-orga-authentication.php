<?php
global $WDGOrganization;
?>

<h2><?php _e( 'account.authentication.orga.AUTHENTICATION_OF', 'yproject' ); ?> <?php echo $WDGOrganization->get_name(); ?></h2>

<?php locate_template( array( 'pages/view/mon-compte/partial-authentication.php' ), true, false ); ?>

<br><br>

<div class="center">
	<?php echo wpautop( WDGConfigTexts::get_config_text_by_name( WDGConfigTexts::$type_info_lemonway, 'lemonway_generalities' ) ); ?>
</div>
