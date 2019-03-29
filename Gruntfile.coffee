module.exports = (grunt) ->
  @initConfig
    pkg: @file.readJSON('package.json')
    release:
      branch: ''
      repofullname: ''
      lasttag: ''
      msg: ''
      post: ''
      url: ''
    watch:
      files: [
        'css/src/*.scss'
      ]
      tasks: ['sasslint', 'compass:dev']
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
          {src: ['js/public.min.js']},
          {src: ['bower_components/fastclick/lib/fastclick.js']},
          {src: ['bower_components/foundation/{css,js}/**']},
          {src: ['bower_components/modernizr/modernizr.js']},
          {src: ['bower_components/jquery/{dist,sizzle}/**/*.js']},
          {src: ['bower_components/jquery-placeholder/*.js']},
          {src: ['bower_components/jquery.cookie/jquery.cookie.js']},
          {src: ['bower_components/respond/{cross-domain,dest}/*.js']},
          {src: ['bower_components/html5shiv/dist/html5shiv.js']},
          {src: ['vendor/**']},
          {src: ['functions.php']},
          {src: ['README.md']},
          {src: ['rtl.css']},
          {src: ['screenshot.png']},
          {src: ['search.php']},
          {src: ['style.css']}
        ]

  @loadNpmTasks 'grunt-contrib-compass'
  @loadNpmTasks 'grunt-jsvalidate'
  @loadNpmTasks 'grunt-contrib-watch'
  @loadNpmTasks 'grunt-contrib-compress'
  @loadNpmTasks 'grunt-gh-release'
  @loadNpmTasks 'grunt-sass-lint'

  @registerTask 'default', ['compass:pkg', 'jsvalidate']
  @registerTask 'develop', ['sasslint', 'compass:dev', 'jsvalidate']
  @registerTask 'release', ['compress', 'makerelease']
  @registerTask 'makerelease', 'Set release branch for use in the release task', ->
    done = @async()

    # Define simple properties for release object
    grunt.config 'release.key', process.env.RELEASE_KEY
    grunt.config 'release.file', grunt.template.process '<%= pkg.name %>.zip'

    grunt.util.spawn {
      cmd: 'git'
      args: [ 'rev-parse', '--abbrev-ref', 'HEAD' ]
    }, (err, result, code) ->
      if result.stdout isnt ''
        matches = result.stdout.match /([^\n]+)$/
        grunt.config 'release.branch', matches[1]
        grunt.task.run 'setrepofullname'

      done(err)
      return
    return
  @registerTask 'setrepofullname', 'Set repo full name for use in the release task', ->
    done = @async()

    # Get repository owner and name for use in Github REST requests
    grunt.util.spawn {
      cmd: 'git'
      args: [ 'config', '--get', 'remote.origin.url' ]
    }, (err, result, code) ->
      if result.stdout isnt ''
        grunt.log.writeln 'Remote origin url: ' + result
        matches = result.stdout.match /([^\/:]+)\/([^\/.]+)(\.git)?$/
        grunt.config 'release.repofullname', matches[1] + '/' + matches[2]
        grunt.task.run 'setlasttag'

      done(err)
      return
    return
  @registerTask 'setlasttag', 'Set release message as range of commits', ->
    done = @async()

    grunt.util.spawn {
      cmd: 'git'
      args: [ 'describe', '--tags' ]
    }, (err, result, code) ->
      if result.stdout isnt ''
        matches = result.stdout.match /([^\n]+)/
        grunt.config 'release.lasttag', matches[1] + '..'

      grunt.task.run 'setmsg'

      done(err)
      return
    return
  @registerTask 'setmsg', 'Set gh_release body with commit messages since last release', ->
    done = @async()

    releaserange = grunt.template.process '<%= release.lasttag %>HEAD'

    grunt.util.spawn {
      cmd: 'git'
      args: ['shortlog', releaserange, '--no-merges']
    }, (err, result, code) ->
      msg = grunt.template.process 'Upload <%= release.file %> to your dashboard.'

      if result.stdout isnt ''
        message = result.stdout.replace /(\n)\s\s+/g, '$1- '
        message = message.replace /\s*\[skip ci\]/g, ''
        msg += '\n\n# Changes\n' + message

      grunt.config 'release.msg', msg
      grunt.task.run 'setpostdata'
      done(err)
      return
    return
  @registerTask 'setpostdata', 'Set post object for use in the release task', ->
    val =
      tag_name: 'v' + grunt.config.get 'pkg.version'
      name: grunt.template.process '<%= pkg.name %> (v<%= pkg.version %>)'
      body: grunt.config.get 'release.msg'
      draft: false
      prerelease: false
      # target_commitish: grunt.config.get 'release.branch'
    grunt.config 'release.post', JSON.stringify val
    grunt.log.write JSON.stringify val

    grunt.task.run 'createrelease'
    return
  @registerTask 'createrelease', 'Create a Github release', ->
    done = @async()

    # Create curl arguments for Github REST API request
    args = ['-X', 'POST', '--url']
    args.push grunt.template.process 'https://api.github.com/repos/<%= release.repofullname %>/releases?access_token=<%= release.key %>'
    args.push '--data'
    args.push grunt.config.get 'release.post'
    grunt.log.write 'curl args: ' + args

    # Create Github release using REST API
    grunt.util.spawn {
      cmd: 'curl'
      args: args
    }, (err, result, code) ->
      grunt.log.write '\nResult: ' + result + '\n'
      grunt.log.write 'Error: ' + err + '\n'
      grunt.log.write 'Code: ' + code + '\n'

      if result.stdout isnt ''
        obj = JSON.parse result.stdout
        # Check for error from Github
        if 'errors' of obj and obj['errors'].length > 0
          grunt.fail.fatal 'Github Error'
        else
          # We need the resulting "release" ID value before we can upload the .zip file to it.
          grunt.config 'release.id', obj.id
          grunt.task.run 'uploadreleasefile'

      done(err)
      return
    return
  @registerTask 'uploadreleasefile', 'Upload a zip file to the Github release', ->
    done = @async()

    # Create curl arguments for Github REST API request
    args = ['-X', 'POST', '--header', 'Content-Type: application/zip', '--upload-file']
    args.push grunt.config.get 'release.file'
    args.push '--url'
    args.push grunt.template.process 'https://uploads.github.com/repos/<%= release.repofullname %>/releases/<%= release.id %>/assets?access_token=<%= release.key %>&name=<%= release.file %>'
    grunt.log.write 'curl args: ' + args

    # Upload Github release asset using REST API
    grunt.util.spawn {
      cmd: 'curl'
      args: args
    }, (err, result, code) ->
      grunt.log.write '\nResult: ' + result + '\n'
      grunt.log.write 'Error: ' + err + '\n'
      grunt.log.write 'Code: ' + code + '\n'

      if result.stdout isnt ''
        obj = JSON.parse result.stdout
        # Check for error from Github
        if 'errors' of obj and obj['errors'].length > 0
          grunt.fail.fatal 'Github Error'

      done(err)
      return
    return

  @event.on 'watch', (action, filepath) =>
    @log.writeln('#{filepath} has #{action}')
