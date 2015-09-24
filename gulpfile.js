process.env.DISABLE_NOTIFIER = true;

require('./foundation-apps-gulp.js');
var elixir = require('laravel-elixir'),
    gulp = require('gulp'),
    replace = require('gulp-replace'),
    sequence = require('run-sequence');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

gulp.task('copyAndReplaceTemplates', function() {
    sequence('copy:templates', 'replaceTemplatePaths');
});

gulp.task('replaceIconicPath', ['copy:foundation'], function () {
    gulp.src('public/setup/assets/js/foundation.js')
        .pipe(replace('assets/img/iconic/', 'setup/assets/img/iconic/'))
        .pipe(gulp.dest('public/setup/assets/js'));
});

gulp.task('replaceTemplatePaths', ['copy:templates'], function () {
    return gulp.src('public/setup/assets/js/routes.js')
        .pipe(replace('"templates/', '"setup/templates/'))
        .pipe(gulp.dest('public/setup/assets/js'));
});

elixir(function (mix) {
    mix
        .task('clean')
        .task('copy', ['resources/assets/client/**/*', '!resources/assets/client/setup/templates/**/*.html', '!resources/assets/client/assets/scss/**/*.scss', '!resources/assets/client/assets/js/**/*.js'])
        .task('copy:foundation')
        .task('sass', 'resources/assets/client/assets/scss/**/*.scss')
        .task('uglify', 'resources/assets/client/assets/js/**/*.js')
        .task('copy:templates', 'resources/assets/client/templates/**/*.html')
        .task('replaceIconicPath', 'public/setup/assets/js/foundation.js')
        .task('replaceTemplatePaths', 'resources/assets/client/templates/**/*.html');

    mix.version([
        'setup/assets/css/app.css', 'setup/assets/js/app.js', 'setup/assets/js/foundation.js',
        'setup/assets/js/routes.js', 'setup/assets/js/templates.js'
    ]);
});
