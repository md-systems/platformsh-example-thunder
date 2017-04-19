<?php

namespace Drupal\mds_thunder_demo\EventSubscriber;

use Drupal\Core\PageCache\ResponsePolicy\KillSwitch;
use Drupal\Core\Routing\RouteMatch;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Forces initial tour to be enabled when site is accessed initially.
 */
class InitialTourSubscriber implements EventSubscriberInterface {

  /**
   * Page cache kill switch service.
   *
   * @var \Drupal\Core\PageCache\ResponsePolicy\KillSwitch
   */
  protected $pageCacheKillswitch;

  /**
   * Constructs InitialTourSubscriber.
   *
   * @param \Drupal\Core\PageCache\ResponsePolicy\KillSwitch $killswitch
   *   Page cache kill switch service.
   */
  public function __construct(KillSwitch $killswitch) {
    $this->pageCacheKillswitch = $killswitch;
  }

  /**
   * Forces tour if needed.
   *
   * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
   *   The Event to process.
   */
  public function onRequest(FilterResponseEvent $event) {
    $request = $event->getRequest();
    $route_match = RouteMatch::createFromRequest($request);
    if ($route_match->getRouteName() == 'user.register' && !$request->query->has('tour') && _mds_thunder_demo_is_new_site()) {
      $this->pageCacheKillswitch->trigger();
      $event->setResponse(new RedirectResponse(Url::fromRoute(
        'user.register',
        [],
        ['query' => ['tour' => 1]]
      )->toString(), 307, ['Cache-Control' => 'no-cache, private']));
    }
  }

  /**
   * Registers the methods in this class that should be listeners.
   *
   * @return array
   *   An array of event listener definitions.
   */
  static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = array('onRequest', 255);

    return $events;
  }

}
