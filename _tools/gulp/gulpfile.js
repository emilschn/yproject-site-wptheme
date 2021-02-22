// Chargement de gulp & plugins nécessaires
var gulp = require( 'gulp' );
var plugins = require( 'gulp-load-plugins' )();
var concat = require( 'gulp-concat' );
const terser = require('gulp-terser');
var uglify = require( 'gulp-uglifyes' );

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
// CSS - Project Dashboard
var cssProjectDashboardFileList = [
	source + '_inc/css/campaign-dashboard.css'
];
// CSS - Project Dashboard
var cssProjectDashboardStatsFileList = [
	source + '_inc/css/campaign-dashboard-stats.css'
];

// JS - Common
var jsCommonFileList = [
	source + '_inc/js/common.js',
	source + '_inc/js/sharer.min.js'
];
// JS - Project Dashboard
var jsProjectDashboardFileList = [
	source + '_inc/js/campaign-dashboard.js'
];
// JS - Project Dashboard
var jsProjectDashboardGraphsFileList = [
	source + '_inc/js/campaign-dashboard-graphs.js'
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
gulp.task( 'concat-projectdb', function() {
	return gulp.src( cssProjectDashboardFileList )
		.pipe( concat( 'concatProjectDBStyles.css' ) )
		.pipe( gulp.dest( destination ) );
} );
gulp.task( 'concat-js-common', function() {
	return gulp.src( jsCommonFileList )
		.pipe( concat( 'concatCommonScripts.js' ) )
		.pipe( gulp.dest( destination ) );
} );
gulp.task( 'concat-js-projectdb', function() {
	return gulp.src( jsProjectDashboardFileList )
		.pipe( concat( 'concatProjectDBScripts.js' ) )
		.pipe( gulp.dest( destination ) );
} );


// Tache 2 : minifier le fichier concaténé (si la tâche concat est terminée)
gulp.task( 'minify', gulp.series('concat', function() {
	return gulp.src(destination+'concatStyles.css')
		.pipe( plugins.csso() ) // minify
		.pipe( plugins.rename( { // rename .min.css  
			dirname: "",
			basename: "common",
			suffix: ".min",
			extname: ".css"
		} ) )
		.pipe( gulp.dest( source + '_inc/css/' ) );
} ));
gulp.task( 'minify-campaign', gulp.series('concat-campaign', function() {
	return gulp.src(destination+'concatCampaignStyles.css')
		.pipe( plugins.csso() ) // minify
		.pipe( plugins.rename( { // rename .min.css  
			dirname: "",
			basename: "campaign",
			suffix: ".min",
			extname: ".css"
		} ) )
		.pipe( gulp.dest( source + '_inc/css/' ) );
} ));
gulp.task( 'minify-invest', gulp.series('concat-invest', function() {
	return gulp.src(destination+'concatInvestStyles.css')
		.pipe( plugins.csso() ) // minify
		.pipe( plugins.rename( { // rename .min.css  
			dirname: "",
			basename: "invest",
			suffix: ".min",
			extname: ".css"
		} ) )
		.pipe( gulp.dest( source + '_inc/css/' ) );
} ));
gulp.task( 'minify-projectdb', gulp.series('concat-projectdb', function() {
	return gulp.src(destination+'concatProjectDBStyles.css')
		.pipe( plugins.csso() ) // minify
		.pipe( plugins.rename( { // rename .min.css  
			dirname: "",
			basename: "campaign-dashboard",
			suffix: ".min",
			extname: ".css"
		} ) )
		.pipe( gulp.dest( source + '_inc/css/' ) );
} ));
gulp.task( 'minify-css-projectdb-stats', function() {
	return gulp.src(cssProjectDashboardStatsFileList[0] )
		.pipe( plugins.csso() ) // minify
		.pipe( plugins.rename( { // rename .min.css  
			dirname: "",
			basename: "campaign-dashboard-stats",
			suffix: ".min",
			extname: ".css"
		} ) )
		.pipe( gulp.dest( source + '_inc/css/' ) );
} );
gulp.task( 'minify-js-common', gulp.series('concat-js-common', function() {
	return gulp.src(destination+'concatCommonScripts.js')
		.pipe( uglify() ) // minify
		.pipe( plugins.rename( { // rename .min.css  
			dirname: "",
			basename: "common",
			suffix: ".min",
			extname: ".js"
		} ) )
		.pipe( gulp.dest( source + '_inc/js/' ) );
} ));
gulp.task( 'minify-js-projectdb', gulp.series('concat-js-projectdb', function() {
	return gulp.src(destination+'concatProjectDBScripts.js')
		.pipe( uglify() ) // minify
		.pipe( plugins.rename( { // rename .min.css  
			dirname: "",
			basename: "campaign-dashboard",
			suffix: ".min",
			extname: ".js"
		} ) )
		.pipe( gulp.dest( source + '_inc/js/' ) );
} ));
gulp.task( 'minify-js-projectdb-graphs', function() {
	return gulp.src(jsProjectDashboardGraphsFileList[0] )
		.pipe( uglify().on('error', console.error) ) // minify
		.pipe( plugins.rename( { // rename .min.css  
			dirname: "",
			basename: "campaign-dashboard-graphs",
			suffix: ".min",
			extname: ".js"
		} ) )
		.pipe( gulp.dest( source + '_inc/js/' ) );
} );


// Tâche de mise à jour du fichier de version
gulp.task( 'update-version', function( done ) {
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
	done();
} );


// Tâches lançant les différentes actions
gulp.task( 'css', gulp.series( 'concat', 'minify', 'update-version' ) );
gulp.task( 'css-campaign', gulp.series( 'concat-campaign', 'minify-campaign', 'update-version' ) );
gulp.task( 'css-invest', gulp.series( 'concat-invest', 'minify-invest', 'update-version' ) );
gulp.task( 'css-projectdb', gulp.series( 'concat-projectdb', 'minify-projectdb', 'update-version' ) );
gulp.task( 'css-projectdb-stats', gulp.series( 'minify-css-projectdb-stats', 'update-version' ) );
gulp.task( 'js-common', gulp.series( 'concat-js-common', 'minify-js-common', 'update-version' ) );
gulp.task( 'js-projectdb', gulp.series( 'concat-js-projectdb', 'minify-js-projectdb', 'update-version' ) );
gulp.task( 'js-projectdb-graphs', gulp.series( 'minify-js-projectdb-graphs', 'update-version' ) );


// Tâche de veille pour les modifications des différents fichiers
gulp.task( 'watch', function() {
	gulp.watch( cssFileList, gulp.series('css') );
	gulp.watch( cssCampaignFileList, gulp.series('css-campaign') );
	gulp.watch( cssInvestFileList, gulp.series('css-invest') );
	gulp.watch( cssProjectDashboardFileList, gulp.series('css-projectdb') );
	gulp.watch( cssProjectDashboardStatsFileList, gulp.series('css-projectdb-stats') );
	gulp.watch( jsCommonFileList, gulp.series('js-common') );
	gulp.watch( jsProjectDashboardFileList, gulp.series('js-projectdb') );
	gulp.watch( jsProjectDashboardGraphsFileList, gulp.series('js-projectdb-graphs') );
} );