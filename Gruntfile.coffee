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
    sasslint:
      options:
        configFile: '.sass-lint.yml'
      target: ['css/src/**/*.s+(a|c)ss']
    concat:
      adminjs:
        src: ['js/src/admin/*.js']
        dest: 'js/admin.min.js'
      publicjs:
        src: ['js/src/public/*.js']
        dest: 'js/public.min.js'
    compress:
      main:
        options:
          archive: 'AgriFlex3.zip'
        files: [
          {src: ['AgriFlex/*.php']},
          {src: ['css/*.css']},
          {src: ['img/**']},
          {src: ['js/*.js']},
          {src: ['bower_components/fastclick/lib/fastclick.js']},
          {src: ['bower_components/foundation/{css,js}/**']},
          {src: ['bower_components/html5shiv/dist/html5shiv.js']},
          {src: ['bower_components/jquery/{dist,sizzle}/**/*.js']},
          {src: ['bower_components/jquery-placeholder/*.js']},
          {src: ['bower_components/jquery.cookie/jquery.cookie.js']},
          {src: ['bower_components/modernizr/modernizr.js']},
          {src: ['bower_components/respond/{cross-domain,dest}/*.js']},
          {src: ['vendor/**', '!vendor/composer/autoload_static.php']},
          {src: ['functions.php']},
          {src: ['README.md']},
          {src: ['rtl.css']},
          {src: ['screenshot.png']},
          {src: ['search.php']},
          {src: ['style.css']}
        ]
    gitinfo:
      commands:
        'lastUpdate': ['log', '-1', '--pretty=format:"%s"', '--no-merges']
    gh_release:
      options:
        token: process.env.RELEASE_KEY
        owner: 'agrilife'
        repo: grunt.file.readJSON('package.json').name
      release:
        tag_name: grunt.file.readJSON('package.json').version
        target_commitish: 'master'
        name: 'Release'
        body: '<%= gitinfo.lastUpdate %>'
        draft: false
        prerelease: false
        asset:
          name: 'AgriFlex3.zip'
          file: 'AgriFlex3.zip'
          'Content-Type': 'application/zip'

  @loadNpmTasks 'grunt-contrib-coffee'
  @loadNpmTasks 'grunt-contrib-compass'
  @loadNpmTasks 'grunt-contrib-jshint'
  @loadNpmTasks 'grunt-contrib-concat'
  @loadNpmTasks 'grunt-contrib-watch'
  @loadNpmTasks 'grunt-contrib-compress'
  @loadNpmTasks 'grunt-gh-release'
  @loadNpmTasks 'grunt-sass-lint'
  @loadNpmTasks 'grunt-gitinfo'

  @registerTask 'default', ['coffee', 'compass:dist']
  @registerTask 'develop', ['sasslint', 'compass:dev', 'coffee', 'jshint', 'concat']
  @registerTask 'package', ['default', 'jshint', 'concat']
  @registerTask 'release', ['gitinfo', 'package', 'compress', 'gh_release']

  @event.on 'watch', (action, filepath) =>
    @log.writeln('#{filepath} has #{action}')