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

// Tache 1 : récupérer tous les fichiers communs et les assembler en un seul
gulp.task( 'concat', function() {
	return gulp.src( cssFileList )
		.pipe( concat( 'concatStyles.css' ) )
		.pipe( gulp.dest( destination ) );
} );


// Tache 2 : minifier le fichier concaténé (si la tâche concat est terminée)
gulp.task( 'minify', ['concat'], function() {
	return gulp.src(destination+'*.css')
		.pipe( plugins.csso() ) // minify
		.pipe( plugins.rename( { // rename .min.css  
			dirname: "",
			basename: "common",
			suffix: ".min",
			extname: ".css"
		} ) )
		.pipe( gulp.dest( source + '_inc/css/' ) );
} );


// Tâche par défaut
gulp.task( 'css', ['concat', 'minify'] );

// Tâche de veille
gulp.task( 'watch', function() {
	gulp.watch( cssFileList, ['css'] );
} );