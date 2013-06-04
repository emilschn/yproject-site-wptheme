<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<?php if ( current_theme_supports( 'bp-default-responsive' ) ) : ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" /><?php endif; ?>
		<title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?></title>
		
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<?php do_action( 'bp_head' ); ?>
		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?> id="bp-default">

		<?php do_action( 'bp_before_header' ); ?>

		<nav id="navigation" role="navigation">
		    <div class="center">
			<ul id="nav">
			    <li class="page_item"><a href=""><img src="" width="16" height="16" /></a></li>
			    <li class="page_item"><a href="">DECOUVRIR LES PROJETS</a></li>
			    <li class="page_item"><a href="">COMMENT ÇA MARCHE ?</a></li>
			    <li class="page_item"><a href="">PROPOSER UN PROJET</a></li>
			    <li class="page_item"><a href="">COMMUNAUTE</a></li>
			    <li class="page_item"><a href="">CONNEXION</a></li>
			    <li class="page_item"><a href=""><img src="" width="16" height="16" /></a></li>
			    <li class="page_item_last"><a href=""><img src="" width="16" height="16" /></a></li>
			</ul>
		    </div>
		</nav>
	    
		<header>
		    <div id="site_name" class="center">
			    <h1 id="logo" role="banner"><a href="<?php echo home_url(); ?>" title="<?php _ex( 'Home', 'Home page banner link title', 'buddypress' ); ?>"><?php bp_site_name(); ?></a></h1>
			    <br />
		    </div>

		    <?php do_action( 'bp_header' ); ?>
		</header>

		<?php do_action( 'bp_after_header'     ); ?>
		<?php do_action( 'bp_before_container' ); ?>

		<div id="container" class="center">
