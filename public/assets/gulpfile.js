var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var pump = require('pump');

gulp.task('compress', function(cb) {
    pump([
        gulp.src('js/*.js'),
        concat('app.min.js'),
        uglify(),
        gulp.dest('js')
    ], cb);
});

gulp.task('default', ['compress']);