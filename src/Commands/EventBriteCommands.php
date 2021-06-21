<?php

namespace Drupal\eventbrite_api\Commands;

use Drush\Commands\DrushCommands;
use \Drupal\Component\Utility\UrlHelper;
use \Drupal\node\Entity\Node;

/**
 * A drush command file.
 *
 * @package Drupal\drush_eventbrite_commands\Commands
 */
class EventBriteCommands extends DrushCommands {

  private $client; 
  
  function __construct() {
    parent::__construct();
    $this->client = \Drupal::httpClient();
  }

  /**
   * Drush command that create events from eventbrite api.
   * @command eventsbrite_api_create:createEvents
   * @aliases eba_create
   */
  public function createEvents() {
    $this->internal();
  }

  private function internal(){

    $url = 'https://www.eventbriteapi.com/v3/organizations/471463838731/events';

    $query = array(
      'token' => 'ZSLMBAMGL67N5SQ7T7LZ'
    );

    $query_str = UrlHelper::buildQuery($query);

    $url = $url . '?' . $query_str;

    $response;

    try{
      $response = $this->client->get($url);
    }catch(\Exception $e){
      var_dump($e);
      return;
    }

    $data = $response->getBody()->getContents();
    $data = json_decode($data);

    foreach( $data->events as $key => $val ){

      $nodeExist = \Drupal::entityTypeManager()
                ->getStorage('node')
                ->loadByProperties(['field_event_id' => $val->id]);
    
      if( empty( $nodeExist ) ){
        $this->output()->writeln("Creating {$val->id} event");
        $node = Node::create([
          'type'        => 'event',
          'title'       => $val->name->text,
          'field_description' => [
            'value' => $val->description->html,
            'format' => 'full_html'
          ],
          'field_location'    => [
            $val->start->timezone,
          ], 
          'field_date'        => [
            $val->start->local
          ],
          'field_event_id' => [
            $val->id
          ]
        ]);
        $node->save();
      }else{
        $this->output()->writeln("This {$val->id} event already exists");
      };
    }
  }  
}