var gulp = require('gulp');
var autoprefixer = require('gulp-autoprefixer');
var exec = require('child_process').exec;

gulp.task('default', ['css', 'watch', 'node']);

gulp.task('css', function () {
	return gulp.src('public/css/src/*')
		.pipe(autoprefixer())
		.pipe(gulp.dest('public/css/'));
});

gulp.task('watch', function() {
	gulp.watch('public/css/src/*', ['css']);
});

gulp.task('node', function() {
	exec('sudo node bin/', { maxBuffer: 1 }, function(err, stdout, stderr) { console.log(stdout); });
});