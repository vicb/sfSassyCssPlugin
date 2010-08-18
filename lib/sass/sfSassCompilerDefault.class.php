<?php
/*
 * This file is part of the sfSassyCssPlugin.
 * (c) 2010 Victor Berchet <http://github.com/vicb>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfSassCompilerArgs Compiles using the provided or default arguments
 *
 * @package    sfSassyCssPlugin
 * @subpackage sass
 * @author     Victor Berchet
 */
class sfSassCompilerDefault extends sfSassCompilerBase
{
  private static $instance;

  /**
   * Return the instance of the compiler
   *
   * @param sfEventDispatcher $dispatcher Event dispatcher
   * @return sfSassCompiler The instance
   */
  public static function getInstance(sfEventDispatcher $dispatcher)
  {
    if (!self::$instance)
    {
      self::$instance = new sfSassCompilerDefault($dispatcher);
    }
    return self::$instance;
  }

  /**
   * Compile the source files and fix permissions
   *
   * @param string $in      Input directory containing sass files
   * @param string $out     Output directory where to write the css files
   * @param string $cache   Cache folder (null if cache is not used)
   * @param array  $params  Sass compiler parameters
   */
  public function compile($in, $out, $cache, array $params = array())
  {
    parent::compile($in, $out, $cache, array_merge($this->getParameters(), $params));
  }

  /**
   * Build the parameters for the sass compiler
   *
   * @return array Array of parameters
   */
  protected function getParameters()
  {
    $params = array();

    // format
    if (strtolower(sfConfig::get('app_sfSassyCssPlugin_format')) == 'scss')
    {
      $params[] = '--scss';
    }

    // cache
    if (sfConfig::get('app_sfSassyCssPlugin_cache'))
    {
      $params[] = sprintf('--cache-location "%s"', sfConfig::get('app_sfSassyCssPlugin_cache_dir'));
    }
    else
    {
      $params[] = '--no-cache';
    }

    // output style
    $params[] = sprintf('--style %s', sfConfig::get('app_sfSassyCssPlugin_style'));

    // encoding
    $params[] = sprintf('-E "%s"', sfConfig::get('app_sfSassyCssPlugin_encoding'));

    // debug
    if (sfConfig::get('app_sfSassyCssPlugin_trace'))
    {
      $params[] = '--trace';
    }
    if (sfConfig::get('app_sfSassyCssPlugin_debug_info'))
    {
      $params[] = '--debug-info';
    }
    if (sfConfig::get('app_sfSassyCssPlugin_line_numbers'))
    {
      $params[] = '--line-numbers';
    }
    if (sfConfig::get('app_sfSassyCssPlugin_line_comments'))
    {
      $params[] = '--line-comments';
    }

    // include path
    foreach (sfConfig::get('app_sfSassyCssPlugin_include_dirs') as $path)
    {
      $params[] = sprintf('--load-path "%s"', $path);
    }

    return $params;
  }

}