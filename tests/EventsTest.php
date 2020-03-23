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
    $event = factory(App\Models\Event::class)->make();
    $event = $event->toArray();

    $this->json('POST', "/api/events", $event)
      ->seeJson(['created' => true]);
  }

  public function test_retrieve_event_by_code()
  {
    $event = factory(App\Models\Event::class)->create();
    $code = $event->event_code;

    $this->get("/api/events/$code")
      ->seeStatusCode(200);
  }

  public function test_update_event()
  {
    $event = factory(App\Models\Event::class)->create();
    $code = $event->event_code;

    $updatedInfo = ['title' => 'The updated title', 'location' => 'Ponce City Market'];

    $this->put("/api/events/$code", $updatedInfo)
      ->seeJson(["updated" => true]);
  }

  public function test_add_single_guest_to_event()
  {
    $event = factory(App\Models\Event::class)->create();
    $guest = factory(App\Models\Guest::class)->create();

    $event_code = $event->event_code;

    $this->json('POST', "/api/events/$event_code/guests", ["guests" => [$guest]])
      ->seeJson(['added' => true]);
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

    $this->json('POST', "/api/events/$event_code/guests", ["guests" => $guests])
      ->seeJson(['added' => true]);
  }

  public function test_retrieve_guests_for_event()
  {
    $event = factory(App\Models\Event::class)->create();
    $event_code = $event->event_code;
    $guest = factory(App\Models\Guest::class)->create();

    $this->post("api/events/$event_code/guests", ["guests" => [$guest]]);

    $this->get("/api/events/$event_code/guests")
      ->seeStatusCode(200);
  }

  public function test_post_comment()
  {
    $event = factory(App\Models\Event::class)->create();
    $guest = factory(App\Models\Guest::class)->create();
    $eventCode = $event->event_code;
    $eventId = $event->id;
    $guestId = $guest->id;

    $comment = ['body' => 'Test, test', 'event_id' => $eventId, 'guest_id' => $guestId];

    $this->post("/api/events/$eventCode/comments", $comment)
      ->seeJson(['success' => true]);
  }

  public function test_retrieve_comments()
  {
    $event = factory(App\Models\Event::class)->create();
    $guest = factory(App\Models\Guest::class)->create();
    $eventCode = $event->event_code;
    $eventId = $event->id;
    $guestId = $guest->id;

    App\Models\Comment::create(['body' => 'test, test', 'event_id' => $eventId, 'guest_id' => $guestId]);

    $this->get("/api/events/$eventCode/comments")
      ->seeStatusCode(200);
  }

  public function test_delete_event()
  {
    $event = factory(App\Models\Event::class)->create();
    $id = $event->id;

    $this->json('DELETE', "api/events/$id")
      ->seeJson(['success' => true]);
  }
}
