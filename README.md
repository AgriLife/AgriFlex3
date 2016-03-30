## System Requirements

* [Node][node]
* [NPM][npm]
* [Grunt][grunt]
* [Ruby][ruby]
* [Ruby Gems][gems]
* [Compass][compass]
* [Bower][bower]

## WordPress Site Requirements

* Genesis Theme
* AgriLife Core Plugin
* Advanced Custom Fields Plugin
 
## Installation and Use

1. Clone this repo to the desired location.
2. In your terminal, navigate to the plugin location `cd /path/to/the/plugin`
3. Run `npm install` to set up the grunt modules
4. Run `bower install` to install JS dependencies
5. Run `grunt watch` and do what you do best
 
*NOTE:* If you add Compass or Coffeescript files, be sure to add them to the `Gruntfile`.

[node]: http://nodejs.org/
[npm]: https://npmjs.org/
[grunt]: http://gruntjs.com/
[ruby]: http://www.ruby-lang.org/en/
[gems]: http://rubygems.org/
[compass]: http://compass-style.org/
[bower]: http://bower.io/

## Codeship Status
![master branch](https://codeship.com/projects/8a885560-d8b2-0133-1e96-3ab00a090344/status?branch=master)