<?php
/*
 * This file is part of the cvUrlMoverPlugin package
 * (c) 2007 Carl Vondrick <carl@carlsoft.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
  * Unit tests for the matchers.
  *
  * @author Carl Vondrick <carl@carlsoft.net>
  * @package cvUrlMoverPlugin
  * @version SVN: $Id$
  */

require_once dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';
require_once dirname(__FILE__) . '/../../lib/matcher/cvUrlMoverMatcher.interface.php';
require_once dirname(__FILE__) . '/../../lib/matcher/cvUrlMoverMatcherSchema.class.php';
require_once dirname(__FILE__) . '/../../lib/matcher/cvUrlMoverMatcherPreg.class.php';
require_once dirname(__FILE__) . '/../../lib/matcher/cvUrlMoverMatcherString.class.php';

$t = new lime_test(15, new lime_output_color);

$t->diag('testing preg matcher');

$m = new cvUrlMoverMatcherPreg('f[oa]+b.r(\d+)\.html', 'foo/bar?id=$1', true);
$t->is($m->match('/doesnotmatch'), null, '->match() returns null if it does not match');
$t->is($m->match('/foooabar52.html'), 'foo/bar?id=52', '->match() returns the replaced string if it matches');

$m = new cvUrlMoverMatcherPreg('f[oa]+b.r(\d+)\.html', 'foo/bar?id=$1', false);
$t->is($m->match('/foooabar52.html'), 'foo/bar?id=$1', '->match() does not do replacements if turned off');

$m = new cvUrlMoverMatcherPreg('(\w+\d+)', 'ucfirst("$1")', true, array('e'));
$t->is($m->match('/whatever22'), 'Whatever22', '->match() handles flags correctly');

$t->is(cvUrlMoverMatcherPreg::generate('regex', 'replacement'), "new cvUrlMoverMatcherPreg('regex', 'replacement', 1, array (
  0 => 'i',
))", '::generate() generates the replacement correctly');

$t->is(cvUrlMoverMatcherPreg::generate('regex', 'replacement', array('flags' => array('a', 'b'), 'case' => true)), "new cvUrlMoverMatcherPreg('regex', 'replacement', 1, array (
  0 => 'a',
  1 => 'b',
))", '::generate() generates the replacement correctly with parameters');

$t->diag('testing string matcher');

$m = new cvUrlMoverMatcherString('foobar', 'barfoo');
$t->is($m->match('/doesnotmatch'), null, '->match() returns null it does not match');
$t->is($m->match('/Foobar'), 'barfoo', '->match() returns the new URL if it matches');

$m = new cvUrlMoverMatcherString('foobar', 'barfoo', true);
$t->is($m->match('/Foobar'), null, '->match() returns null if it does not match case sensitively');
$t->is($m->match('/foobar'), 'barfoo', '->match() returns the new URL if it matches case sensitively');

$t->is(cvUrlMoverMatcherString::generate('foobar', 'barfoo'), "new cvUrlMoverMatcherString('foobar', 'barfoo', 0)", '::generate() generates replacement correctly');

$t->is(cvUrlMoverMatcherString::generate('foobar', 'barfoo', array('case' => true)), "new cvUrlMoverMatcherString('foobar', 'barfoo', 1)", '::generate() generates replacement correctly with parameters');

$t->diag('testing schema matcher');

$matchers = array(
  new cvUrlMoverMatcherString('foobar.html', 'barfoo'),
  new cvUrlMoverMatcherPreg('f([oa]+)b.r\.html', 'foo/bar?id=$1', true)
);

$m = new cvUrlMoverMatcherSchema($matchers);

$t->is($m->match('/doesnotmatch'), null, '->match() returns null if it does not match');
$t->is($m->match('/foobar.html'), 'barfoo', '->match() returns the first hit that matches');
$t->is($m->match('/faoooobar.html'), 'foo/bar?id=aoooo', '->match() returns a match if it matches');