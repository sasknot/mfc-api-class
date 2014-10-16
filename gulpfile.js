var gulp = require('gulp');
var compass = require('gulp-compass');

var paths = {
	fonts: [
		'bower_components/bootstrap/dist/fonts/*.*'
	],
	stylesheets: [
		'bower_components/bootstrap/dist/css/bootstrap.min.css',
		'bower_components/bootstrap/dist/css/bootstrap-theme.min.css'
	],
	scripts: [
		'bower_components/jquery/dist/jquery.min.js',
		'bower_components/bootstrap/dist/js/bootstrap.min.js',
		'bower_components/imagesloaded/imagesloaded.pkgd.min.js',
		'bower_components/masonry/dist/masonry.pkgd.min.js'
	]
};

gulp.task('copy', function() {
	gulp.src(paths.fonts)
		.pipe(gulp.dest('demos/fonts'));

	gulp.src(paths.stylesheets)
		.pipe(gulp.dest('demos/css'));

	gulp.src(paths.scripts)
		.pipe(gulp.dest('demos/js'));
});

gulp.task('watch', function() {
	gulp.watch(paths.stylesheets, ['copy']);
});

gulp.task('default', ['copy', 'watch']);