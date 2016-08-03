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

                    $("#ndashboard-content .page-dashboard").hide();
                    $("#ndashboard-content " + target).show();

                    //Charge les iframe
                    var iframetoload = $("#ndashboard-content " + target+" .google-doc iframe");
                    iframetoload.each(function(){
                        $(this).prop('src',$(this).data('src'));
                        $(this).on('load', function(){
                            $(this).addClass('isloaded');
                        });
                    });

                    history.pushState(null, null, target);
                    return false; //Empêche le défilement automatique lorsqu'on clique sur un lien avec un #
                });

                var hash = window.location.hash
                var tabsaved = $('#ndashboard-navbar li a[href="'+window.location.hash+'"]')
                if(hash != '' && !tabsaved.hasClass("disabled")){
                    tabsaved.trigger("click");
                } else {
                    $("#ndashboard-navbar li a:not(.disabled)").first().trigger("click");
                }
                window.scrollTo(0, 0);
            }

            //Infobulles
            $('#ndashboard .infobutton[title!=""]').qtip({
                position: {
                    my: 'bottom center',
                    at: 'top center',
                },
                style: {
                    classes: 'qtip-tipsy qtip-shadow'
                },
                hide: {
                    fixed: true,
                    delay: 300
                }
            });

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
                            $("#new_team_member_string").next().after("<div id=\"fail_add_team_indicator\"><br/><em>L'utilisateur "+data+" n'a pas été trouvé</em><div>");
                            $("#fail_add_team_indicator").delay(4000).fadeOut(400);
                        } else {
                            res = JSON.parse(result);

                            //Teste si l'user existait déjà
                            doublon = false;
                            $(".project-manage-team").each(function(){
                                doublon = doublon || (res.id == $(this).attr('data-user'));
                            });

                            if(!doublon){
                                newline ='<li style="display: none;">';
                                newline+=res.firstName+" "+res.lastName+" ("+res.userLink+") ";
                                newline+='<a class="project-manage-team button" data-action="yproject-remove-member" data-user="'+res.id+'">x</a>';
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
                $("a[data-user="+data+"]").closest("li").css("opacity",0.25);
                $("a[data-user="+data+"]").text("..");
                $("a[data-user="+data+"]").addClass("wait-delete");

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