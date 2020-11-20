<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$list_current_organizations = $page_controler->get_current_user_organizations();
?>

<main data-userid="<?php echo $page_controler->get_user_id(); ?>">
		
	<?php if ( $page_controler->get_wallet_to_bankaccount_result() != FALSE ): ?>
		<?php if ( $page_controler->get_wallet_to_bankaccount_result() === TRUE ): ?>
			<div class="success">Transfert effectué</div>
		<?php else: ?>
			<div class="wdg-message error">
				<?php echo $page_controler->get_wallet_to_bankaccount_result(); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<!-- <?php if ( $page_controler->get_change_wire_amount_result() != FALSE ): ?>
		<?php if ( $page_controler->get_change_wire_amount_result() === TRUE ): ?>
			<div class="success">Mise à jour du virement effectué</div>
		<?php else: ?>
			<div class="wdg-message error">
				<?php echo $page_controler->get_change_wire_amount_result(); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?> -->
	<div>

		<div id="item-body">
			<div id="item-body-wallet" class="item-body-tab">
				<?php locate_template( array( 'pages/view/mon-compte/content-wallet.php' ), true ); ?>
			</div>

			<div id="item-body-investments" class="item-body-tab hidden">
				<?php locate_template( array( 'pages/view/mon-compte/content-investments.php' ), true ); ?>
			</div>
			
			<div id="item-body-documents" class="item-body-tab hidden">
				<?php locate_template( array( 'pages/view/mon-compte/content-documents.php' ), true ); ?>
			</div>

			<div id="item-body-parameters" class="item-body-tab hidden">
				<?php locate_template( array( 'pages/view/mon-compte/content-parameters.php' ), true ); ?>
			</div>

			<div id="item-body-identitydocs" class="item-body-tab hidden">
				<?php locate_template( array( 'pages/view/mon-compte/content-identitydocs.php' ), true ); ?>
			</div>

			<div id="item-body-bank" class="item-body-tab hidden">
				<?php locate_template( array( 'pages/view/mon-compte/content-bank.php' ), true ); ?>
			</div>

			<div id="item-body-authentication" class="item-body-tab hidden">
				<?php locate_template( array( 'pages/view/mon-compte/content-authentication.php' ), true ); ?>
			</div>

			<div id="item-body-notifications" class="item-body-tab hidden">
				<?php locate_template( array( 'pages/view/mon-compte/content-user-notifications.php' ), true ); ?>
			</div>
			
			<?php if ( count( $list_current_organizations ) > 0 ): ?>
				<?php global $WDGOrganization; ?>
				<?php foreach ( $list_current_organizations as $organization_item ): ?>
					<?php $WDGOrganization = $organization_item; ?>
					<div id="item-body-orga-wallet-<?php echo $WDGOrganization->get_wpref(); ?>" class="item-body-tab hidden">
						<?php locate_template( array( 'pages/view/mon-compte/content-orga-wallet.php' ), true, false ); ?>
					</div>
			
					<div id="item-body-orga-investments-<?php echo $WDGOrganization->get_wpref(); ?>" class="item-body-tab hidden">
						<?php locate_template( array( 'pages/view/mon-compte/content-orga-investments.php' ), true, false ); ?>
					</div>
			
					<div id="item-body-orga-documents-<?php echo $WDGOrganization->get_wpref(); ?>" class="item-body-tab hidden">
						<?php locate_template( array( 'pages/view/mon-compte/content-orga-documents.php' ), true, false ); ?>
					</div>
			
					<div id="item-body-orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>" class="item-body-tab hidden">
						<?php locate_template( array( 'pages/view/mon-compte/content-orga-parameters.php' ), true, false ); ?>
					</div>
			
					<div id="item-body-orga-identitydocs-<?php echo $WDGOrganization->get_wpref(); ?>" class="item-body-tab hidden">
						<?php locate_template( array( 'pages/view/mon-compte/content-orga-identitydocs.php' ), true, false ); ?>
					</div>
			
					<div id="item-body-orga-bank-<?php echo $WDGOrganization->get_wpref(); ?>" class="item-body-tab hidden">
						<?php locate_template( array( 'pages/view/mon-compte/content-orga-bank.php' ), true, false ); ?>
					</div>
			
					<div id="item-body-orga-authentication-<?php echo $WDGOrganization->get_wpref(); ?>" class="item-body-tab hidden">
						<?php locate_template( array( 'pages/view/mon-compte/content-orga-authentication.php' ), true, false ); ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

	</div>
	
</main>