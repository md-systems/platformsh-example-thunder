<?php

namespace Drupal\mds_thunder_demo\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Brought by MD' block.
 *
 * @Block(
 *   id = "mds_thunder_demo_mdsystems",
 *   admin_label = @Translation("Infra by Azure")
 * )
 */
class MDSystemsBlock extends BlockBase {

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
      '#markup' => $this->t('Brought to you by <a href=":url">MD Systems</a>.', [':url' => 'http://www.md-systems.ch']),
    ];
  }

}
