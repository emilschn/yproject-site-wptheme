<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head>
		<?php global $stylesheet_directory_uri; ?>
		<?php if ( ATCF_CrowdFunding::get_platform_context() == 'wedogood' ): ?>
		<link href="<?php echo $stylesheet_directory_uri; ?>/images/favicon.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
		<!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="<?php echo $stylesheet_directory_uri; ?>/images/favicon.ico"/><![endif]-->
		<?php endif; ?>
		<title><?php echo wp_title(); ?></title>

		<link href="https://plus.google.com/+WedogoodCo" rel="publisher" />
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="Première plateforme de financement participatif en royalties (royalty crowdfunding). Entrepreneurs : levez des fonds sans diluer votre capital !" />

		<!--[if lt IE 9]>
		    <script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/html5shiv.js"></script>
		<![endif]-->
		<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/common.min.css?d=<?php echo ASSETS_VERSION; ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?d=<?php echo ASSETS_VERSION; ?>" type="text/css" media="screen" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		
		<?php if ( !is_user_logged_in() && $post->post_name == 'inscription' ): ?>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<?php endif; ?>

		<?php wp_head(); ?>

		<!-- Meta spécifiques à Facebook -->
		<meta property="og:description" content="Première plateforme de financement participatif en royalties (royalty crowdfunding). Entrepreneurs : levez des fonds sans diluer votre capital !" />
		<meta property="fb:app_id" content="<?php echo YP_FB_APP_ID; ?>" />
	</head>

	<body <?php body_class(get_locale()); ?>>
		
		<div id="container"> 
