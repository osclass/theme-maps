/*
 * grunt-contrib-less
 * http://gruntjs.com/
 *
 * Copyright (c) 2013 Tyler Kellen, contributors
 * Licensed under the MIT license.
 */

'use strict';

module.exports = function(grunt) {

    grunt.initConfig({
        archive: 'packages/theme_'+ grunt.option('theme') + '_'+(grunt.option('theme_version') || '1.0.0')+'.zip',
        aux_theme: grunt.option('theme'),
        shell: {
            compress: {
                command : 'zip -r <%- archive %> tmp/<%- aux_theme %>',
                options: {
                    stdout: true
                }
            }
        }
    });

    grunt.registerTask('copy', function() {
        var theme = grunt.option('theme');
        if(theme!=undefined) {
            var themes = new Array('brasil', 'india', 'italia', 'spain', 'usa');
            if(themes.indexOf(theme)!==-1) {
                grunt.log.writeln('Generating theme for <<'+theme+'>>');

                // Copy theme files
                grunt.log.writeln('Copy theme files');
                grunt.file.recurse('theme_map/', function(abspath, rootdir, subdir, filename) {
                    osc_copy(abspath, rootdir, subdir, filename, grunt, theme);
                });

                // Copy theme specific files
                grunt.log.writeln('Copy theme specific files');
                grunt.file.recurse('data/'+theme, function(abspath, rootdir, subdir, filename) {
                    osc_copy(abspath, rootdir, subdir, filename, grunt, theme);
                });
            } else {
                grunt.log.writeln('The theme "'+theme+'" is not a valid one');
            }
        } else {
            grunt.log.writeln('No theme set, use "grunt build -theme=name_of_the_theme"');
        }
    });

    grunt.registerTask('build', ['copy', 'shell']);

    grunt.loadNpmTasks('grunt-shell');

};

function osc_copy(abspath, rootdir, subdir, filename, grunt, theme) {
    if(filename.substr(-3,3)=='php') {
        var content = grunt.file.read(abspath);
        if(filename=='index.php') {
            var version = content.match(/version\s*:\s*([0-9\.]+)/i);
            if(version!=null) {
                content = content.replace('theme_country_title', theme);
                grunt.option('theme_version', version[1]);
            }
        }
        content = content.replace('theme_map', theme);
        grunt.file.write('tmp/'+theme+'/'+(subdir!=undefined?(subdir+'/'):'/')+filename, content);
    } else {
        grunt.file.copy(abspath, 'tmp/'+theme+'/'+(subdir!=undefined?(subdir+'/'):'/')+filename);
    };
}
