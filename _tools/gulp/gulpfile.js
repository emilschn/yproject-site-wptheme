// Chargement de gulp & plugins nécessaires
var gulp = require( 'gulp' );
var plugins = require( 'gulp-load-plugins' )();
var concat = require( 'gulp-concat' );
var uglify = require( 'gulp-uglify' );

// Variables de chemins
var source = '../../'; // (=> yproject)
var destination = './'; // _tools/gulp

// Listes de fichiers
// CSS - Common
var cssFileList = [
	source + '_inc/css/common.css',
	source + '_inc/css/components.css',
	source + '_inc/css/responsive-inf997.css',
	source + '_inc/css/responsive.css',
	source + '_inc/css/responsive-medium.css'
];
// CSS - Campaign
var cssCampaignFileList = [
	source + '_inc/css/campaign.css'
];
// CSS - Invest
var cssInvestFileList = [
	source + '_inc/css/invest.css'
];

// JS - Common
var jsCommonFileList = [
	source + '_inc/js/common.js',
	source + '_inc/js/sharer.min.js'
];


// Tache 1 : récupérer tous les fichiers communs et les assembler en un seul
gulp.task( 'concat', function() {
	return gulp.src( cssFileList )
		.pipe( concat( 'concatStyles.css' ) )
		.pipe( gulp.dest( destination ) );
} );
gulp.task( 'concat-campaign', function() {
	return gulp.src( cssCampaignFileList )
		.pipe( concat( 'concatCampaignStyles.css' ) )
		.pipe( gulp.dest( destination ) );
} );
gulp.task( 'concat-invest', function() {
	return gulp.src( cssInvestFileList )
		.pipe( concat( 'concatInvestStyles.css' ) )
		.pipe( gulp.dest( destination ) );
} );
gulp.task( 'concat-js-common', function() {
	return gulp.src( jsCommonFileList )
		.pipe( concat( 'concatCommonScripts.js' ) )
		.pipe( gulp.dest( destination ) );
} );


// Tache 2 : minifier le fichier concaténé (si la tâche concat est terminée)
gulp.task( 'minify', ['concat'], function() {
	return gulp.src(destination+'concatStyles.css')
		.pipe( plugins.csso() ) // minify
		.pipe( plugins.rename( { // rename .min.css  
			dirname: "",
			basename: "common",
			suffix: ".min",
			extname: ".css"
		} ) )
		.pipe( gulp.dest( source + '_inc/css/' ) );
} );
gulp.task( 'minify-campaign', ['concat-campaign'], function() {
	return gulp.src(destination+'concatCampaignStyles.css')
		.pipe( plugins.csso() ) // minify
		.pipe( plugins.rename( { // rename .min.css  
			dirname: "",
			basename: "campaign",
			suffix: ".min",
			extname: ".css"
		} ) )
		.pipe( gulp.dest( source + '_inc/css/' ) );
} );
gulp.task( 'minify-invest', ['concat-invest'], function() {
	return gulp.src(destination+'concatInvestStyles.css')
		.pipe( plugins.csso() ) // minify
		.pipe( plugins.rename( { // rename .min.css  
			dirname: "",
			basename: "invest",
			suffix: ".min",
			extname: ".css"
		} ) )
		.pipe( gulp.dest( source + '_inc/css/' ) );
} );
gulp.task( 'minify-js-common', ['concat-js-common'], function() {
	return gulp.src(destination+'concatCommonScripts.js')
		.pipe( uglify() ) // minify
		.pipe( plugins.rename( { // rename .min.css  
			dirname: "",
			basename: "common",
			suffix: ".min",
			extname: ".js"
		} ) )
		.pipe( gulp.dest( source + '_inc/js/' ) );
} );


// Tâche de mise à jour du fichier de version
gulp.task( 'update-version', function() {
	fs = require('fs');
	fs.readFile( destination + 'assets-version-template.php', 'utf8', function ( err, templateData ) {
		if (err) {
			return console.log( 'Read error : ' + err);
		}
		var writeData = templateData.split( '%version%' ).join( Date.now() );
		fs.writeFile( source + "/functions/assets-version.php", writeData, function( err ) {
			if (err) {
				console.log( 'Write error : ' + err);
			}
		} );
	});
} );


// Tâches lançant les différentes actions
gulp.task( 'css', [ 'concat', 'minify', 'update-version' ] );
gulp.task( 'css-campaign', [ 'concat-campaign', 'minify-campaign', 'update-version' ] );
gulp.task( 'css-invest', [ 'concat-invest', 'minify-invest', 'update-version' ] );
gulp.task( 'js-common', [ 'concat-js-common', 'minify-js-common', 'update-version' ] );


// Tâche de veille pour les modifications des différents fichiers
gulp.task( 'watch', function() {
	gulp.watch( cssFileList, ['css'] );
	gulp.watch( cssCampaignFileList, ['css-campaign'] );
	gulp.watch( cssInvestFileList, ['css-invest'] );
	gulp.watch( jsCommonFileList, ['js-common'] );
} );