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
      $response = ['success' => 'Event found! Joining now..', 'event' => $event, 'created' => true];
    }

    return $response;
  }

  public static function generateCode()
  {
    $characters = 3;
    $code = bin2hex(random_bytes($characters));

    return $code;
  }

  public function make($request)
  {
    $response = [];
    $event = new Event($request->all());

    try {
      $code = Self::generateCode();

      /**
       * manually add event_code (expected), but not sure why I have to manually specify user_id & header_image..
       */
      $event->event_code = $code;
      $event->user_id = $request->input('user_id');
      $event->header_image = $request->input('header_image');
      $event->save();
      $response = [$event, 'created' => true, 'flash' => 'Successfully RSVP-d! See you there 🤙', 'code' => 200];
    } catch (Exception $e) {
      $response = [$e, 400];
    }
    return $response;
  }
}
