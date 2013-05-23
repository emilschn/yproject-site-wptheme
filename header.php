<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<?php if ( current_theme_supports( 'bp-default-responsive' ) ) : ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" /><?php endif; ?>
		<title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?></title>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<?php do_action( 'bp_head' ); ?>
		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?> id="bp-default">

		<?php do_action( 'bp_before_header' ); ?>

		<div id="header">
			<div class="padder">
				<h1 id="logo" role="banner"><a href="<?php echo home_url(); ?>" title="<?php _ex( 'Home', 'Home page banner link title', 'buddypress' ); ?>"><?php bp_site_name(); ?></a></h1>
				<br />
				<div id="header_links">
				    <?php 
					$page_projects = get_page_by_title('Projets');
					$page_help = get_page_by_title('Aide');
					$page_submit_project = get_page_by_title('Proposer un projet');
					$page_community = get_page_by_title('Communauté');
				    ?>
				    <a href="<?php echo home_url(); ?>">Accueil</a>
				    <a href="<?php echo get_page_link($page_projects->ID); ?>">Découvrir les projets</a>
				    <a href="<?php echo get_page_link($page_help->ID); ?>">Aide</a>
				    <a href="<?php echo get_page_link($page_submit_project->ID); ?>">Proposer un projet</a>
				    <a href="<?php echo get_page_link($page_community->ID); ?>">Communauté</a>
				    
				</div>
			</div><!-- .padder -->

			<div id="navigation" role="navigation">
				<?php wp_nav_menu( array( 'container' => false, 
				                          'menu_id' => 'nav', 
										  'theme_location' => 'primary', 
										  'fallback_cb' => 'bp_dtheme_main_nav' ) ); 
				?>
			</div>

			<?php do_action( 'bp_header' ); ?>

		</div><!-- #header -->

		<?php do_action( 'bp_after_header'     ); ?>
		<?php do_action( 'bp_before_container' ); ?>

		<div id="container">
