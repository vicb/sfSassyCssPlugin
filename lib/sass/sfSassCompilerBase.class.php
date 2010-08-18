<?php
/*
 * This file is part of the sfSassyCssPlugin.
 * (c) 2010 Victor Berchet <http://github.com/vicb>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfSassCompilerBase Abstract compiler
 *
 * @package    sfSassyCssPlugin
 * @subpackage sass
 * @author     Victor Berchet
 */
abstract class sfSassCompilerBase
{
  protected $driver, $dispatcher;

  /**
   * Protected constructor
   *
   * @param sfEventDispatcher $dispatcher Event dispatcher
   */
  protected function __construct(sfEventDispatcher $dispatcher)
  {
    $this->dispatcher = $dispatcher;
    $this->driver = new sfSassCompilerDriver($dispatcher);
  }

  /**
   * Compile the source files and fix permissions
   *
   * @param string|array  $in      Source folder|Array of files (source => target)
   * @param string        $out     Output directory where to write the css files
   * @param string|null   $cache   Cache folder
   * @param array         $params  Sass compiler parameters
   */
  public function compile($in, $out, $cache, array $params = array())
  {
    $timer = sfTimerManager::getTimer('Sass compilation');
    $this->createFolderIfNeeded($out);
    if (!is_null($cache))
    {
      $this->createFolderIfNeeded($cache);
    }

    $this->driver->compile($in, $out, $params);

    $this->fixPermissions($out);
    if (!is_null($cache))
    {
      $this->fixPermissions($cache);
    }
    $timer->addTime();
  }

  /**
   * Create the folder when it does not exist
   *
   * @param string $folder An absolute path
   */
  protected function createFolderIfNeeded($folder)
  {
    if (!is_dir($folder))
    {
      mkdir($folder, 0777, true);
      // PHP workaround to fix nested folders
      chmod($folder, 0777);
    }
  }

  /**
   * Make the folder content publicly available
   *
   * @param string $folder Path to the folder
   */
  protected function fixPermissions($folder)
  {
    if (is_dir($folder))
    {
      foreach (sfFinder::type('any')->in($folder) as $f)
      {
        @chmod($f, 0777);
      }
    }
  }

  /**
   * Clean the generated css files
   *
   * @param string $in   Absolute path to the source files
   * @param string $out  Absolute path to the target files
   */
  public function cleanFolder($in, $out)
  {
    $files = sfFinder::type('file')->discard('_*')->relative()->in($in);

    $fs = new sfSassyFilesystem($this->dispatcher);

    foreach ($files as $file)
    {
      $target = rtrim($out, '/\\') . '/' . $file;
      $target = preg_replace('/\.s[ac]ss$/i', '.css', $target);
      if (is_file($target) && is_writable($target))
      {
        @unlink($target);
      }      
    }
  }

  /**
   * @see sfSassCompilerDriver
   */
  public function getStdOut()
  {
    return $this->driver->getStdOut();
  }

 /**
  * @see sfSassCompilerDriver
  */
  public function getStdErr()
  {
    return $this->driver->getStdErr();
  }

  /**
   * @see sfSassCompilerDriver
   */
  public function getCommand()
  {
    return $this->driver->getCommand();
  }



}