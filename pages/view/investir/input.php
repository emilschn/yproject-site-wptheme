<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

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
		<?php $complementary_text = '.'; ?>
		<?php if ( $page_controler->get_current_campaign()->contract_budget_type() == 'collected_funds' ): ?>
			<?php $complementary_text = __( " (pourcentage indicatif).", 'yproject' ); ?>
		<?php endif; ?>
		<span class="number"><span id="royalties-percent">0</span> %</span> <?php _e( "du chiffre d'affaires pendant", 'yproject' ); ?> <?php echo $page_controler->get_current_campaign()->funding_duration_str() . $complementary_text; ?>
	</div>
	
	<div id="thanks-to-me">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-invest/picto-ensemble.png">
		<div>
			<span><?php _e( "Gr&acirc;ce &agrave; moi" ); ?></span><br>
			<br>
			<span class="number"><?php echo $page_controler->get_campaign_investors_number(); ?></span> <?php _e( "investisseurs" ); ?><br>
			<span class="number"><span id="amount-reached" data-current-amount="<?php echo $page_controler->get_campaign_current_amount(); ?>"><?php echo $page_controler->get_campaign_current_amount(); ?></span> &euro;</span> <?php _e( "atteints" ); ?><br>
		</div>
	</div>
	
	<button type="submit" class="button half right transparent hidden clear"><?php _e( "Suivant", 'yproject' ); ?></button>
	
	<div class="clear"></div>
	
</form>



<?php if ( $page_controler->is_warning_visible() ): ?>

	<?php ob_start(); ?>

		<?php echo $page_controler->get_warning_content(); ?>
		<br><br>
		<button class="button transparent close right"><?php _e( "Continuer", 'yproject' ); ?></button>

	<?php $lightbox_content = ob_get_contents(); ?>
	<?php ob_clean(); ?>

	<?php echo do_shortcode( '[yproject_lightbox_cornered id="invest-warning" autoopen="1" title="'.__( "Avant d'investir", 'yproject' ).'"]'.$lightbox_content.'[/yproject_lightbox_cornered]' ); ?>

<?php endif;
