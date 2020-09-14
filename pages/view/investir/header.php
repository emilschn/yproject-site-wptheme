<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php if ( $page_controler->get_current_campaign() !== FALSE ): ?>
<header>
	
	<div class="center">
		<?php if ( $page_controler->get_current_investment()->has_token() ): ?>
			<h1><?php _e('Investir en royalties', 'yproject'); ?></h1>
		<?php else: ?>
			<h1><?php _e('Investir sur le projet', 'yproject'); ?> <?php echo $page_controler->get_current_campaign()->data->post_title; ?></h1>
		<?php endif; ?>

		<?php if ( !$page_controler->get_current_investment()->has_token() ): ?>
			<div class="back-project">
				<a href="<?php echo $page_controler->get_current_campaign()->get_public_url(); ?>"><?php _e("Retour au projet", 'yproject'); ?></a>
			</div>
		<?php endif; ?>

		<div id="invest-breadcrumb">
			<span class="<?php if ( $page_controler->get_current_step() == 1 ): ?>selected<?php endif; ?>"><?php _e( "Montant", 'yproject' ); ?></span>
			<span class="<?php if ( $page_controler->get_current_step() == -1 ||  $page_controler->get_current_step() == 2 ||  $page_controler->get_current_step() == 2.5 ): ?>selected<?php endif; ?>"><?php _e( "Informations personnelles", 'yproject' ); ?></span>
			<span class="<?php if ( $page_controler->get_current_step() == 3 ): ?>selected<?php endif; ?>"><?php _e( "Contrat", 'yproject' ); ?></span>
			<span class="<?php if ( $page_controler->get_current_step() == 4 ): ?>selected<?php endif; ?>"><?php _e( "Paiement", 'yproject' ); ?></span>
			<span class="<?php if ( $page_controler->get_current_step() == 5 ): ?>selected<?php endif; ?>"><?php _e( "Partage", 'yproject' ); ?></span>
		</div>
	</div>

</header>
<?php endif; ?>