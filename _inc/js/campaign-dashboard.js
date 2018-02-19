function WDGCampaignDashboard() {
	this.walletTimetableDatatable;
    this.initWithHash();
	this.initMenu();
	this.drawTimetable();
	this.initAjaxForms();
}

/**
 * Initialise l'affichage avec le # de l'url
 */
WDGCampaignDashboard.prototype.initWithHash = function() {

	var sCurrentTab = window.location.hash.substring(1);
	if (sCurrentTab !== '') {
		this.switchTab( sCurrentTab );
	} else {
		this.switchTab( 'home' );
	}
	
};

/**
 * Initialise le menu
 */
WDGCampaignDashboard.prototype.initMenu = function() {
	
	var self = this;
	$( 'ul.nav-menu li a' ).each( function() {
		$( this ).click( function() {
			self.switchTab( $( this ).data( 'tab' ) );
		} );
	} );
	
};

/**
 * Change d'onglet
 */
WDGCampaignDashboard.prototype.switchTab = function(sType) {
	
	$( 'ul.nav-menu li' ).removeClass( 'selected' );
	$( 'div#item-body > div.item-body-tab' ).hide();
	
	$( 'ul.nav-menu li#menu-item-' + sType ).addClass( 'selected' );
	$( 'div#item-body > div#item-body-' + sType ).show();
	
};

/**
 * Gère les formulaires ajax
 */
WDGCampaignDashboard.prototype.initAjaxForms = function() {
	
	var self = this;
	$( 'form.ajax-db-form' ).submit( function( e ) {
		
		if ( $(this).attr( 'action' ) != '' && $(this).attr( 'action' ) != undefined ) {
			return;
		}
		e.preventDefault();
		if ($(this).data("action")==undefined) return false;
		var thisForm = $(this);

		//Receuillir informations du formulaire
		var data_to_update = {
			'action': $(this).data("action"),
			'campaign_id': campaign_id
		};

		$(this).find(".field-value").each(function(index){
			 var id = $(this).data('id');
			 switch ($(this).data("type")){
				 case 'datetime':
					 var sDate = $(this).find("input:eq(0)").val();
					 var aDate = sDate.split('/');
					 data_to_update[id] = aDate[1]+'/'+aDate[0]+'/'+aDate[2]+"\ "
						 + $(this).find("select:eq(0)").val() +':'
						 + $(this).find("select:eq(1)").val();
					 break;
				 case 'editor':
					 data_to_update[id] = tinyMCE.get(id).getContent();
					 break;
				 case 'check':
					 data_to_update[id] = $("#"+id).is(':checked');
					 break;
				 case 'multicheck':
					 var data_temp = new Array();
					 $('input', this).each(function() {
						 if ($(this).is(':visible') && $(this).is(':checked')) {
							 data_temp.push($(this).val());
						 }
					 });
					 data_to_update[id] = data_temp;
					 break;
				 case 'text':
				 case 'number':
				 case 'date':
				 case 'link':
				 case 'textarea':
				 case 'select':
				 default:
					 data_to_update[id] = $(':input', this).val();
					 break;
			 }
			 if(data_to_update[id] == undefined){
				 delete data_to_update[id];
			 }
		 });

		//Désactive les champs
		var save_button = $("#"+$(this).attr("id")+"_button");
		save_button.find(".button-text").hide();
		save_button.find(".button-waiting").show();
		$(":input", this).prop('disabled', true);

		thisForm.find('.feedback_save span').fadeOut();

		//Envoi de requête Ajax
		$.ajax({
			'type': "POST",
			'url': ajax_object.ajax_url,
			'data': data_to_update
		}).done(function (result) {
			if (result != "") {
				var jsonResult = JSON.parse(result);
				feedback = jsonResult;

				//Affiche les erreurs
				for(var input in feedback.errors){
					self.fieldError(thisForm.find('#'+input), feedback.errors[input])
				}

				for(var input in feedback.success){
					var thisinput = thisForm.find('#'+input)
					self.removeFieldError(thisinput);
					thisinput.closest(".field-value").parent().find('i.fa.validation').remove();
					thisinput.addClass("validation");
					thisinput.closest(".field-value").after('<i class="fa fa-check validation" aria-hidden="true"></i>');
				}

				//Scrolle jusqu'à la 1ère erreur et la sélectionne
				var firsterror = thisForm.find(".error").first();
				if(firsterror.length == 1){
					self.scrollTo(firsterror);
					//La sélection (ci-dessous) Ne fonctione ne marche pas
					firsterror.focus();
					thisForm.find('.save_errors').fadeIn();
				} else {
					thisForm.find('.save_ok').fadeIn();                          
				}

				// Enregistrer l'organisation liée au projet dans tab-organization
				if ($("#tab-organization").is(":visible") && ($("#ndashboard #orgainfo_form.db-form").data("action")) == "save_project_organization"){
					//Afficher le bouton d'édition de l'organisation après enregistrement de la liaison
//					WDGProjectDashboard.updateEditOrgaBtn(thisForm);
					//Mise à jour du formulaire d'édition après enregistrement de la liaison
//					WDGProjectDashboard.updateOrgaForm(feedback);
					//Mise à jour des liens de téléchargement des docs du formulaire d'édition
//					WDGProjectDashboard.updateOrgaFormDoc(feedback);
					$("#save-mention").hide();
					$("#orgainfo_form_button").hide();
					thisForm.find('.save_ok').hide();
					$("#tab-organization #wdg-lightbox-valid-changeOrga").css('display', 'block');
					new_project_organization = $("#new_project_organization option:selected").val();
				}
			}
		}).fail(function() {
			thisForm.find('.save_fail').fadeIn();
		}).always(function() {
			//Réactive les champs
			save_button.find(".button-waiting").hide();
			save_button.find(".button-text").show();
			thisForm.find(":input").prop('disabled', false);
		});
	});
};

