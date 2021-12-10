<?php 
	global $stylesheet_directory_uri;
	$page_controler = WDG_Templates_Engine::instance()->get_controler(); 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head>
		<?php if (ATCF_CrowdFunding::get_platform_context() == 'wedogood'): ?>
		<link href="<?php echo $stylesheet_directory_uri; ?>/images/favicon.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
		<!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="<?php echo $stylesheet_directory_uri; ?>/images/favicon.ico"/><![endif]-->
		<?php endif; ?>
		<title><?php echo $page_controler->get_page_title(); ?></title>

		<?php /* Google Tag Manager */ ?>
		<?php
			/* WDG :
			si c'est déjà défini par le biais des controler (= si l'utilisateur est identifié),
			on peut déjà envoyer la donnée ; sinon ce sera envoyé plus tard
			*/
		?>
		<script>
			function wdg_gtm_call() {
				(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
				})(window,document,'script','dataLayer','GTM-KFV5RN5');
			}
			dataLayer = [];

			<?php if ( !empty( $analytics_data[ 'payment' ] ) ): ?>
				dataLayer.push({
					'event': 'purchase',
					'event_category': 'Ecommerce',
					'event_action': 'Transaction',
					// ID de la transaction - Format : String
					'event_label': '<?php echo $analytics_data[ 'payment' ][ 'event_label' ]; ?>',
					// Montant total de l'investissement - Format : Numérique
					'value': <?php echo $analytics_data[ 'payment' ][ 'value' ]; ?>,
					'currency': 'EUR',
					'ecommerce': {
						'purchase': {
							'actionField': {
								// ID de la transaction - Format : String
								'id': '<?php echo $analytics_data[ 'payment' ][ 'event_label' ]; ?>',
								// Montant total de l'investissement, incluant la TVA - Format : Numérique
								'revenue': <?php echo $analytics_data[ 'payment' ][ 'value' ]; ?>
							},
							'products': [{
								// Titre du projet - Format : String
								'name': '<?php echo $analytics_data[ 'payment' ][ 'product_name' ]; ?>',
								// ID du projet - Format : String
								'id': '<?php echo $analytics_data[ 'payment' ][ 'product_id' ]; ?>',
								// Montant de l'investissement - Format : Numérique
								'price': <?php echo $analytics_data[ 'payment' ][ 'value' ]; ?>,
								// Nom de la société qui porte le projet - Format : String
								'brand': '<?php echo $analytics_data[ 'payment' ][ 'product_brand' ]; ?>',
								// Catégorie du projet - Format : String
								'category': '<?php echo $analytics_data[ 'payment' ][ 'product_category' ]; ?>',
								// Aide pour Analytics
								'quantity': 1
							}]
						}
					}
				});

			<?php elseif ( !empty( $analytics_data[ 'event' ] ) ): ?>
				dataLayer.push({
					'event': '<?php echo $analytics_data[ 'event' ]; ?>'
				});
				<?php
					ypcf_session_start();
					$_SESSION['send_creation_event'] = '';
					unset( $_SESSION['send_creation_event'] );
				?>
			<?php endif; ?>
		</script>
		<?php /* Campagne */ ?>
		<?php if ( !empty( $campaign_google_tag_manager_id ) ): ?>
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','campaignDataLayer','<?php echo $campaign_google_tag_manager_id; ?>');</script>
		<?php endif; ?>
		<?php /* End Google Tag Manager */ ?>

		<link href="https://plus.google.com/+WedogoodCo" rel="publisher" />
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php if ( $page_controler->get_page_description() !== FALSE ): ?>
		<meta name="description" content="<?php echo $page_controler->get_page_description(); ?>" />
		<?php endif; ?>

		<!--[if lt IE 9]>
		    <script src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/html5shiv.js"></script>
		<![endif]-->
		<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/common.min.css?d=<?php echo ASSETS_VERSION; ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?d=<?php echo ASSETS_VERSION; ?>" type="text/css" media="screen" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		
		<?php if (!is_user_logged_in() && $post->post_name == 'inscription'): ?>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<?php endif; ?>

		<?php wp_head(); ?>

		<!-- Meta spécifiques à Facebook -->
		<meta property="og:description" content="<?php echo $page_controler->get_page_description(); ?>" />
		<?php $imageFacebook = $stylesheet_directory_uri .'/images/common/wedogood-logo-rouge.png'; ?>
		<meta property="og:image" content="<?php echo $imageFacebook ?>" />
		<meta property="og:image:secure_url" content="<?php echo $imageFacebook ?>" />
		<meta property="og:image:type" content="image/png" />
		<meta property="fb:app_id" content="<?php echo YP_FB_APP_ID; ?>" />
	</head>

	<body <?php body_class(get_locale()); ?>>
		<?php /* Google Tag Manager (noscript) */ ?>
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KFV5RN5"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		
		<?php if ( !empty( $campaign_google_tag_manager_id ) ): ?>
			<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $campaign_google_tag_manager_id; ?>"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<?php endif; ?>
		<?php /* End Google Tag Manager (noscript) */ ?>
		
		<div id="container"> 
