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
						<h1><?php echo __('Informations', 'yproject'); ?></h1>
						
						<ul class="xoxo">
							<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
						</ul>
					</div><!-- #first .widget-area -->

				<?php endif; ?>

				<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>

					<div id="second" class="widget-area">
						<h1><?php echo __('Questions', 'yproject'); ?></h1>
						
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
						<h1><?php echo __('Communaut&eacute;', 'yproject'); ?></h1>
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
									<a href="https://www.facebook.com/pages/Y-Project/381460615282040" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook_grenade.png" /></a><br />
									<?php if ($facebook_infos) echo $facebook_infos; ?>
								    </div>
								    <div class="social-icons">
									<a href="https://twitter.com/yproject_co" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/twitter_grenade.png" /></a><br />
									<?php if ($twitter_infos) echo $twitter_infos; ?>
								    </div>
								    <div class="social-icons">
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/viadeo_grenade.png" />
								    </div>
								    <div class="social-icons">
									<a href="<?php echo get_permalink($page_community_blog->ID); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/blog_grenade.png" /></a>
								    </div>
								    <div style="clear: both"></div>
								</li>
								<li class="menu-item">
								    Newsletter<br />
								    <?php 
								    $snsf_args = array("thanks" => __("Merci", "yproject"));
								    echo do_newsletter($snsf_args);
								    if (!isset($_POST['snsf-subscriber-email'])) {
								    ?>
								    <form method="post" name="simple-newsletter-signup-form" id="snsf-form">
									<input type="text" name="snsf-subscriber-email" />
									<input type="submit" value="<?php _e('Sinscrire', 'yproject'); ?>" id="snsf-submit-button" />
								    </form>
								    <?php
								    }
								    ?>
								</li>
								
							    </ul>
							</div>
						    </li> 
						</ul>
					</div><!-- #third .widget-area -->

					<div style="clear: both" />
			</div><!-- #footer-widget-area -->
