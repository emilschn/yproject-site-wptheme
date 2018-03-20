<?php 
$page_publish = get_page_by_path('financement');
$page_mes_investissements = get_page_by_path('mes-investissements');
$WDGUser_displayed = WDGUser::current();
?>

<h2 class="underlined">Mon porte-monnaie électronique</h2>

<?php $post_details = get_page_by_path("details-des-investissements"); ?>
<?php $amount = $WDGUser_displayed->get_lemonway_wallet_amount(); ?>
Vous disposez de <?php echo $amount; ?> &euro; dans votre porte-monnaie.
<a href="<?php echo get_permalink($post_details->ID); ?>">Voir le d&eacute;tail de mes royalties</a>
<br><br>

<?php if ( !$WDGUser_displayed->is_document_lemonway_registered( LemonwayDocument::$document_type_bank ) ): ?>
	<?php _e( "Afin de lutter contre la fraude et le blanchiment d'argent, il est n&eacute;cessaire que votre RIB soit contr&ocirc;l&eacute; par notre prestataire de paiement.", 'yproject' ); ?><br>
	<?php _e( "Le compte bancaire qui vous permettra de r&eacute;cup&eacute;rer l'argent doit &ecirc;tre &agrave; votre nom.", 'yproject' ); ?><br>
	
	<?php if ( $WDGUser_displayed->get_document_lemonway_status( LemonwayDocument::$document_type_bank ) == LemonwayDocument::$document_status_waiting ): ?>
		<br>
		<?php _e( "Votre RIB est en cours de validation par notre prestataire de paiement. Merci de revenir d'ici 48h pour vous assurer de sa validation.", 'yproject' ); ?><br>
	
	<?php else: ?>
		<?php if ( $WDGUser_displayed->has_document_lemonway_error( LemonwayDocument::$document_type_bank ) ): ?>
			<?php echo $WDGUser_displayed->get_document_lemonway_error( LemonwayDocument::$document_type_bank ); ?><br>
		<?php endif; ?>
		<br>
		<form action="" method="POST" enctype="multipart/form-data">
			<label for="holdername" class="large-label"><?php _e( "Nom du propri&eacute;taire du compte :", 'yproject' ); ?></label>
				<input type="text" id="holdername" name="holdername" value="<?php echo $WDGUser_displayed->get_bank_holdername(); ?>">
				<br>
			<label for="address" class="large-label"><?php _e( "Adresse du compte :", 'yproject' ); ?></label>
				<input type="text" id="address" name="address" value="<?php echo $WDGUser_displayed->get_bank_address(); ?>">
				<br>
			<label for="address2" class="large-label"><?php _e( "Pays :", 'yproject' ); ?></label>
				<input type="text" id="address2" name="address2" value="<?php echo $WDGUser_displayed->get_bank_address2(); ?>">
				<br>
			<label for="iban" class="large-label"><?php _e( "IBAN :", 'yproject' ); ?></label>
				<input type="text" id="iban" name="iban" value="<?php echo $WDGUser_displayed->get_bank_iban(); ?>">
				<br>
			<label for="bic" class="large-label"><?php _e( "BIC :", 'yproject' ); ?></label>
				<input type="text" id="bic" name="bic" value="<?php echo $WDGUser_displayed->get_bank_bic(); ?>">
				<br>
			<label for="rib" class="large-label"><?php _e( "Fichier de votre RIB :", 'yproject' ); ?></label>
				<input type="file" id="rib" name="rib">
				<br>
				<br>
			<p class="align-center">
				<input type="submit" class="button" value="<?php _e( "Enregistrer", 'yproject' ); ?>" />
			</p>
			<input type="hidden" name="action" value="register_rib" />
			<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>" />
		</form>
	<?php endif; ?>

<?php elseif ($amount > 0): ?>
	<form action="" method="POST" enctype="multipart/form-data">
		<p class="align-center">
			<input type="submit" class="button" value="Reverser sur mon compte bancaire" />
		</p>
		<input type="hidden" name="action" value="user_wallet_to_bankaccount" />
		<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>" />
	</form>
	<br><br>
	
<?php endif; ?>

<h2 class="underlined"><?php _e( 'Mes transferts d&apos;argent', 'yproject' ); ?></h2>
<?php
$args = array(
	'author'    => get_current_user_id(),
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


<h2 class="underlined"><?php _e( 'Mes attestations de transactions annuelles', 'yproject' ); ?></h2>
<?php
$has_declaration = false;
$date_now = new DateTime();
?>
<?php for( $year = 2016; $year < $date_now->format('Y'); $year++ ): ?>
	<?php if ( $WDGUser_displayed->has_royalties_for_year( $year ) ): ?>
		<?php
		$has_declaration = true;
		$declaration_url = $WDGUser_displayed->get_royalties_certificate_per_year( $year );
		?>
		<a href="<?php echo $declaration_url; ?>" download="attestation-royalties-<?php echo $year; ?>.pdf" class="button red">Télécharger l'attestation <?php echo $year; ?></a><br /><br />
	<?php endif; ?>
<?php endfor; ?>
<?php if ( !$has_declaration ): ?>
	<?php _e( "Aucune", 'yproject' ); ?>
<?php endif; ?>
<br><br>

<h2 class="underlined">Projets</h2>

	<div>
		<div class="right">
			<a href="<?php echo get_permalink($page_publish->ID); ?>" class="button right">Financer mon projet</a>
		</div>
	    
		<div class="clear"></div>
	</div>
	<br /><br /><br />
	
<div id="ajax-loader" class="center" style="text-align: center;"><img id="ajax-loader-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>

