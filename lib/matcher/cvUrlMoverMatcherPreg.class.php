<?php
/*
 * This file is part of the cvUrlMoverPlugin package
 * (c) 2007 Carl Vondrick <carl@carlsoft.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
  * A regular expression (Perl) matcher.
  *
  * @author Carl Vondrick <carl@carlsoft.net>
  * @package cvUrlMoverPlugin
  * @version SVN: $Id$
  */
class cvUrlMoverMatcherPreg implements cvUrlMoverMatcher
{
  protected $regex;

  protected $replacement;

  protected $replace = false;

  /**
   * Constructor.
   * @param regex $regrex The regular expression to match on the route.
   * @param string $better The new URL if $regex matches
   * @param boolean $replace If true, preg_replace is done with $better as the replacement.  If false, $better is simply returned.
   */
  public function __construct($regex, $replacement, $replace = false, $flags = array())
  {
    // we pick #  because they never appear in URLs (they are anchors)
    $regex = str_replace('#', '\#', $regex);
    $regex = '#^/' . $regex . '$#';

    $regex .= implode($flags, '');

    $this->regex = $regex;
    $this->replacement = $replacement;
    $this->replace = (bool) $replace;
  }

  public function match($url)
  {
    if (preg_match($this->regex, $url))
    {
      if ($this->replace)
      {
        return preg_replace($this->regex, $this->replacement, $url);
      }
      else
      {
        return $this->replacement;
      }
    }

    return null;
  }

  static public function generate($name, $replacement, $params = array())
  {
    if (!isset($params['replace']))
    {
      $params['replace'] = true;
    }
    if (!isset($params['flags']))
    {
      $params['flags'] = array();
    }
    if (!isset($params['case']) || !$params['case'])
    {
      $params['flags'][] = 'i';
    }

    $params['flags'] = array_unique($params['flags']);

    return sprintf("new %s('%s', '%s', %d, %s)", __CLASS__, $name, $replacement, $params['replace'], var_export($params['flags'],true));
  }
}