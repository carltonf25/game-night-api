<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class EventsTest extends TestCase
{
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
      'description' => 'Testing event creation...',
      'date' => '2019-03-18 00:00:00',
      'header_image' => 'https://www.adventuresnt.com.au/wp-content/uploads/2015/03/banner-placeholder.jpg',
      'user_id' => 1
    ];

    $this->call('POST', '/api/events', $event);

    $this->seeJsonContains(['created' => true]);
  }

  public function test_add_single_guest_to_event()
  {
    $event = factory(App\Models\Event::class)->create();
    $guest = factory(App\Models\Guest::class)->create();

    $event_code = $event->event_code;

    $this->call('post', "/api/events/$event_code/guests", ["guests" => [$guest]]);

    $this->seejsoncontains(['added' => true]);
  }

  public function test_add_multiple_guests_to_event()
  {
    $event = factory(App\Models\Event::class)->create();
    $event_code = $event->event_code;

    $guests = [];

    for ($i = 0; $i < 10; $i++) {
      $guest = factory(App\Models\Guest::class)->create();
      $guests[] = $guest;
    }

    $response = $this->call('POST', "/api/events/$event_code/guests", ["guests" => [$guests]]);

    echo $response;
    $this->seejsoncontains(['added' => true]);
  }
}
