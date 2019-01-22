<?php
global $WDGOrganization;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
?>

<h2>Porte-monnaie électronique de <?php echo $WDGOrganization->get_name(); ?></h2>

Montant des royalties vers&eacute;es : <?php echo $WDGOrganization->get_rois_amount(); ?> &euro;<br>
<?php if ( !$WDGOrganization->is_registered_lemonway_wallet() ): ?>
	<?php $pending_amount = $WDGOrganization->get_pending_rois_amount(); ?>
	<?php if ( $pending_amount > 0 ): ?>
	<?php echo $pending_amount; ?> &euro; sont en attente d'authentification.<br>
	<?php endif; ?>
<?php else: ?>
	Montant que vous pouvez retirer : <?php echo $WDGOrganization->get_available_rois_amount(); ?> &euro;<br>
<?php endif; ?>
<a href="<?php echo home_url( '/details-des-investissements/' ); ?>?organization=<?php echo $WDGOrganization->get_wpref(); ?>">Voir le d&eacute;tail de mes royalties</a><br>
<br><br>

<?php if ( !$WDGOrganization->is_document_lemonway_registered( LemonwayDocument::$document_type_bank ) ): ?>
	<?php if ( $WDGOrganization->get_document_lemonway_status( LemonwayDocument::$document_type_bank ) == LemonwayDocument::$document_status_waiting ): ?>
		<?php _e( "Le RIB de l'organisation est en cours de validation par notre prestataire de paiement. Merci de revenir d'ici 48h pour vous assurer de sa validation.", 'yproject' ); ?><br>

	<?php else: ?>
		<?php _e( "Afin de retirer les royalties per&ccedil;ues par l'organisation, merci de renseigner ses coordonn&eacute;es bancaires.", 'yproject' ); ?><br><br>
		<a href="#orga-bank-<?php echo $WDGOrganization->get_wpref(); ?>" class="button red go-to-tab" data-tab="orga-bank-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Coordonn&eacute;es bancaires", 'yproject' ); ?></a>

	<?php endif; ?>

<?php elseif ( $WDGOrganization->get_available_rois_amount() > 0 ): ?>
	<form action="" method="POST" enctype="multipart/form-data">
		<p class="align-center">
			<input type="submit" class="button" value="Reverser sur mon compte bancaire" />
		</p>
		<input type="hidden" name="action" value="user_wallet_to_bankaccount" />
		<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>" />
		<input type="hidden" name="orga_id" value="<?php echo $WDGOrganization->get_wpref(); ?>" />
	</form>
	<br><br>
	
<?php endif; ?>


<h3><?php _e( 'Transferts d&apos;argent', 'yproject' ); ?></h3>
<?php
$args = array(
	'author'    => $WDGOrganization->get_wpref(),
	'post_type' => 'withdrawal_order_lw',
	'post_status' => 'any',
	'orderby'   => 'post_date',
	'order'     =>  'ASC'
);
$transfers = get_posts($args);
if ($transfers) :
?>
<ul class="user_history">
	<?php 
	foreach ( $transfers as $post ) :
		$post = get_post($post);
		$post_amount = $post->post_title;
		?>
		<?php if ($post->post_status == 'publish'): ?>
		<li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- Termin&eacute;</li>
		<?php elseif ($post->post_status == 'draft'): ?>
		<li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- Annul&eacute;</li>
		<?php else: ?>
		<li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- En cours</li>
		<?php endif; ?>
	<?php
	endforeach;
	?>
</ul>
<?php else: ?>
	Aucun transfert d&apos;argent.
<?php endif; ?>
<br><br>


