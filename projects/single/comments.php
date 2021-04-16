<?php
global $campaign;
$comment_list = get_comments(array(
	'post_id'	=> $campaign->ID
));
$WDGUser_current = WDGUser::current();
?>
<div class="project-comments padder">
	<h2 class="standard">/ <?php _e('Commentaires', 'yproject'); ?> /</h2>
	
	<?php if (!is_user_logged_in() && !$campaign->get_show_comments_for_everyone()): ?>
		<div class="align-center">
			<?php _e('Vous devez &ecirc;tre connect&eacute;(e) pour lire et poster des commentaires.', 'yproject'); ?><br><br>
			<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'inscription' ); ?>" id="register" class="wdg-button-lightbox-open button red"><?php _e("Inscription", 'yproject'); ?></a>
			<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'connexion' ); ?>" id="connexion" class="wdg-button-lightbox-open button red"><?php _e("Connexion", 'yproject'); ?></a>
		</div>
	
	<?php else: ?>
    
		<?php if ( count($comment_list) > 0 ) : ?>
			<ul>
			<?php foreach ($comment_list as $comment): ?>
				<?php $WDGUser_author = new WDGUser( $comment->user_id, FALSE ); ?>
				<li id="comment-<?php echo $comment->comment_ID; ?>">
					<strong><?php echo $WDGUser_author->get_display_name(). ' (' .get_comment_date('', $comment->comment_ID). ') : '; ?></strong>
					<?php echo $comment->comment_content; ?>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<div class="align-center"><?php _e('Aucun commentaire pour l&apos;instant.', 'yproject'); ?></div>
		<?php endif; ?>
			
			
		<?php if ( !is_user_logged_in() ): ?>
			<div class="align-center">
				<br><br>
				<?php _e('Vous devez &ecirc;tre connect&eacute;(e) pour poster des commentaires.', 'yproject'); ?><br><br>
				<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'inscription' ); ?>" id="register" class="wdg-button-lightbox-open button red"><?php _e("Inscription", 'yproject'); ?></a>
				<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'connexion' ); ?>" id="connexion" class="wdg-button-lightbox-open button red"><?php _e("Connexion", 'yproject'); ?></a>
			</div>
			
		<?php else: ?>
			
			<?php if ( !comments_open( $campaign->ID ) ): ?>
				<div class="align-center"><?php _e('Les commentaires ne sont pas ouverts.', 'yproject'); ?></div>
				
			<?php else: ?>
				<?php comment_form( array(
						"title_reply"			=> __('Poster un commentaire', 'yproject'),
						"comment_notes_after"	=> "",
						"logged_in_as"	=> __('Connect&eacute;(e) en tant que ', 'yproject') . $WDGUser_current->get_display_name()
				), $campaign->ID ); ?>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
</div>