WDGCampaignDashboard.prototype.getContactsTable = function(inv_data, campaign_id) {
	var self = this;
	
	$.ajax({
		'type' : "POST",
		'url' : ajax_object.ajax_url,
		'data': {
			'action':'create_contacts_table',
			'id_campaign':campaign_id,
			'data' : inv_data
		}
	}).done(function(result){
		//Affiche resultat requete Ajax une fois reçue
		$('#ajax-contacts-load').after(result);
		$('#ajax-loader-img').hide();//On cache la roue de chargement.

		YPUIFunctions.initQtip();

		//Création du tableau dynamique dataTable
		self.table = $('#contacts-table').DataTable({
			scrollX: '100%',
			scrollY: '70vh', //Taille max du tableau : 70% de l'écran
			scrollCollapse: true, //Diminue taille du tableau si peu d'éléments*/

			paging: false, //Pas de pagination, affiche tous les éléments yolo
			order: [[result_contacts_table['default_sort'],"desc"]],

			colReorder: { //On peut réorganiser les colonnes
				fixedColumnsLeft: result_contacts_table['id_column_index']+1 //Les 5 colonnes à gauche sont fixes
			},
			fixedColumns : {
				leftColumns: result_contacts_table['id_column_index']+1
			},


			columnDefs: [
				{
					targets: result_contacts_table['array_hidden'], //Cache colonnes par défaut
					visible: false
				},{
					targets: [result_contacts_table['id_column_index']], //Cache colonnes par défaut
					visible: false
				},{
					className: 'select-checkbox',
					targets : 0,
					orderable: false,
				},{
					width: "30px",
					className: "dt-body-center nopadding",
					targets: [2,3,4]
				}
			],

			//Permet la sélection de lignes
			select: {
				style: 'multi', //Sélection multiple
				selector: 'td:first-child'
			},

			dom: 'Bfrtip',
			buttons: [
				{
					text: '<i class="fa fa-square-o" aria-hidden="true"></i> Sélectionner les éléments affichés',
					action: function () {
						self.table.rows( { search: 'applied' } ).select();
					}
				},{
					//Bouton envoi de mail
					extend: 'selected',
					text: '<i class="fa fa-envelope" aria-hidden="true"></i> Envoyer un mail',
					action: function ( e, dt, button, config ) {
						$("#send-mail-tab").slideDown();
						var target = $(this).data("target");
						self.scrollTo($("#send-mail-tab"));
					}
					//TODO : Scroller jusqu'au panneau
				},


				{
					extend: 'collection',
					text: '<i class="fa fa-eye" aria-hidden="true"></i> Informations à afficher',
					buttons: [{
						//Bouton d'affichage de colonnes
						extend: 'colvis',
						text: '<i class="fa fa-columns" aria-hidden="true"></i> Colonnes à afficher',
						columns: ':gt('+result_contacts_table['id_column_index']+')', //On ne peut pas cacher les 5 premières colonnes
						collectionLayout: 'two-column'
					},{
						extend: 'colvisGroup',
						text: 'Tout afficher',
						show: ':gt('+result_contacts_table['id_column_index']+'):hidden'
					},{
						extend: 'colvisGroup',
						text: 'Tout masquer',
						hide: ':gt('+result_contacts_table['id_column_index']+')'
					},{
						extend: 'colvisRestore',
						text: '<i class="fa fa-refresh" aria-hidden="true"></i> Rétablir colonnes par défaut'
					}]
				},

				//Menu d'export
				{
					extend: 'collection',
					text: '<i class="fa fa-download" aria-hidden="true"></i> Exporter',
					buttons: [ {
						//Bouton d'export excel
						extend: 'excel',
						text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Fichier Excel',
						exportOptions: {
							modifier: {
								columns: ':visible'
							}
						}
					},{
						//Bouton d'export impression
						extend: 'print',
						text: '<i class="fa fa-print" aria-hidden="true"></i> Imprimer',
						exportOptions: {
							modifier: {
								columns: ':visible'
							}
						}
					} ]
				}
			],

			language : {
				"sProcessing":     "Traitement en cours...",
				"sSearch":         "Rechercher&nbsp;:",
				"sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
				"sInfo":           "Affichage de _TOTAL_ &eacute;l&eacute;ments",
				"sInfoEmpty":      "Aucun &eacute;l&eacute;ment &agrave; afficher",
				"sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
				"sInfoPostFix":    "",
				"sLoadingRecords": "Chargement en cours...",
				"sZeroRecords":    "Aucun &eacute;l&eacute;ment",
				"sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
				"oPaginate": {
					"sFirst":      "Premier",
					"sPrevious":   "Pr&eacute;c&eacute;dent",
					"sNext":       "Suivant",
					"sLast":       "Dernier"
				},
				"oAria": {
					"sSortAscending":  ": activer pour trier la colonne par ordre croissant",
					"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
				},
				select: {
					rows: {
						_: "<br /><b>%d</b> contacts sélectionnés",
						0: '<br />Cliquez sur un contact pour le sélectionner',
						1: "<br /><b>1</b> contact sélectionné"
					}
				}
			}
		});
		self.table.columns.adjust();

		var mailButtonDefault = self.table.button(1).text()
		self.table.on("select.dt deselect.dt", function ( e, dt, type, indexes ) {
			//Maj Bouton de Mail
			var selectedCount = self.table.rows({ selected: true }).count();
			if(selectedCount==0){
				self.table.button(1).text(mailButtonDefault);
				$("#send-mail-tab").slideUp();
			} else {
				self.table.button(1).text(mailButtonDefault+" ("+selectedCount+")");
			}


			//Maj Bouton de sélection
			var allContained = true;
			self.table.rows( { search:'applied' } ).every( function ( rowIdx, tableLoop, rowLoop ) {
				if($.inArray(rowIdx, self.table.rows({ selected: true }).indexes())==-1){
					allContained= false;
				}
			} );

			if(allContained){
				self.table.button(0).text('<i class="fa fa-check-square-o" aria-hidden="true"></i> Déselectionner les éléments affichés');
				self.table.button(0).action(function () {
					self.table.rows( { search: 'applied' } ).deselect();
				});
			} else {
				self.table.button(0).text('<i class="fa fa-square-o" aria-hidden="true"></i> Sélectionner les éléments affichés');
				self.table.button(0).action(function () {
					self.table.rows( { search: 'applied' } ).select();
				});
			}

			//Maj Champs de Mail
			$("#nb-mailed-contacts").text(selectedCount);

			//Maj liste des identifiants à mailer
			var recipients_array = [];
			$.each(self.table.rows({ selected: true }).data(), function(index, element){
				recipients_array.push(element[result_contacts_table['id_column_index']]);
			});
			$("#mail_recipients").val(recipients_array);
		} );

		// Champs de filtrage
		$( self.table.table().container() ).on( 'keyup', 'tfoot .text input', function () {
			self.table
				.column( $(this).data('index') )
				.search( this.value )
				.draw();
		} );
		$( self.table.table().container() ).on( 'change', 'tfoot .check input', function () {
			if($(this).is(":checked")){
				self.table
					.column( $(this).data('index') )
					.search("1")
					.draw();
			}
			else {
				self.table
					.column( $(this).data('index') )
					.search("")
					.draw();
			}
		} );


	}).fail(function(){
		$('#ajax-contacts-load').after("<em>Le chargement du tableau a échoué</em>");
		$('#ajax-loader-img').hide();//On cache la roue de chargement.
	});
	
};

