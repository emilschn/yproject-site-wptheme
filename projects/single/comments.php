<?php
global $campaign;
$comment_list = get_comments(array(
	'post_id'	=> $campaign->ID
));
$WDGUser_current = WDGUser::current();
?>
<div class="project-comments padder">
	<h2 class="standard">/ <?php _e('Commentaires', 'yproject'); ?> /</h2>
	
	<?php if (!is_user_logged_in()): ?>
		<div class="align-center">
			<?php _e('Vous devez &ecirc;tre connect&eacute; pour lire et poster des commentaires.', 'yproject'); ?><br /><br />
			<a href="#register" id="register" class="wdg-button-lightbox-open button red" data-lightbox="register" data-redirect="<?php echo get_permalink(); ?>"><?php _e("Inscription", 'yproject'); ?></a>
			<a href="#connexion" id="connexion" class="wdg-button-lightbox-open button red" data-lightbox="connexion" data-redirect="<?php echo get_permalink(); ?>"><?php _e("Connexion", 'yproject'); ?></a>
		</div>
	<?php else: ?>
    
		<?php if ( count($comment_list) > 0 ) : ?>
			<ul>
			<?php foreach ($comment_list as $comment): ?>
				<li id="comment-<?php echo $comment->comment_ID; ?>">
					<strong><?php echo $comment->comment_author. ' (' .get_comment_date('', $comment->comment_ID). ') : '; ?></strong>
					<?php echo $comment->comment_content; ?>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<div class="align-center"><?php _e('Aucun commentaire pour l&apos;instant.', 'yproject'); ?></div>
		<?php endif; ?>
			
		<?php if (!comments_open()): ?>
			<div class="align-center"><?php _e('Les commentaires ne sont pas ouverts.', 'yproject'); ?></div>
		<?php else: ?>
			<?php comment_form( array(
					"title_reply"			=> __('Poster un commentaire', 'yproject'),
					"comment_notes_after"	=> "",
					"logged_in_as"	=> __('Connect&eacute; en tant que ', 'yproject') . $WDGUser_current->wp_user->display_name
			), $campaign->ID ); ?>
		<?php endif; ?>
	<?php endif; ?>
</div>