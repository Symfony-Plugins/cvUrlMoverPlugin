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

class cvUrlMover
{
  protected $context;

  protected $matcher = null;

  const CONFIG_FILE = 'bcurls.yml';

  public function __construct(sfContext $context, cvUrlMoverMatcher $matcher)
  {
    $this->context = $context;
    $this->matcher = $matcher;
  }

  public function register()
  {
    $this->context->getEventDispatcher()->connect('controller.page_not_found', array($this, 'handle404Event'));
  }

  /**
   * Handles the 404 if coming from an event.  This will *only* handle the 404
   * error if there have not been any forwards.
   * @param sfEvent $event The event
   * @throws sfStopException If redirect happens
   */
  public function handle404Event(sfEvent $event)
  {
    // has another symfony action touched it?
    // if so, then we shouldn't bother because that action should of taken care of it.
    if ($this->context->getController()->getActionStack()->getSize() <= 1)
    {
      $this->handle404();
    }
  }

  /**
   * Handles the 404 if it can find a new URL.  If it finds a new URL, it sends
   * a 301 redirect to the client.  This is optimal for search engines.
   *
   * @throws sfStopException If redirect happens
   */
  public function handle404()
  {
    if ($new = $this->findNewUrl())
    {
      $this->context->getEventDispatcher()->notify(new sfEvent($this, 'application.log', array('page has permanetly moved to ' . $new . ', sending 301 redirect to client now')));

      $this->redirect($new);
    }
  }

  /**
   * Finds the new URL
   */
  protected function findNewUrl()
  {
    $url = $this->context->getRequest()->getPathInfo();

    return $this->matcher->match($url);
  }

  /**
   * Forces the redirect.
   * @param string $to The new symfony URL to use
   * @throws sfStopException No matter what.
   */
  protected function redirect($to)
  {
    $to = $this->context->getController()->genUrl($to);

    $this->context->getResponse()->setStatusCode(301);
    $this->context->getResponse()->setHttpHeader('Location', $to);
    $this->context->getResponse()->setHttpHeader('Status', '301 Permanently Moved');
    $this->context->getResponse()->sendHttpHeaders();

    throw new sfStopException('page has moved to ' . $to);
  }
}