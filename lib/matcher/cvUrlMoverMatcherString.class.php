<?php
/*
 * This file is part of the cvUrlMoverPlugin package
 * (c) 2007 Carl Vondrick <carl@carlsoft.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
  * A string matcher
  *
  * @author Carl Vondrick <carl@carlsoft.net>
  * @package cvUrlMoverPlugin
  * @version SVN: $Id$
  */
class cvUrlMoverMatcherString implements cvUrlMoverMatcher
{
  protected $oldUrl;
  protected $newUrl;
  protected $sensitive;

  public function __construct($oldUrl, $newUrl, $case = false)
  {
    $this->oldUrl = '/' . $oldUrl;
    $this->newUrl = $newUrl;
    $this->sensitive = $case;
  }

  public function match($url)
  {
    if (!$this->sensitive)
    {
      $url = strtolower($url);
      $old = strtolower($this->oldUrl);
    }
    else
    {
      $old = $this->oldUrl;
    }

    if ($url == $old)
    {
      return $this->newUrl;
    }

    return null;
  }

  static public function generate($name, $replacement, $params = array())
  {
    if (!isset($params['case']))
    {
      $params['case'] = false;
    }

    return sprintf("new %s('%s', '%s', %d)", __CLASS__, $name, $replacement, $params['case']);
  }
}