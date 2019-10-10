<?php
global $wdg_current_field;
$input_type = empty( $wdg_current_field[ 'options' ] ) ? 'text' : $wdg_current_field[ 'options' ];
if ( empty( $wdg_current_field[ 'complementary_class' ] ) ) {
	$wdg_current_field[ 'complementary_class' ] = '';
}
?>

<?php if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'Chrome' ) !== FALSE ): ?> 
	<input autocomplete="random" type="<?php echo $input_type; ?>" name="<?php echo $wdg_current_field[ 'name' ]; ?>" id="<?php echo $wdg_current_field[ 'name' ]; ?>" value="<?php echo $wdg_current_field[ 'value' ]; ?>" class="<?php echo $wdg_current_field[ 'complementary_class' ]; ?>">

<?php else : ?>
	<input autocomplete="off" type="<?php echo $input_type; ?>" name="<?php echo $wdg_current_field[ 'name' ]; ?>" id="<?php echo $wdg_current_field[ 'name' ]; ?>" value="<?php echo $wdg_current_field[ 'value' ]; ?>" class="<?php echo $wdg_current_field[ 'complementary_class' ]; ?>">

<?php endif; ?>

