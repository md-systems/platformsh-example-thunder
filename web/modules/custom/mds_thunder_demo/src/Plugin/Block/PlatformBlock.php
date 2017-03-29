<?php

namespace Drupal\mds_thunder_demo\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hosted by PLatform' block.
 *
 * @Block(
 *   id = "mds_thunder_demo_platform",
 *   admin_label = @Translation("Hosted by Platform")
 * )
 */
class PlatformBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['label_display' => FALSE];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'inline_template',
      '#template' => '<div class="widget platform_sh_widget">{{ sponsored_by }} <a title="platform.sh" href="http://platform.sh/?utm_campaign=sponsored_sites&utm_source=thunder_demo">{{ platform }}</a>.</div>',
      '#context' => [
        'sponsored_by' => $this->t('Hosting sponsored by'),
        'platform' => $this->t('Platform.sh, Continuous Deployment Drupal Hosting'),
      ],
    ];
  }

}
