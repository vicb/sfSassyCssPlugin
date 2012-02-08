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
class sassCompileTask extends sfBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', $this->getFirstApplication()),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('no-clean', null, sfCommandOption::PARAMETER_NONE, 'Do not remove generated CSS before compilation'),
      new sfCommandOption('debug', null, sfCommandOption::PARAMETER_NONE, 'Generate debug info'),
      new sfCommandOption('style', null, sfCommandOption::PARAMETER_OPTIONAL, '[nested|compact|compressed|expanded]', 'compressed'),
    ));

    $this->namespace            = 'sass';
    $this->name                 = 'compile';
    $this->briefDescription     = 'Sass files compilation';
    $this->detailedDescription  = <<<EOF
The [sass:compile|INFO] task compiles the sass files.
The input folder, output folder, format, file encoding and include paths are retrieved from the application configuration.

Call it with:

  [php symfony sass:compile|INFO]
EOF;
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    $compiler = sfSassCompilerArgs::getInstance($this->dispatcher);

    $in = sfConfig::get('app_sfSassyCssPlugin_input_dir', sfConfig::get('sf_data_dir') . '/sass');
    $out = sfConfig::get('app_sfSassyCssPlugin_output_dir', sfConfig::get('sf_web_dir') . '/css');

    $params = array();

    // Clean
    if (!$options['no-clean'])
    {
      $this->logSection('Clean', 'remove previously generated css files');
      $compiler->clean($in, $out);
    }

    $params[] = '--no-cache';
    if(sfConfig::get('app_sfSassyCssPlugin_encoding') !== null)
    {
      $params[] = sprintf('-E "%s"', sfConfig::get('app_sfSassyCssPlugin_encoding'));
    }

    if ($options['format'] == 'scss')
    {
      $params[] = sprintf('--scss');
    }

    if ($options['debug'])
    {
      $params[] = '--debug-info';
      $params[] = '--line-numbers';
      $params[] = '--line-comments';
      $params[] = '--style expanded';
    }
    else
    {
      $params[] = sprintf('--style %s', $options['style']);
    }

    foreach(sfConfig::get('app_sfSassyCssPlugin_include_dirs') as $path)
    {
      $params[] = sprintf('--load-path "%s"', sfSassCompilerDriver::fixPath($path));
    }
 
    $compiler->compile($in, $out, null, $params);

    $this->logSection('Command', $compiler->getCommand());

    $stdout = $compiler->getStdOut();
    $status = $compiler->getStatus();

    if (!empty($stdout))
    {
      $this->logBlock("\n" . $stdout . "\n", preg_match('/error /i', $stdout) !== 0?'ERROR':'INFO');
    }

    $this->logSection('Status', $status);

  }
}
