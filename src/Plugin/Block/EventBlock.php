<?php

namespace Drupal\eventbrite_api\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "eventbrite_api_events",
 *   admin_label = @Translation("Event List"),
 *   category = @Translation("Eventbrite Api")
 * )
 */
class EventBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $nodes = $this->queryNodes();
    $renderable = [
      '#theme' => 'event_template',
      '#test_var' => $nodes,
      '#attached' => [
        'library' => [
          'eventbrite_api/eventbrite_api',
        ],
      ],
    ];

    return $renderable;
  }

  private function queryNodes(){
    $query = \Drupal::entityQuery('node')
      ->condition('status', 1) 
      ->condition('type', 'event') 
      ->pager(10); 
    $nids = $query->execute();

    $nodes = array(); 

    foreach ($nids as $nid) {
      $node = \Drupal\node\Entity\Node::load($nid); 
      $newNode = new \stdClass;
      $newNode->title = $node->title->value;
      $newNode->description = $node->field_description->value;
      $newNode->date = $node->field_date->value;
      $newNode->location = $node->field_location->value;
      $newNode->id = $nid;
      array_push($nodes, $newNode);
    }  

    return $nodes; 
  }

}
