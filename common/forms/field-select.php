<?php global $wdg_current_field; ?>
<select id="select-<?php echo $wdg_current_field[ 'name' ]; ?>" name="<?php echo $wdg_current_field[ 'name' ]; ?>">
<?php foreach ( $wdg_current_field[ 'options' ] as $option_value => $option_label ): ?>
<option value="<?php echo $option_value; ?>" <?php selected( $option_value, $wdg_current_field[ 'value' ] ); ?>><?php echo $option_label; ?></option>
<?php endforeach; ?>
</select>