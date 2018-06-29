// AUTHOR JOE CURRAN (@JBIRD608)
var gulp       = require('gulp');

var theme      = "paprika";

// INCLUDE PLUGINS & MODULES.
var concat     = require('gulp-concat');
var sass       = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var plumber    = require('gulp-plumber');

// SET THE WATCH TASK.
gulp.task('watch', function() {
    gulp.watch('templates/motionlab/' + theme + '/*.scss', ['production-sass']);
});

// PRODUCTION RUN CODE.
gulp.task('production-sass', function() {
    return gulp.src('templates/motionlab/' + theme + '/*.scss')
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(concat('template.css'))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('templates/motionlab/' + theme));
});

// SET THE DEFAULT TASK.
gulp.task('default', ['production-sass', 'watch']);
