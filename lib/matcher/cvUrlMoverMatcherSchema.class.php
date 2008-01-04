<?php
/*
 * This file is part of the cvUrlMoverPlugin package
 * (c) 2007 Carl Vondrick <carl@carlsoft.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
  * A schema matcher
  *
  * @author Carl Vondrick <carl@carlsoft.net>
  * @package cvUrlMoverPlugin
  * @version SVN: $Id$
  */
class cvUrlMoverMatcherSchema implements cvUrlMoverMatcher
{
  protected $matchers;

  public function __construct(array $matchers)
  {
    $this->matchers = $matchers;
  }

  public function match($url)
  {
    foreach ($this->matchers as $matcher)
    {
      if ($better = $matcher->match($url))
      {
        return $better;
      }
    }

    return null;
  }

  static public function generate($name, $replacement, $options = array())
  {
    throw new sfException('You cannot generate a schema matcher.');
  }
}