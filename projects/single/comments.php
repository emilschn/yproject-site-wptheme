<?php
global $campaign;
?>
<div class="project-comments center">
	<div class="project-comments-title separator-title">
		<span> 
			<?php _e('Commentaires', 'yproject'); ?>
		</span>
	</div>
    
	<?php if ( have_comments() ) : ?>
		<?php wp_list_comments( array( 'callback' => 'bp_dtheme_blog_comments', 'type' => 'comment' ) ); ?>
	<?php else: ?>
		<div class="align-center"><?php _e('Aucun commentaire pour l&apos;instant', 'yproject'); ?></div>
	<?php endif; ?>
	
	<?php if (!is_user_logged_in()): ?>
		<div class="align-center"><?php _e('Vous devez &ecirc;tre connect&eacute; pour poster un commentaire.', 'yproject'); ?></div>
	<?php elseif (!comments_open()): ?>
		<div class="align-center"><?php _e('Les commentaires ne sont pas ouverts pour l&apos;instant.', 'yproject'); ?></div>
	<?php else: ?>
		<?php comment_form( array(
				"title_reply"			=> __('Poster un commentaire', 'yproject'),
				"comment_notes_after"	=> ""
		)); ?>
	<?php endif; ?>
</div>