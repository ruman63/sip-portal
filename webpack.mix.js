let mix = require('laravel-mix');
var tailwindcss = require('tailwindcss');
let glob = require("glob-all");
let PurgecssPlugin = require("purgecss-webpack-plugin");

class TailwindExtractor {
  static extract(content) {
    return content.match(/[A-z0-9-:\/]+/g) || [];
  }
}

if(mix.inProduction()){
    mix.webpackConfig({
        plugins: [
          new PurgecssPlugin({
            // Specify the locations of any files you want to scan for class names.
            paths: glob.sync([
              path.join(__dirname, "resources/views/**/*.blade.php"),
              path.join(__dirname, "resources/assets/js/**/*.vue"),
              path.join(__dirname, "public/js/main.js")
            ]),
            extractors: [
              {
                extractor: TailwindExtractor,
    
                // Specify the file extensions to include when scanning for
                // class names.
                extensions: ["html", "js", "php", "vue"]
              }
            ]
          })
        ]
    });
}
mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .options({
    processCssUrls: false,
    postCss: [ tailwindcss('./tailwind.js') ],
  })
  .browserSync({
    proxy: "portal.test"
  });
