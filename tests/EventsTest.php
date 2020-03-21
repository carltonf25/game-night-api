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
	 $token = 'cFXzFgA5pOCzhkcBFUYn8WjQ6mwNwbOhokZzwqtq8Ukwgl9b8vDhh7n34dkS';
	 $event = factory(App\Models\Event::class)->make();
	 $event = $event->toArray();
		
   $response = $this->json('POST', "/api/events?api_token=$token", $event);

	 $response->seeJson(['created' => true]);
  }

	/*
  public function test_add_single_guest_to_event()
  {
    $event = factory(App\Models\Event::class)->create();
    $guest = factory(App\Models\Guest::class)->create();

    $event_code = $event->event_code;

    $response = $this->call('POST', "/api/events/$event_code/guests", ["guests" => [$guest]])->response->getContent();
	echo $response;

    $this->seeJsonContains(['added' => true]);
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

    $response = $this->call('POST', "/api/events/$event_code/guests", ["guests" => [$guests]])->response()->getContent();

    echo $response;
    $this->seeJsonContains(['added' => true]);
  }
	 */
}
