<?php
	/**
	 * The footer widget area is triggered if any of the areas
	 * have widgets.
	 *
	 * If none of the sidebars have widgets, bail early.
	 */
	if (   ! is_active_sidebar( 'first-footer-widget-area'  )
		&& ! is_active_sidebar( 'second-footer-widget-area' )
		&& ! is_active_sidebar( 'third-footer-widget-area'  )
		&& ! is_active_sidebar( 'fourth-footer-widget-area' )
	)
	return; ?>

			<div id="footer-widget-area" role="complementary">

				<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>

					<div id="first" class="widget-area">
						<span class="menutitle only_on_mobile"><?php echo __('Informations & questions', 'yproject'); ?></span>
						<span class="menutitle"><?php echo __('Informations', 'yproject'); ?></span>
						
						<ul class="xoxo">
							<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
						</ul>
					</div><!-- #first .widget-area -->

				<?php endif; ?>

				<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>

					<div id="second" class="widget-area">
						<span class="menutitle"><?php echo __('Questions', 'yproject'); ?></span>
						
						<ul class="xoxo">
							<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
						</ul>
					</div><!-- #second .widget-area -->

				<?php endif; ?>

				<?php /* if ( is_active_sidebar( 'third-footer-widget-area' ) ) : ?>

					<div id="third" class="widget-area">
						<ul class="xoxo">
							<?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
						</ul>
					</div><!-- #third .widget-area -->

				<?php endif; ?>

				<?php if ( is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>

					<div id="fourth" class="widget-area">
						<ul class="xoxo">
							<?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
						</ul>
					</div><!-- #fourth .widget-area -->

				<?php endif; */ ?>
					
					<div id="third" class="widget-area-large">
						<span class="menutitle"><?php echo __('Communaut&eacute;', 'yproject'); ?></span>
						<ul class="xoxo">
						    <li id="nav_menu-4" class="widget widget_nav_menu">
							<div class="menu-footer-3-container">
							    <ul id="menu-footer-3" class="menu">
								<?php 
								    global $facebook_infos, $twitter_infos;
								    $page_community_blog = get_page_by_path('blog');
								?>
								<li class="menu-item">
								    <div class="social-icons">
									<a href="https://www.facebook.com/wedogood.co" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook_grenade.png" alt="compte facebook" /></a><br />
									<?php if ($facebook_infos) echo $facebook_infos; ?>
								    </div>
								    <div class="social-icons">
									<a href="https://twitter.com/wedogood_co" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/twitter_grenade.png" alt="compte twitter" /></a><br />
									<?php if ($twitter_infos) echo $twitter_infos; ?>
								    </div>
								    <div class="social-icons">
									<a href="<?php echo get_permalink($page_community_blog->ID); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/blog_grenade.png" alt="blog" /></a>
								    </div>
								    <div style="clear: both"></div>
								</li>
								<li class="menu-item">
								    Newsletter<br />
								    <?php 
								    if (yp_has_shortcode('mc4wp_form')) {
									echo do_shortcode('[mc4wp_form]');
								    }
								    ?>
								</li>
								
							    </ul>
							</div>
						    </li> 
						</ul>
					</div><!-- #third .widget-area -->

					<div style="clear: both"></div>
			</div><!-- #footer-widget-area -->

<?php
//TODO : deprecated : changer pour shortcode_exists lors du passage en 3.6.0
function yp_has_shortcode( $tag = NULL ) {
    global $shortcode_tags;
    return array_key_exists( $tag, $shortcode_tags );
}
?>