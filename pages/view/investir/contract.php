<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php
$fields_hidden = $page_controler->get_form()->getFields( WDG_Form_Invest_Contract::$field_group_hidden );
?>

<form action="<?php echo $page_controler->get_form_action(); ?>" method="post" class="db-form v3 full bg-white enlarge">
	
	<div class="align-left">
		<?php $form_errors = $page_controler->get_form_errors(); ?>
		<?php if ( $form_errors ): ?>
			<?php foreach ( $form_errors as $form_error ): ?>
				<span class="invest_error"><?php echo $form_error[ 'text' ]; ?></span>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	
	<div id="contract-intro">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/common/picto-contrat.png" width="150" />
		<?php echo $page_controler->get_contract_warning(); ?>
	</div>
	
	<?php locate_template( array( 'pages/view/moyen-de-paiement/list.php'  ), true ); ?>
	
	<div id="change-mean-payment" class="align-center hidden">
		<a href="#" class="button transparent"><?php _e( "Modifier le moyen de paiement", 'yproject' ); ?></a>
	</div>
	
	<div id="contract-preview" class="hidden">
		<?php echo $page_controler->get_current_investment_contract_preview(); ?>
	</div>

	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	
	<div id="contract-buttons" class="hidden">
		<br /><br /><br />

		<button type="submit" class="button half right red"><?php _e( "Valider le contrat", 'yproject' ); ?></button>

		<button type="submit" name="nav" value="previous" class="button half left transparent"><?php _e( "Pr&eacute;c&eacute;dent", 'yproject' ); ?></button>

		<div class="clear"></div>
	</div>
</form>

