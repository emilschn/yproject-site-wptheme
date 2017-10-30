<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php
$fields_hidden = $page_controler->get_form()->getFields( WDG_Form_Invest_Contract::$field_group_hidden );
$fields_contract_validate = $page_controler->get_form()->getFields( WDG_Form_Invest_Contract::$field_group_contract_validate );
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
		<?php echo sprintf( __( "En investissant sur le projet %s, je souscris &agrave; une contrepartie financi&egrave;re :", 'yproject' ), $page_controler->get_campaign_name() ); ?>
		<?php echo sprintf( __( "une redevance index&eacute;e sur le chiffre d'affaires de %s sur les %s prochaines ann&eacute;es,", 'yproject' ), $page_controler->get_campaign_organization_name(), $page_controler->get_campaign_funding_duration() ); ?>
		<?php echo sprintf( __( "plafonn&eacute;e &agrave; %s fois mon investissement.", 'yproject' ), $page_controler->get_campaign_maximum_profit() ); ?>
	</div>
	
	<div id="contract-preview">
		<?php echo $page_controler->get_current_investment_contract_preview(); ?>
	</div>

	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	
	<br /><br />
	
	<?php foreach ( $fields_contract_validate as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	
	<button type="submit" name="nav" value="previous" class="button half left transparent"><?php _e( "Pr&eacute;c&eacute;dent", 'yproject' ); ?></button>
	
	<button type="submit" class="button half right red"><?php _e( "Valider le contrat", 'yproject' ); ?></button>
	
	<div class="clear"></div>
</form>

