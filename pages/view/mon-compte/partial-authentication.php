<?php
global $stylesheet_directory_uri, $WDGOrganization;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$can_register_lemonway = ( isset( $WDGOrganization ) ) ? $WDGOrganization->can_register_lemonway() : $WDGUser_displayed->can_register_lemonway();
$is_lemonway_registered = ( isset( $WDGOrganization ) ) ? $WDGOrganization->is_registered_lemonway_wallet() : $WDGUser_displayed->is_lemonway_registered();
?>

<p class="center">
	<?php if ( $is_lemonway_registered ): ?>
		<?php _e( "Vous &ecirc;tes authentifi&eacute; chez notre prestataire de paiement, Lemon Way.", 'yproject' ); ?>
	<?php elseif ( $can_register_lemonway ): ?>
		<?php _e( "Pour investir des montants sup&eacute;rieurs &agrave; 250 &euro; sur WEDOGOOD, vous devez &ecirc;tre authentifi&eacute; aupr&egrave;s de notre prestataire de paiement, Lemon Way.", 'yproject' ); ?>
	<?php else: ?>
		<?php _e( "Pour investir sur WEDOGOOD, vous devez &ecirc;tre authentifi&eacute; aupr&egrave;s notre prestataire de paiement, Lemon Way.", 'yproject' ); ?>
	<?php endif; ?><br>
	<?php _e( "Il existe 3 niveaux d'authentification :", 'yproject' ); ?>
</p>

<div class="center">
	<table class="authentication-list">
		<thead>
			<tr>
				<td></td>
				<td class="align-center title col"><strong><?php _e( "Inscription", 'yproject' ); ?></strong></td>
				<?php if ( isset( $WDGOrganization ) ): ?>
				<td class="align-center title col"><strong><?php _e( "Informations enregistr&eacute;es", 'yproject' ); ?></strong></td>
				<?php else: ?>
				<td class="align-center title col"><strong><?php _e( "Informations personnelles enregistr&eacute;es", 'yproject' ); ?></strong></td>
				<?php endif; ?>
				<td class="align-center title col"><strong><?php _e( "Pi&egrave;ces justificatives enregistr&eacute;es", 'yproject' ); ?></strong></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="title">
					<strong><?php _e( "&Eacute;valuer les projets", 'yproject' ); ?></strong>
				</td>
				<td class="align-center inside">
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="40" height="40">
				</td>
				<td class="align-center inside">
					<?php if ( $can_register_lemonway ): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="40" height="40">
					<?php else: ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-unchecked.png" alt="uncheck" width="40" height="40">
					<?php endif; ?>
				</td>
				<td class="align-center inside">
					<?php if ( $is_lemonway_registered ): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="40" height="40">
					<?php else: ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-unchecked.png" alt="uncheck" width="40" height="40">
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td class="title">
					<strong><?php _e( "Investissement", 'yproject' ); ?></strong><br>
					- <?php _e( "jusqu'&agrave; 250 &euro; par carte ou virement", 'yproject' ); ?><br>
					<strong><?php _e( "Retrait de royalties", 'yproject' ); ?></strong><br>
					- <?php _e( "jusqu'&agrave; 2 500 &euro;", 'yproject' ); ?><br>
				</td>
				<td class="align-center inside"></td>
				<td class="align-center inside">
					<?php if ( $can_register_lemonway ): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="40" height="40">
					<?php else: ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-unchecked.png" alt="uncheck" width="40" height="40">
					<?php endif; ?>
				</td>
				<td class="align-center inside">
					<?php if ( $is_lemonway_registered ): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="40" height="40">
					<?php else: ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-unchecked.png" alt="uncheck" width="40" height="40">
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td class="title">
					<strong><?php _e( "Investissement", 'yproject' ); ?></strong><br>
					- <?php _e( "illimit&eacute;", 'yproject' ); ?><br>
					<strong><?php _e( "Retrait de royalties", 'yproject' ); ?></strong><br>
					- <?php _e( "illimit&eacute;", 'yproject' ); ?><br>
				</td>
				<td class="align-center inside"></td>
				<td class="align-center inside"></td>
				<td class="align-center inside">
					<?php if ( $is_lemonway_registered ): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="40" height="40">
					<?php else: ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-unchecked.png" alt="uncheck" width="40" height="40">
					<?php endif; ?>
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td></td>
				<td class="align-center"></td>
				<?php if ( isset( $WDGOrganization ) ): ?>
					<td class="align-center">
						<?php if ( $can_register_lemonway ): ?>
							<a href="#orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>" class="button blue go-to-tab" data-tab="orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Informations de<br>l'organisation", 'yproject' ); ?></a>
						<?php else: ?>
							<a href="#orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>" class="button red go-to-tab" data-tab="orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Informations de<br>l'organisation", 'yproject' ); ?></a>
						<?php endif; ?>
					</td>
					<td class="align-center">
						<?php if ( $is_lemonway_registered ): ?>
							<a href="#orga-identitydocs-<?php echo $WDGOrganization->get_wpref(); ?>" class="button blue go-to-tab" data-tab="orga-identitydocs-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Justificatifs<br>d'identification", 'yproject' ); ?></a>
						<?php else: ?>
							<a href="#orga-identitydocs-<?php echo $WDGOrganization->get_wpref(); ?>" class="button red go-to-tab" data-tab="orga-identitydocs-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Justificatifs<br>d'identification", 'yproject' ); ?></a>
						<?php endif; ?>
					</td>
					
				<?php else: ?>
					<td class="align-center">
						<?php if ( $can_register_lemonway ): ?>
							<a href="#parameters" class="button blue go-to-tab" data-tab="parameters"><?php _e( "Mes informations<br>personnelles", 'yproject' ); ?></a>
						<?php else: ?>
							<a href="#parameters" class="button red go-to-tab" data-tab="parameters"><?php _e( "Mes informations<br>personnelles", 'yproject' ); ?></a>
						<?php endif; ?>
					</td>
					<td class="align-center">
						<?php if ( $is_lemonway_registered ): ?>
							<a href="#identitydocs" class="button blue go-to-tab" data-tab="identitydocs"><?php _e( "Mes justificatifs<br>d'identit&eacute;", 'yproject' ); ?></a>
						<?php else: ?>
							<a href="#identitydocs" class="button red go-to-tab" data-tab="identitydocs"><?php _e( "Mes justificatifs<br>d'identit&eacute;", 'yproject' ); ?></a>
						<?php endif; ?>
					</td>
				<?php endif; ?>
			</tr>
		</tfoot>
	</table>
</div>