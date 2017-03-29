<?php

namespace Drupal\mds_thunder_demo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;

/**
 * Returns response for Thunder demo welcome page.
 */
class DemoWelcomeController extends ControllerBase {

  /**
   * The route match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Constructs a DemoWelcomeController object.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match service.
   */
  public function __construct(RouteMatchInterface $route_match) {
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_route_match')
    );
  }


  /**
   * Builds Thunder demo welcome page.
   *
   * @return array
   *   The welcome page render array.
   */
  public function welcomePage() {
    $output = [];
    $output['first_paragraph'] = [
      '#markup' => $this->t('Thunder is a Drupal 8 based CMS from publishers for publishers. It was originally started by Hubert Burda Media and it is developed by a group of partners and certified integrators.'),
      '#prefix' => '<p>',
      '#suffix' => '</p>',
    ];

    $output['video'] = [
      '#type' => 'html_tag',
      '#tag' => 'video',
      '#attributes' => [
        'controls' => 'controls',
        'src' => 'http://nx-d01.akamaized.net/0e0ebece-bffa-4e51-8b6a-d627f7749645/71959_src_640x360_900.mp4',
        'preload' => 'metadata',
      ],
    ];

    $output['second_paragraph'] = [
      '#markup' => $this->t('The purpose of this demo is to show you the basic functionality of the Thunder distribution. In order to do that effectively we prepared a tour, which will demonstrate the following topics:'),
      '#prefix' => '<p>',
      '#suffix' => '</p>',
    ];

    $output['tours'] = [
      '#theme' => 'item_list',
      '#list_type' => 'ul',
      '#prefix' => '<p>',
      '#suffix' => '</p>',
      '#items' => [
        [
          '#type' => 'link',
          '#title' => $this->t('Content creation'),
          '#url' => Url::fromRoute('node.add', ['node_type' => 'article'], ['query' => ['tour' => 'tour']]),
        ],
        [
          '#type' => 'link',
          '#title' => $this->t('Advanced content creation with Paragraphs'),
          '#url' => Url::fromRoute('entity.node.edit_form', ['node' => 7], ['query' => ['tour' => 'tour']]),
        ],
        [
          '#type' => 'link',
          '#title' => $this->t('Content listing'),
          '#url' => Url::fromUri('internal:/admin/content', ['query' => ['tour' => 'tour']]),
        ],
      ],
    ];

    $output['cta'] = [
      '#markup' => $this->t('Ready? <a href=":url">Let’s start!</a>', [':url' => Url::fromRoute($this->routeMatch->getRouteName(), $this->routeMatch->getParameters()->all(), ['query' => ['tour' => 'tour']])->toString()]),
      '#prefix' => '<p>',
      '#suffix' => '</p>',
    ];

    $output['social'] = [
      'heading' => [
        '#type' => 'html_tag',
        '#tag' => 'h2',
        '#value' => $this->t('Do you like Thunder? Please share it with your friends!'),
      ],
      'content' => [
        '#theme' => 'sharemessage_socialshareprivacy',
        '#attributes' => new Attribute([
          'class' => ['socialshareprivacy'],
          'data-uri' => 'http://thunderdemo.com',
          'data-layout' => 'box',
          'data-order' => 'twitter linkedin facebook reddit fbshare mail',
          'data-title' => $this->t('I tried Thunder CMS and I love it!'),
          'data-description' => $this->t('Thunder is a Drupal 8 based CMS from publishers for publishers. It was originally started by Hubert Burda Media and it is developed by a group of partners and certified integrators.'),
        ]),
        '#attached' => [
          'library' => ['sharemessage/socialshareprivacy', 'mds_thunder_demo/welcome'],
          'drupalSettings' => [
            'socialshareprivacy_config' => [
              'services' => [
                'buffer' => ['status' => FALSE],
                'delicious' => ['status' => FALSE],
                'disqus' => ['status' => FALSE],
                'flattr' => ['status' => FALSE],
                'gplus' => ['status' => FALSE],
                'hackernews' => ['status' => FALSE],
                'pinterest' => ['status' => FALSE],
                'stumbleupon' => ['status' => FALSE],
                'xing' => ['status' => FALSE],
                'tumblr' => ['status' => FALSE],
                'mail' => [
                  'line_img' => file_create_url('libraries/socialshareprivacy/images/mail.png'),
                  'box_img' => file_create_url('libraries/socialshareprivacy/images/box_mail.png'),
                ],
                'fbshare' => [
                  'line_img' => file_create_url('libraries/socialshareprivacy/images/fbshare.png'),
                  'box_img' => file_create_url('libraries/socialshareprivacy/images/box_fbshare.png'),
                ],
                'facebook' => [
                  'dummy_line_img' => file_create_url('libraries/socialshareprivacy/images/dummy_facebook.png'),
                  'dummy_box_img' => file_create_url('libraries/socialshareprivacy/images/dummy_box_facebook.png'),
                ],
                'twitter' => [
                  'dummy_line_img' => file_create_url('libraries/socialshareprivacy/images/dummy_twitter.png'),
                  'dummy_box_img' => file_create_url('libraries/socialshareprivacy/images/dummy_box_twitter.png'),
                ],
                'reddit' => [
                  'dummy_line_img' => file_create_url('libraries/socialshareprivacy/images/dummy_reddit.png'),
                  'dummy_box_img' => file_create_url('libraries/socialshareprivacy/images/dummy_box_reddit.png'),
                ],
                'linkedin' => [
                  'dummy_line_img' => file_create_url('libraries/socialshareprivacy/images/dummy_linkedin.png'),
                  'dummy_box_img' => file_create_url('libraries/socialshareprivacy/images/dummy_box_linkedin.png'),
                ],
              ],
              'url' => 'http://thunderdemo.com',
            ],
          ],
        ],
      ],
    ];

    $output['md'] = [
      'heading' => [
        '#type' => 'html_tag',
        '#tag' => 'h2',
        '#value' => $this->t('Brought to you by MD Systems'),
      ],
      'content' => [
        '#type' => 'inline_template',
        '#template' => '<p>This demo was created by <a href="{{ md_web }}">MD Systems</a> - world
class Drupal 8 experts. We shipped one of the first Drupal 8 projects back in 2015 and
<a href="{{ md_d8 }}">have been involved in many others since then</a>. We are
<a href="{{ md_nr1 }}">one of the world’s top Drupal 8 contributors</a> and
<a href="{{ md_lead }}">leading innovation</a> in the open-source ecosystem. With some of the
<a href="{{ md_berdir }}">brightest minds</a> from the Drupal community on our staff we’re
the best Drupal 8 partner for every company.</p>

<p>We can build your next project, help you plan the architecture, develop custom modules,
integrate with 3rd party services, provide support, … If you’d like your team to learn from
the best we can also organize a <a href="md_bootstrap">bootstrap week for them</a>.</p>

<p>Do not hesitate to <a href="{{ md_contact }}">contact us</a> if you’d like to learn more!</p>',
        '#context' => [
          'md_web' => Url::fromUri('http://www.md-systems.ch'),
          'md_d8' => Url::fromUri('http://www.md-systems.ch/en/projects/portfolio'),
          'md_nr1' => Url::fromUri('http://www.md-systems.ch/en/blog/business/2016/05/18/drupals-number-1-switzerland'),
          'md_lead' => Url::fromUri('http://www.md-systems.ch/en/projects/open-source'),
          'md_berdir' => Url::fromUri('https://drupal.org/u/berdir'),
          'md_bootstrap' => Url::fromUri('http://www.md-systems.ch/en/projects/portfolio/drupal-8-bootstrap-week'),
          'md_contact' => Url::fromUri('http://www.md-systems.ch/en/contact'),
        ],
      ],
    ];

    return $output;
  }
}
