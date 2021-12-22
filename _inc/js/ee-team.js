function EasterEggTeam() {
	this.hasSeenPeople = new Array();
	this.hasLaunchedAnim = false;
	this.init();
}

/**
 * Initialise les actions sur les différents boutons d'affichage
 */
EasterEggTeam.prototype.init = function () {
	const self = this;

	// Init du tableau qui contient toutes les personnes
	$('.wdg-button-lightbox-open.button').each(
		function () {
			self.hasSeenPeople[$(this).data('lightbox')] = false;
		}
	);
	// Action sur le click pour considérer une personne comme vue
	$('.wdg-button-lightbox-open.button').click(
		function (e) {
			self.hasSeenPeople[$(this).data('lightbox')] = true;
			self.testIfAnimate();
		}
	);
}

/**
 * Vérifie si toutes les personnes ont été vues
 */
EasterEggTeam.prototype.testIfAnimate = function () {
	const self = this;

	let hasSeenEverybody = true;
	for (let id in self.hasSeenPeople) {
		if (!self.hasSeenPeople[id]) {
			hasSeenEverybody = false;
		}
	}

	if (hasSeenEverybody) {
		self.startAnim();
	}
}

/**
 * Lancement de l'animation
 */
EasterEggTeam.prototype.startAnim = function () {
	const self = this;

	if (this.hasLaunchedAnim) {
		return false;
	}
	this.hasLaunchedAnim = true;

	// Nouveaux styles pour le ciel, les étoiles et les météors
	$('body').append('<style>' +
		'body{ background-color: #080e21; }' +
		'.star{ position: absolute; width: 1px; height: 1px; background: yellow; }' +
		'.meteor { position: absolute; z-index: 999; }' +
		'.meteor div { animation: meteor 2s linear; width: 25px; height: 25px; border-radius: 50%; background-color: #080e21; border-color: #080e21; }' +
		'@keyframes meteor {' +
		'0% { opacity: 0; margin-top: -600px; margin-left: 600px; width: 1px; height: 1px; background-color: #FFAE00; border-color: #FFAE00; }' +
		'25% { opacity: 0.5; background-color: #FF3B19; border-color: #080e21; }' +
		'80% { opacity: 0.8; background-color: #FF3B19; border-color: #080e21; }' +
		'100% { opacity: 1; margin-top: 0px; margin-left: 0px; width: 25px; height: 25px; background-color: #080e21; border-color: #080e21; }' +
		'}' +
		'</style>');
	$('.ts-background-gradient-parent').hide();

	// Ajout des étoiles décoratives
	for (let i = 0; i < 500; i++) {
		let left = Math.random() * $('body').width();
		let top = Math.random() * $('body').height();
		$('body').append('<div class="star" style="left: ' + left + 'px; top: ' + top + 'px;"></div>');
	}

	// Toute les 500ms, ajout d'une nouvelle météor
	setInterval(self.launchMeteor, 200);
}

/**
 * 
 */
EasterEggTeam.prototype.launchMeteor = function () {
	let left = Math.random() * $('body').width();
	let top = Math.random() * ($('body').height());
	$('body').append('<div class="meteor" style="left: ' + left + 'px; top: ' + top + 'px;"><div></div></div>');
}


$(function () {
	jQuery(document).ready(function ($) {
		new EasterEggTeam();
	});
});