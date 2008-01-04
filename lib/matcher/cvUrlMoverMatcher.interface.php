<?php
/*
 * This file is part of the cvUrlMoverPlugin package
 * (c) 2007 Carl Vondrick <carl@carlsoft.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
  * Interface for a matcher.
  *
  * @author Carl Vondrick <carl@carlsoft.net>
  * @package cvUrlMoverPlugin
  * @version SVN: $Id$
  */
interface cvUrlMoverMatcher
{
  /**
   * Matches a given URL to find a better one. Returns the string if possible
   * or null on no route.
   */
  public function match($url);

  static public function generate($name, $replacement, $options = array());
}