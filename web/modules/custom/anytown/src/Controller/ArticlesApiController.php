<?php

namespace Drupal\anytown\Controller;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;

class ArticlesApiController extends ControllerBase
{

  public function list(): CacheableJsonResponse
  {

    $hardcoded_nids = [2, 5, 80];

    $nids = $this->entityTypeManager()
      ->getStorage('node')
      ->getQuery()
      ->condition('type', 'article')
      ->condition('status', 1)
      ->condition('nid', $hardcoded_nids, 'IN')
      ->accessCheck(FALSE)
      ->execute();

    $nodes = $this->entityTypeManager()
      ->getStorage('node')
      ->loadMultiple($nids);

    $data = [];
    $cache_metadata = new CacheableMetadata();

    foreach ($nodes as $node) {
      $data[] = [
        'nid'   => (int) $node->id(),
        'title' => $node->getTitle(),
      ];
      $cache_metadata->addCacheableDependency($node);
    }

    $response = new CacheableJsonResponse($data);
    // $cache_metadata->setCacheMaxAge(300); // 5 minutes
    $cache_metadata->setCacheMaxAge(Cache::PERMANENT);
    $response->addCacheableDependency($cache_metadata);

    return $response;
  }
}
