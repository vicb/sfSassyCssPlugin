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
   * @param string   $in      Source folder
   * @param string   $out     Target folder
   * @param array    $params  Compiler options
   * @return integer $statut  Compiler process exit code
   */
  public function compile($in, $out, array $params)
  {
    $this->command = sprintf('sass --update "%s":"%s" %s 2>&1', $in, $out, join(' ', $params));

    $this->stdout = $this->stderr = "";

    exec($this->command, $this->stdout, $this->status);

    $this->stdout = join("\n", $this->stdout);

    return $this->status;
  }

  /**
   * @return string STDOUT content
   */
  public function getStdOut()
  {
    return $this->stdout;
  }

  /**
   * @return integer status
   */
  public function getStatus()
  {
    return $this->status;
  }


  /**
   * @return string Command
   */
  public function getCommand()
  {
    return $this->command;
  }
}