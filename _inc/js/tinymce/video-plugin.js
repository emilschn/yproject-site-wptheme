(function() {
    tinymce.create('tinymce.plugins.video', {
        codeUrlVideo: null,
        init : function(ed, url) {
            var t = this;
          
          //on ajoute le bouton ainsi que son picto
            ed.addButton('video', {
                title : 'Ajouter une vidéo',
                image : url + '/img/mon_picto.png',
                onclick : function() {
                    var urlVideo = prompt('Lien de votre vidéo', '');
                    if ( urlVideo != null && urlVideo != '') {
                        codeUrlVideo = '[embed]'+urlVideo+'[/embed]';
                        ed.execCommand('mceInsertContent', false, codeUrlVideo);    
                    }
                }
            });  
        },   
   });
   tinymce.PluginManager.add('video', tinymce.plugins.video);
})();