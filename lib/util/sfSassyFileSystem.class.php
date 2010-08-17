<?php
/*
 * This file is part of the sfSassyCssPlugin.
 * (c) 2010 Victor Berchet <http://github.com/vicb>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sassCompileTask Compilation task
 *
 * @package    sfSassyCssPlugin
 * @subpackage task
 * @author     Victor Berchet
 */
class sfSassyFileSystem extends sfFilesystem
{

  /**
   * Executes a shell command
   * Override the base function not to throw an Exception when the process
   * exit code is not 0.
   *
   * @param string $cmd            The command to execute on the shell
   * @param array  $stdoutCallback A callback for stdout output
   * @param array  $stderrCallback A callback for stderr output
   *
   * @return array An array composed of the content output, the error output and the process exit code
   */
  public function execute($cmd, $stdoutCallback = null, $stderrCallback = null)
  {
    $this->logSection('exec ', $cmd);

    $descriptorspec = array(
      1 => array('pipe', 'w'), // stdout
      2 => array('pipe', 'w'), // stderr
    );

    $process = proc_open($cmd, $descriptorspec, $pipes);
    if (!is_resource($process))
    {
      throw new RuntimeException('Unable to execute the command.');
    }

    stream_set_blocking($pipes[1], false);
    stream_set_blocking($pipes[2], false);

    $output = '';
    $err = '';
    while (!feof($pipes[1]))
    {
      foreach ($pipes as $key => $pipe)
      {
        if (!$line = fread($pipe, 128))
        {
          continue;
        }

        if (1 == $key)
        {
          // stdout
          $output .= $line;
          if ($stdoutCallback)
          {
            var_dump($line);
            call_user_func($stdoutCallback, $line);
          }
        }
        else
        {
          // stderr
          $err .= $line;
          if ($stderrCallback)
          {
            call_user_func($stderrCallback, $line);
          }
        }
      }

      usleep(100000);
    }

    fclose($pipes[1]);
    fclose($pipes[2]);

    return array($output, $err, proc_close($process));
  }
}
