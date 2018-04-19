<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
?>

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
			<a href="<?php echo home_url( '/financement' ); ?>" class="button right">Financer mon projet</a>
		</div>
	    
		<div class="clear"></div>
	</div>
	<br /><br /><br />
	
<div id="ajax-loader" class="center" style="text-align: center;"><img id="ajax-loader-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>

