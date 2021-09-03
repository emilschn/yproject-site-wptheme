<h2><?php _e( 'account.menu.MY_AUTHENTICATION', 'yproject' ); ?></h2>

<?php locate_template( array( 'pages/view/mon-compte/partial-authentication.php' ), true, false ); ?>

<br>

<div class="center">
	<?php
	// La récupération de la locale ne devrait pas être nécessaire, ça devrait déjà être fait... mais apparemment non !
	global $locale;
	$locale = WDG_Languages_Helpers::get_current_locale_id();
	echo wpautop( WDGConfigTexts::get_config_text_by_name( WDGConfigTexts::$type_info_lemonway, 'lemonway_generalities' ) );
	?>
</div>