		</div> <!-- #container -->

<?php
//*******************
//CACHE FOOTER
global $WDG_cache_plugin, $client_context;
$cache_footer = $WDG_cache_plugin->get_cache('footer', 3);
if ($cache_footer !== FALSE && empty($client_context)) { echo $cache_footer; }
else {
	ob_start();
?>
		<footer class="bg-dark-gray<?php if (!empty($client_context)) { ?> theme-<?php echo $client_context; ?><?php } ?>">
		    <section class="center-lg">

				<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
					<div>
						<h3><?php _e('Qui sommes-nous ?', 'yproject'); ?></h3>
						<ul>
							<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
						</ul>
					</div>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>
					<div>
						<h3><?php _e('Entrepreneurs', 'yproject'); ?></h3>
						<ul>
							<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
						</ul>

						<h3><?php _e('Investisseurs', 'yproject'); ?></h3>
						<ul>
							<?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
						</ul>
					</div>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) : ?>
					<div>
						<h3 class="aligncenter"><?php _e('Nous suivre', 'yproject'); ?></h3>
						<div>
							<?php if (shortcode_exists('mc4wp_form')): ?>
								<?php echo do_shortcode('[mc4wp_form]'); ?>
							<?php endif; ?>
						</div>

						<a href="https://www.facebook.com/wedogood.co" target="_blank">Facebook</a>
						<a href="https://twitter.com/wedogood_co" target="_blank">Twitter</a>
						<a href="https://www.linkedin.com/company/3171289" target="_blank">LinkedIn</a>
						<a href="https://www.wedogood.co/rss.xml" target="_blank"><?php _e("Flux RSS", 'yproject'); ?></a>


						<div>
							<a href="<?php echo home_url('/contact'); ?>"><?php _e( "Nous contacter", 'yproject' ); ?></a>
						</div>
					</div>
				<?php endif; ?>

				<div class="clear"></div>
				
			</section>
			
			<section class="center-lg">
				<div>
					<a href="http://www.lemonway.fr" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo-lemonway.png" alt="logo Lemonway" /></a><br />
					Partenaire de Lemon Way, &eacute;tablissement de paiement agr&eacute;&eacute; par l’ACPR en France le 24/12/2012 sous le num&eacute;ro 16568J.
				</div>

				<?php if ( is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
				<div>
					<ul>
						<?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
					</ul>
				</div>
				<?php endif; ?>

				<div>
					<a href="http://www.lemonway.fr" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo-lemonway.png" alt="logo Lemonway" /></a><br />
					Partenaire de Lemon Way, &eacute;tablissement de paiement agr&eacute;&eacute; par l’ACPR en France le 24/12/2012 sous le num&eacute;ro 16568J.
				</div>

				<div class="clear"></div>

				<div class="aligncenter" role="contentinfo">
					<a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/fr/">
						<img alt="Licence Creative Commons" width="25" height="25" style="vertical-align: middle" src="<?php echo get_stylesheet_directory_uri(); ?>/images/cc_logo.png" /> Some rights reserved
					</a>
					<span>Plateforme d&apos;investissement participatif &agrave; impact positif.</span>
				</div>
			</section>
		</footer>

<?php
	$cache_footer = ob_get_contents();
	$WDG_cache_plugin->set_cache('footer', $cache_footer, 60*60*24, 1);
	ob_end_clean();
	echo $cache_footer;
}
//FIN CACHE FOOTER
//*******************
?>

	<?php wp_footer(); ?>

	</body>
</html>