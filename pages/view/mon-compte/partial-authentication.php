<?php
global $stylesheet_directory_uri, $WDGOrganization;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$WDGUser_current = WDGUser::current();
$can_register_lemonway = ( isset( $WDGOrganization ) ) ? $WDGOrganization->can_register_lemonway() : $WDGUser_displayed->can_register_lemonway();
$is_lemonway_registered = ( isset( $WDGOrganization ) ) ? $WDGOrganization->is_registered_lemonway_wallet() : $WDGUser_displayed->is_lemonway_registered();
?>


<div class="center">
	<?php if ( $is_lemonway_registered ): ?>
		<div class="wdg-message confirm">
			<?php _e( 'account.authentication.ACCOUNT_AUTHENTICATED', 'yproject' ); ?><br>
			<?php _e( 'account.authentication.THANK_YOU_FOR_TRUST', 'yproject' ); ?>
		</div>
	
		<p class="align-center">
			<?php if ( isset( $WDGOrganization ) ): ?>
				<a href="#orga-investments-<?php echo $WDGOrganization->get_wpref(); ?>" class="button blue go-to-tab" data-tab="orga-investments-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( 'account.authentication.VIEW_MY_INVESTMENTS', 'yproject' ); ?></a>
			<?php else: ?>
				<a href="#investments" class="button blue go-to-tab" data-tab="investments"><?php _e( 'account.authentication.VIEW_MY_INVESTMENTS', 'yproject' ); ?></a>
			<?php endif; ?>
		</p>


	<?php else: ?>
		<div class="wdg-message notification">
			<?php _e( 'account.authentication.AUTHENTICATION_IS_NECESSARY', 'yproject' ); ?>
			<?php _e( 'account.authentication.PROVIDE_DOCUMENTS', 'yproject' ); ?>
		</div>

		<div>
			<?php _e( 'account.authentication.PROVIDE_DOCUMENTS_TO_INVEST', 'yproject' ); ?><br>
			<?php _e( 'account.authentication.FILL_IN_PERSONAL_INFO', 'yproject' ); ?>
			<?php _e( 'account.authentication.DOCUMENTS_WILL_BE_PROCESSED', 'yproject' ); ?>
		</div>
		
		<div class="authentication-items">
			
			<div class="authentication-item <?php if ( !$can_register_lemonway ) { echo 'alert'; } ?>">
				<div>
					<div>
						<?php _e( 'account.authentication.PERSONAL_INFORMATION', 'yproject' ); ?>
					</div>

					<?php if ( $can_register_lemonway ): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="80" height="80">
					<?php else: ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-error.png" alt="check" width="80" height="80">
					<?php endif; ?>
				</div>
				
				<?php if ( isset( $WDGOrganization ) ): ?>
					<a href="#orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>" class="button <?php echo ( $can_register_lemonway ) ? 'blue' : 'red'; ?> go-to-tab" data-tab="orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( 'account.authentication.EDIT_MY_INFORMATION', 'yproject' ); ?></a>
				<?php else: ?>
					<a href="#parameters" class="button <?php echo ( $can_register_lemonway ) ? 'blue' : 'red'; ?> go-to-tab" data-tab="parameters"><?php _e( 'account.authentication.EDIT_MY_INFORMATION', 'yproject' ); ?></a>
				<?php endif; ?>
			</div>
			
			<div class="authentication-item <?php if ( !$is_lemonway_registered ) { echo 'alert'; } ?>">
				<div>
					<div>
						<?php _e( 'account.authentication.IDENTITY_DOCUMENTS', 'yproject' ); ?>
					</div>

					<?php if ( $is_lemonway_registered ): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="80" height="80">
					<?php else: ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-error.png" alt="check" width="80" height="80">
					<?php endif; ?>
				</div>
				
				<?php if ( isset( $WDGOrganization ) ): ?>
					<a href="#orga-identitydocs-<?php echo $WDGOrganization->get_wpref(); ?>" class="button <?php echo ( $is_lemonway_registered ) ? 'blue' : 'red'; ?>  go-to-tab" data-tab="orga-identitydocs-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( 'account.authentication.EDIT_IDENTITY_DOCUMENTS', 'yproject' ); ?></a>
				<?php else: ?>
					<a href="#identitydocs" class="button <?php echo ( $is_lemonway_registered ) ? 'blue' : 'red'; ?>  go-to-tab" data-tab="identitydocs"><?php _e( 'account.authentication.EDIT_IDENTITY_DOCUMENTS', 'yproject' ); ?></a>
				<?php endif; ?>
			</div>
			
		</div>

	<?php endif; ?>

	<?php if ( $WDGUser_current->is_admin() ): ?>		
		<div class="admin-theme">
			<?php if ( isset( $WDGOrganization ) ): ?>
				ID LemonWay : <?php echo $WDGOrganization->get_lemonway_id(); ?>
			<?php else: ?>
				ID LemonWay : <?php echo $WDGUser_displayed->get_lemonway_id(); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>