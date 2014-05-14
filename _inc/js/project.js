function move_picture(campaign_id) {
    $('#img-container').draggable('enable');
    $('#img-container').draggable({ axis : 'y' }); // appel du plugin
    $('#reposition-cover').text('Sauvegarder');
    $('#reposition-cover').attr("onclick", "save_position("+campaign_id+")");
    $("#head-content").css({ opacity: 0 });
    $("#head-content").css({ 'z-index': -1 });
}

function save_position(campaign_id){
    $("#head-content").css({ opacity: 1 });
    $("#head-content").css({ 'z-index': 2 });
    $('#img-container').draggable('disable');
    $('#reposition-cover').text('Repositionner');
    $('#reposition-cover').attr("onclick", "move_picture("+campaign_id+")");
    $.ajax({
              'type' : "POST",
              'url' : ajax_object.ajax_url,
              'data': { 
                      'action':'setCoverPosition',
                      'top' : $('#img-container').css('top'),
                      'id_campaign' : campaign_id
                    }
            }).done()
}
function move_cursor(campaign_id){
  $('#move-cursor').text('Sauvegarder la position du curseur');
  $('#move-cursor').attr("onclick", "save_cursor_position("+campaign_id+")");
  $('#map-cursor').draggable('enable');
  $('#map-cursor').draggable({
    containment: '#project-map'
    });
}
function save_cursor_position(campaign_id){
  $('#move-cursor').text('Modifier la position du curseur');
  $('#move-cursor').attr("onclick", "move_cursor("+campaign_id+")");
  $('#map-cursor').draggable('disable');
  $.ajax({
              'type' : "POST",
              'url' : ajax_object.ajax_url,
              'data': { 
                      'action':'setCursorPosition',
                      'top' : $('#map-cursor').css('top'),
                      'left' : $('#map-cursor').css('left'),
                      'id_campaign' : campaign_id
                    }
            }).done(); 
}

function update_jycrois(jy_crois,campaign_id,home_url){
  var img_url=home_url+'/wp-content/themes/yproject/images/';
if(jy_crois==0) {
  jy_crois_temp=1;
  img_url+='grenage_projet.jpg';
  $('#jy-crois-btn').css('background-image','url("'+img_url+'")');
  $('#jy-crois-txt').text('J\'y crois');
}else{
  jy_crois_temp=0;
  img_url+='jycrois_gris.png';
  $('#jy-crois-txt').text('');
  $('#jy-crois-btn').css('background-image','url("'+img_url+'")');
}
var actual_text=$('#nb-jycrois').text();
            if (jy_crois==1) {
              $('#nb-jycrois').text(parseInt(actual_text)+1);
            }
            else{
               $('#nb-jycrois').text(parseInt(actual_text)-1);
            }
$('.jy-crois').attr("href", "javascript:update_jycrois("+jy_crois_temp+","+campaign_id+",\""+home_url+"\")");
   $.ajax({
              'type' : "POST",
              'url' : ajax_object.ajax_url,
              'data': { 
                      'action':'updateJyCrois',
                      'jy_crois' : jy_crois,
                      'id_campaign' : campaign_id
                    },
            }).done(function(){
            
          });
}

function share_btn_click(){
  $("#dialog").dialog("open");
  
    
}
function print_vote_form(){
  $("#vote-form").animate({ 
        bottom: "-500px",
      }, 500 );
}

$(document).ready(function () {
    $(function () {
        $("#dialog").dialog({
            width: '350px',
            draggable: false,
            resizable: false,
            autoOpen: false,
            modal: true,
            show: {
                effect: "blind",
                duration: 300
            },
            hide: {
                 effect: "blind",
                duration: 300
            }
        });
    });
     
});