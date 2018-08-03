var gulp = require('gulp');
var sass = require('gulp-sass');
var minifyCss = require('gulp-minify-css');
var htmlmin = require('gulp-htmlmin');
var imagemin = require('gulp-imagemin');

// start
gulp.task('start', function(){
	gulp.start('css:watch');
	gulp.start('minify:watch');
	gulp.start('htmlmin:watch');
	gulp.start('imagemin');
});


// sass
gulp.task('css', function(){
	gulp.src('./dev/css/**/*.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(gulp.dest('./css'));
});

gulp.task('css:watch', function(){
	gulp.watch('./dev/css/**/*.scss', ['css']);
});


// css minify
gulp.task('minify-css', function(){
	return gulp.src('./css/*.css')
		.pipe(minifyCss({keepSpecialComments: 1}))
		.pipe(gulp.dest('css'))
});

gulp.task('minify:watch', function(){
	gulp.watch('./css/*.css', ['minify-css']);
});


// html minify
gulp.task('htmlmin', function(){
	return gulp.src('./dev/views/**/*.html')
		.pipe(htmlmin({collapseWhitespace: true}))
		.pipe(gulp.dest('views'))
});

gulp.task('htmlmin:watch', function(){
	gulp.watch('./dev/views/**/*.html', ['htmlmin']);
});

// html minify
gulp.task('imagemin', function(){
	return gulp.src([
		'./dev/img/**/*.jpg',
		'./dev/img/**/*.jpeg',
		'./dev/img/**/*.png',
		'./dev/img/**/*.gif',
		])
		.pipe(imagemin())
		.pipe(gulp.dest('img'))
});