/*
 * grunt-contrib-less
 * http://gruntjs.com/
 *
 * Copyright (c) 2013 Tyler Kellen, contributors
 * Licensed under the MIT license.
 */

'use strict';

module.exports = function(grunt) {

    getVersion( 'theme_map/' , grunt);

    grunt.initConfig({
        theme: grunt.file.readJSON('themes.json'),
        shell: {
            compress: {},
            xgettext: {}
        },
        replace: {

        }
    });

//    grunt.registerTask('copy_'+theme.slug, function() {
//        grunt.log.writeln('Generating theme for <<'+theme.slug+'>>');
//
//        // Copy theme files
//        grunt.log.writeln('Copy theme files');
//        grunt.file.recurse('theme_map/', function(abspath, rootdir, subdir, filename) {
//            osc_copy(abspath, rootdir, subdir, filename, grunt, theme.slug);
//        });
//
//        // Copy theme specific files
//        grunt.log.writeln('Copy theme specific files');
//        grunt.file.recurse('data/'+theme.slug, function(abspath, rootdir, subdir, filename) {
//            osc_copy(abspath, rootdir, subdir, filename, grunt, theme.slug);
//        });
//    });



//    grunt.registerTask('build_spain', ['copy', 'shell:gettext', 'shell:compress']);


    var themeObj = grunt.config.get('theme');
    for ( var key in themeObj ) {
        var theme = themeObj[key];

        grunt.config( 'copy.theme_'+ theme.slug , {
            files: [
                {
                    expand: true,
                    cwd: 'theme_map/',
                    src: '**',
                    dest: 'tmp/'+ theme.slug +'/'  // no se añade
                }
            ]
        });

        // zip destination
        var archive = '../packages/theme_'+ theme.slug + '_'+(grunt.option('theme_version') || '1.0.0')+'.zip';
        // shell gettext + compress
        grunt.config( 'shell.compress_'+ theme.slug , {
            command : 'cd tmp/; zip -r ' + archive + ' ' + theme.slug + '; rm -rf ' + theme.slug ,
            options: {
                stdout: true
            }
        });
        var varaux = theme.slug;
        varaux = varaux.toUpperCase() + '_THEME_VERSION';

        // replace theme strings
        grunt.config( 'replace.theme_name_'+ theme.slug , {
            src: ['tmp/'+theme.slug+'/*.php'],
            overwrite: true,                 // overwrite matched source files
            replacements: [{
                from: 'theme_map',
                to: theme.slug
            },{
                from: 'theme_country_title',
                to: theme.slug
            },{
                from: '_theme_version',
                to: varaux
            }]
        });

        // generate po files & mo files
        grunt.config( 'shell.gettext_' + theme.slug, {
            command : 'xgettext --from-code=UTF-8 -k_n -k_e -k__ --package-name="<% theme.slug %> - theme map" --msgid-bugs-address="info@osclass.org" --package-version="'+grunt.option('theme_version')+'" -o default.po $(find tmp/<% theme.slug %>/. -name "*.php") && msginit --no-translator -l en_US.UTF-8 -o theme.po -i default.po ; msgfmt -o theme.mo theme.po;/\n\
                       cp -f theme.po theme.mo tmp/<% theme.slug %>/languages/en_US/; rm -f theme.po default.po theme.mo',
            options: {
                stdout: true
            }
        });

        grunt.registerTask('build:'+theme.slug , ['copy:theme_'+theme.slug, 'replace:theme_name_'+theme.slug, 'shell:gettext_'+theme.slug, 'shell:compress_'+theme.slug]);
    }

    grunt.registerTask('build', ['build:spain', 'build:italia', 'build:brasil', 'build:india', 'build:usa']);

    grunt.loadNpmTasks('grunt-shell');
    grunt.loadNpmTasks('grunt-gettext');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-text-replace');

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
        content = content.replace(/theme_map/g, theme);
        grunt.file.write('tmp/'+theme+'/'+(subdir!=undefined?(subdir+'/'):'/')+filename, content);
    } else {
        grunt.file.copy(abspath, 'tmp/'+theme+'/'+(subdir!=undefined?(subdir+'/'):'/')+filename);
    };
}

function getVersion(abspath, grunt) {
    var content = grunt.file.read(abspath + '/index.php');
    var version = content.match(/version\s*:\s*([0-9\.]+)/i);
    if(version!=null) {
        grunt.option('theme_version', version[1]);
    }
}