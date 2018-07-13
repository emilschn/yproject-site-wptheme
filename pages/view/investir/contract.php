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
	
	<?php if ( $page_controler->needs_two_contracts() ): ?>
	<div id="two-contracts-preview" class="two-contracts-preview hidden">
		<div class="align-justify">
			<?php _e( "Conform&eacute;ment &agrave; la r&eacute;glementation, votre niveau d'authentification actuel vous permet d'investir jusqu'&agrave; 250 &euro;.", 'yproject' ); ?>
			<?php _e( "Votre investissement sera donc valid&eacute; en deux temps :", 'yproject' ); ?>
			<?php _e( "un premier d&eacute;bit imm&eacute;diat de", 'yproject' ); ?> <?php echo $page_controler->get_first_contract_amount(); ?> &euro;,
			<?php _e( "et un second d&eacute;bit du montant restant apr&egrave;s validation de vos pi&egrave;ces justificatives.", 'yproject' ); ?>
		</div>
		
		<div class="contract-preview-with-tabs">
			<div class="contract-preview-tabs investment-with-card">
				<div class="selected">
					<div><?php echo $page_controler->get_first_contract_amount(); ?> &euro;</div>
				</div><div>
					<div><?php echo $page_controler->get_second_contract_amount(); ?> &euro;</div>
				</div>
			</div>
			<div class="contract-preview-content">
				<div>
					<?php echo $page_controler->get_current_investment_contract_preview(); ?>
				</div>
				<div class="hidden">
					<?php echo $page_controler->get_current_investment_contract_preview( FALSE ); ?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
	
	<?php if ( $page_controler->needs_two_contracts( TRUE ) ): ?>
	<div id="two-contracts-preview-with-wallet" class="two-contracts-preview hidden">
		<div class="align-justify">
			<?php _e( "Conform&eacute;ment &agrave; la r&eacute;glementation, votre niveau d'authentification actuel vous permet d'investir jusqu'&agrave; 250 &euro;.", 'yproject' ); ?>
			<?php _e( "Votre investissement sera donc valid&eacute; en deux temps :", 'yproject' ); ?>
			<?php _e( "un premier d&eacute;bit imm&eacute;diat de", 'yproject' ); ?> <?php echo $page_controler->get_first_contract_amount( TRUE ); ?> &euro;,
			<?php _e( "et un second d&eacute;bit du montant restant apr&egrave;s validation de vos pi&egrave;ces justificatives.", 'yproject' ); ?>
		</div>
		
		<div class="contract-preview-with-tabs">
			<div class="contract-preview-tabs hidden">
				<div class="selected">
					<div><?php echo $page_controler->get_first_contract_amount( TRUE ); ?> &euro;</div>
				</div><div>
					<div><?php echo $page_controler->get_second_contract_amount( TRUE ); ?> &euro;</div>
				</div>
			</div>
			<div class="contract-preview-content">
				<div>
					<?php echo $page_controler->get_current_investment_contract_preview( TRUE, TRUE); ?>
				</div>
				<div class="hidden">
					<?php echo $page_controler->get_current_investment_contract_preview( FALSE, TRUE ); ?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

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

