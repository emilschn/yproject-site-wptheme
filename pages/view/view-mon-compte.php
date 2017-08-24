<?php if ( ATCF_CrowdFunding::get_platform_context() != 'wedogood' ): ?>
<div class="align-center" style="padding: 15px 0px; background: #FFFFFF;">
	<a href="<?php echo wp_logout_url(); ?>" class="button"><?php _e( "D&eacute;connexion", 'yproject' ); ?></a>
</div>
<?php endif; ?>

<div id="content">
	<?php locate_template( array( 'pages/view/mon-compte/nav.php'  ), true ); ?>	
	<?php locate_template( array( 'pages/view/mon-compte/content.php'  ), true ); ?>
</div>