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
* AgriLife Extension Research plugin, for sites representing an AgriLife Extension Research Unit

## Installation

1. [Download the latest release](https://github.com/AgriLife/AgriFlex3/releases/latest)
2. Upload the theme to your site

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
2. In your terminal, navigate to the theme location 'cd /path/to/the/theme'
3. Run "npm start" to install dependencies and build files for a production environment.
4. Or, run "npm start -- develop" to install dependencies and build files for a development environment.

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
