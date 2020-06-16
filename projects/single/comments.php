<?php
global $campaign;
?>
<div class="project-comments padder">
	<h2 class="standard">/ <?php _e('Commentaires', 'yproject'); ?> /</h2>
	
	<?php if (!is_user_logged_in() && !$campaign->get_show_comments_for_everyone()): ?>
		<div class="align-center">
			<?php _e('Vous devez &ecirc;tre connect&eacute;(e) pour lire et poster des commentaires.', 'yproject'); ?><br><br>
			<a href="<?php echo home_url( '/inscription/' ); ?>" id="register" class="wdg-button-lightbox-open button red"><?php _e("Inscription", 'yproject'); ?></a>
			<a href="<?php echo home_url( '/connexion/' ); ?>" id="connexion" class="wdg-button-lightbox-open button red"><?php _e("Connexion", 'yproject'); ?></a>
		</div>
	
	<?php else: ?>
		
		<?php
		$comments = get_comments(array(
			'post_id'	=> $campaign->ID,
			'status'	=> 'approve'
		));
		?>

		<?php wp_list_comments( array(), $comments ); ?>

		<?php if ( !is_user_logged_in() ): ?>
			<div class="align-center">
				<br><br>
				<?php _e('Vous devez &ecirc;tre connect&eacute;(e) pour poster des commentaires.', 'yproject'); ?><br><br>
				<a href="<?php echo home_url( '/inscription/' ); ?>" id="register" class="wdg-button-lightbox-open button red"><?php _e("Inscription", 'yproject'); ?></a>
				<a href="<?php echo home_url( '/connexion/' ); ?>" id="connexion" class="wdg-button-lightbox-open button red"><?php _e("Connexion", 'yproject'); ?></a>
			</div>
			
		<?php else: ?>
			<?php comment_form( array(), $campaign->ID ); ?>

		<?php endif; ?>
	<?php endif; ?>
</div>