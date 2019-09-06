<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class EventsTest extends TestCase
{
  public $code = 121146;
  /**
   * Test EventsController 
   *
   * @return void
   */
  use DatabaseTransactions;

  public function test_create_event()
  {
    $event = [
      'title' => 'Test Event',
      'description' => 'Testing the event endpoint..',
      'date' => '1970-03-18 00:00:00',
      'header_image' => 'https://lorempixel.com/640/480/?32109',
      'user_id' => 1
    ];

    $this->post('/api/events', $event);

    $this->seeJsonContains([
      'created' => true
    ]);
  }

  public function test_add_guests_to_event()
  {
    $guests = [
      [
        'name' => 'Carlton',
      ],
    ];

    $this->post("api/events/121146/guests", $guests);

    $this->seeJsonContains([
      'added' => true
    ]);
  }
}
