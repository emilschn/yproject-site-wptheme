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

                    //Change le contenu de la page
                    var target = $(this).attr("href");

                    //Cache les infobulles ouvertes
                    $(".qtip").hide()

                    $("#ndashboard-content .page-dashboard").hide();
                    $("#ndashboard-content " + target).show();

                    //Redessine le tableau si besoin en fonction de la taille de la fenêtre
                    if(target=="#page-contacts" && (typeof WDGProjectDashboard.table !== 'undefined')){
                        WDGProjectDashboard.table.draw();
                        WDGProjectDashboard.table.columns.adjust();
                    }

                    //Charge les iframe
                    var iframetoload = $("#ndashboard-content " + target+" .google-doc iframe");
                    iframetoload.each(function(){
                        $(this).prop('src',$(this).data('src'));
                        $(this).ready(function(){
                            $(this).addClass('isloaded');
                        });
                    });

                    history.pushState(null, null, target);
                    return false; //Empêche le défilement automatique lorsqu'on clique sur un lien avec un #
                });

                var hash = window.location.hash
                var tabsaved = $('#ndashboard-navbar li a[href="'+window.location.hash+'"]')
                if(tabsaved.length >0 && !tabsaved.hasClass("disabled")){
                    tabsaved.trigger("click");
                } else {
                    $("#ndashboard-navbar li a:not(.disabled)").first().trigger("click");
                }
                window.scrollTo(0, 0);
            }

            //Infobulles
            WDGProjectDashboard.initQtip();

            //Datepickers
            $("input.adddatepicker").datepicker({
                dateFormat: "yy-mm-dd",
                regional: "fr",
                changeMonth: true,
                changeYear: true
            });

            //Fonction d'envoi de mise à jour d'informations
            var update_tab = function(data_to_update, form_id, form_button_id, form_loading_id, form_errors_id){
                $("#"+form_button_id).hide();
                $("#"+form_loading_id).show();
                $("#"+form_id+" input, #"+form_id+" select").prop('disabled', true);

                data_to_update.campaign_id= campaign_id;
                $.ajax({
                    'type': "POST",
                    'url': ajax_object.ajax_url,
                    'data': data_to_update
                }).done(function (result) {
                    if (result != "") {
                        var jsonResult = JSON.parse(result);
                        response = jsonResult.response;
                        $("#"+form_button_id).show();
                        $("#"+form_loading_id).hide();
                        $("#"+form_id+" input, #"+form_id+" select").prop('disabled', false);
                        $("#"+form_errors_id).empty();
                        for (var i = 0; i < jsonResult.errors.length; i++) {
                            $("#"+form_errors_id).append("<li>" + jsonResult.errors[i] + "</li>");
                        }
                    }
                });
            };

            /**
             * DASHBOARD TABS
             */
            //Page Résumé
            if($("#page-resume").length > 0){
                //Ajax infos d'étape
                $("#statusmanage_form").submit(function (e) {
                    e.preventDefault();
                    var data_to_update = {
                        'action': 'save_project_status',
                        'can_go_next': $("#update_can_go_next_status").is(':checked'),
                        'campaign_status': $("#update_campaign_status").val()
                    }
                    update_tab(data_to_update, "statusmanage_form", "statusmanage-form_button", "statusmanage-form_loading", "statusmanage-form_errors")
                });

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
                            $(".bloc-grid .display-bloc").removeClass("active").animate({
                                top: "0px"
                            }, { duration: 500, queue: false });
                            $("#tab-container .tab-content").slideUp();

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
                }

                //Ajax Infos personnelles
                if ($("#tab-user-infos").length > 0) {
                    $("#userinfo_form").submit(function (e) {
                        e.preventDefault();
                        var birthday = new Date($("#update_birthday").val());

                        var data_to_update = {
                            'action': 'save_user_infos',
                            'invest_type': $("#invest_type").val(),
                            'gender': $("#update_gender").val(),
                            'firstname': $("#update_firstname").val(),
                            'lastname': $("#update_lastname").val(),
                            'birthday_day': birthday.getDate(),
                            'birthday_month': birthday.getMonth()+1,
                            'birthday_year': birthday.getFullYear(),
                            'birthplace': $("#update_birthplace").val(),
                            'nationality': $("#update_nationality").val(),
                            'address': $("#update_address").val(),
                            'postal_code': $("#update_postal_code").val(),
                            'city': $("#update_city").val(),
                            'country': $("#update_country").val(),
                            'telephone': $("#update_mobile_phone").val(),
                            'is_project_holder': $("#input_is_project_holder").val(),
                        }
                        update_tab(data_to_update, "userinfo_form", "userinfo_form_button", "userinfo_form_loading", "userinfo_form_errors")
                    });
                }

                //Ajax Infos organisation
                if ($("#tab-organization").length > 0) {
                    $("#orgainfo_form").submit(function (e) {
                        e.preventDefault();
                        var data_to_update = {
                            'action': 'save_project_organisation',
                            'project-organisation': $("#update_project_organisation").val()
                        }
                        update_tab(data_to_update, "orgainfo_form", "orgainfo_form_button", "orgainfo_form_loading", "orgainfo_form_errors");

                    });

                    $("#update_project_organisation").change(function(e){
                        var newval = $("#update_project_organisation").val();

                        if(newval!=''){
                            $("#edit-orga-button").show();
                            var newname = $("#update_project_organisation").find('option:selected').text();
                            $("#edit-orga-button").attr("href",$("#edit-orga-button").data("url-edit")+newval);

                            $("#edit-orga-button").text("Editer "+newname);
                        } else {
                            $("#edit-orga-button").hide();
                        }

                    });
                }

                //Ajax Infos projet
                if ($("#tab-project").length > 0) {
                    $("#projectinfo_form").submit(function (e) {
                        e.preventDefault();
                        var data_to_update = {
                            'action': 'save_project_infos',
                            'project_name': $("#update_project_name").val(),
                            'backoffice_summary' : tinyMCE.get('update_backoffice_summary').getContent(),
                            'project_category': $("#update_project_category").val(),
                            'project_activity': $("#update_project_activity").val(),
                            'project_location': $("#update_project_location").val()
                        }
                        update_tab(data_to_update, "projectinfo_form", "projectinfo_form_button", "projectinfo_form_loading", "projectinfo_form_errors");
                    });
                }

                //Ajax Infos financement
                if ($("#tab-funding").length > 0) {
                    //Etiquettes de numéros d'années pour le CA prévisionnel
                    $("#update_first_payment").change(function(){
                        var start_year = new Date($("#update_first_payment").val()).getFullYear();
                        $("#estimated-turnover li .year").each(function(index){
                            $(this).html((parseInt(start_year)+index));
                        });
                    });

                    //Cases pour le CA prévisionnel
                    $("#update_funding_duration").change(function() {
                        var nb_years_li_existing = ($("#estimated-turnover li").length);
                        var new_nb_years = parseInt($("#update_funding_duration").val());
                        "change nb year trigger "+new_nb_years+"(exist : "+nb_years_li_existing+")";

                        //Ajoute des boîtes au besoin
                        if(new_nb_years > nb_years_li_existing){
                            var newlines = $("#estimated-turnover").html();

                            for(var i=0; i<new_nb_years-nb_years_li_existing;i++){
                                newlines = newlines+
                                    '<li><label>Année <span class="year"></span></label>'+
                                    '<input type="text" value="0"/></li>'
                            }
                            $("#estimated-turnover").html(newlines);

                            //MAJ des étiquettes "Année XXXX"
                            $("#update_first_payment").trigger("change");
                            nb_years_li_existing = new_nb_years;
                        } else {
                            //N'affiche que les boites nécessaires
                            $("#estimated-turnover li").hide();
                            $("#estimated-turnover li").slice(0,new_nb_years).show();
                        }
                        nb_years_li_existing = Math.max(new_nb_years,nb_years_li_existing);
                    });
                    $("#update_funding_duration").trigger('change');

                    $("#projectfunding_form").submit(function (e) {
                        e.preventDefault();

                        var list_turnover = {};
                        $("#estimated-turnover li:visible").each(function(){
                            list_turnover[$(this).find('.year').html().toString()] = $(this).find('input').val();
                        });
                        var data_to_update = {
                            'action': 'save_project_funding',
                            'minimum_goal': $("#update_minimum_goal").val(),
                            'maximum_goal': $("#update_maximum_goal").val(),
                            'funding_duration': $("#update_funding_duration").val(),
                            'roi_percent_estimated': $("#update_roi_percent_estimated").val(),
                            'first_payment_date': $("#update_first_payment").val(),
                            'list_turnover': JSON.stringify(list_turnover)
                        }
                        update_tab(data_to_update, "projectfunding_form", "projectfunding_form_button", "projectfunding_form_loading", "projectfunding_form_errors");

                    });
                }

                //Ajax Infos communication
                if ($("#tab-communication").length > 0) {
                    $("#communication_form").submit(function (e) {
                        e.preventDefault();
                        var data_to_update = {
                            'action': 'save_project_communication',
                            'website': $("#update_website").val(),
                            'facebook': $("#update_facebook").val(),
                            'twitter': $("#update_twitter").val()
                        }
                        update_tab(data_to_update, "communication_form", "communication_form_button", "communication_form_loading", "communication_form_errors");
                    });
                }

                //Ajax Infos contractualisation
                if ($("#tab-contract").length > 0) {
                    $("#contract_form").submit(function (e) {
                        e.preventDefault();
                        var data_to_update = {
                            'action': 'save_project_contract',
                            'contract_url': $("#update_contract_url").val()
                        }
                        update_tab(data_to_update, "contract_form", "contract_form_button", "contract_form_loading", "contract_form_errors");
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
                    $("#campaign_form").submit(function (e) {
                        e.preventDefault();
                        var data_to_update = {
                            'action': 'save_project_campaigntab',
                            'google_doc': $("#update_planning_gdrive").val(),
                            'logbook_google_doc': $("#update_logbook_gdrive").val(),
                            'end_vote_date': $("#update_end_vote_date").val()+"\ "+$("#update_h_end_vote_date").val()+':'+$("#update_m_end_vote_date").val(),
                            'end_collecte_date': $("#update_end_collecte_date").val()+"\ "+$("#update_h_end_collecte_date").val()+':'+$("#update_m_end_collecte_date").val()
                        }
                        update_tab(data_to_update, "campaign_form", "campaign_form_button", "campaign_form_loading", "campaign_form_errors");

                    });

                    $("#update_project_organisation").change(function(e){
                        var newval = $("#update_project_organisation").val();

                        if(newval!=''){
                            $("#edit-orga-button").show();
                            var newname = $("#update_project_organisation").find('option:selected').text();
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
        },

        initQtip: function(){
            $('#ndashboard .infobutton, #ndashboard .qtip-element').each(function () {

                $(this).qtip({
                    content: $(this).next('.tooltiptext'),
                    position: {
                        my: 'bottom center',
                        at: 'top center',
                    },
                    style: {
                        classes: 'wdgQtip qtip-dark qtip-rounded qtip-shadow'
                    },
                    hide: {
                        fixed: true,
                        delay: 300
                    }
                });
            });
        },

        fieldError: function($param, errorText){
            $param.addClass("error");
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

                WDGProjectDashboard.initQtip();

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
                                _: "<b>%d</b> contacts sélectionnés",
                                0: 'Cliquez sur un contact pour le sélectionner',
                                1: "<b>1</b> contact sélectionné"
                            }
                        }
                    }
                });

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
    };

})(jQuery);


/*
//Fonction globale d'update d'informations
$("#ndashboard form").submit(function(e){
    e.preventDefault();
    var data_to_update = {
        'action': $(this).data("action")
    }
    $(this).find(".field-value").each(function(index){
        var id;
        switch ($(this).data("type")){
            case 'text':
            case 'number':
            case 'date':
            case 'link':
                data_to_update[$(this).find("input").attr('id')] = $(this).find("input").val();
                break;
            case 'datetime':
                id = data_to_update[$(this).find("input:eq(0)").attr('id')];// = $(this).find("input").val();
                data_to_update[id] = $(this).find("input:eq(0)").val()+"\ "
                    + $(this).find("input:eq(1)").val() +':'
                    + $(this).find("input:eq(2)").val();

                //$("#update_end_vote_date").val()+"\ "+$("#update_h_end_vote_date").val()+':'+$("#update_m_end_vote_date").val()
                break;
            case 'select':
                data_to_update[$(this).find("select").attr('id')] = $(this).find("select").val();
                break;
            case 'editor':
                id = data_to_update[$(this).find("textarea").attr('id')];
                data_to_update[id] = "later";//.get(id).getContent();
                break;
        }
    });
    console.log(data_to_update);
});*/