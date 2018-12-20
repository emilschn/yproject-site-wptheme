<?php
global $stylesheet_directory_uri, $WDGOrganization;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$can_register_lemonway = ( isset( $WDGOrganization ) ) ? $WDGOrganization->can_register_lemonway() : $WDGUser_displayed->can_register_lemonway();
$is_lemonway_registered = ( isset( $WDGOrganization ) ) ? $WDGOrganization->is_registered_lemonway_wallet() : $WDGUser_displayed->is_lemonway_registered();
?>


<div class="center">
	<?php if ( $is_lemonway_registered ): ?>
		<div class="wdg-message confirm">
			<?php _e( "Votre compte est authentifi&eacute; aupr&egrave;s de notre prestataire de paiement Lemon Way.", 'yproject' ); ?><br>
			<?php _e( "Merci pour votre confiance.", 'yproject' ); ?>
		</div>
	
		<p class="align-center">
			<?php if ( isset( $WDGOrganization ) ): ?>
				<a href="#orga-investments-<?php echo $WDGOrganization->get_wpref(); ?>" class="button blue go-to-tab" data-tab="orga-investments-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Voir mes investissements", 'yproject' ); ?></a>
			<?php else: ?>
				<a href="#investments" class="button blue go-to-tab" data-tab="investments"><?php _e( "Voir mes investissements", 'yproject' ); ?></a>
			<?php endif; ?>
		</p>


	<?php else: ?>
		<div class="wdg-message notification">
			<?php _e( "Depuis Janvier 2019 et suite &agrave; un renforcement de la l&eacute;gislation contre le blanchiment d'argent, l'authentification sera n&eacute;cessaire aupr&egrave;s de notre prestataire pour tout investissement.", 'yproject' ); ?>
			<?php _e( "Nous vous invitons ainsi &agrave; renseigner d&egrave;s maintenant les documents permettant de vous authentifier.", 'yproject' ); ?>
		</div>

		<div>
			<?php _e( "Pour investir des montants sup&eacute;rieurs &agrave; 250 &euro; sur WEDOGOOD, vous devez &ecirc;tre authentifi&eacute; aupr&egrave;s de notre prestataire de paiement, Lemon Way.", 'yproject' ); ?><br>
			<?php _e( "Merci de saisir vos informations personnelles, puis de nous transmettre vos justificatifs d'identit&eacute;.", 'yproject' ); ?>
		</div>
		
		<div class="authentication-items">
			
			<div class="authentication-item <?php if ( !$can_register_lemonway ) { echo 'alert'; } ?>">
				<div>
					<div>
						<?php _e( "Informations personnelles", 'yproject' ); ?>
					</div>

					<?php if ( $can_register_lemonway ): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="80" height="80">
					<?php else: ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-error.png" alt="check" width="80" height="80">
					<?php endif; ?>
				</div>
				
				<?php if ( isset( $WDGOrganization ) ): ?>
					<a href="#orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>" class="button <?php echo ( $can_register_lemonway ) ? 'blue' : 'red'; ?> go-to-tab" data-tab="orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Editer mes informations", 'yproject' ); ?></a>
				<?php else: ?>
					<a href="#parameters" class="button <?php echo ( $can_register_lemonway ) ? 'blue' : 'red'; ?> go-to-tab" data-tab="parameters"><?php _e( "Editer mes informations", 'yproject' ); ?></a>
				<?php endif; ?>
			</div>
			
			<div class="authentication-item <?php if ( !$is_lemonway_registered ) { echo 'alert'; } ?>">
				<div>
					<div>
						<?php _e( "Justificatifs d'identit&eacute;", 'yproject' ); ?>
					</div>

					<?php if ( $is_lemonway_registered ): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="80" height="80">
					<?php else: ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-error.png" alt="check" width="80" height="80">
					<?php endif; ?>
				</div>
				
				<?php if ( isset( $WDGOrganization ) ): ?>
					<a href="#orga-identitydocs-<?php echo $WDGOrganization->get_wpref(); ?>" class="button <?php echo ( $is_lemonway_registered ) ? 'blue' : 'red'; ?>  go-to-tab" data-tab="orga-identitydocs-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Editer mes justificatifs", 'yproject' ); ?></a>
				<?php else: ?>
					<a href="#identitydocs" class="button <?php echo ( $is_lemonway_registered ) ? 'blue' : 'red'; ?>  go-to-tab" data-tab="identitydocs"><?php _e( "Editer mes justificatifs", 'yproject' ); ?></a>
				<?php endif; ?>
			</div>
			
		</div>

	<?php endif; ?>
</div>