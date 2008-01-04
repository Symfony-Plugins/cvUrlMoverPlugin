<?php
/*
 * This file is part of the cvUrlMoverPlugin package
 * (c) 2007 Carl Vondrick <carl@carlsoft.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
  * Configuration file.  This configures the event dispatcher.
  *
  * @author Carl Vondrick <carl@carlsoft.net>
  * @package cvUrlMoverPlugin
  * @version SVN: $Id$
  */

$context = sfContext::getInstance();
$config = sfConfigCache::getInstance()->checkConfig(sfConfig::get('sf_config_dir_name') . DIRECTORY_SEPARATOR. cvUrlMover::CONFIG_FILE);

$matcher = new cvUrlMoverMatcherLazy($config);

$mover = new cvUrlMover($context, $matcher);
$mover->register();