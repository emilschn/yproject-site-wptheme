<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>
<main>
	<div>

		<div id="item-body">
			<div id="item-body-loading" class="item-body-tab">
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-loading.php'  ), true ); ?>
			</div>
			
			<div id="item-body-home" class="item-body-tab">
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-home.php'  ), true ); ?>
			</div>
			
			<div id="item-body-stats" class="item-body-tab">
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-stats.php'  ), true ); ?>
			</div>
			<div id="item-body-contacts" class="item-body-tab">
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-contacts.php'  ), true ); ?>
			</div>
			<div id="item-body-presentation" class="item-body-tab">
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-presentation.php'  ), true ); ?>
			</div>
			<div id="item-body-news" class="item-body-tab">
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-news.php'  ), true ); ?>
			</div>
			<div id="item-body-tools" class="item-body-tab">
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-tools.php'  ), true ); ?>
			</div>
			<div id="item-body-documents" class="item-body-tab">
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-documents.php'  ), true ); ?>
			</div>
			<div id="item-body-royalties" class="item-body-tab">
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-royalties.php'  ), true ); ?>
			</div>
			
			<div id="item-body-author" class="item-body-tab">
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-author.php'  ), true ); ?>
			</div>
			<div id="item-body-organization" class="item-body-tab">
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-organization.php'  ), true ); ?>
			</div>
			<div id="item-body-team" class="item-body-tab">
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-team.php'  ), true ); ?>
			</div>
			<div id="item-body-finance" class="item-body-tab">
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-finance.php'  ), true ); ?>
			</div>
			<div id="item-body-contracts" class="item-body-tab">
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-contracts.php'  ), true ); ?>
			</div>
			<div id="item-body-campaign" class="item-body-tab">
				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-campaign.php'  ), true ); ?>
			</div>
		</div>

	</div>
</main>
		