module.exports = function(grunt) {

    require('load-grunt-tasks')(grunt);

    var jpegtran = require('imagemin-jpegtran');
    var optipng = require('imagemin-optipng');


    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        // Empty dist folder
        clean: {
            dist: {
                src: ['web/dist']
            }
        },

        copy: {
            dist: {
                expand: true,
                cwd: 'web/fonts',
                src: '**',
                dest: 'web/dist/fonts/'
            }
        },

        // Example of testing cssCalc with Modernizr
        modernizr: {
            dist: {
                "crawl": false,
                "customTests": [],
                "dest": "web/js/frontend/modernizr-output.js",
                "tests": [
                    "csscalc"
                ],
                "options": [
                    "setClasses"
                ],
                "uglify": true
            }
        },

        // Test javascript files for errors and good code
        jshint: {
            options: {
                curly: true,
                eqeqeq: true,
                eqnull: true,
                browser: true,
                reporter: require('jshint-stylish'),
                globals: {
                    jQuery: true
                }
            },
            src: [
                'web/js/*/projectname.*.js'
            ]
        },

        // merge and uglify javascript files into one
        // Add libraries if you need
        uglify: {
            dist: {
                files: {
                    'web/dist/js/projectname.min.js': ['web/js/frontend/modernizr-output.js', 'web/js/frontend/projectname.frontend.js']
                }
            }
        },


        // Optimize images
        // Dist optimizes the project static files
        // Upload optimizes the images uploaded :)
        imagemin: {
            options: {
                cache: false,
                use: [jpegtran(), optipng()]
            },
            dist: {
                files: [{
                    expand: true,
                    cwd: 'web/images/',
                    src: ['**/*.{png,jpg}'],
                    dest: 'web/dist/images/'
                }]
            },
            uploads: {
                files: [{
                    expand: true,
                    cwd: 'web/uploads/images/',
                    src: ['**/*.{png,jpg}'],
                    dest: 'web/uploads/images/'
                }]
            }
        },

        // If need to optimize svgs
        svgmin: {
            dist: {
                files: [{
                    expand: true,
                    cwd: 'web/images/',
                    src: ['**/*.svg'],
                    dest: 'web/dist/images/'
                }]
            }
        },

        // Compile sass with Compass
        compass: {
            dev: {
                options: {
                    config: 'config.rb'
                }
            },
            dist: {
                options: {
                    config: 'config_dist.rb'
                }
            }
        },

        // Postcss
        postcss: {
            dev: {
                options: {
                    processors: [
                        require('pixrem')(), // add fallbacks for rem units
                        require('autoprefixer') // add vendor prefixes
                    ]
                },
                src: 'web/css/main.css'
            },
            dist: {
                options: {
                    processors: [
                        require('pixrem')(), // add fallbacks for rem units
                        require('autoprefixer'), // add vendor prefixes
                        require('cssnano')() // minify the result
                    ]
                },
                src: 'web/dist/css/main.css'
            }

        },

        // Watch changes in files
        watch: {
            files: "web/scss/**/*.scss",
            tasks: ['compass:dev','postcss:dev'],
            options: {
                spawn: false

            }
        }

    });


    // For dev purpose
    grunt.registerTask('dev', ['compass:dev','postcss:dev']);

    // Minify websites images and uploaded ones
    grunt.registerTask('minify', ['imagemin', 'svgmin']);

    // Js tasks
    grunt.registerTask('js', ['modernizr','jshint','uglify']);

    // Css dev tasks
    grunt.registerTask('css', ['compass','postcss']);

    // Css dist tasks
    grunt.registerTask('css-dist', ['compass:dist','postcss:dist']);

    // Production tasks
    grunt.registerTask('dist', ['clean','copy','minify','js', 'css-dist']);

};