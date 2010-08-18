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
      $this->dispatcher->connect('context.load_factories', array($this, 'compileSass'));

      if (sfConfig::get('sf_web_debug') && sfConfig::get('app_sfSassyCssPlugin_toolbar'))
      {
        $this->dispatcher->connect('debug.web.load_panels', array(
          'sfSassyWebDebugPanel',
          'listenToLoadDebugWebPanelEvent'
        ));
      }
    }    
  }

  public function compileSass()
  {
    $compiler = sfSassCompilerDefault::getInstance($this->dispatcher);

    $compiler->compile(
      sfConfig::get('app_sfSassyCssPlugin_input_dir'),
      sfConfig::get('app_sfSassyCssPlugin_output_dir'),
      sfConfig::get('app_sfSassyCssPlugin_cache_dir')
    );
  }

}
