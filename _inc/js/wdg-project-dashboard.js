jQuery(document).ready(function ($) {
    WDGProjectDashboard.init();
});


var WDGProjectDashboard = (function ($) {
    return {
        forceInvestSubmit: false,
        currentOpenedROI: 0,

        init: function () {
            var campaign_id = $("#ndashboard").data("campaign-id");

            //Gestion de l'AJAX pour la lightbox de ROI
            if ($(".transfert-roi-open").length > 0) {
                $(".transfert-roi-open").click(function () {
                    if ($(this).data('roideclaration-id') !== WDGProjectDashboard.currentOpenedROI) {
                        //Affichage
                        WDGProjectDashboard.currentOpenedROI = $(this).data('roideclaration-id');
                        $("#wdg-lightbox-transfer-roi #lightbox-content .loading-content").html("");
                        $("#wdg-lightbox-transfer-roi #lightbox-content .loading-image").show();
                        $("#wdg-lightbox-transfer-roi #lightbox-content .loading-form").hide();

                        //Lancement de la requête pour récupérer les utilisateurs et les sommes associées
                        $.ajax({
                            'type': "POST",
                            'url': ajax_object.ajax_url,
                            'data': {
                                'action': 'display_roi_user_list',
                                'roideclaration_id': $(this).data('roideclaration-id')
                            }
                        }).done(function (result) {
                            var content = '<table>';
                            content += '<tr><td>Utilisateur</td><td>Investissement</td><td>Versement</td><td>Commission</td></tr>';
                            content += result;
                            content += '</table>';
                            $("#wdg-lightbox-transfer-roi #lightbox-content .loading-content").html(content);
                            $("#wdg-lightbox-transfer-roi #lightbox-content .loading-image").hide();
                            $("#wdg-lightbox-transfer-roi #lightbox-content .loading-form input#hidden-roi-id").val(WDGProjectDashboard.currentOpenedROI);
                            $("#wdg-lightbox-transfer-roi #lightbox-content .loading-form").show();
                        });
                    }
                });
            }

            /**
             * DASHBOARD GENERAL
             */
            //Onglets dashboard
            if ($("#ndashboard-navbar li a").length > 0) {
                $("#ndashboard-navbar li a:not(.disabled)").click(function (e) {

                    $("#ndashboard-navbar li a").removeClass("active");
                    $(this).addClass("active");

                    //Arrête chargement des iframes
                    $("#ndashboard-content .google-doc iframe").not(".isloaded").prop('src','');

                    //Cache les infobulles ouvertes
                    $(".qtip").hide()

                    //Change le contenu de la page
                    var target = $(this).data("target");

                    $("#ndashboard-content .page-dashboard").hide();

                    //Montre nouvelle page ou celle de chargement
                    if(target==undefined){
                        $("#ndashboard-navbar li a").addClass("disabled").unbind( "click" );
                        $("#ndashboard-content #page-redirect").show();
                    } else {
                        $("#ndashboard-content #" + target).show();
                    }

                    //Redessine le tableau si besoin en fonction de la taille de la fenêtre
                    if(target=="page-contacts" && (typeof WDGProjectDashboard.table !== 'undefined')){
                        WDGProjectDashboard.table.draw();
                        WDGProjectDashboard.table.columns.adjust();
                    }

                    //Charge les iframe
                    if (target=="page-campaign") {
						console.log("LOAD IFRAMES");
						var iframetoload = $("#ndashboard-content #" +target+ " .google-doc iframe");
						iframetoload.each(function(){
							console.log("- src : " + $(this).data('src'));
							$(this).prop('src',$(this).data('src'));
							$(this).ready(function(){
								$(this).addClass('isloaded');
							});
						});
					}
                });

                var pagesaved = $('#ndashboard-navbar li a[href="'+window.location.hash+'"]')
                if(pagesaved.length >0 && !pagesaved.hasClass("disabled")){
                    pagesaved.trigger("click");
                } else {
                    $("#ndashboard-navbar li a:not(.disabled)").first().trigger("click");
                }
            }

            //Datepickers
            $("input.adddatepicker").datepicker({
                dateFormat: "yy-mm-dd",
                regional: "fr",
                changeMonth: true,
                changeYear: true
            });

            /**
             * DASHBOARD TABS
             */
            //Page Résumé
            if($("#page-resume").length > 0){
                //Passage à l'étape suivante
                if ($("#submit-go-next-status").length > 0) {
                    $("#submit-go-next-status").attr('disabled','');
                    //$("#submit-go-next-status").attr('style','background-color:#333 !important; border: 0px !important; ');

                    checkall = function() {
                        var allcheck = true;
                        $(".checkbox-next-status:visible").each(function(){
                            allcheck = allcheck && this.checked;
                        });
                        return allcheck;
                    };

                    //Actions des cases à cocher
                    $(".checkbox-next-status").change(function() {
                        if(checkall()){
                            $("#submit-go-next-status").removeAttr('disabled');
                            //$("#submit-go-next-status").attr('style','background-color:#EA4F51');
                        } else {
                            $("#submit-go-next-status").attr('disabled','');
                            //$("#submit-go-next-status").attr('style','background-color:#333 !important; border: 0px !important;');
                        };
                    });
                    $(".checkbox-next-status").trigger('change');

                    //Changements du formulaire lorsque l'on veut passer de validé à vote (sans Avant-premiere)
                    $("#no-preview-button").click(function(){
                        $("#cb-go-preview").slideUp();
                        $("#desc-preview").slideUp();
                        $("#vote-checklist").slideDown();
                        $("#no-preview-button").slideUp();
                        $("#next-status-choice").val("2");
                    });
                }

                //Preview date fin collecte sur LB étape suivante
                if(($("#innbdayvote").length > 0)||($("#innbdaycollecte").length > 0)) {

                    updateDate = function(idfieldinput, iddisplay) {
                        $("#"+iddisplay).empty();
                        if($("#"+idfieldinput).val()<=$("#"+idfieldinput).prop("max") && $("#"+idfieldinput).val()>=$("#"+idfieldinput).prop("min")){
                            var d = new Date();
                            var jsupp = $("#"+idfieldinput).val();
                            d.setDate(d.getDate()+parseInt(jsupp));
                            $("#"+iddisplay).prepend(' '+d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear());
                        } else {
                            $("#"+iddisplay).prepend("La durée doit être comprise entre "+($("#"+idfieldinput).prop("min"))+" et "+($("#"+idfieldinput).prop("max"))+" jours");
                        }
                    };

                    updateDate("innbdaycollecte","previewenddatecollecte");
                    updateDate("innbdayvote","previewenddatevote");

                    $("#innbdaycollecte").on( 'keyup change', function () {
                        updateDate("innbdaycollecte","previewenddatecollecte");});

                    $("#innbdayvote").on( 'keyup change', function () {
                        updateDate("innbdayvote","previewenddatevote");});
                }
            }

            //Page Informations
            if($("#page-informations").length > 0){
                //Onglets information
                if ($(".bloc-grid").length > 0) {
                    $(".bloc-grid .display-bloc").click(function () {
                        if($(this).hasClass("active")){
                            /* Replie le bloc actif, désactivé pour l'instant
                            $(".bloc-grid .display-bloc").removeClass("active").animate({
                                top: "0px"
                            }, { duration: 500, queue: false });
                            $("#tab-container .tab-content").slideUp();*/
                        } else {
                            $(".bloc-grid .display-bloc").removeClass("active").animate({
                                top: "0px"
                            }, { duration: 500, queue: false });
                            $("#tab-container .tab-content").slideUp();
                            $(this).addClass("active").animate({
                                top: "20px"
                            }, { duration: 500, queue: false });
                            $("#tab-container #" + $(this).data("tab-target")).slideDown();
                        }
                    });

                    $("#tab-container .tab-content").hide();
                    $(".bloc-grid .display-bloc").first().trigger("click");
                }


                //Infos organisation
                if ($("#tab-organization").length > 0) {
					$("#orgainfo_form_button").hide();//suppression bouton enregistrer
					if($("#new_project_organization").val() !== ""){
						var new_project_organization = $("#new_project_organization option:selected").val();
						$("#edit-orga-button").show();
					}
                    $("#new_project_organization").change(function(e){
                        e.preventDefault();
						$("#orgainfo_form_button").hide();//suppression bouton enregistrer
						if($("#new_project_organization option:selected").val() !== new_project_organization) {
							$("#edit-orga-button").hide();
							$("#orgainfo_form_button").show();//apparition bouton enregistrer
							//Suppression des éléments d'une validation précédente
							if($(".save_ok").length > 0) $(".save_ok").hide();
							if($("#orgainfo_form i.fa.validation").length > 0) $("#orgainfo_form i.fa.validation").remove();
							if($("#new_project_organization").hasClass("validation")) $("#new_project_organization").removeClass("validation");
							if($("#save-mention").is(":hidden")) $("#save-mention").show();
							//
						}else{
							if($("#save-mention").is(":visible")) $("#save-mention").hide();
							$("#edit-orga-button").show();
						}
						$("#wdg-lightbox-editOrga ul.errors li").remove();
                    });
					//Suppression du feedback "enregistré" à l'ouverture de la lightbox
                    $("#orgainfo_form #edit-orga-button").click(function(){
						$("#orgaedit_form").find('.save_ok').fadeOut();
					});

					//Création objet FormData (Envoi des fichiers uploadés en ajax dans le formulaire d'édition)
					$("#wdg-lightbox-editOrga form#orgaedit_form").submit(function(e){
						e.preventDefault();
						var thisForm = $(this);
						var fd = new FormData($('#wdg-lightbox-editOrga #orgaedit_form')[0]);

						//Désactive les champs
                        var save_button = $("#"+$(this).attr("id")+"_button");
                        save_button.find(".button-text").hide();
                        save_button.find(".button-waiting").show();
                        $(":input", this).prop('disabled', true);
                        thisForm.find('.feedback_save span').fadeOut();

						$.ajax({
							'type' : "POST",
							'url' :ajax_object.ajax_url,
							'data': fd,
							'cache': false,
							'contentType': false,
							'processData': false,
						}).done(function(result) {

							if(result === "FALSE"){//user non connecté
								window.location.reload();//affiche message de non permission
							}else{
								var jsonResult = JSON.parse(result);
								feedback = jsonResult;
								//Vérification s'il y a des erreurs sur l'envoi de fichiers
								var fdFileInfo = feedback.files_info;
								var count_errors = 0;
								for (var doc in fdFileInfo){
									if (fdFileInfo[doc]['code'] === 1){//erreur
										count_errors += 1;
										var li = $('<li>'+fdFileInfo[doc]['info']+'</li>');
										$("#wdg-lightbox-editOrga ul.errors").append(li);
										WDGProjectDashboard.scrollTo($("#wdg-lightbox-editOrga ul.errors"));
									}
									WDGProjectDashboard.updateOrgaDoc(fdFileInfo, doc);//mise à jour des liens de téléchargement
								}
								//Affichage confirmation enregistrement
								if (count_errors === 0){
									thisForm.find('.save_ok').fadeIn();
									$("#wdg-lightbox-editOrga").hide();
									$("#tab-organization #wdg-lightbox-valid-editOrga").css('display', 'block');

									//Mise à jour du reste du formulaire d'édition (input type text)
									WDGProjectDashboard.updateOrgaForm(feedback);
								}
							}
						}).fail(function() {
                            thisForm.find('.save_fail').fadeIn();
                        }).always(function() {
                            //Réactive les champs
                            save_button.find(".button-waiting").hide();
                            save_button.find(".button-text").show();
                            thisForm. find(":input").prop('disabled', false);
                        });
					});
					//Vider les champs à l'ouverture de la lightbox de création
					//Suppression du feedback "enregistré" à l'ouverture de la lightbox
					$('#orgainfo_form #btn-new-orga').click(function(){
						$(':input', '#orgacreate_form')
							.not(':hidden')
							.val('')
							.removeAttr('checked')
							.removeAttr('selected');
						$("#orgacreate_form").find('.save_ok').fadeOut();
						$("#wdg-lightbox-newOrga ul.errors").show();
					});

					//fermeture de la lightbox de création d'organisation après enregistrement
                    $("#wdg-lightbox-newOrga form.wdg-forms").submit(function(e){
                        e.preventDefault();
                        var thisForm = $(this);
                        
                        var campaign_id, org_name, org_email, org_description, org_legalform,
                        org_idnumber, org_rcs,org_capital, org_ape, org_address, org_postal_code,
                        org_city, org_nationality, org_bankownername, org_bankowneraddress,
                        org_bankowneriban, org_bankownerbic, org_capable;

                        campaign_id = $('#tab-organization #wdg-lightbox-newOrga input[name=campaign_id]').val();
                        org_name = $('#tab-organization #wdg-lightbox-newOrga input[name=org_name]').val();
                        org_email = $('#tab-organization #wdg-lightbox-newOrga input[name=org_email]').val();
						org_description = $('#tab-organization #wdg-lightbox-newOrga input[name=org_description]').val();
                        org_legalform = $('#tab-organization #wdg-lightbox-newOrga input[name=org_legalform]').val();
                        org_idnumber = $('#tab-organization #wdg-lightbox-newOrga input[name=org_idnumber]').val();
                        org_rcs = $('#tab-organization #wdg-lightbox-newOrga input[name=org_rcs]').val();
                        org_capital = $('#tab-organization #wdg-lightbox-newOrga input[name=org_capital]').val();
                        org_ape = $('#tab-organization #wdg-lightbox-newOrga input[name=org_ape]').val();
                        org_address = $('#tab-organization #wdg-lightbox-newOrga input[name=org_address]').val();
                        org_postal_code = $('#tab-organization #wdg-lightbox-newOrga input[name=org_postal_code]').val();
                        org_city = $('#tab-organization #wdg-lightbox-newOrga input[name=org_city]').val();
                        org_nationality = $('#tab-organization #wdg-lightbox-newOrga #org_nationality option:selected').text();
                        org_bankownername = $('#tab-organization #wdg-lightbox-newOrga input[name=org_bankownername]').val();
                        org_bankowneraddress = $('#tab-organization #wdg-lightbox-newOrga input[name=org_bankowneraddress]').val();
                        org_bankowneriban = $('#tab-organization #wdg-lightbox-newOrga input[name=org_bankowneriban]').val();
                        org_bankownerbic = $('#tab-organization #wdg-lightbox-newOrga input[name=org_bankownerbic]').val();
                        org_capable = $('#tab-organization #wdg-lightbox-newOrga input[name=org_capable]').is(':checked');
                        
                        //Désactive les champs
                        var save_button = $("#"+$(this).attr("id")+"_button");
                        save_button.find(".button-text").hide();
                        save_button.find(".button-waiting").show();
                        $(":input", this).prop('disabled', true);
                        thisForm.find('.feedback_save span').fadeOut();
                        
                        $.ajax({  
                            'type': "POST",
                            'url': ajax_object.ajax_url,
                            'data': {
                                'action': 'save_new_organization',
                                'campaign_id': campaign_id,
                                'org_name': org_name,
                                'org_email': org_email,
								'org_description': org_description,
                                'org_legalform': org_legalform,
                                'org_idnumber': org_idnumber,
                                'org_rcs': org_rcs,
                                'org_capital': org_capital,
                                'org_ape': org_ape,
                                'org_address': org_address,
                                'org_postal_code': org_postal_code,
                                'org_city': org_city,
                                'org_nationality': org_nationality,
                                'org_bankownername': org_bankownername,
                                'org_bankowneraddress': org_bankowneraddress,
                                'org_bankowneriban': org_bankowneriban,
                                'org_bankownerbic': org_bankownerbic,
                                'org_capable': org_capable
                            }
                        }).done(function(result){
							if(result === "FALSE"){//user non connecté
								window.location.reload();//affiche message de non permission
							}else{
								var jsonResult = JSON.parse(result);
								feedback = jsonResult;

								//Vérification s'il y a des erreurs dans le formulaire
								$("#wdg-lightbox-newOrga ul.errors li").remove();//supprime les erreurs éventuellement affichées après un 1er enregistrement
								var errors = feedback.errors;
								var count_errors = 0;
								for (var error in errors){
									if(error !== ""){
										count_errors+=1;
										var li = $('<li>'+errors[error]+'</li>');
										$("#wdg-lightbox-newOrga ul.errors").append(li);
										WDGProjectDashboard.scrollTo($("#wdg-lightbox-newOrga ul.errors"));
									}
								}
								//Affichage confirmation enregistrement
								if(count_errors === 0){
									$("#wdg-lightbox-newOrga ul.errors").hide();//cache les erreurs éventuellement affichées après un 1er enregistrement
									thisForm.find('.save_ok').fadeIn();
									$("#wdg-lightbox-newOrga").hide();
									$("#tab-organization #wdg-lightbox-valid-newOrga").css('display', 'block');
									//Mise à jour de l'input select
									WDGProjectDashboard.updateOrgaSelectInput(feedback);

									//Mise à jour du bouton d'édition
									var newname = $("#new_project_organization").find('option:selected').text();
									var edit_btn = $('#tab-organization #orgainfo_form').find($("#edit-orga-button"));
									edit_btn.attr("href","#");
									edit_btn.text("Editer "+newname);

									//Mise à jour du formulaire d'édition
									WDGProjectDashboard.updateOrgaForm(feedback);
								}
							}
                        }).fail(function() {
                            thisForm.find('.save_fail').fadeIn();
                        }).always(function() {
                            //Réactive les champs
                            save_button.find(".button-waiting").hide();
                            save_button.find(".button-text").show();
                            thisForm. find(":input").prop('disabled', false);

                        });

                    });


                    $("#update_project_organization").change(function(e){
                        var newval = $("#update_project_organization").val();

                        if(newval!=''){
                            $("#edit-orga-button").show();
                            var newname = $("#update_project_organization").find('option:selected').text();
                            $("#edit-orga-button").attr("href",$("#edit-orga-button").data("url-edit")+newval);

                        };

                    });
                }

                //Ajax Infos financement
                if ($("#tab-funding").length > 0) {
                    //Etiquettes de numéros d'années pour le CA prévisionnel
                    $("#new_first_payment").change(function(){
                        var start_year = 1;
                        $("#estimated-turnover li .year").each(function(index){
                            $(this).html((parseInt(start_year)+index));
                        });
                    });
                    
                    //Cases pour le CA prévisionnel
                    $("#new_funding_duration").change(function() {
                        var nb_years_li_existing = ($("#estimated-turnover li").length);
                        var new_nb_years = parseInt($("#new_funding_duration").val());
                        "change nb year trigger "+new_nb_years+"(exist : "+nb_years_li_existing+")";
                        
                        //Ajoute des boîtes au besoin
                        if(new_nb_years > nb_years_li_existing){
                            var newlines = $("#estimated-turnover").html();
							if(new_nb_years <= 20){
								for(var i=0; i<new_nb_years-nb_years_li_existing;i++){
									newlines = newlines+
										'<li class="field">' +
										'<label>Année&nbsp;<span class="year">'+(i+1+nb_years_li_existing)+'</span></label>'+
										'<span class="field-container">'+
										'&nbsp;<span class="field-value" data-type="number" data-id="new_estimated_turnover_'+(i+nb_years_li_existing)+'">'+
										'<i class="right fa fa-eur" aria-hidden="true"></i>'+
										'<input type="number" value="0" id="new_estimated_turnover_'+(i+nb_years_li_existing)+'" class="right-icon" />'+                                   
										'</span>'+
										'<span class="like-input-center"><p id="roi-amount-'+(i+nb_years_li_existing)+'">0 €</p></span>'+
										'</span>'+
										'</li>';
								}
							}

                            $("#estimated-turnover").html(newlines);
                                                      
                            //MAJ des étiquettes "Année XXXX"
                            $("#new_first_payment").trigger("change");
                            nb_years_li_existing = new_nb_years;
                        } else {
                            //N'affiche que les boites nécessaires
                            $("#estimated-turnover li").hide();
                            $("#estimated-turnover li").slice(0,new_nb_years).show();
                        }
                        nb_years_li_existing = Math.max(new_nb_years,nb_years_li_existing);
                        //Calculs de tous les élements et rattachement du keyup/click sur changement de CA
                        WDGProjectDashboard.calculAndShowResult();                       
                    });
                    $("#new_funding_duration").trigger('change');
                    $("#new_funding_duration").keyup(function(){
                        if($("#new_funding_duration").val()!==""){
                                 $("#new_funding_duration").trigger('change');
                        }
                    });

                    //A l'ouverture de l'onglet besoin de financement : calculs de tous les élements
                    WDGProjectDashboard.calculAndShowResult();
                    //Rattachement des events sur modif du CA
                    WDGProjectDashboard.attachEventOnCa();
                    
                    //Recalcul du rendement si modification de l'objectif max / % royalties / durée financement
                    $("#new_maximum_goal, #new_roi_percent_estimated, #new_funding_duration").bind('keyup click', function(){
                        //Rattachement des events sur modif du CA
                        WDGProjectDashboard.attachEventOnCa();
                        
                        if($("#new_maximum_goal").val()!=="" && ($("#new_minimum_goal").val()!=="" && $("#new_funding_duration").val()!==""
                            && $("#new_roi_percent_estimated").val()!=="" )){
                            WDGProjectDashboard.calculAndShowResult();
                        }
                        else{
                            WDGProjectDashboard.initResultCalcul();
                        }
                    });
                }
            }

            //Page campagne
            if($("#page-campaign").length > 0){
                //Gestion equipe
                $(".project-manage-team").click(function(){
                    var action, data
                    action = $(this).attr('data-action');
                    if(action==="yproject-add-member"){
                        data=($("#new_team_member_string")[0].value);
                    }
                    else if (action==="yproject-remove-member"){
                        data=$(this).attr('data-user');
                    }
                    WDGProjectDashboard.manageTeam(action, data, campaign_id);
                });

                //Formulaire
                //Infos organisation
                if ($("#campaign_form").length > 0) {
                    $("#update_project_organization").change(function(e){
                        var newval = $("#update_project_organization").val();

                        if(newval!=''){
                            $("#edit-orga-button").show();
                            var newname = $("#update_project_organization").find('option:selected').text();
                            $("#edit-orga-button").attr("href",$("#edit-orga-button").data("url-edit")+newval);

                            $("#edit-orga-button").text("Editer "+newname);
                        } else {
                            $("#edit-orga-button").hide();
                        }

                    });
                }
            }

            //Page contacts
            if($("#page-contacts").length > 0){

                var mail_content, mail_title, originalText;
                $("#direct-mail #mail-preview-button").click(function () {
                    mail_content = tinyMCE.get('mail_content').getContent();
                    mail_title = $("#direct-mail #mail-title").val();

                    if (mail_title == ""){
                        WDGProjectDashboard.fieldError($("#direct-mail #mail-title"),"L'objet du mail ne peut être vide");
                    } else {
                        WDGProjectDashboard.removeFieldError($("#direct-mail #mail-title"));
                        originalText = $(this).html();
                        $(this).html('<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');

                        $.ajax({
                            'type' : "POST",
                            'url' : ajax_object.ajax_url,
                            'data': {
                                'action':'preview_mail_message',
                                'id_campaign':campaign_id,
                                'mail_content' : mail_content,
                                'mail_title' : mail_title
                            }
                        }).done(function(result){
                            var res = JSON.parse(result);

                            $("#direct-mail .preview-title").html('<i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;'+res.content.title);
                            $("#direct-mail .preview").html(res.content.body);
                            $("#direct-mail .step-write").slideUp();
                            $("#direct-mail .step-confirm").slideDown();
                            $("#direct-mail #mail-preview-button").html(originalText);
                        })
                    }
                });

                $("#direct-mail #mail-back-button").click(function () {
                    $("#direct-mail .step-confirm").slideUp();
                    $("#direct-mail .step-write").slideDown();
                });
            }


            //Fonction globale d'update d'informations
           $("#ndashboard form.db-form").submit(function(e){
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
                            data_to_update[id] = $(this).find("input:eq(0)").val()+"\ "
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
                           WDGProjectDashboard.fieldError(thisForm.find('#'+input), feedback.errors[input])
                       }

                       for(var input in feedback.success){
                           var thisinput = thisForm.find('#'+input)
                           WDGProjectDashboard.removeFieldError(thisinput);
                           thisinput.closest(".field-value").parent().find('i.fa.validation').remove();
                           thisinput.addClass("validation");
                           thisinput.closest(".field-value").after('<i class="fa fa-check validation" aria-hidden="true"></i>');
                       }

                       //Scrolle jusqu'à la 1ère erreur et la sélectionne
                       var firsterror = thisForm.find(".error").first();
                       if(firsterror.length == 1){
                           WDGProjectDashboard.scrollTo(firsterror);
                           //La sélection (ci-dessous) Ne fonctione ne marche pas
                           firsterror.focus();
                           thisForm.find('.save_errors').fadeIn();
                       } else {
                           thisForm.find('.save_ok').fadeIn();                          
                       }

                        // Enregistrer l'organisation liée au projet dans tab-organization
                        if ($("#tab-organization").is(":visible") && ($("#ndashboard #orgainfo_form.db-form").data("action")) == "save_project_organization"){
                            //Afficher le bouton d'édition de l'organisation après enregistrement de la liaison
                            WDGProjectDashboard.updateEditOrgaBtn(thisForm);
                            //Mise à jour du formulaire d'édition après enregistrement de la liaison
                            WDGProjectDashboard.updateOrgaForm(feedback);
							//Mise à jour des liens de téléchargement des docs du formulaire d'édition
							WDGProjectDashboard.updateOrgaFormDoc(feedback);
							$("#save-mention").hide();
							$("#orgainfo_form_button").hide();
							new_project_organization = $("#new_project_organization option:selected").val();
                        }
                   }
               }).fail(function() {
                   thisForm.find('.save_fail').fadeIn();
               }).always(function() {
                   //Réactive les champs
                   save_button.find(".button-waiting").hide();
                   save_button.find(".button-text").show();
                   thisForm. find(":input").prop('disabled', false);
               });;
            });
        },

        scrollTo: function(target){
            $('html, body, .wdg-lightbox-padder').animate(
                { scrollTop: target.offset().top - 75 },
                "slow"
            );
        },

        fieldError: function($param, errorText){
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
        },

        removeFieldError: function($param){
            if($param.hasClass("error")){
                $param.removeClass("error");
                $param.qtip().destroy();
            }
        },

        manageTeam: function(action, data, campaign_id){
            //Clic pour ajouter un membre
            if(action==="yproject-add-member"){
                //Test si le champ de texte est vide
                if (data===""){
                    //Champ vide, ne rien faire
                } else {
                    //Bloque le champ de texte d'ajout
                    $("#new_team_member_string").prop('disabled',true);
                    $("#new_team_member_string").val('');
                    tmpPlaceHolder = $("#new_team_member_string").prop('placeholder');
                    $("#new_team_member_string").prop('placeholder',"Ajout de "+data+"...");
                    $("#new_team_member_string").next().hide();

                    //Lance la requête Ajax
                    $.ajax({
                        'type' : "POST",
                        'url' : ajax_object.ajax_url,
                        'data': {
                            'action':'add_team_member',
                            'id_campaign':campaign_id,
                            'new_team_member' : data
                        }
                    }).done(function(result){
                        //Nettoie le champ de texte d'ajout
                        $("#new_team_member_string").prop('disabled', false);
                        $("#new_team_member_string").prop('placeholder',tmpPlaceHolder);
                        $("#new_team_member_string").next().show();

                        if(result==="FALSE"){
                            $("#new_team_member_string").next().next().after("<div id=\"fail_add_team_indicator\"><br/><em>L'utilisateur "+data+" n'a pas été trouvé</em><div>");
                            $("#fail_add_team_indicator").delay(4000).fadeOut(400);
                        } else {
                            res = JSON.parse(result);

                            //Teste si l'user existait déjà
                            doublon = false;
                            $(".project-manage-team").each(function(){
                                doublon = doublon || (res.id == $(this).attr('data-user'));
                            });

                            if(!doublon){
                                if($("#team-list li").length==0){
                                    $("#team-list").html("");
                                }
                                newline ='<li style="display: none;">';
                                newline+=res.firstName+" "+res.lastName+" ("+res.userLink+") ";
                                newline+='<a class="project-manage-team button" data-action="yproject-remove-member" data-user="'+res.id+'"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>';
                                newline+="</li>";
                                $("#team-list").append(newline);
                                $("a[data-user="+res.id+"]").closest("li").slideDown();

                                //Recharge l'UI pour ajouter listener au nouveau button
                                $(".project-manage-team").click(function(){
                                    action = $(this).attr('data-action');
                                    if(action==="yproject-add-member"){
                                        data=($("#new_team_member_string")[0].value);
                                    }
                                    else if (action==="yproject-remove-member"){
                                        data=$(this).attr('data-user');
                                    }
                                    WDGProjectDashboard.manageTeam(action, data, campaign_id);
                                });
                            }
                        }
                    });
                }
            }

            //Clic pour supprimer un membre
            else if(action==="yproject-remove-member") {
                //Affichage en attente de suppression
                $("a[data-user="+data+"]").closest("li").css("opacity",0.5);
                $("a[data-user="+data+"]").html('<i class="fa fa-spinner fa-spin fa-fw"></i>');

                $.ajax({
                    'type' : "POST",
                    'url' : ajax_object.ajax_url,
                    'data': {
                        'action':'remove_team_member',
                        'id_campaign':campaign_id,
                        'user_to_remove' : data
                    }
                }).done(function(result){
                    $("a[data-user="+data+"]").closest("li").slideUp("slow",function(){ $(this).remove();});
                });
            }
        },

        getContactsTable: function(inv_data, campaign_id) {
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
                WDGProjectDashboard.table = $('#contacts-table').DataTable({
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
                                WDGProjectDashboard.table.rows( { search: 'applied' } ).select();
                            }
                        },{
                            //Bouton envoi de mail
                            extend: 'selected',
                            text: '<i class="fa fa-envelope" aria-hidden="true"></i> Envoyer un mail',
                            action: function ( e, dt, button, config ) {
                                $("#send-mail-tab").slideDown();
                                var target = $(this).data("target");
                                WDGProjectDashboard.scrollTo($("#send-mail-tab"));
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
                WDGProjectDashboard.table.columns.adjust();

                var mailButtonDefault = WDGProjectDashboard.table.button(1).text()
                WDGProjectDashboard.table.on("select.dt deselect.dt", function ( e, dt, type, indexes ) {
                    //Maj Bouton de Mail
                    var selectedCount = WDGProjectDashboard.table.rows({ selected: true }).count();
                    if(selectedCount==0){
                        WDGProjectDashboard.table.button(1).text(mailButtonDefault);
                        $("#send-mail-tab").slideUp();
                    } else {
                        WDGProjectDashboard.table.button(1).text(mailButtonDefault+" ("+selectedCount+")");
                    }


                    //Maj Bouton de sélection
                    var allContained = true;
                    WDGProjectDashboard.table.rows( { search:'applied' } ).every( function ( rowIdx, tableLoop, rowLoop ) {
                        if($.inArray(rowIdx, WDGProjectDashboard.table.rows({ selected: true }).indexes())==-1){
                            allContained= false;
                        }
                    } );

                    if(allContained){
                        WDGProjectDashboard.table.button(0).text('<i class="fa fa-check-square-o" aria-hidden="true"></i> Déselectionner les éléments affichés');
                        WDGProjectDashboard.table.button(0).action(function () {
                            WDGProjectDashboard.table.rows( { search: 'applied' } ).deselect();
                        });
                    } else {
                        WDGProjectDashboard.table.button(0).text('<i class="fa fa-square-o" aria-hidden="true"></i> Sélectionner les éléments affichés');
                        WDGProjectDashboard.table.button(0).action(function () {
                            WDGProjectDashboard.table.rows( { search: 'applied' } ).select();
                        });
                    }

                    //Maj Champs de Mail
                    $("#nb-mailed-contacts").text(selectedCount);

                    //Maj liste des identifiants à mailer
                    var recipients_array = [];
                    $.each(WDGProjectDashboard.table.rows({ selected: true }).data(), function(index, element){
                        recipients_array.push(element[result_contacts_table['id_column_index']]);
                    });
                    $("#mail_recipients").val(recipients_array);
                } );

                // Champs de filtrage
                $( WDGProjectDashboard.table.table().container() ).on( 'keyup', 'tfoot .text input', function () {
                    WDGProjectDashboard.table
                        .column( $(this).data('index') )
                        .search( this.value )
                        .draw();
                } );
                $( WDGProjectDashboard.table.table().container() ).on( 'change', 'tfoot .check input', function () {
                    if($(this).is(":checked")){
                        WDGProjectDashboard.table
                            .column( $(this).data('index') )
                            .search("1")
                            .draw();
                    }
                    else {
                        WDGProjectDashboard.table
                            .column( $(this).data('index') )
                            .search("")
                            .draw();
                    }
                } );


            }).fail(function(){
                $('#ajax-contacts-load').after("<em>Le chargement du tableau a échoué</em>");
                $('#ajax-loader-img').hide();//On cache la roue de chargement.
            });
        },

        /**
         * Récupération de tous les CA en fonction de la durée de financement
         * @returns {Array} tableau avec le montant des CA pour chaque année
         */
        createCaTab: function(){           
            if(new_funding_duration!== "0"){
                var nbYears = parseInt(new_funding_duration);
                var caTab = new Array;
                for (var ii=0; ii < nbYears; ii++){
                    var new_estimated_turnover = ($("#new_estimated_turnover_"+ii).val() == null) ? ($.trim($("span[data-id=new_estimated_turnover_"+ii+"]").text())) : $("#new_estimated_turnover_"+ii).val();
                    caTab.push(parseFloat(new_estimated_turnover));
                }
                return caTab;
            }
        },
        /**
         * Calcul du CA total sur les années de CA renseignées
         */
        calculTotalCa: function(){
            caTab = WDGProjectDashboard.createCaTab();
            totalca = 0; 
            //boucler sur le nb d'années renseignées pour le cas où celui-ci diminue
            //(ce qui cache les années en moins) sans pour autant perdre les données
            //on peut rajouter une année et retrouver le montant de CA précédemment
            //rempli (tant qu'on n'a pas enregistré)
            
            $.each(caTab, function(year, ca){
                if(ca >= 0){
                    totalca += ca;
                }
            });
        },
        /**
         * Calcul des royalties reversées par année de CA renseignée
         */
        calculRoiPerYear: function(){
            if(new_roi_percent_estimated !== ""){
                percent = parseFloat(new_roi_percent_estimated)/100;
            }
            else{ percent = false; }
            for (var ii=0; ii < caTab.length; ii++ ) {
                if (caTab[ii] > 0) {
                    if(percent){
                        var roi_amount = percent * caTab[ii];
                        var roi_amount_format = WDGProjectDashboard.numberFormat(roi_amount);
                        $("#roi-amount-"+ii).html(roi_amount_format+" €");
                    }else{ $("#roi-amount-"+ii).html("0 €"); }
                }else{ $("#roi-amount-"+ii).html("0 €"); }
            }
        },
        /**
         * Calcul du total de royalties reversées sur le nombre d'années de CA
         * renseigné
         */
        calculReturn: function(){
            if(percent){
                totalRoi = percent * totalca;
                if(totalRoi){
                    var totalRoi_format = WDGProjectDashboard.numberFormat(totalRoi);
                    $("#total-roi").html(totalRoi_format+" €");
                }
            }
	},
        /**
         * Calcul du montant total de la collecte incluant la commission WDG
         * need = montant max incluant la commission de WDG
         */
        calculCollect: function (){
            if (need!==""){
                collect = need;
                var collect_format = WDGProjectDashboard.numberFormat(collect);
                $("#total-funding").html(collect_format);
            }
        },
        /**
         * Fonction de calcul du rendement annuel investisseur
         */
        calculAnnualRend: function(){
            var nbYears = 0;
            for (var ii=0; ii < caTab.length; ii++ ) {
                if (caTab[ii] > 0) {
                    nbYears++;
                }
            }
            // Ecarter les dénominateurs à zéro
            mediumRend = null;
            if(collect != "0" && nbYears != 0){
                mediumRend = (Math.pow((percent*totalca/collect),(1/nbYears))-1)*100;
                var mediumRend_format = WDGProjectDashboard.numberFormat(mediumRend);
                $("#medium-rend").html(mediumRend_format+' %');
            }
            else if(collect == "0" || nbYears == 0){
                WDGProjectDashboard.initResultCalcul();
            }
        },
        /**
         * Vérification d'un rendement minimum supérieur à 3%
         */
        verifMediumRend: function () {
            var rend = $("#medium-rend");
            var errorHtml = "<br/>(insuffisant en dessous de 3%, étant donné le risque)";
            if (mediumRend < "3" ) {
                rend.css('color', '#EA4F51').css('display','inline-block').css('margin', 0);
                rend.append(errorHtml);
            }
            else{
                rend.css('color','#2B2C2C');
            }
        },
		/**
		 * Calcul le gain (montant ROI reçues/montant maximum de la collecte)
		 */
		calculGain: function(){
			if(need != "" && need != "0" && new_roi_percent_estimated != "" && new_roi_percent_estimated != "0"){
				var gain = totalRoi/need;
				var gain_format = WDGProjectDashboard.numberFormat(gain);
				var gain_text = "x"+gain_format+" en "+nb_years+" ans";
				$("#total-roi-container #gain").html(gain_text);
			}
			else{
				$("#total-roi-container #gain").html("");
			}
		},
        /**
         * Processus de calcul des investissements et du rendement investisseur
         * et mise à jour des résultats dans l'interface
         */
        simuProcess: function(){
            WDGProjectDashboard.calculTotalCa();
            WDGProjectDashboard.calculRoiPerYear();
            WDGProjectDashboard.calculReturn();
            WDGProjectDashboard.calculCollect();
            WDGProjectDashboard.calculAnnualRend();
            WDGProjectDashboard.verifMediumRend();
			WDGProjectDashboard.calculGain();
        },
        /**
         * Initialisation de l'affichage lorsque les calculs ne peuvent être 
         * réalisés par manque de données
         */
        initResultCalcul: function(){
            $("#total-roi").html("0 €");
            $("#total-funding").html("---");
            $("#medium-rend").html("--- %").css('color','#2B2C2C');
            $("#total-roi-container #gain").html("");
            caTab = WDGProjectDashboard.createCaTab();
            for (var ii=0; ii < caTab.length; ii++ ) {
                $("#roi-amount-"+ii).html("0 €");
            }
        },
        /**
         * Calcul des royalties reversés par année et du rendement (si données déjà renseignées)
         * et rattachement des events sur la modif du CA
         */
        calculAndShowResult: function(){
            WDGProjectDashboard.getDataCalculator();
            if(new_minimum_goal!="" && need!="" && new_funding_duration!="" && new_roi_percent_estimated!="" && new_estimated_turnover_0!=""){
                WDGProjectDashboard.simuProcess();
            }
            else{
                WDGProjectDashboard.initResultCalcul();
            }
        },

        /**
         * Récupère les données renseignées dans l'onglet "besoin de financement"
         * selon qu'elles sont encore modifiables (input) ou non (span)
         */
        getDataCalculator: function(){           
            new_minimum_goal = $("#new_minimum_goal").val() == null ? $.trim($("span[data-id=new_minimum_goal] span").text()) : $("#new_minimum_goal").val();
            need = $("#new_maximum_goal").val() == null ? $.trim($("span[data-id=new_maximum_goal] span").text()) : $("#new_maximum_goal").val();
            new_roi_percent_estimated = $("#new_roi_percent_estimated").val() == null ? $.trim($("span[data-id=new_roi_percent_estimated] span").text()) : $("#new_roi_percent_estimated").val();
            new_funding_duration = ($("#new_funding_duration").val() == null) ? $.trim($("span[data-id=new_funding_duration] span").text()) : $("#new_funding_duration").val();
            new_estimated_turnover_0 = $("#new_estimated_turnover_0").val() == null ? $.trim($("span[data-id=new_estimated_turnover_0]").text()) : $("#new_estimated_turnover_0").val();
			nb_years = $("#new_funding_duration").val() == null ? $.trim($("span[data-id=new_funding_duration]").text()) : $("#new_funding_duration").val();
        },
        /**
         * Attache les events click et keyup sur les inputs des CA de chaque année
         * et déclenche les calculs
         */
        attachEventOnCa: function(){
            for(var ii = 0; ii < parseInt(new_funding_duration); ii++){
                var new_estimated_turnover = ($("#new_estimated_turnover_"+ii).val() == null) ? $("span[data-id=new_estimated_turnover_"+ii+"]") : $("#new_estimated_turnover_"+ii);
                $(new_estimated_turnover).bind('click keyup',function(){
                    WDGProjectDashboard.calculAndShowResult();
                });
            }
        },
        
        /**
         * Formate les nombres pour afficher au maximum 2 décimales
         * @param {number} number
         * @returns {string}
         */
		numberFormat: function(number){
			var nb_format = number.toString().replace(",",".");
			nb_format = parseFloat(nb_format);
			nb_format = nb_format.toFixed(2);
			nb_format = nb_format.toString().replace(".",",");
			//Suppression des "00" après la virgule
			if(nb_format.substr(nb_format.length - 2, nb_format.length) === "00"){
				nb_format = nb_format.substr(0, nb_format.length - 3);
			}
			return nb_format;
		},

		/* Fonction de mise à jour du bouton d'édition d'une organisation
         * une fois l'organisation sélectionnée et enregistrée
         * @param {type} form : formulaire de saisie
         */
        updateEditOrgaBtn: function(form){
            var newval = $("#new_project_organization").val();
            if(newval!== ''){
                var edit_btn = form.find($("#edit-orga-button")).show();

                var newname = $("#new_project_organization").find('option:selected').text();
                edit_btn.attr("href","#");
                edit_btn.text("Editer "+newname);
            } else {
                edit_btn.hide();
            }
        },

        /**
         * Fonction de mise à jour du formulaire d'édition d'une organisation
         * une fois l'organisation sélectionnée et enregistrée
         * @param {objet} feedback : retour ajax
         */
        updateOrgaForm: function(feedback){
            $("#tab-organization #wdg-lightbox-editOrga #org_name").html(feedback.organization.name);
            $("#tab-organization #wdg-lightbox-editOrga input[name=org_email]").val(feedback.organization.email);
            $("#tab-organization #wdg-lightbox-editOrga input[name=org_description]").val(feedback.organization.description);
            $("#tab-organization #wdg-lightbox-editOrga input[name=org_legalform]").val(feedback.organization.legalForm);
            $("#tab-organization #wdg-lightbox-editOrga input[name=org_idnumber]").val(feedback.organization.idNumber);
            $("#tab-organization #wdg-lightbox-editOrga input[name=org_rcs]").val(feedback.organization.rcs);
            $("#tab-organization #wdg-lightbox-editOrga input[name=org_capital]").val(feedback.organization.capital);
            $("#tab-organization #wdg-lightbox-editOrga input[name=org_ape]").val(feedback.organization.ape);
            $("#tab-organization #wdg-lightbox-editOrga input[name=org_address]").val(feedback.organization.address);
            $("#tab-organization #wdg-lightbox-editOrga input[name=org_postal_code]").val(feedback.organization.postal_code);
            $("#tab-organization #wdg-lightbox-editOrga input[name=org_city]").val(feedback.organization.city);
            $("#tab-organization #wdg-lightbox-editOrga input[name=org_nationality]").val(feedback.organization.nationality);
            $("#tab-organization #wdg-lightbox-editOrga input[name=org_bankownername]").val(feedback.organization.bankownername);
            $("#tab-organization #wdg-lightbox-editOrga input[name=org_bankowneraddress]").val(feedback.organization.bankowneraddress);
            $("#tab-organization #wdg-lightbox-editOrga input[name=org_bankowneriban]").val(feedback.organization.bankowneriban);
            $("#tab-organization #wdg-lightbox-editOrga input[name=org_bankownerbic]").val(feedback.organization.bankownerbic);
        },
		
		/**
		 * Fonction de mise à jour des liens de téléchargement des documents
		 * uploadés de l'organisation après l'action save_project_organisation
		 * @param {object} feedback : infos renvoyées par l'action php
		 */
		updateOrgaFormDoc: function(feedback){
			if(feedback.organization.doc_bank.path != null){
				if($("#tab-organization #wdg-lightbox-editOrga a#org_doc_bank").length === 0){
					var link_bank = $('<a id="org_doc_bank" class="button blue-pale download-file" target="_blank" href="'+feedback.organization.doc_bank.path+'">'+feedback.organization.doc_bank.date_uploaded+'</a><br />');
					link_bank.insertBefore($("#tab-organization #wdg-lightbox-editOrga input[name=org_doc_bank]"));
				}
				else{
					$("#tab-organization #wdg-lightbox-editOrga a#org_doc_bank").attr("href", feedback.organization.doc_bank.path);
					$("#tab-organization #wdg-lightbox-editOrga a#org_doc_bank").html(feedback.organization.doc_bank.date_uploaded);
				}
			}
			else {
				$("#tab-organization #wdg-lightbox-editOrga a#org_doc_bank").remove();
			}

			if(feedback.organization.doc_kbis.path != null){
				if($("#tab-organization #wdg-lightbox-editOrga a#org_doc_kbis").length === 0){
					var link_kbis = $('<a id="org_doc_kbis" class="button blue-pale download-file" target="_blank" href="'+feedback.organization.doc_kbis.path+'">'+feedback.organization.doc_kbis.date_uploaded+'</a><br />');
					link_kbis.insertBefore($("#tab-organization #wdg-lightbox-editOrga input[name=org_doc_kbis]"));
				}
				else{
					$("#tab-organization #wdg-lightbox-editOrga a#org_doc_kbis").attr("href", feedback.organization.doc_kbis.path);
					$("#tab-organization #wdg-lightbox-editOrga a#org_doc_kbis").html(feedback.organization.doc_kbis.date_uploaded);
				}
			}
			else {
				$("#tab-organization #wdg-lightbox-editOrga a#org_doc_kbis").remove();
			}

			if(feedback.organization.doc_status.path != null){
				if($("#tab-organization #wdg-lightbox-editOrga a#org_doc_status").length === 0){
					var link_status = $('<a id="org_doc_status" class="button blue-pale download-file" target="_blank" href="'+feedback.organization.doc_status.path+'">'+feedback.organization.doc_status.date_uploaded+'</a><br />');
					link_status.insertBefore($("#tab-organization #wdg-lightbox-editOrga input[name=org_doc_status]"));
				}
				else{
					$("#tab-organization #wdg-lightbox-editOrga a#org_doc_status").attr("href", feedback.organization.doc_status.path);
					$("#tab-organization #wdg-lightbox-editOrga a#org_doc_status").html(feedback.organization.doc_status.date_uploaded);
				}
			}
			else {
				$("#tab-organization #wdg-lightbox-editOrga a#org_doc_status").remove();
			}

			if(feedback.organization.doc_id.path != null){
				if($("#tab-organization #wdg-lightbox-editOrga a#org_doc_id").length === 0){
					var link_id = $('<a id="org_doc_id" class="button blue-pale download-file" target="_blank" href="'+feedback.organization.doc_id.path+'">'+feedback.organization.doc_id.date_uploaded+'</a><br />');
					link_id.insertBefore($("#tab-organization #wdg-lightbox-editOrga input[name=org_doc_id]"));
				}
				else{
					$("#tab-organization #wdg-lightbox-editOrga a#org_doc_id").attr("href", feedback.organization.doc_id.path);
					$("#tab-organization #wdg-lightbox-editOrga a#org_doc_id").html(feedback.organization.doc_id.date_uploaded);
				}
			}
			else {
				$("#tab-organization #wdg-lightbox-editOrga a#org_doc_id").remove();
			}

			if(feedback.organization.doc_home.path != null){
				if($("#tab-organization #wdg-lightbox-editOrga a#org_doc_home").length === 0){
					var link_home = $('<a id="org_doc_home" class="button blue-pale download-file" target="_blank" href="'+feedback.organization.doc_home.path+'">'+feedback.organization.doc_home.date_uploaded+'</a><br />');
					link_home.insertBefore($("#tab-organization #wdg-lightbox-editOrga input[name=org_doc_home]"));
				}
				else{
					$("#tab-organization #wdg-lightbox-editOrga a#org_doc_home").attr("href", feedback.organization.doc_home.path);
					$("#tab-organization #wdg-lightbox-editOrga a#org_doc_home").html(feedback.organization.doc_home.date_uploaded);
				}
			}
			else {
				$("#tab-organization #wdg-lightbox-editOrga a#org_doc_home").remove();
			}
		},

		/**
		 * Fonction de mise à jour du lien de téléchargement du fichier uploadé
		 * @param {array} fileInfo : tableau des infos sur tous les fichiers uploadés
		 * @param {String} document : nom du document uploadé
		 */
		updateOrgaDoc: function(fileInfo, document){
			if(fileInfo[document]['info'] !== null) { //il y a un fichier à uploader
				if($("#tab-organization #wdg-lightbox-editOrga a#"+document).length === 0){
					var link = $('<a id="'+document+'" class="button blue-pale download-file" target="_blank" href="'+fileInfo[document]['info']+'">'+fileInfo[document]['date']+'</a><br />');
					link.insertBefore($("#tab-organization #wdg-lightbox-editOrga input[name="+document+"]"));
				}
				else{
					$("#tab-organization #wdg-lightbox-editOrga a#"+document).attr("href", fileInfo[document]['info']);
					$("#tab-organization #wdg-lightbox-editOrga a#"+document).html(fileInfo[document]['date']);
				}
			}
		},

        /**
         * Fonction de mise à jour du select pour le choix de l'organisation
         * après la création d'une organisation depuis le tableau de bord
         * @param {objet} feedback : retour ajax
         */
        updateOrgaSelectInput: function(feedback){
            var orgaName = feedback.organization.name;
            var orgaWpref = feedback.organization.wpref;

			$("#tab-organization #orgainfo_form #new_project_organization").append(new Option(orgaName, orgaWpref));
			$("#tab-organization #orgainfo_form #new_project_organization option:selected").removeAttr('selected');
			$("#tab-organization #orgainfo_form #new_project_organization option[value="+orgaWpref+"]").attr("selected", "selected");
        },

    };

})(jQuery);