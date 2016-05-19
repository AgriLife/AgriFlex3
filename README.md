## WordPress Requirements

1. Genesis theme
2. AgriLife Core plugin
3. Advanced Custom Fields plugin

## Optional Site Requirements

* AgriLife College plugin, for sites representing the College of Agriculture and Life Sciences
* AgriLife Extension Unit plugin, for sites representing an AgriLife Extension Unit
 
## Installation

1. Copy this repository to your desktop
2. Do one of the following:
    a. Compress this repository and upload it to your WordPress site as a new theme
    b. Use FTP/SFTP to upload your copy to the *themes* folder of your WordPress directory

## Features

* Responsive layout and navigation for desktop, tablet, and mobile devices
* Required header and footer links for AgriLife websites
* Multiple page layouts
* Post and page archives that show full or excerpted content, with or without a featured image
* Widget Areas
    * Header Right: Positioned in the header on the right side for desktop browsers, and inside the collapsible menu on mobile browsers
    * Primary Sidebar: Positioned next to a page's content depending on which page layout it uses
* Theme customization for background images on Extension and Extension Unit sites

## Development Installation

1. Copy this repo to the desired location.
2. In your terminal, navigate to the plugin location `cd /path/to/the/plugin`
3. Run `composer install` to set up the php modules
4. Run `npm install` to set up the grunt modules
5. Run `bower install` to install JS dependencies
6. Run `grunt watch` and do what you do best
 
*NOTE:* If you add Compass or Coffeescript files, be sure to add them to the `Gruntfile`.

## System Requirements

* [Node][node]: http://nodejs.org/
* [NPM][npm]: https://npmjs.org/
* [Grunt][grunt]: http://gruntjs.com/
* [Ruby][ruby]: http://www.ruby-lang.org/en/
* [Ruby Gems][gems]: http://rubygems.org/
* [Compass][compass]: http://compass-style.org/
* [Bower][bower]: http://bower.io/
