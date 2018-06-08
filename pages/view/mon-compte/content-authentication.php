<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$current_user_authentication = $page_controler->get_current_user_authentication();
$current_user_authentication_info = $page_controler->get_current_user_authentication_info();
?>

<h2 class="underlined"><?php _e( 'Mon authentification', 'yproject' ); ?></h2>

<p>
	Intro
</p>

<table>
	<thead>
		<tr>
			<td></td>
			<td class="align-center"><?php _e( "Inscription", 'yproject' ); ?></td>
			<td class="align-center"><?php _e( "Informations", 'yproject' ); ?></td>
			<td class="align-center"><?php _e( "Authentifi&eacute;", 'yproject' ); ?></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				- évaluer les projets
			</td>
			<td class="align-center">
				<img src="" alt="check">
			</td>
			<td class="align-center">
				<?php if ( $WDGUser_displayed->can_register_lemonway() ): ?>
					<img src="" alt="check">
				<?php else: ?>
					<img src="" alt="uncheck">
				<?php endif; ?>
			</td>
			<td class="align-center">
				<?php if ( $WDGUser_displayed->is_lemonway_registered() ): ?>
					<img src="" alt="check">
				<?php else: ?>
					<img src="" alt="uncheck">
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td>
				- investir jusqu'&agrave; 250 &euro; ou par ch&egrave;que<br>
				- recevoir jusqu'&agrave; 2 500 &euro; de royalties
			</td>
			<td class="align-center"></td>
			<td class="align-center">
				<?php if ( $WDGUser_displayed->can_register_lemonway() ): ?>
					<img src="" alt="check">
				<?php else: ?>
					<img src="" alt="uncheck">
				<?php endif; ?>
			</td>
			<td class="align-center">
				<?php if ( $WDGUser_displayed->is_lemonway_registered() ): ?>
					<img src="" alt="check">
				<?php else: ?>
					<img src="" alt="uncheck">
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td>
				- investir une sommes infinie<br>
				- recevoir des royalties infinies
			</td>
			<td class="align-center"></td>
			<td class="align-center"></td>
			<td class="align-center">
				<?php if ( $WDGUser_displayed->is_lemonway_registered() ): ?>
					<img src="" alt="check">
				<?php else: ?>
					<img src="" alt="uncheck">
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td></td>
			<td class="align-center"></td>
			<td class="align-center">
				<a href="#parameters" class="button blue" data-tab="parameters"><?php _e( "Saisir mes informations personnelles", 'yproject' ); ?></a>
			</td>
			<td class="align-center">
				<a href="#identitydocs" class="button blue" data-tab="identitydocs"><?php _e( "Envoyer mes justificatifs", 'yproject' ); ?></a>
			</td>
		</tr>
	</tfoot>
</table>