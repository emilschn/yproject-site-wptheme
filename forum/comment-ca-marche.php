 <div class="post_bottom_buttons">
	<div class="dark" style="color:white;">
	    <?php /* Lien page faq */ $page_manage = get_page_by_path('faq-2'); ?>
	    <a href="<?php echo get_permalink($page_manage->ID); ?>"><?php echo __('FAQ', 'yproject'); ?></a>
	</div>
    </div>

    <div class="post_bottom_buttons">
	<div class="light" >
	    <?php /* forum questions */  $forum = get_page_by_path('Forum Questions'); ?>
	    <a href="<?php echo get_permalink($forum->ID); ?>"> <?php echo __('FORUM Questions', 'yproject'); ?></a>
	</div>
    </div>

    <div class="post_bottom_buttons">
	<div class="light" >
	    <?php /* forum idees */  $forum = get_page_by_path('Forum Idees'); ?>
	    <a href="<?php echo get_permalink($forum->ID); ?>"> <?php echo __('FORUM Idées', 'yproject'); ?></a>
	</div>
    </div>

    <div class="post_bottom_buttons">
	<div class="light" >
	    <?php /* forum idees */  $forum = get_page_by_path('Forum Bugs'); ?>
	    <a href="<?php echo get_permalink($forum->ID); ?>"> <?php echo __('FORUM Bugs', 'yproject'); ?></a>
	</div>
    </div>