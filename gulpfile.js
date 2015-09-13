//
// Gulp dependencies
//
var gulp            = require('gulp');
var babel           = require('babelify');
var sass            = require('gulp-sass');
var gutil           = require('gulp-util');
var browserify      = require('browserify');
var rename          = require('gulp-rename');
var uglify          = require('gulp-uglify');
var plumber         = require('gulp-plumber');
var buffer          = require('vinyl-buffer');
var minifycss       = require('gulp-minify-css');
var sourcemaps      = require('gulp-sourcemaps');
var autoprefixer    = require('gulp-autoprefixer');
var source          = require('vinyl-source-stream');

//
// Form widgets
//
gulp.task('scss', function() {
    gulp.src('./assets/scss/main.scss')
        .pipe(plumber({
            errorHandler: function (err) {
                gutil.log(util.colors.red('ERROR:'), 'Failed to compile SCSS\n', err).beep();
                this.emit('end');
            }
        }))
        .pipe(sass())
        .pipe(autoprefixer('last 10 versions'))
        .pipe(minifycss())
        .pipe(rename('splitter.min.css'))
        .pipe(gulp.dest('./assets/dist'));
});

//
// Scripts
//
gulp.task('js', function() {
    browserify('./assets/js/main.es6', {
            debug: true,
            extensions: ['.js', '.es6'],
        })
        .transform(babel)
        .bundle()
        .on('error', function(err) {
            gutil
                .log(gutil.colors.red('ERROR:'), 'Browserify failed.\n', err.toString())
                .beep();
        })
        .pipe(source('bundle.min.js'))
        .pipe(buffer())
        .pipe(sourcemaps.init({
            loadMaps: true,
        }))
        .pipe(uglify())
        .pipe(sourcemaps.write('./../maps/'))
        .pipe(gulp.dest('./assets/dist'));
});

//
// Watch file system
//
gulp.task('default', function () {
    gulp.watch('./assets/scss/**/*', ['scss']);
    gulp.watch('./assets/js/**/*', ['js']);
});
