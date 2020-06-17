<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php if ( $page_controler->get_display_session_lost() ): ?>
<p class="wdg-message error">
	<?php _e( "Votre parcours a subi une perte de donn&eacute;e de session. Merci de reprendre votre parcours.", 'yproject' ); ?>
</p>
<?php endif; ?>

<?php
$fields_hidden = $page_controler->get_form()->getFields( WDG_Form_Invest_Input::$field_group_hidden );
$fields_amount = $page_controler->get_form()->getFields( WDG_Form_Invest_Input::$field_group_amount );
?>

<form action="<?php echo $page_controler->get_form_action(); ?>#amounttyped" method="post" class="db-form v3 full bg-white" novalidate>
	<?php /* différencier le calculateur de royalties pour l'épargne positive */ ?>
	<?php if ($page_controler->get_current_campaign()->is_positive_savings() ): ?>
		<input type="hidden" id="is_positive_savings" value="true"><input type="hidden" id="asset_price" value="<?php echo $page_controler->get_current_campaign()->minimum_goal(); ?>">
		<input type="hidden" id="asset_singular" value="<?php echo $page_controler->get_current_campaign()->get_asset_name_singular(); ?>">
		<input type="hidden" id="asset_plural" value="<?php echo $page_controler->get_current_campaign()->get_asset_name_plural(); ?>">
		<input type="hidden" id="common_goods_turnover_percent" value="<?php echo $page_controler->get_current_campaign()->get_api_data( 'common_goods_turnover_percent' ); ?>">
	<?php else: ?>
		<input type="hidden" id="is_positive_savings" value=false">
	<?php endif; ?>
	
	<?php if ( $page_controler->is_authentication_alert_visible() ): ?>
		<p class="align-justify">
			<?php _e( "Afin de lutter contre le blanchiment d'argent, des justificatifs d'identit&eacute; vous seront demand&eacute;s au format num&eacute;rique pour authentifier votre compte.", 'yproject' ); ?>
			<?php _e( "Si vous ne les avez pas avec vous, vous aurez la possibilit&eacute; de revenir plus tard pour compl&eacute;ter votre profil et finaliser votre investissement.", 'yproject' ); ?>
			<br><br>
		</p>
	<?php endif; ?>

	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>

	<?php foreach ( $fields_amount as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>

	<div class="align-justify">
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
		<span class="hidden" id="invest_error_alert"></span>
	</div>

	<div class="align-left">
		<?php 
		$funding_duration = $page_controler->get_current_campaign()->funding_duration();
		$funding_duration_str = ( $funding_duration == 0 ) ? __( "une dur&eacute;e ind&eacute;termin&eacute;e", 'yproject' ) : $funding_duration. " " .__( "ans", 'yproject' );
		$complementary_text = '.';
		if ( $page_controler->get_current_campaign()->contract_budget_type() == 'collected_funds' ):
			$complementary_text = __( " (pourcentage indicatif).", 'yproject' );
		endif;
		?>
		
		<?php if ($page_controler->get_current_campaign()->is_positive_savings() ): ?>
			<span class="roi_percent_user">0</span> % <?php echo __( "du chiffre d'affaires de", 'yproject' ) . ' '; ?><span class="nb_assets">0</span><span class="name_assets"><?php echo ' '.$page_controler->get_current_campaign()->get_asset_name_singular(); ?></span><?php echo ' '.__( "pendant", 'yproject' ).' '.$funding_duration_str. $complementary_text; ?><br>
		<?php else: ?>
			<span class="roi_percent_user">0</span> % <?php echo __( "du chiffre d'affaires de ce projet pendant", 'yproject' ) . ' ' .$funding_duration_str. $complementary_text; ?><br>
		<?php endif; ?>
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
