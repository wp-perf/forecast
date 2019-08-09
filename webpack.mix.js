const mix = require( 'laravel-mix' );
const { CleanWebpackPlugin } = require( 'clean-webpack-plugin' );

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

mix.options( {
  extractVueStyles: false,
  processCssUrls: false, // Webpack url() rewriting
  purifyCss: {}, // Removes unused CSS and JS
  // purifyCss: false,
  postCss: [
    require( 'autoprefixer' ),
    // require( 'postcss-custom-properties' )( {
    //   preserve: false,
    // } ),
  ],
} );

mix.setPublicPath( 'public' );

let sassOptions = {};

mix.autoload( {
  jquery: [ '$', 'window.jQuery' ],
} );

if ( mix.inProduction() ) {
  mix.version();
  sassOptions = {
    outputStyle: 'compressed',
  };
} else {
  mix.sourceMaps();
  sassOptions = {
    indentWidth: 1,
    outputStyle: 'expanded',
  };
}

mix.sass( 'resources/scss/main.scss', 'public/css/main.css', sassOptions );

mix.js( 'resources/js/main.js', 'public/js/main.js' );
mix.babel( 'public/js/main.js', 'public/js/main.js' );

mix.copy( 'resources/fonts/**/*', 'public/fonts' );
mix.copy( 'resources/img/**/*', 'public/img' );
mix.copy( 'resources/lang/**/*.mo', 'public/lang' );
mix.copy( 'resources/svg/**/*', 'public/svg' );

mix.browserSync( {
  proxy: 'dev.test',
  files: [
    'app/**/*.php',
    'public/**/*',
    'resources/views/**/*.php',
  ],
} );

mix.webpackConfig( {
  plugins: [
    new CleanWebpackPlugin(),
  ],
} );
