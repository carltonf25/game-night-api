<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Need;
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

  public function getNeeds($eventCode)
  {
    $event = Event::where('event_code', $eventCode);
    if ($event) {
      return response()->json($event->needs);
    } else {
      return response()->json(['error' => 'Event not found']);
    }
  }

  public function addNeed($eventCode, Request $request)
  {
    $event = Event::where('event_code', $eventCode);
    if ($event) {
      $need = new Need([
        'title' => $request->title,
        'event_id' => $event->id,
        'guest_id' => -1
      ]);
      $need->save();

      return response()->json([
        'need' => $need,
        'added' => true,
        'flash' => 'Successfully added!'
      ]);
    } else {
      return response()->json(['error' => 'Event not found'], 400);
    }
  }

  public function removeNeed($id)
  {
    $need = Need::find($id);
    if ($need) {
      $need->delete();
      return response()->json(['flash' => 'Successfully deleted!']);
    } else {
      return response()->json(['error' => 'Need not found']);
    }
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

    $event->header_image = $request->header_image;
    $event->title = $request->title;
    $event->description = $request->description;
    $event->date = $request->date;
    $event->location = $request->location;
    $event->event_code = $request->event_code;

    $event->save();

    return response()->json(["event" => $event, "updated" => true], 200);
  }

  public function delete($id)
  {
    Event::findOrFail($id)->delete();
    return response()->json(['success' => true], 200);
  }
}
