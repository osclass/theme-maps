'use strict';

module.exports = function(grunt) {

    grunt.initConfig({
        theme: grunt.file.readJSON('themes.json'),
        shell: {
            compress: {},
            xgettext: {}
        },
        replace: {

        }
    });

    var themeObj   = grunt.config.get('theme');
    var packageObj = grunt.file.readJSON('package.json');

    for ( var key in themeObj ) {
        var theme = themeObj[key];
        var version = packageObj.version_modern;
        if(theme.slug!="modern") {
            version = packageObj.version_modern_maps;
        }

        grunt.config( 'copy.theme_'+ theme.slug , {
            files: [
                {
                    expand: true,
                    cwd: 'theme_map/',
                    src: '**',
                    dest: 'tmp/'+ theme.slug +'/'
                },
                {
                    expand: true,
                    cwd: 'data/'+theme.slug,
                    src: '**',
                    dest: 'tmp/'+theme.slug+'/'
                }
            ]
        });

        // zip destination
        var archive = '../packages/theme_'+ theme.slug + '_'+(version || '1.0.0')+'.zip';
        // shell gettext + compress
        grunt.config( 'shell.compress_'+ theme.slug , {
            command : 'cd tmp/; zip -r ' + archive + ' ' + theme.slug + '; rm -rf ' + theme.slug ,
            options: {
                stdout: false
            }
        });


        var varaux = theme.slug;
        varaux = varaux.toUpperCase() + '_THEME_VERSION';


        // replace theme strings
        grunt.config( 'replace.theme_name_'+ theme.slug , {
            src: ['tmp/'+theme.slug+'/*.php', 'tmp/'+theme.slug+'/admin/*.php'],
            overwrite: true,                 // overwrite matched source files
            replacements: [{
                from: '_theme_maps_n_regions',
                to: theme.n_regions
            },{
                from: 'theme_map',
                to: theme.slug
            },{
                from: 'theme_country_title',
                to: theme.slug
            },{
                from: '_theme_version_const',
                to: varaux
            },{
                from: '_theme_version_number',
                to: version
            }]
        });


        // generate po files & mo files
        grunt.config( 'shell.gettext_' + theme.slug, {

            command : 'xgettext --from-code=UTF-8 -k_n:1,2 -k_e -k__ --package-name="'+theme.slug+' - theme map" --msgid-bugs-address="info@osclass.org" --package-version="'+version+'" -o temp.po $(find tmp/'+theme.slug+'/. -name "*.php") && sed \'s/CHARSET/UTF-8/\' temp.po > default.po ;msginit --no-translator --locale=en_US.UTF-8 -o theme.po -i default.po ;msgfmt -o theme.mo theme.po; cp -f theme.po theme.mo tmp/'+theme.slug+'/languages/en_US; rm -f theme.po theme.mo temp.po default.po',
            options: {
                stdout: true,
                stderr: true
            }
        });

        if(theme.slug=="modern") {
           // if modern theme, minor changes on building
           grunt.registerTask('build:'+theme.slug , ['copy:theme_'+theme.slug, 'replace:theme_name_'+theme.slug, 'shell:gettext_'+theme.slug, 'shell:compress_'+theme.slug]);
        } else {
            grunt.registerTask('build:'+theme.slug , ['copy:theme_'+theme.slug, 'replace:theme_name_'+theme.slug, 'shell:gettext_'+theme.slug, 'shell:compress_'+theme.slug]);
        }
    }


    grunt.registerTask('build', ['build:modern', 'build:spain', 'build:italia', 'build:brasil', 'build:india', 'build:usa']);

    grunt.loadNpmTasks('grunt-shell');
    grunt.loadNpmTasks('grunt-gettext');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-text-replace');

};