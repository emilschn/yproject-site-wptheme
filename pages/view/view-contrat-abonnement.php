<?php
 	global $WDGOrganization;        
	global $stylesheet_directory_uri;
	global $shortcode_subscription_obj;
 	$page_controler = WDG_Templates_Engine::instance()->get_controler();
 	$WDGContractSubscriptionForm = $page_controler->get_contract_subscription_form();
 	$fields_hidden = $WDGContractSubscriptionForm->getFields( WDG_Form_Subscription_Contract::$field_group_hidden );
	$form_feedback = $page_controler->get_user_form_feedback();
?>


<form class="db-form v3 full bg-white enlarge" action="<?php echo $page_controler->get_form_action(); ?>" method = "post">

	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<?php if ( !empty( $form_feedback[ 'errors' ] ) ): ?>
        <?php foreach ( $form_feedback[ 'errors' ] as $error ): ?>
			<div class="wdg-message error">
				<?php echo $error[ 'text' ]; ?>
			</div>
		<?php endforeach; ?>
    <?php endif; ?>

	<div id="contract-intro">
		<?php echo $page_controler->get_contract_warning(); ?>
	</div>
	
	<div id="contract-preview">
		<?php echo $page_controler->get_current_investment_contract_preview(); ?>
	</div>
	
	<div id="contract-buttons">
		<br><br><br>
		<button type="submit" class="button right red" name="contract-action" value="validate-contract-subscription">
			<?php _e( 'invest.contract.VALIDATE_SUBSCRIPTION', 'yproject' ); ?>
		</button>
		<br><br><br>
		<button class="button left transparent" name="contract-action" value="previous-contract-subscription"> 
			<?php _e( 'common.PREVIOUS', 'yproject' ); ?>
		</button>
		<div class="clear"></div>
	</div>
</form>