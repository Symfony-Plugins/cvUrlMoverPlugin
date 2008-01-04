<?php
/*
 * This file is part of the cvUrlMoverPlugin package
 * (c) 2007 Carl Vondrick <carl@carlsoft.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
  * Responsible for loading a matcher only on demand.
  *
  * @author Carl Vondrick <carl@carlsoft.net>
  * @package cvUrlMoverPlugin
  * @version SVN: $Id$
  */
class cvUrlMoverMatcherLazy implements cvUrlMoverMatcher
{
  protected $config;

  public function __construct($config)
  {
    $this->config = $config;
  }

  public function match($url)
  {
    $matcher = require($this->config);

    return $matcher->match($url);
  }

  static public function generate($name, $replacement, $options = array())
  {
    throw new sfException('You cannot generate a lazy matcher.');
  }
}