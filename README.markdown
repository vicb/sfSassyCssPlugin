# sfSassyCssPlugin #

sfSassyCssPlugin brings the power of [sass stylesheets](http://sass-lang.com) to symfony

**WARNING: This plugin is in an alpha state. Use with caution ! **

## Requirements ##

 * PHP 5.2.4+
 * Symfony 1.3 or 1.4

## Installation ##

### Installing the sass compiler ###

Follow [the instructions](http://sass-lang.com/tutorial.html).

### Installing sfSassyCssPlugin using git ###

    $ cd /path/to/symfony/project
    $ git clone git://github.com/vicb/sfSassyCssPlugin.git plugins/sfSassyCssPlugin

If your project already uses `git`, you can add the plugin as a submodule:

    $ cd /path/to/symfony/project
    $ git submodule add git://github.com/vicb/sfSassyCssPlugin.git plugins/sfSassyCssPlugin
    $ git submodule update --init
    $ git commit -a -m "added sfSassyCssPlugin submodule"

## Enabling the plugin ##

Edit your `config/ProjectConfiguration.class.php` file to enable the plugin:

    <?php
    class ProjectConfiguration extends sfProjectConfiguration
    {
      public function setup()
      {
        $this->enablePlugins(array(
          // ... other plugin(s)
          'sfSassyCssPlugin',
        ));
      }
    }

## Configuration ##

### Default ###

The default configuration can be found in the `app.yml` configuration file inside the plugin `config` folder:

    all:
      sfSassyCssPlugin:
        enabled:          false               # Wether to trigger sass compilation
        input_dir:        %SF_DATA_DIR%/sass  # Sass source folder
        output_dir:       %SF_WEB_DIR%/css    # Target folder where to generate the files
        format:           scss                # Input format: scss, sass
        include_dirs:     []                  # Array of sass import path.
        cache:            true                # Wether to use the cache
        cache_dir:        %SF_CACHE_DIR%/sass # The path to put cached Sass files
        trace:            false               # Show a full traceback on error
        style:            compact             # Output style: nested, compact, compressed, or expanded.
        debug_info:       false               # Emit extra information in the generated CSS that can be used by the FireSass Firebug plugin.
        line_numbers:     false               # Emit comments in the generated CSS indicating the corresponding sass line.
        line_comments:    false
        toolbar:          false               # Wether to display the debug toolbar

    dev:
      sfSassyCssPlugin:
        enabled:          true                # Wether to trigger sass compilation
        style:            expanded            # Output style: nested, compact, compressed, or expanded.
        toolbar:          true                # Wether to display the debug toolbar


## Usage ##

### In development ###

Using the default configuration the sass stylesheets are generated on each request.
The sass built-in cache helps speeding up the processing.

### In production ###

No automatic compilation is done in production with the default configuration for obvious speed reasons.

You should run the `sass:compile` task instead in order to re-generate your css files.

## Authors and contributors ##

  * sfSassyCssPlugin has been created by [Victor Berchet](http://github.com/vicb)
  * The inspiration and some parts of the code comes from [sfLESSPlugin](http://github.com/everzet/sfLESSPlugin) by [Kudryashov Konstantin](http://everzet.com)

##Changelog ##

### v0.1.0 - 2010-08-17 ###

 * Initial release

