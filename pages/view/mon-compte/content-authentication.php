<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$current_user_authentication = $page_controler->get_current_user_authentication();
$current_user_authentication_info = $page_controler->get_current_user_authentication_info();
?>

<h2 class="underlined"><?php _e( 'Mon authentification', 'yproject' ); ?></h2>

<p class="center">
	<?php if ( $WDGUser_displayed->is_lemonway_registered() ): ?>
		<?php _e( "Vous &ecirc;tes authentifi&eacute; chez notre prestataire de paiement, Lemon Way.", 'yproject' ); ?>
	<?php elseif ( $WDGUser_displayed->can_register_lemonway() ): ?>
		<?php _e( "Pour investir des montants sup&eacute;rieurs &agrave; 250 &euro; sur WEDOGOOD, vous devez &ecirc;tre authentifi&eacute; aupr&egrave;s de notre prestataire de paiement, Lemon Way.", 'yproject' ); ?>
	<?php else: ?>
		<?php _e( "Pour investir sur WEDOGOOD, vous devez &ecirc;tre authentifi&eacute; aupr&egravee notre prestataire de paiement, Lemon Way.", 'yproject' ); ?>
	<?php endif; ?><br>
	<?php _e( "Il existe 3 niveaux d'authentification :", 'yproject' ); ?>
</p>

<div class="center">
	<table>
		<thead>
			<tr>
				<td></td>
				<td class="align-center title col"><strong><?php _e( "Inscription", 'yproject' ); ?></strong></td>
				<td class="align-center title col"><strong><?php _e( "Informations personnelles enregistr&eacute;es", 'yproject' ); ?></strong></td>
				<td class="align-center title col"><strong><?php _e( "Pi&egrave;ces justificatives enregistr&eacute;es", 'yproject' ); ?></strong></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="title">
					<strong><?php _e( "&Eacute;valuer les projets", 'yproject' ); ?></strong>
				</td>
				<td class="align-center inside">
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/minimum-goal-full.png" alt="check" width="40" height="40">
				</td>
				<td class="align-center inside">
					<?php if ( $WDGUser_displayed->can_register_lemonway() ): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/minimum-goal-full.png" alt="check" width="40" height="40">
					<?php else: ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/minimum-goal-empty.png" alt="uncheck" width="40" height="40">
					<?php endif; ?>
				</td>
				<td class="align-center inside">
					<?php if ( $WDGUser_displayed->is_lemonway_registered() ): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/minimum-goal-full.png" alt="check" width="40" height="40">
					<?php else: ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/minimum-goal-empty.png" alt="uncheck" width="40" height="40">
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td class="title">
					<strong><?php _e( "Investissement", 'yproject' ); ?></strong><br>
					- <?php _e( "jusqu'&agrave; 250 &euro; par carte ou virement", 'yproject' ); ?><br>
					- <?php _e( "illimit&eacute; par ch&egrave;que", 'yproject' ); ?><br>
					<strong><?php _e( "Retrait de royalties", 'yproject' ); ?></strong><br>
					- <?php _e( "jusqu'&agrave; 2 500 &euro;", 'yproject' ); ?><br>
				</td>
				<td class="align-center inside"></td>
				<td class="align-center inside">
					<?php if ( $WDGUser_displayed->can_register_lemonway() ): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/minimum-goal-full.png" alt="check" width="40" height="40">
					<?php else: ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/minimum-goal-empty.png" alt="uncheck" width="40" height="40">
					<?php endif; ?>
				</td>
				<td class="align-center inside">
					<?php if ( $WDGUser_displayed->is_lemonway_registered() ): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/minimum-goal-full.png" alt="check" width="40" height="40">
					<?php else: ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/minimum-goal-empty.png" alt="uncheck" width="40" height="40">
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
					<?php if ( $WDGUser_displayed->is_lemonway_registered() ): ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/minimum-goal-full.png" alt="check" width="40" height="40">
					<?php else: ?>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/minimum-goal-empty.png" alt="uncheck" width="40" height="40">
					<?php endif; ?>
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td></td>
				<td class="align-center"></td>
				<td class="align-center">
					<?php if ( $WDGUser_displayed->can_register_lemonway() ): ?>
						<a href="#parameters" class="button blue go-to-tab" data-tab="parameters"><?php _e( "Mes informations<br>personnelles", 'yproject' ); ?></a>
					<?php else: ?>
						<a href="#parameters" class="button red go-to-tab" data-tab="parameters"><?php _e( "Mes informations<br>personnelles", 'yproject' ); ?></a>
					<?php endif; ?>
				</td>
				<td class="align-center">
					<?php if ( $WDGUser_displayed->is_lemonway_registered() ): ?>
						<a href="#identitydocs" class="button blue go-to-tab" data-tab="identitydocs"><?php _e( "Mes justificatifs<br>d'identit&eacute;", 'yproject' ); ?></a>
					<?php else: ?>
						<a href="#identitydocs" class="button red go-to-tab" data-tab="identitydocs"><?php _e( "Mes justificatifs<br>d'identit&eacute;", 'yproject' ); ?></a>
					<?php endif; ?>
				</td>
			</tr>
		</tfoot>
	</table>
</div>