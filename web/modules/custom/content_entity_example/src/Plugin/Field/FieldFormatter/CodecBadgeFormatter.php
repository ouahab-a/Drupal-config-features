<?php
// src/Plugin/Field/FieldFormatter/CodecBadgeFormatter.php

namespace Drupal\content_entity_example\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Displays codec as a colored badge.
 *
 * @FieldFormatter(
 *   id = "codec_badge",
 *   label = @Translation("Codec Badge"),
 *   field_types = {"string", "uri"}
 * )
 */
class CodecBadgeFormatter extends FormatterBase {

  public function viewElements(FieldItemListInterface $items, $langcode): array {
    $elements = [];
    $colors = [
      'https://source-inconnue.fr/1' => '#00b894',
      'h265' => '#0984e3',
      'vp9'  => '#e17055',
    ];

    foreach ($items as $delta => $item) {
      $value = $item->value ?? $item->uri ?? '';
      $color = $colors[$value] ?? '#636e72';
      $elements[$delta] = [
        '#type' => 'html_tag',
        '#tag' => 'span',
        '#value' => htmlspecialchars($value),
        '#attributes' => [
          'style' => "background:{$color};color:#fff;padding:2px 8px;border-radius:12px;font-family:monospace"
        ],
      ];
    }
    return $elements;
  }
}