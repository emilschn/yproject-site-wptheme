<?php 
/**
 * Tableau de bord des flux monétaires
 */ 
if ( !current_user_can('manage_options') ) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( 404 ); exit();
}
if (isset($_POST["id-cancel-payment"])) {
    WDGCampaignInvestments::cancel($_POST["id-cancel-payment"]);
    $feedback = 'Paiement annulé';
}
global $disable_logs;
$disable_logs = TRUE;

get_header(); ?>

<div id="content">
    <div class="padder center">
	<h1>Flux mon&eacute;taires - Tableau de bord</h1>
	
	<div>
	    <ul class="no-style">
		<?php /*<li>
		    <h2 class="expandator" data-target="1">WE DO GOOD +</h2>
		    <div id="extendable-1" class="expandable">
			<h3>Etat du porte-monnaie sur Mangopay :</h3>
			<?php // $wdg_mp_user = ypcf_mangopay_get_user_by_id(1); print_r($wdg_mp_user); ?>
			<?php // $wdg_mp_wallet = ypcf_mangopay_get_wallet_by_id(1); print_r($wdg_mp_wallet); ?>
			<div>
			    <h3>Liste des transactions</h3>
			    <table>
				<thead>
				    <td>Date</td>
				    <td>Objet</td>
				    <td>Débit</td>
				    <td>Crédit</td>
				</thead>
				<tfoot>
				    <td>Date</td>
				    <td>Objet</td>
				    <td>Débit</td>
				    <td>Crédit</td>
				</tfoot>
				<tr>
				    <td>06/06/2015</td>
				    <td>Achat d'une piscine</td>
				    <td>500€</td>
				    <td>-</td>
				</tr>
			    </table>
			</div>
		    </div>
		</li> */ ?>
		
		<?php $project_list = ATCF_Campaign::list_projects_started(); ?>
		<?php foreach ($project_list as $project_post): ?>
		<li class="db-money-flow-item">
		    <h2 class="expandator" data-target="<?php echo $project_post->ID; ?>"><?php echo $project_post->post_title; ?> +</h2>
		    <div id="extendable-<?php echo $project_post->ID; ?>" class="expandable"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>
		</li>
		<?php endforeach; ?>
	    </ul>
	</div>
	
	<hr />
	
	<div>
		<h2>Fonctions supplémentaires</h2>
		
		<a id="action-feedback"></a>
		<?php echo $feedback; ?>
		
		<?php $page_payment_dashboard = get_page_by_path('liste-des-paiements'); ?>
		<form method="POST" action="<?php echo get_permalink($page_payment_dashboard->ID); ?>#action-feedback">
		    <h3>Annuler un paiement en attente</h3>
		    Id du paiement : <input type="text" name="id-cancel-payment" /> <input type="submit" class="button" value="Annuler" />
		</form>
		
		<?php /* <form method="POST" action="">
		    <h3>Annuler un virement en attente</h3>
		    Id du paiement : <input type="text" name="id-payment" /> <input type="submit" class="button" value="Annuler" />
		</form> */ ?>
	</div>
	
	<hr />
	
    </div>
</div>

<?php get_footer();