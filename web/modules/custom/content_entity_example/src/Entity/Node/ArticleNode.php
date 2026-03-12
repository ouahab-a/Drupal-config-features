<?php

namespace Drupal\content_entity_example\Entity\Node;

use Drupal\Core\Url;

/**
 * Article bundle class with share behavior.
 */
final class ArticleNode extends ShareableNodeBase {

  /**
   * Customize share title for Articles to append last update date.
   */
  public function getShareTitle(): string {
    // Use parent share title and append last update date.
    $parent = parent::getShareTitle();
    return $parent . ' | Last Update: ' . date('Y-m-d');
  }

  /**
   * Customize share URL for Articles to add tracking parameters.
   */
  public function getShareUrl(): Url {
    $url = parent::getShareUrl();
    $options = $url->getOptions();
    $options['query']['utm_source'] = 'share';
    $options['query']['utm_content'] = 'article';
    $url->setOptions($options);
    return $url;
  }

}
