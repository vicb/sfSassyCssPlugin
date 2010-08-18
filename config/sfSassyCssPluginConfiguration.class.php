<?php
/*
 * This file is part of the sfSassyCssPlugin.
 * (c) 2010 Victor Berchet <http://github.com/vicb>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfSassyCssPlugin configuration
 *
 * @package    sfSassyCssPlugin
 * @subpackage configuration
 * @author     Victor Berchet
 */
class sfSassyCssPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    if (sfConfig::get('app_sfSassyCssPlugin_enabled'))
    {
      if (sfConfig::get('app_sfSassyCssPlugin_compile_all'))
      {
        $this->dispatcher->connect('context.load_factories', array($this, 'compileSassFolder'));
      }
      else
      {
        $this->dispatcher->connect('sass.include_css', array($this, 'compileSassFiles'));
      }

      if (sfConfig::get('sf_web_debug') && sfConfig::get('app_sfSassyCssPlugin_toolbar'))
      {
        $this->dispatcher->connect('debug.web.load_panels', array(
          'sfSassyWebDebugPanel',
          'listenToLoadDebugWebPanelEvent'
        ));
      }
    }    
  }

  public function compileSassFolder()
  {
    $compiler = sfSassCompilerDefault::getInstance($this->dispatcher);

    $compiler->compile(
      sfConfig::get('app_sfSassyCssPlugin_input_dir'),
      $out = sfConfig::get('app_sfSassyCssPlugin_output_dir'),
      sfConfig::get('app_sfSassyCssPlugin_cache_dir')
    );
  }

  public function compileSassFiles(sfEvent $event)
  {
    require_once sfConfig::get('sf_symfony_lib_dir') . '/helper/AssetHelper.php';

    $response = $event->getSubject();
    $files = array();
    $out = sfConfig::get('app_sfSassyCssPlugin_output_dir');
    $in = sfConfig::get('app_sfSassyCssPlugin_input_dir');

    foreach ($response->getStylesheets() as $file => $options)
    {
      $response->removeStylesheet($file);
      // Include the stylesheet with a .less extension
      $parts = explode('?', $file);
      $parts[0] = preg_replace('/.s[ac]ss$/i', '.css', $parts[0]);
      $response->addStylesheet(join('?', $parts));

      // Compute the absolute path of the target file
      $target = sfConfig::get('sf_web_dir') . stylesheet_path($parts[0]);

      // Compute the absolute path of the source file
      $source = preg_replace('/.css$/i', '.' . sfConfig::get('app_sfSassyCssPlugin_format'), $target);
      $source = str_replace('\\', '/', $source);
      $source = str_replace(rtrim($out, '/\\'), rtrim($in, '/\\'), $source);

      if (is_file($source))
      {
        $files[$source] = $target;
      }
    }

    if (count($files))
    {
      $compiler = sfSassCompilerDefault::getInstance($this->dispatcher);
      $compiler->compile($files, $out, sfConfig::get('app_sfSassyCssPlugin_cache_dir'));
    }
  }
  
}
