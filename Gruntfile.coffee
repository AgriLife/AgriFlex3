module.exports = (grunt) ->
  @initConfig
    pkg: @file.readJSON('package.json')
    watch:
      files: [
        'js/src/admin/coffee/**.coffee',
        'js/src/public/coffee/**.coffee',
        'js/src/admin/*.js',
        'js/src/public/*.js',
        'css/src/*.scss'
      ]
      tasks: ['develop']
    coffee:
      compileAdmin:
        options:
          bare: true
          sourceMap: true
        expand: true
        cwd: 'js/src/admin/coffee'
        src: ['*.coffee']
        dest: 'js/src/admin/'
        ext: '.js'
      compilePublic:
        options:
          bare: true
          sourceMap: true
        expand: true
        cwd: 'js/src/public/coffee'
        src: ['*.coffee']
        dest: 'js/src/public/'
        ext: '.js'
    compass:
      dist:
        options:
          config: 'config.rb'
          force: true
      dev:
        options:
          config: 'config.rb'
          force: true
          outputStyle: 'expanded'
          sourcemap: true
          noLineComments: true
    jshint:
      files: ['js/src/admin/*.js', 'js/src/public/*.js']
      options:
        globals:
          jQuery: true
          console: true
          module: true
          document: true
        force: true
    csslint:
      options:
        'star-property-hack': false
        'duplicate-properties': false
        'unique-headings': false
        # 'ids': false
        'display-property-grouping': false
        'floats': false
        'outline-none': false
        'box-model': false
        'adjoining-classes': false
        'box-sizing': false
        'universal-selector': false
        'font-sizes': false
        'overqualified-elements': false
        force: true
      src: ['css/*.css']
    concat:
      adminjs:
        src: ['js/src/admin/*.js']
        dest: 'js/admin.min.js'
      publicjs:
        src: ['js/src/public/*.js']
        dest: 'js/public.min.js'

  @loadNpmTasks 'grunt-contrib-coffee'
  @loadNpmTasks 'grunt-contrib-compass'
  @loadNpmTasks 'grunt-contrib-jshint'
  @loadNpmTasks 'grunt-contrib-csslint'
  @loadNpmTasks 'grunt-contrib-concat'
  @loadNpmTasks 'grunt-contrib-watch'

  @registerTask 'default', ['coffee', 'compass:dist']
  @registerTask 'develop', ['coffee', 'compass:dev', 'jshint', 'concat']
  @registerTask 'package', ['default', 'jshint', 'concat']

  @event.on 'watch', (action, filepath) =>
    @log.writeln('#{filepath} has #{action}')