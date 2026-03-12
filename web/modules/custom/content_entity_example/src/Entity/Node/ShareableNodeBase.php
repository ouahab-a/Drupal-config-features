<?php

namespace Drupal\content_entity_example\Entity\Node;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\media\MediaInterface;
use Drupal\node\Entity\Node;

/**
 * Abstract base for share-enabled node bundles.
 *
 * Assumes the presence of site-configured fields:
 * - field_share_image (Media—Image)
 * - field_share_summary (Text—plain)
 */
abstract class ShareableNodeBase extends Node
{

    /**
     * Returns a title suitable for sharing.
     */
    public function getShareTitle(): string
    {
        // Prefer an explicit share summary, fall back to the node title.
        $summary = $this->get('field_share_summary')->value ?? '';
        return $summary !== '' ? $summary : $this->label();
    }

    /**
     * Returns the share image file entity if present.
     */
    public function getShareImageFile(): ?File
    {
        // dump($this);
        if (!$this->hasField('field_share_image') || $this->get('field_share_image')->isEmpty()) {
            return NULL;
        }

        /** @var \Drupal\media\MediaInterface $media */
        $media = $this->get('field_share_image')->entity;
        if ($media instanceof MediaInterface && $media->hasField('field_media_image') && !$media->get('field_media_image')->isEmpty()) {
            return $media->get('field_media_image')->entity;
        }

        return NULL;
    }

    /**
     * Returns the canonical URL for sharing.
     */
    public function getShareUrl(): Url
    {
        return $this->toUrl('canonical', ['absolute' => TRUE]);
    }

    /**
     * Builds a JSON-LD script tag with share metadata.
     *
     * @return array
     *   Render array of a JSON-LD <script> tag.
     */
    public function buildShareMeta(): array
    {
        $image = $this->getShareImageFile();

        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'headline' => $this->getShareTitle(),
            'url' => $this->getShareUrl()->toString(),
        ];

        if ($image) {
            $data['image'] = \Drupal::service('file_url_generator')
                ->generateAbsoluteString($image->getFileUri());
        }

        $build = [
            '#type' => 'html_tag',
            '#tag' => 'script',
            '#attributes' => [
                'type' => 'application/ld+json',
            ],
            '#value' => Json::encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ];

        // Collect and apply cacheability in one place.
        $cache = CacheableMetadata::createFromObject($this);
        if ($image) {
            $cache->addCacheableDependency($image);
        }
        $cache->applyTo($build);

        return $build;
    }
}
