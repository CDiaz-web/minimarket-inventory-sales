const { src, dest, watch, parallel } = require('gulp');

/* ===============================
   CSS
================================= */

const sass = require('gulp-sass')(require('sass'));
const plumber = require('gulp-plumber');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const postcss = require('gulp-postcss');
const sourcemaps = require('gulp-sourcemaps');

/* ===============================
   IMÁGENES
================================= */

const cache = require('gulp-cache');
const imagemin = require('gulp-imagemin');
const webp = require('gulp-webp');
const avif = require('gulp-avif');

/* ===============================
   JS
================================= */

const terser = require('gulp-terser');
const rename = require('gulp-rename');
const webpack = require('webpack-stream');

/* ===============================
   PATHS
================================= */

const paths = {
    scss: 'src/scss/**/*.scss',
    jsEntry: 'src/js/app.js',
    jsWatch: 'src/js/**/*.js',
    imagenes: 'src/img/**/*'
};

/* ===============================
   CSS
================================= */

function css() {
    return src('src/scss/app.scss')
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(sass({ outputStyle: 'expanded' }))
        .pipe(postcss([
            autoprefixer(),
            cssnano()
        ]))
        .pipe(sourcemaps.write('.'))
        .pipe(dest('public/build/css'));
}

/* ===============================
   JAVASCRIPT
================================= */

function javascript() {
    return src(paths.jsEntry)
        .pipe(plumber())
        .pipe(webpack({
            mode: 'production',
            output: {
                filename: 'app.js'
            }
        }))
        .pipe(sourcemaps.init({ loadMaps: true }))
        .pipe(terser())
        .pipe(rename({ suffix: '.min' }))
        .pipe(sourcemaps.write('.'))
        .pipe(dest('public/build/js'));
}

/* ===============================
   IMÁGENES
================================= */

function imagenes() {
    return src(paths.imagenes)
        .pipe(cache(imagemin({ optimizationLevel: 3 })))
        .pipe(dest('public/build/img'));
}

function versionWebp() {
    return src('src/img/**/*.{png,jpg}')
        .pipe(webp({ quality: 50 }))
        .pipe(dest('public/build/img'));
}

function versionAvif() {
    return src('src/img/**/*.{png,jpg}')
        .pipe(avif({ quality: 50 }))
        .pipe(dest('public/build/img'));
}

/* ===============================
   WATCH
================================= */

function dev() {
    watch(paths.scss, css);
    watch(paths.jsWatch, javascript);
    watch(paths.imagenes, parallel(imagenes, versionWebp, versionAvif));
}

/* ===============================
   EXPORTS
================================= */

exports.css = css;
exports.js = javascript;
exports.dev = parallel(css, javascript, imagenes, versionWebp, versionAvif, dev);