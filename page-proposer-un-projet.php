<?php get_header(); ?>
<?php require_once("common.php"); ?>

<div id="content">
    <div class="padder">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	    <?php locate_template( array( 'basic/basic-header.php' ), true ); ?>
	    <div id="post_bottom_bg">
		<div id="post_bottom_content" class="center">
		    <div class="left post_bottom_desc_small">
			<?php
			global $submit_errors;
			if (isset($submit_errors)) :
			?>
			<div class="errors">
			    Nous n&apos;avons pas pu valider votre proposition :
			    <ul>
			    <?php
			    foreach($submit_errors->errors as $error) {
				echo '<li>'.$error[0].'</li>';
			    }
			    ?>
			    </ul>
			</div>
			<?php 
			endif;
			?>
			
			<?php 
			    the_content();
			?>
		    </div>
		    <div style="clear: both"></div>
		</div>
	    </div>

	<?php endwhile; endif; ?>

    </div><!-- .padder -->
</div><!-- #content -->
	
<?php get_footer(); ?>