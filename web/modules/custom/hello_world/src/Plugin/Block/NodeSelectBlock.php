<?php

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Nouvelle méthode (Attribute)
 */

#[Block(
  id: "node_select_block",
  admin_label: new TranslatableMarkup("Node Select Block"),
  category: new TranslatableMarkup("Hello World")
)]

class NodeSelectBlock extends BlockBase
{
    public function build(): array
    {
        // 1. Formulaire entity reference
        $form = \Drupal::formBuilder()->getForm(
            'Drupal\hello_world\Form\NodeSelectForm'
        );
        $selected_title = NULL;
        $related_titles = [];
        // 2. Récupérer le nid sauvegardé
        $nid = \Drupal::service('tempstore.private')
            ->get('hello_world')
            ->get('selected_nid');
        if ($nid) {
            // 3. Charger le node sélectionné
            $node = \Drupal::entityTypeManager()
                ->getStorage('node')
                ->load($nid);
            // bundle != entity type
            if ($node) {
                $selected_title = $node->getTitle();
                $bundle = $node->bundle();
                // 4. Entity query — même type, exclure le nid courant
                $nids = \Drupal::entityTypeManager()
                    ->getStorage('node')
                    ->getQuery()
                    ->accessCheck(TRUE)
                    ->condition('type', $bundle)
                    ->condition('status', 1)
                    ->condition('nid', $nid, '!=')
                    ->range(0, 10)
                    ->execute();
                // 5. Charger les nodes liés
                $nodes = \Drupal::entityTypeManager()
                    ->getStorage('node')
                    ->loadMultiple($nids);
                foreach ($nodes as $related) {
                    $related_titles[] = $related->getTitle();
                }
            }
        }
        // 6. Passer au twig via hook_theme
        return [
            'form'   => $form,
            'result' => [
                '#theme'          => 'node_select_result',
                '#selected_title' => $selected_title,
                '#related_titles' => $related_titles,
            ],
        ];
    }
}
