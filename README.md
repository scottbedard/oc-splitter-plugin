# oc-splitter-plugin

> **Warning:** This plugin is a work in progress, and is not ready for use by anyone.

[![Build Status](https://travis-ci.org/scottbedard/oc-splitter-plugin.svg?branch=master)](https://travis-ci.org/scottbedard/oc-splitter-plugin)

### Installation
First, make sure your installation of October CMS is up and running with [scheduled tasks up](http://octobercms.com/docs/help/installation#crontab-setup). The easiest way to install this plugin is via the [October CMS plugins page](http://octobercms.com). Alternatively, you can install the plugin manually by executing `git clone https://github.com/scottbedard/oc-splitter-plugin.git bedard/splitter` from your `/plugins` directory.

Once the plugin is installed, the only remaining setup required is attaching the `Splitter` component to your layout files.

### Usage
To begin a new split test, navigate to your backend CMS area, and select a partial you wish to improve. From here, simply click the `Split Test` tab, and enter in your version A and version B markup. You may also specify a duration and a name for this split test.

Once a split test is completed, the partial will be automatically updated with permenant content to reflect the winning version.
