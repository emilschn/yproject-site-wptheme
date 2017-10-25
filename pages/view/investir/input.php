<?php global $page_controler, $stylesheet_directory_uri; ?>

<?php
$fields_hidden = $page_controler->get_form()->getFields( WDG_Form_Invest_Input::$field_group_hidden );
$fields_amount = $page_controler->get_form()->getFields( WDG_Form_Invest_Input::$field_group_amount );
?>
	
<form action="<?php echo $page_controler->get_form_action(); ?>" method="post" class="db-form v3 full bg-white">

	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	
	<?php foreach ( $fields_amount as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	
	<div class="align-left">
		<?php $form_errors = $page_controler->get_form_errors(); ?>
		<?php if ( $form_errors ): ?>
			<?php foreach ( $form_errors as $form_error ): ?>
				<span class="invest_error"><?php echo $form_error[ 'text' ]; ?></span>
			<?php endforeach; ?>
		<?php endif; ?>
		<span class="invest_error <?php if ($current_error != "min") { ?>hidden<?php } ?>" id="invest_error_min"><?php _e("Vous devez investir au moins", 'yproject'); ?> <?php echo $page_controler->get_campaign_min_part(); ?> &euro;.</span>
		<span class="invest_error <?php if ($current_error != "max") { ?>hidden<?php } ?>" id="invest_error_max"><?php _e("Vous ne pouvez pas investir plus de", 'yproject'); ?> <?php echo $page_controler->get_campaign_max_amount(); ?> &euro;.</span>
		<span class="invest_error <?php if ($current_error != "interval") { ?>hidden<?php } ?>" id="invest_error_interval"><?php _e("Merci de ne pas laisser moins de", 'yproject'); ?> <?php echo $page_controler->get_campaign_min_amount(); ?>&euro; <?php _e("&agrave; investir.", 'yproject'); ?></span>
		<span class="invest_error <?php if ($current_error != "integer") { ?>hidden<?php } ?>" id="invest_error_integer"><?php _e("Le montant que vous pouvez investir doit &ecirc;tre entier.", 'yproject'); ?></span>
		<span class="invest_error <?php if ($current_error != "general") { ?>hidden<?php } ?>" id="invest_error_general"><?php _e("Le montant saisi semble comporter une erreur.", 'yproject'); ?></span>
	</div>
		
	<div class="align-left">
		<span id="royalties-percent">0</span> % <?php _e( "du chiffre d'affaires pendant", 'yproject' ); ?> <?php echo $page_controler->get_current_campaign()->funding_duration(); ?> <?php _e( "ans", 'yproject' ); ?>.
	</div>
	<br /><br />
	
	<button type="submit" class="button half right transparent hidden"><?php _e( "Suivant", 'yproject' ); ?></button>
	
	<div class="clear"></div>
	
</form>