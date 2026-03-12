<?php
// src/Plugin/Field/FieldFormatter/CodecPlainFormatter.php

namespace Drupal\content_entity_example\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Displays codec as plain uppercase text.
 *
 * @FieldFormatter(
 *   id = "codec_plain",
 *   label = @Translation("Codec Plain"),
 *   field_types = {"string", "uri"}
 * )
 */
class CodecPlainFormatter extends FormatterBase {

  public function viewElements(FieldItemListInterface $items, $langcode): array {
    $elements = [];
    foreach ($items as $delta => $item) {
      $value = $item->value ?? $item->uri ?? '';
      $elements[$delta] = [
        '#markup' => strtoupper($value),
      ];
    }
    return $elements;
  }
}