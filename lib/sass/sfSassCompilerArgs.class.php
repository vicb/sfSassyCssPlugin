<?php
/*
 * This file is part of the sfSassyCssPlugin.
 * (c) 2010 Victor Berchet <http://github.com/vicb>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfSassCompilerArgs Compiles using the provided arguments
 *
 * @package    sfSassyCssPlugin
 * @subpackage sass
 * @author     Victor Berchet
 */
class sfSassCompilerArgs extends sfSassCompilerBase
{
  private static $instance;

  /*
   * Return the instance of the compiler
   *
   * @param sfEventDispatcher $dispatcher Event dispatcher
   * @return sfSassCompiler The instance
   */
  public static function getInstance(sfEventDispatcher $dispatcher)
  {
    if (!self::$instance)
    {
      self::$instance = new sfSassCompilerArgs($dispatcher);
    }
    return self::$instance;
  }
}