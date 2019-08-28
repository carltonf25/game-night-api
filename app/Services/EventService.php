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

  public static function generateCode()
  {
    $characters = 3;
    $code = bin2hex(random_bytes($characters));

    return $code;
  }

  public static function make($request)
  {
    $response = [];
    try {
      $event = new Event($request->all());
      $code = $this->generateCode();
      $event->event_code = $code;
      $event->save();
      $response = [$event, 201];
    } catch (Exception $e) {
      $response = [$e, 400];
    }
    return $response;
  }
}
