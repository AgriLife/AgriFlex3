module.exports = (grunt) ->
  @initConfig
    pkg: @file.readJSON('package.json')
    watch:
      files: [
        'css/src/*.scss'
      ]
      tasks: ['develop']
    compass:
      pkg:
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
    jsvalidate:
      options:
        globals:
          jQuery: true
          console: true
          module: true
          document: true
      targetName:
        files:
          src: [
            'js/*.js',
            'bower_components/foundation/js/vendor/fastclick.js',
            'bower_components/foundation/js/foundation/foundation?(.topbar).js',
            'bower_components/modernizr/modernizr.js',
            'bower_components/jquery/{dist,sizzle}/**/*.js',
            'bower_components/jquery-placeholder/*.js',
            'bower_components/jquery.cookie/jquery.cookie.js',
            'bower_components/respond/{cross-domain,dest}/*.js',
            'bower_components/html5shiv/dist/html5shiv.js'
          ]
    sasslint:
      options:
        configFile: '.sass-lint.yml'
      target: ['css/src/**/*.s+(a|c)ss']
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
          {src: ['bower_components/modernizr/modernizr.js']},
          {src: ['bower_components/jquery/{dist,sizzle}/**/*.js']},
          {src: ['bower_components/jquery-placeholder/*.js']},
          {src: ['bower_components/jquery.cookie/jquery.cookie.js']},
          {src: ['bower_components/respond/{cross-domain,dest}/*.js']},
          {src: ['bower_components/html5shiv/dist/html5shiv.js']},
          {src: ['vendor/**', '!vendor/composer/autoload_static.php']},
          {src: ['functions.php']},
          {src: ['README.md']},
          {src: ['rtl.css']},
          {src: ['screenshot.png']},
          {src: ['search.php']},
          {src: ['style.css']}
        ]
    gh_release:
      options:
        token: process.env.RELEASE_KEY
        owner: 'agrilife'
        repo: '<%= pkg.name %>'
      release:
        tag_name: '<%= pkg.version %>'
        target_commitish: 'master'
        name: 'Release'
        body: 'Release'
        draft: false
        prerelease: false
        asset:
          name: '<%= pkg.name %>.zip'
          file: '<%= pkg.name %>.zip'
          'Content-Type': 'application/zip'

  @loadNpmTasks 'grunt-contrib-compass'
  @loadNpmTasks 'grunt-jsvalidate'
  @loadNpmTasks 'grunt-contrib-watch'
  @loadNpmTasks 'grunt-contrib-compress'
  @loadNpmTasks 'grunt-gh-release'
  @loadNpmTasks 'grunt-sass-lint'

  @registerTask 'default', ['compass:pkg']
  @registerTask 'develop', ['sasslint', 'compass:dev', 'jsvalidate']
  @registerTask 'package', ['compass:pkg', 'jsvalidate']
  @registerTask 'release', ['compress', 'setreleasemsg', 'gh_release']
  @registerTask 'setreleasemsg', 'Set release message as range of commits', ->
    done = @async()
    grunt.util.spawn {
      cmd: 'git'
      args: [ 'tag' ]
    }, (err, result, code) ->
      if result.stdout isnt ''
        matches = result.stdout.match /([^\n]+)$/
        grunt.config.set 'lasttag', matches[1]
        grunt.task.run 'shortlog'
      done(err)
      return
    return
  @registerTask 'shortlog', 'Set gh_release body with commit messages since last release', ->
    done = @async()
    releaserange = grunt.template.process '<%= lasttag %>..HEAD'
    grunt.util.spawn {
      cmd: 'git'
      args: ['shortlog', releaserange, '--no-merges']
    }, (err, result, code) ->
      if result.stdout isnt ''
        message = result.stdout.replace /(\n)\s\s+/g, '$1- '
        grunt.config 'gh_release.release.body', message
      done(err)
      return
    return

  @event.on 'watch', (action, filepath) =>
    @log.writeln('#{filepath} has #{action}')
