<?php get_header( 'buddypress' ); ?>

    <div id="content">
	<div class="padder">
	
	    <?php locate_template( array( 'basic/basic-header.php' ), true ); ?>

	    <?php do_action( 'bp_before_register_page' ); ?>
	    
	    <div id="post_bottom_bg">
		<div id="post_bottom_content" class="center" id="register-page" style="padding-left: 30px;">

                    <?php include 'form_register.php' ?>
		</div>
	    </div>

	    <?php do_action( 'bp_after_register_page' ); ?>

	</div><!-- .padder -->
    </div><!-- #content -->

    <script type="text/javascript">
	jQuery(document).ready( function() {
	    if ( jQuery('div#blog-details').length && !jQuery('div#blog-details').hasClass('show') )
		    jQuery('div#blog-details').toggle();

	    jQuery( 'input#signup_with_blog' ).click( function() {
		    jQuery('div#blog-details').fadeOut().toggle();
	    });
	});
    </script>

<?php get_footer( 'buddypress' ); ?>