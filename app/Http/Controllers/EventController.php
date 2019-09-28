<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Guest;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{

  public function __construct()
  {
    $this->eventService = new EventService;
  }

  public function showAllEvents()
  {
    return response()->json(Event::all());
  }

  public function showOneEvent($eventCode)
  {
    $response = $this->eventService->getOneEvent($eventCode);

    return response()->json($response);
  }

  public function create(Request $request)
  {
    $response = $this->eventService->make($request);
    return response()->json($response);
  }

  public function getGuests($eventCode)
  {
    $event = Event::where('event_code', $eventCode)->first();

    return response()->json(['guests' => $event->guests], 200);
  }

  public function addGuests($eventCode, Request $request)
  {
    $guests = $request->input('guests');
    $event = Event::where('event_code', $eventCode)->first();

    $guestsArray = [];
    $errors = [];

    foreach ($guests as $guest) {
      $guest = Guest::firstOrCreate(['name' => $guest['name']]);
      $guestsArray[] = $guest;
    }

    $guestIds = [];

    foreach ($guestsArray as $guest) {
      $guestIds[] = $guest['id'];
    }

    try {
      $event->guests()->syncWithoutDetaching($guestIds);
      return response()->json(
        [
          'guests' => $event->guests,
          'flash' => 'Successfully RSVP-d! See you there 🤙',
          'added' => true
        ],
        200
      );
    } catch (Exception $e) {
      return response()->json($e, 400);
    }
  }

  public function update($eventCode, Request $request)
  {
    $event = Event::where('event_code', $eventCode)->first();
    $updatedEvent = [
      'header_image' => $request->header_image,
      'title' => $request->title,
      'description' => $request->description,
      'date' => $request->date,
      'location' => $request->location,
    ];
    $event->update($updatedEvent);

    return response()->json(["event" => $event, "updated" => true], 200);
  }

  public function delete($id)
  {
    Event::findOrFail($id)->delete();
    return response()->json(['success' => true], 200);
  }
}
