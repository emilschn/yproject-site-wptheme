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
			<td><?php _e( "Inscription", 'yproject' ); ?></td>
			<td><?php _e( "Informations", 'yproject' ); ?></td>
			<td><?php _e( "Authentifi&eacute;", 'yproject' ); ?></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Evaluer les projets</td>
			<td><img src="" alt="check"></td>
			<td><img src="" alt="check"></td>
			<td><img src="" alt="check"></td>
		</tr>
		<tr>
			<td>Investir jusqu'&agrave; 250 &euro; ou par ch&egrave;que et recevoir jusqu'&agrave; 2 500 &euro; de royalties</td>
			<td></td>
			<td><img src="" alt="check"></td>
			<td><img src="" alt="check"></td>
		</tr>
		<tr>
			<td>Investir une sommes infinie et recevoir des royalties infinies</td>
			<td></td>
			<td></td>
			<td><img src="" alt="check"></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td></td>
			<td></td>
			<td><?php _e( "Bouton informations perso", 'yproject' ); ?></td>
			<td><?php _e( "Bouton documents", 'yproject' ); ?></td>
		</tr>
	</tfoot>
</table>