 <div class="left community-menu">
	<div class="post_bottom_buttons">
	    <div class="dark">
		<?php $page_team = get_page_by_path('lequipe'); //Lien vers l'équipe ?>
		<a href="<?php echo get_permalink($page_team->ID); ?>"><?php _e("L&apos;&eacute;quipe", "yproject"); ?></a>
	    </div>
	    <div class="dark">
		<?php $page_makesense = get_page_by_path('makesense'); //Lien vers MakeSense ?>
		<a href="<?php echo get_permalink($page_makesense->ID); ?>">MakeSense</a>
	    </div>
	    <div class="dark">
		<?php $page_partners = get_page_by_path('partenaires'); //Lien vers Partenaires ?>
		<a href="<?php echo get_permalink($page_partners->ID); ?>"><?php _e("Partenaires", "yproject"); ?></a>
	    </div>
	     <div class="dark">
		<?php $page_like = get_page_by_path('les-projets-que-nous-aimons'); //Lien vers l'équipe ?>
		<a href="<?php echo get_permalink($page_like->ID); ?>">Projets que nous aimons</a>
	    </div>
	    <div class="dark">
		<?php $page_blog = get_page_by_path('blog'); //Lien vers l'équipe ?>
		<a href="<?php echo get_permalink($page_blog->ID); ?>"><?php _e('Blog', 'yproject'); ?></a>
	    </div>
	</div>
    </div>