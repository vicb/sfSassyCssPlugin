<?php
/*
 * This file is part of the sfSassyCssPlugin.
 * (c) 2010 Victor Berchet <http://github.com/vicb>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfSassyCompilerDriver Wrapper around the ruby sass compiler
 *
 * @package    sfSassyCssPlugin
 * @subpackage sass
 * @author     Victor Berchet
 */
class sfSassCompilerDriver
{
  protected $stdout,
    $stderr,
    $command,
    $dispatcher;

  /**
   * Constructor
   *
   * @param sfEventDispatcher $dispatcher Event dispatcher
   */
  public function __construct(sfEventDispatcher $dispatcher)
  {
    $this->dispatcher = $dispatcher;
  }

  /**
   * Execute the external sass compiler
   *
   * @param string|array  $in      Source folder|Array of files (source => target)
   * @param string        $out     Target folder
   * @param array         $params  Compiler options
   * @return integer      $statut  Compiler process exit code
   */
  public function compile($in, $out, array $params)
  {
    $cmd = 'sass --update %s:%s ' . join(' ', $params);

    $fs = new sfSassyFilesystem();

    $this->command = $this->stdout = $this->stderr = "";

    if (is_array($in))
    {
      foreach ($in as $source => $target)
      {
        $command = sprintf($cmd, $source, $target);
        list($stdout, $stderr, $statut) = $fs->execute($command);
        $this->command .= $command . "\n";
        if (!empty($stdout))
        {
          $this->stdout .= $stdout . "\n";
        }
        if (!empty($stderr))
        {
          $this->stderr .= $stderr . "\n";
        }
      }
    }
    else
    {
      $this->command = sprintf($cmd, $in, $out);
      list($this->stdout, $this->stderr, $statut) = $fs->execute($this->command);
    }

    return $statut;
  }

  /**
   * @return string STDOUT content
   */
  public function getStdOut()
  {
    return $this->stdout;
  }

  /**
   * @return string STDERR content
   */
  public function getStdErr()
  {
    return $this->stderr;
  }

  /**
   * @return string Command
   */
  public function getCommand()
  {
    return $this->command;
  }
}