<?php

declare(strict_types=1);

namespace Drupal\content_entity_example\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\Attribute\ConfigEntityType;
use Drupal\Core\Entity\EntityDeleteForm;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\content_entity_example\Form\VideoPresetForm;
use Drupal\content_entity_example\VideoPresetInterface;
use Drupal\content_entity_example\VideoPresetListBuilder;

/**
 * Defines the video preset entity type.
 */
#[ConfigEntityType(
  id: 'video_preset',
  label: new TranslatableMarkup('Video preset'),
  label_collection: new TranslatableMarkup('Video presets'),
  label_singular: new TranslatableMarkup('video preset'),
  label_plural: new TranslatableMarkup('video presets'),
  config_prefix: 'video_preset',
  entity_keys: [
    'id' => 'id',
    'label' => 'label',
    'uuid' => 'uuid',
  ],
  handlers: [
    'list_builder' => VideoPresetListBuilder::class,
    'form' => [
      'add' => VideoPresetForm::class,
      'edit' => VideoPresetForm::class,
      'delete' => EntityDeleteForm::class,
    ],
  ],
  links: [
    'collection' => '/admin/structure/video-preset',
    'add-form' => '/admin/structure/video-preset/add',
    'edit-form' => '/admin/structure/video-preset/{video_preset}',
    'delete-form' => '/admin/structure/video-preset/{video_preset}/delete',
  ],
  admin_permission: 'administer video_preset',
  label_count: [
    'singular' => '@count video preset',
    'plural' => '@count video presets',
  ],
  config_export: [
    'id',
    'label',
    'description',
    'codec'
  ],
)]
final class VideoPreset extends ConfigEntityBase implements VideoPresetInterface {

  /**
   * The example ID.
   */
  protected string $id;

  /**
   * The example label.
   */
  protected string $label;

  /**
   * The example description.
   */
  protected string $description;

  /**
   * The codec.
   * 
   * @var string
   */
  protected string $codec;

}
