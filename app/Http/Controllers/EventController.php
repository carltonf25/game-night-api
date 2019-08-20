<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Guest;
use Illuminate\Http\Request;

class EventController extends Controller
{

  public static function generateCode()
  {
    $characters = 3;
    $code = bin2hex(random_bytes($characters));

    return $code;
  }

  public function showAllEvents()
  {
    return response()->json(Event::all());
  }

  public function showOneEvent($code)
  {
    $event = Event::where('event_code', $code)->first();
    if ($code == '') {
      return response()->json(['error' => 'Please enter an event code']);
    } else if (!$event) {
      return response()->json([
        'error' => 'No event found with that code.'
      ]);
    }
    return response()->json($event, 200);
  }

  public function create(Request $request)
  {
    try {
      $event = new Event($request->all());
      $code = $this->generateCode();
      $event->event_code = $code;
      $event->save();
      return response()->json($event, 201);
    } catch (Exception $e) {
      return response()->json($e, 400);
    }
  }

  public function getGuests($eventCode)
  {
    $event = Event::where('event_code', $eventCode)->first();

    return response()->json(['guests' => $event->guests], 200);
  }

  public function addGuests($eventCode, Request $request)
  {
    $input = $request->all();

    $request = json_decode($request, true);
    $event = Event::where('event_code', $eventCode)->first();
    $guestNames = $input['guests'];
    $guests = [];

    foreach ($guestNames as $name) {
      $guest = Guest::firstOrCreate(['name' => $name]);
      $guests[] = $guest->id;
    }

    try {
      $event->guests()->syncWithoutDetaching($guests);
      return response()->json(['guests' => $event->guests], 200);
    } catch (Exception $e) {
      return response()->json($e, 400);
    }
  }

  public function update($id, Request $request)
  {
    $event = Event::findOrFail($id);
    $event->update($request->all());

    return response()->json($event, 200);
  }

  public function delete($id)
  {
    Event::findOrFail($id)->delete();
    return response('Deleted successfully', 200);
  }
}