WDGCampaignDashboard.prototype.drawTimetable = function() {
	// Ajoute mise en page et interactions du tableau
	// Ajoute un champ de filtre à chaque colonne dans le footer
	$('#wdg-timetable tfoot td').each( function () {
		$(this).prepend( '<input type="text" placeholder="Filtrer par :" class="col-filter"/><br/>' );
	} );

	// Ajoute les actions de filtrage
	$("#wdg-timetable tfoot input").on( 'keyup change', function () {
		walletTimetable
			.column( $(this).parent().index()+':visible' )
			.search( this.value )
			.draw();
	} );

	//Récupère le tri par défaut 
	sortColumn = 0;

	this.walletTimetableDatatable = $('#wdg-timetable').DataTable({
		scrollX: true,

		order: [[ sortColumn, "asc" ]], //Colonne à trier (date)

		dom: 'RC<"clear">lfrtip',
		lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Tous"]], //nombre d'élements possibles
		iDisplayLength: 50,//nombre d'éléments par défaut

		//Boutons de sélection de colonnes
		colVis: {
			buttonText: "Afficher/cacher colonnes",
			restore: "Restaurer",
			showAll: "Tout afficher",
			showNone: "Tout cacher",
			overlayFade: 100
		},
		language: {
			"sProcessing":     "Traitement en cours...",
			"sSearch":         "Rechercher&nbsp;:",
			"sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
			"sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
			"sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
			"sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
			"sInfoPostFix":    "",
			"sLoadingRecords": "Chargement en cours...",
			"sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
			"sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
			"oPaginate": {
				"sFirst":      "Premier",
				"sPrevious":   "Pr&eacute;c&eacute;dent",
				"sNext":       "Suivant",
				"sLast":       "Dernier"
			},
			"oAria": {
				"sSortAscending":  ": activer pour trier la colonne par ordre croissant",
				"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
			}
		}
	});
};

WDGCampaignDashboard.prototype.scrollTo = function( target ) {
	$( 'html, body, .wdg-lightbox-padder' ).animate(
		{ scrollTop: target.offset().top - 75 },
		'slow'
	);
};

WDGCampaignDashboard.prototype.fieldError = function( $param, errorText ) {
	$param.addClass("error");
	$param.removeClass("validation");
	$param.qtip({
		content: errorText,
		position: {
			my: 'bottom center',
			at: 'top center',
		},
		style: {
			classes: 'wdgQtip qtip-red qtip-rounded qtip-shadow'
		},
		show: 'focus',
		hide: 'blur'
	});
	$param.closest(".field-value").parent().find('i.fa.validation').remove();
};

WDGCampaignDashboard.prototype.removeFieldError = function( $param ){
	if ( $param.hasClass( "error" ) ) {
		$param.removeClass( "error" );
		$param.qtip().destroy();
	}
};

var wdgCampaignDashboard;
$( function(){
    wdgCampaignDashboard = new WDGCampaignDashboard();
} );