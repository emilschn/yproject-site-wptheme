<?php
global $wdg_current_field;
$editor_params = array( 
	'media_buttons' => TRUE,
	'quicktags'     => FALSE,
	'editor_height' => 500,
	'tinymce'       => array(
		'plugins'		=> 'wordpress, paste, wplink, textcolor, charmap, hr, colorpicker, lists',
		'toolbar1'		=> 'bold,italic,underline,|,hr,bullist,numlist,|,alignleft,aligncenter,alignright,alignjustify,|,link,unlink,video,wp_adv',
		'toolbar2'		=> 'formatselect,fontsizeselect,removeformat,charmap,forecolor,forecolorpicker,pastetext,table,undo,redo',
		'paste_remove_styles' => TRUE,
		'wordpress_adv_hidden' => FALSE,
	)
);
wp_editor( $wdg_current_field[ 'value' ], $wdg_current_field[ 'name' ], $editor_params ); ?>