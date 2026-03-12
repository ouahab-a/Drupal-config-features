<?php

declare(strict_types=1);

namespace Drupal\content_entity_example\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Returns responses for Content Entity Example routes.
 */
final class EntityCrudController extends ControllerBase
{

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager)
  {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  /**
   * Builds the response.
   */
  public function build(): array
  {

    $entity_type = "node";
    $storage = $this->entityTypeManager->getStorage($entity_type);

    /**
     * How to use entity type manager to read entity
     * The value of ->entity in this case is a \Drupal\taxonomy\Entity\Term entity.
     * The value of ->entity in this case is a \Drupal\user\Entity\User entity.
     */
    // $nodeRead = $storage->load(7);
    // dump($nodeRead->field_category[0]->entity->name->value);
    // dump($nodeRead->uid->entity->name->value);

    
    // /**
    //  * How to use entity type manager to create entity
    //  */
    // $nodeCreate = $storage->create(['type' => 'page', 'title' => 'About Us']);
    // $nodeCreate = $storage->save($node);
    // dump($nodeCreate); 


    /**
     * How to use entity type manager to update entity
     */
    // $nodeUpdate = $storage->load(57);
    // $nodeUpdate->set('title', 'Updated About Us');
    // $storage->save($nodeUpdate);
    // dump($nodeUpdate);


    /**
     * How to use entity type manager to delete entity
     */
    // $nodeDelete = $storage->load(57);
    // $storage->delete($nodeDelete);


    /**
     * How to use entity type manager to build query.
     */

    // $query = $this->entityTypeManager->getStorage($entity_type)->getQuery();

    // $node_ids = $query
    //   ->accessCheck(TRUE)
    //   ->condition('type', 'article')
    //   ->condition('status', 1)
    //   ->pager(10)
    //   // Use Devel module to debug the query
    //   ->addTag('debug')
    //   // Once we have our conditions specified we use the execute() method to run the query
    //   ->execute();

    // $nodes = $storage->loadMultiple($node_ids);

    // foreach ($nodes as $article) {
    //   print $article->label();
    // }

    $articleNode = $storage->load(59); // ArticleNode object
    // dump($articleNode->getShareImageFile()->uri->value);

    $build['content'] = [
      '#type' => 'item',
      '#theme' => 'crud_example',
      '#crud_result' => $articleNode->getShareImageFile()->uri->value
    ];

    return $build;
  }
}
