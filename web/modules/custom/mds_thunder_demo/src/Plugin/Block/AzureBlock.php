<?php

namespace Drupal\mds_thunder_demo\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Infra by Auzre' block.
 *
 * @Block(
 *   id = "mds_thunder_demo_azure",
 *   admin_label = @Translation("Infra by Azure")
 * )
 */
class AzureBlock extends BlockBase {

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
      '#template' => '<div class="widget azure_widget">{{ sponsored_by }} <a title="Microsoft Azure" href="https://azure.microsoft.com">{{ azure }}</a>.
            </div>',
      '#context' => [
        'sponsored_by' => $this->t('Infrastructure sponsored by the'),
        'azure' => $this->t('Microsoft Azure'),
      ],
    ];
  }

}
