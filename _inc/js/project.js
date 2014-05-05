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
    containment: '#project-map',
    drag : handleDrag
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
function handleDrag(event, ui){
 /* var cursor_top_position=$('#map-cursor').css('top');
  cursor_top_position=parseInt(cursor_top_position);
  if(cursor_top_position<85){
    var project_about_position=$('#project-about').css('bottom');
    project_about_position=parseInt(project_about_position);
    if(typeof previous_cursor_top_position=== "undefined"){previous_cursor_top_position=cursor_top_position;}
    if (previous_cursor_top_position>cursor_top_position) {//L'utilisateur descend sa souris
      project_about_position=project_about_position+1+"px";
    }
    else if(previous_cursor_top_position<cursor_top_position){//il monte sa souris
      project_about_position=project_about_position-1+"px";
    }
    previous_cursor_top_position=cursor_top_position;
    $('#project-about').css('bottom',project_about_position);
}*/
}

/*$( document ).ready(function() {
  
  }
});*/