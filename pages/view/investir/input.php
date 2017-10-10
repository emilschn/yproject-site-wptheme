<?php global $page_controler, $stylesheet_directory_uri; ?>

<?php
$fields_hidden = $page_controler->get_form()->getFields( WDG_Form_Invest_Input::$field_group_hidden );
$fields_amount = $page_controler->get_form()->getFields( WDG_Form_Invest_Input::$field_group_amount );
?>
	
<form method="post" class="db-form v3 full bg-white">

	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	
	<?php foreach ( $fields_amount as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	
	<div class="align-left">
		<span id="royalties-percent">TODO %</span> <?php _e( "du chiffre d'affaires pendant", 'yproject' ); ?> <?php echo $page_controler->get_current_campaign()->funding_duration(); ?> <?php _e( "ans", 'yproject' ); ?>.
	</div>
	<br /><br />
	
	<button type="submit" class="button half right transparent"><?php _e( "Suivant", 'yproject' ); ?></button>
	
	<div class="clear"></div>
	
</form>