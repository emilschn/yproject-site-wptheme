<?php
    if (is_user_logged_in()):
	    // Bail if post type doesn't support comments
	    if ( ! post_type_supports( get_post_type(), 'comments' ) )
		    return;

	    // Bail if is a page, and comments are not open
	    if ( is_page() && ! have_comments() && ! comments_open() && ! pings_open() )
		    return;

	    if ( post_password_required() ) {
		    echo '<h3 class="comments-header">' . __( 'Prot&eacute;g&eacute; par mot de passe', 'yproject' ) . '</h3>';
		    echo '<p class="alert password-protected">' . __( 'Saisissez le mot de passe pour voir les commentaires', 'yproject' ) . '</p>';
		    return;
	    }

	    if ( have_comments() ) :
		    $num_comments   = 0;
		    $num_trackbacks = 0;
		    foreach ( (array) $comments as $comment ) {
			    if ( 'comment' != get_comment_type() ) {
				    $num_trackbacks++;
			    } else {
				    $num_comments++;
			    }
		    }
    ?>
	    <div id="comments">

		    <h3>
			    <?php printf( _n( '1 r&eacute;ponse &agrave; %2$s', '%1$s r&eacute;ponse &agrave; %2$s', $num_comments, 'yproject' ), number_format_i18n( $num_comments ), '<em>' . get_the_title() . '</em>' ); ?>
		    </h3>

		    <ol class="commentlist">
			    <?php wp_list_comments( array( 'type' => 'comment' ) ); ?>
		    </ol><!-- .comment-list -->

		    <?php if ( get_option( 'page_comments' ) ) : ?>
			    <div class="comment-navigation paged-navigation">
				    <?php paginate_comments_links(); ?>
			    </div>
		    <?php endif; ?>

	    </div><!-- #comments -->

    <?php else : ?>

	    <?php if ( ! comments_open() ) : ?>
		    <?php if ( pings_open() ) : ?>
			    <p class="comments-closed pings-open">
				    <?php printf( __( 'Les commentaires sont ferm&eacute;s, mais <a href="%1$s" title="Trackback URL for this post">trackbacks</a> et pingbacks sont ouverts.', 'yproject' ), get_trackback_url() ); ?>
			    </p>
		    <?php else : ?>
			    <p class="comments-closed">
				    <?php _e( 'Les commentaires sont ferm&eacute;s.', 'yproject' ); ?>
			    </p>
		    <?php endif; ?>
	    <?php endif; ?>

    <?php endif; ?>

    <?php if ( comments_open() ) comment_form(array("comment_notes_after" => "")); ?>

    <?php if ( !empty( $num_trackbacks ) ) : ?>
	    <div id="trackbacks">
		    <h3><?php printf( _n( '1 trackback', '%d trackbacks', $num_trackbacks, 'yproject' ), number_format_i18n( $num_trackbacks ) ); ?></h3>

		    <ul id="trackbacklist">
			    <?php foreach ( (array) $comments as $comment ) : ?>

				    <?php if ( 'comment' != get_comment_type() ) : ?>
					    <li>
						    <h5><?php comment_author_link(); ?></h5>
						    <em>on <?php comment_date(); ?></em>
					    </li>
				    <?php endif; ?>

			    <?php endforeach; ?>
		    </ul>

	    </div>
    <?php endif; ?>
<?php else: ?>
    <h3>Vous devez vous connecter pour poster un commentaire.</h3>	    
<?php endif; ?>