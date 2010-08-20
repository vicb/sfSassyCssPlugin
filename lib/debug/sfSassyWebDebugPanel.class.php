<?php
/*
 * This file is part of the sfSassyCssPlugin.
 * (c) 2010 Victor Berchet <http://github.com/vicb>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfSassyWebDebugPanel Web debug toolbar extension for sass
 *
 * @package    sfSassyCssPlugin
 * @subpackage debug
 * @author     Victor Berchet
 */
class sfSassyWebDebugPanel extends sfWebDebugPanel
{
  protected $compiler;

  /**
   * Listens to LoadDebugWebPanel event & adds this panel to the Web Debug toolbar
   *
   * @param   sfEvent $event
   */
  public static function listenToLoadDebugWebPanelEvent(sfEvent $event)
  {    
    $event->getSubject()->setPanel(
      'documentation',
      new self($event->getSubject())
    );
  }

  /**
   * @see sfWebDebugPanel
   */
  public function getTitle()
  {
    return '<img src="/sfSassyCssPlugin/images/css_go.png" alt="Sassy Css" height="16" width="16" /> Sass';
  }

  /**
   * @see sfWebDebugPanel
   */
  public function getPanelTitle()
  {
    return 'Sass compiler';
  }

  /**
   * @see sfWebDebugPanel
   */
  public function getPanelContent()
  {
    $this->compiler = sfSassCompilerDefault::getInstance(sfContext::getInstance()->getEventDispatcher());

    $status = $this->compiler->getStatus();
    $stdout = $this->compiler->getStdOut();
    $cmd = $this->compiler->getCommand();
    
    $content = <<<DEBUG
    <h2>Command</h2>
    <p style="display: block; border: 1px solid black; padding: 5px; background-color: white;">{command}</p>
    <h2>Status</h2>
    <p style="display: block; border: 1px solid black; padding: 5px; background-color: white;">{status}</p>
DEBUG;

    if (!empty($stdout))
    {
      $content .= <<<STDOUT
    <h2>Output</h2>
    <div style="display: block; border: 1px solid black; padding: 5px; background-color: white;">
    {stdout}
    </div>
STDOUT;
    }

    require_once sfConfig::get('sf_symfony_lib_dir') . '/helper/TextHelper.php';

    $content = strtr($content, array(
      '{command}' => htmlentities($cmd),
      '{stdout}'  => simple_format_text(htmlentities($stdout)),
      '{status}'  => $status
    ));

    if (!empty($stdout))
    {
      $this->setStatus(preg_match('/error /mi', $stdout) === 1?sfLogger::ERR:sfLogger::WARNING);
    }
    else
    {
      $this->setStatus(sfLogger::INFO);
    }

    return $content . "<br/>";
  }

}
