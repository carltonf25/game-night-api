<?php

namespace App\Services;

use App\Models\Event;

class EventService
{
  public function getOneEvent($eventCode)
  {
    $response = [];
    $event = Event::where('event_code', $eventCode)->first();

    if ($eventCode == '') {
      $response = ['error' => 'Please enter an event code'];
    } else if (!$event) {
      $response = ['error' => 'No event found with that code.'];
    } else {
      // dynamically add guests to $event object
      $guests = $event->guests;
      $event->guests = $guests;
      $response = ['success' => 'Event found! Joining now..', 'event' => $event];
    }

    return $response;
  }
}
