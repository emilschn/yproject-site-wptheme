(function () {
	tinymce.create('tinymce.plugins.video', {
		codeUrlVideo: null,
		init: function (ed, url) {
			var t = this;

			//on ajoute le bouton ainsi que son picto
			ed.addButton('video', {
				title: 'Ajouter une vidéo',
				image: url + '/img/mon_picto.png',
				onclick: function () {
					var urlVideo = prompt('Lien de votre vidéo (ceci ajoutera un code spécial, veuillez rafraichir la page pour la visualiser ; si la vidéo est ajoutée dans une actualité, celle-ci ne sera pas affichée dans un e-mail, utilisez plutôt une image)', '');
					if (urlVideo != null && urlVideo != '') {
						codeUrlVideo = '[embed]' + urlVideo + '[/embed]';
						ed.execCommand('mceInsertContent', false, codeUrlVideo);
					}
				}
			});
		},
	});
	tinymce.PluginManager.add('video', tinymce.plugins.video);
})();