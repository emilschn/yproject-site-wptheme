<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php
$fields_hidden = $page_controler->get_form()->getFields( WDG_Form_Declaration_Input::$field_group_hidden );
$fields_declaration = $page_controler->get_form()->getFields( WDG_Form_Declaration_Input::$field_group_declaration );
?>

<form action="<?php echo $page_controler->get_form_action(); ?>" method="post" class="db-form v3 full bg-white" novalidate>
	
	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>

	<?php foreach ( $fields_declaration as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>

	<p class="align-justify">
		<strong><?php _e( "Votre entreprise est-elle en difficult&eacute; ?", 'yproject' ); ?></strong><br>
		<?php _e( "Contactez-nous pour que l'on vous accompagne dans les formalit&eacute;s administratives et la communication vis-&agrave;-vis des investisseurs.", 'yproject' ); ?>
		<?php _e( "Plus t&ocirc;t nous g&eacute;rons les situations difficiles ensemble, plus facilement nous pouvons pr&eacute;venir des incompr&eacute;hensions ou des difficult&eacute;s suppl&eacute;mentaires.", 'yproject' ); ?>
	</p>

	<button type="submit" class="button half right transparent clear"><?php _e( "Suivant", 'yproject' ); ?></button>

	<div class="clear"></div>

</form>

<br><br>
