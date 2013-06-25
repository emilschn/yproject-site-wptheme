<?php 
    date_default_timezone_set("Europe/Paris");
    require_once("common.php");
?>


<div id="content">
    <div id="home_top" class="center">
	<div class="padder">
	    <?php /* Affichage des projets */ ?>
	    <div id="projects_vote" class="projects_preview">
		<h1><?php echo __('Votez !', 'yproject'); ?></h1>
		<?php printPreviewProjectsVote(2); ?>
	    </div>

	    <div id="projects_current" class="projects_preview">
		<h1><?php echo __('Les projets en cours', 'yproject'); ?></h1>
		<?php printPreviewProjectsTop(3); ?>
		<?php printPreviewProjectsNew(3); ?>
	    </div>	

	    <div style="clear: both"></div>
	</div>
    </div>
    
	    
    <div id="home_middle">
	<div id="home_middle_top">
	    <div class="center">
		<div class="round_title_left"><?php echo __('Participez à un projet', 'yproject'); ?></div>
		<div class="round_title_right"><?php echo __('Proposez un projet', 'yproject'); ?></div>
		<div style="clear: both"></div>
	    </div>
	</div>
	<div id="home_middle_content">
	    <div class="center">
		<?php /* Participer à un projet | Proposer un projet */ ?>
		<?php 
		    wp_reset_query();
		    the_content();
		    
		    
		    /*
		     * 
		<strong>Participer à un projet | Proposer un projet | Différenciations</strong><br />
		TODO : Diviser en deux et inclure les pages
		<?php 
		    $page_show_proj = get_page_by_title('Projets'); 
		    $page_input_proj = get_page_by_title('Proposer un projet');
		    $page_how_it_works = get_page_by_title('Comment ça marche');
		?>
		<a href="<?php echo get_permalink($page_show_proj->ID); ?>">Lien vers participer à un projet</a> (<a href="<?php echo get_permalink($page_how_it_works->ID); ?>#everyone">Lien vers Comment participer à un projet ?</a>)<br />
		<a href="<?php echo get_permalink($page_input_proj->ID); ?>">Lien vers proposer un projet</a> (<a href="<?php echo get_permalink($page_how_it_works->ID); ?>#newproject">Lien vers Comment proposer un projet ?</a>)<br />
		Schéma de ce qui fait la particularité de notre plateforme<br /><br />
		     * 
		     * 
		     */
		?>
	    </div>
	</div>
    </div>
	
	
    <div id="home_bottom" class="center">
	<div class="padder">
	    <?php /* Rechercher */ ?>
	    <h1><?php echo __('Trouvez les projets', 'yproject'); ?></h1>
	    TODO: une carte de France qui a un roll sur chacune des régions. Quand on clique sur une région, ça filtre les projets.<br />
	    TODO: recherche des projets par centres d'intérêts<br />
	    
	    <form action="<?php echo bp_search_form_action(); ?>" method="post" id="search-form">
		    <label for="search-terms" class="accessibly-hidden"><?php _e( 'Search for:', 'buddypress' ); ?></label>
		    <input type="text" id="search-terms" name="search-terms" value="<?php echo isset( $_REQUEST['s'] ) ? esc_attr( $_REQUEST['s'] ) : ''; ?>" />

		    <?php echo bp_search_form_type_select(); ?>

		    <input type="submit" name="search-submit" id="search-submit" value="<?php _e( 'Search', 'buddypress' ); ?>" />

		    <?php wp_nonce_field( 'bp_search_form' ); ?>

	    </form><!-- #search-form -->

	    
	    <?php /* Communauté : actualités | derniers investisseurs */ ?>
	    <h1><?php echo __('Actualites', 'yproject'); ?></h1>
	    <strong>Ces personnes viennent d'investir :</strong><br />
	    <ul>
	    <?php printPreviewUsersLastInvestors(10); ?>
	    </ul>
	    
	    <strong>Ces projets ont réussi :</strong><br />
	    <?php printPreviewProjectsFinished(4); ?>
	    
	    <?php
	    /*
	    <strong>Fil d'actualité</strong><br />
	    <ul>
		<li>TODO: une liste d'activité</li>
	    </ul>
	    Voir plus (TODO: un lien vers la page communauté)<br />
	    <br />
	     * 
	     */
	    ?>
	</div>
    </div>
</div><!-- #content -->
