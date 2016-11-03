/* 
 * 
 * File to manage tasks to realize, options and destination sources 
 * 
 */
// Required
var gulp = require('gulp');

// Include plugins
var plugins = require('gulp-load-plugins')(); // all plugins in package.json
var concat = require('gulp-concat');

// Path variables
var source = '../../'; // working directory (=yproject)
var destination = './'; // _tools/gulp directory for gulp resulting files


// Task concat files wich are in differents directory
gulp.task('concat', function () {
     return gulp.src([
                        source + '_inc/css/common.css',
                        source + '_inc/css/components.css',
                        source + '_inc/css/responsive-inf997.css',
                        source + '/_inc/css/responsive.css',
                        source + '/_inc/css/responsive-medium.css',
                        source + 'styles.css'
                    ]) // CSS files ordered
    .pipe(concat('concatStyles.css'))
    .pipe(gulp.dest(destination));
});



// Task "minify" = CSS minification if task concat is done
gulp.task('minify',['concat'], function () {
return gulp.src(destination+'*.css')
  .pipe(plugins.csso())// minify
  .pipe(plugins.rename({// rename .min.css  
    dirname: "",
    basename: "styles",
    suffix: '.min',
    extname: ".css"
  }))
  .pipe(gulp.dest(destination + 'minCss'));
});



// Default task
gulp.task('default', ['concat', 'minify']);
