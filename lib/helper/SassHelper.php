<?php
/*
 * This file is part of the sfSassyCssPlugin.
 * (c) 2010 Victor Berchet <http://github.com/vicb>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * This helper triggers the compilation of sass files when they are used
 *
 * @package    sfSassyCssPlugin
 * @subpackage helper
 * @author     Victor Berchet
 */
use_helper('Asset');

/**
 * @see get_stylesheets
 */
function get_sass_stylesheets()
{
  $response = sfContext::getInstance()->getResponse();
  $dispatcher = sfContext::getInstance()->getEventDispatcher();
  $dispatcher->notify(new sfEvent($response, 'sass.include_css'));
  return get_stylesheets();
}

/**
 * @see include_stylesheets
 */
function include_sass_stylesheets()
{
  echo get_sass_stylesheets();
}