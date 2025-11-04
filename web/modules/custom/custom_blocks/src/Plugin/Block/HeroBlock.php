<?php

namespace Drupal\custom_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hero Block'.
 *
 * @Block(
 *   id = "hero_block",
 *   admin_label = @Translation("Hero Block")
 * )
 */
class HeroBlock extends BlockBase {
  public function build() {
    return [
      '#markup' => '
        <header style="background:#0077cc;color:white;padding:2rem;text-align:center;">
          <h1>Chào mừng đến với Drupal!</h1>
          <p>Đây là khối Hero tùy chỉnh</p>
        </header>',
      '#allowed_tags' => ['header', 'h1', 'p'],
    ];
  }
}
