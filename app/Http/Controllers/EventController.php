<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{

  public static function generateCode($id)
  {
    $characters = 6;
    $event = Event::findOrFail($id);

    $code = bin2hex(random_bytes($characters));
    $event->event_code = $code;
    try {
      $event->save();
      return response()->json($event, 200);
    } catch (Exception $e) {
      return response()->json($e, 403);
    }
  }

  public function showAllEvents()
  {
    return response()->json(Event::all());
  }

  public function showOneEvent($code)
  {
    $event = Event::where('event_code', $code)->first();
    if (!$event) {
      return response()->json([
        'error' => 'No event found with that code.'
      ]);
    }
    return response()->json($event, 200);
  }

  public function create(Request $request)
  {
    $event = Event::create($request->all());
    try {
      $this->generateCode($event->id);
      return response()->json($event, 201);
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
