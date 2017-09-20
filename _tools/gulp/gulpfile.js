// Chargement de gulp & plugins nécessaires
var gulp = require( 'gulp' );
var plugins = require( 'gulp-load-plugins' )();
var concat = require( 'gulp-concat' );

// Variables de chemins
var source = '../../'; // (=> yproject)
var destination = './'; // _tools/gulp
var cssFileList = [  // CSS files ordered
	source + '_inc/css/common.css',
	source + '_inc/css/components.css',
	source + '_inc/css/responsive-inf997.css',
	source + '_inc/css/responsive.css',
	source + '_inc/css/responsive-medium.css'
];
var cssCampaignFileList = [  // CSS files ordered
	source + '_inc/css/campaign.css'
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


// Tâche par défaut
gulp.task( 'css', ['concat', 'minify'] );
gulp.task( 'css-campaign', ['concat-campaign', 'minify-campaign'] );

// Tâche de veille
gulp.task( 'watch', function() {
	gulp.watch( cssFileList, ['css'] );
	gulp.watch( cssCampaignFileList, ['css-campaign'] );
} );