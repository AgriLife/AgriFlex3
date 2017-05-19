![Master branch](https://codeship.com/projects/d0772a40-d81f-0133-7930-2a00185afc36/status?branch=master) on Master branch

![Staging branch](https://codeship.com/projects/d0772a40-d81f-0133-7930-2a00185afc36/status?branch=staging) on Staging branch

# AgriFlex3 (WordPress Theme) by AgriLife Communications

## WordPress Requirements

1. Genesis theme
2. Advanced Custom Fields 5+ plugin
3. [AgriLife Core plugin](https://github.com/agrilife/agrilife-core)

## Optional Site Requirements

* AgriLife College plugin, for sites representing the College of Agriculture and Life Sciences
* AgriLife Extension Unit plugin, for sites representing an AgriLife Extension Unit

## Installation

1. [Download the latest release](https://github.com/AgriLife/AgriFlex3/releases/latest)
2. Upload the plugin to your site

## Features

* Responsive layout and navigation for desktop, tablet, and mobile devices
* Required header and footer links for AgriLife websites
* Multiple page layouts
* Post and page archives that show full or excerpted content, with or without a featured image
* Widget Areas
    * Header Right: Positioned in the header on the right side for desktop browsers, and inside the collapsible menu on mobile browsers
    * Primary Sidebar: Positioned next to a page's content depending on which page layout it uses
* Theme customization for background images on Extension and Extension Unit sites
* Page Layouts:

![Content Sidebar](http://agrilife.org/fishnutrition/wp-content/themes/genesis/lib/admin/images/layouts/cs.gif)
![Sidebar Content](http://agrilife.org/fishnutrition/wp-content/themes/genesis/lib/admin/images/layouts/sc.gif)
![Content](http://agrilife.org/fishnutrition/wp-content/themes/genesis/lib/admin/images/layouts/c.gif)

## Development Installation

1. Copy this repo to the desired location.
2. In your terminal, navigate to the plugin location 'cd /path/to/the/plugin'
3. Run 'composer install' to set up the php modules
4. Run 'npm install' to set up the grunt modules
5. Run 'bower install html5shiv#3.7.0' and 'bower install respond#1.4.1' to install JS dependencies
6. Run 'grunt watch' and do what you do best

## Development Notes

1. If you add Compass files, be sure to add them to the 'Gruntfile'.
2. Release tasks can only be used by the repository's owners

## Development Requirements

* Node: http://nodejs.org/
* NPM: https://npmjs.org/
* Grunt: http://gruntjs.com/
* Ruby: http://www.ruby-lang.org/en/
* Ruby Gems: http://rubygems.org/
* Compass: http://compass-style.org/
* Bower: http://bower.io/
