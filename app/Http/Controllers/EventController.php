<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Comment;
use App\Models\Guest;
use App\Models\Need;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
  /**
   * The request instance
   *
   * @var \Illuminate\Http\Request
   */
  private $request;

  /**
   * Create a new controller instance.
   *
   * @param \Illuminate\Http\Request $request
   * @return void
   */
  public function __construct(Request $request)
  {
    $this->request = $request;
    $this->eventService = new EventService;
  }

  public function showAllEvents()
  {
    try {
      return response()->json(Event::all());
    } catch (Exception $e) {
      return response()->json(['error' => 'Could not retrieve events: ' . $e]);
    }
  }

  public function showOneEvent($eventCode)
  {
    $response = [];
    try {
      $event = Event::where('event_code', $eventCode)->first();
      $response = ['event' => $event, 'success' => 'Event found! Navigating to event page...'];
    } catch (Exception $e) {
      $response = ['error' => $e];
    }
    return $response;
  }

  public function create()
  {
    $response = [];
    $event = Event::make($this->request->all());
    $eventCode = $this->eventService->generateCode();
    $event->event_code = $eventCode;

    try {
      $event->save();
      $response = ['event' => $event, 'created' => true];
    } catch (Exception $e) {
      $response = ['error' => 'Error creating event: ' . $e];
    }

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

  public function addNeed($eventCode)
  {
    $event = Event::where('event_code', $eventCode);
    if ($event) {
      $need = new Need([
        'title' => $this->request->title,
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
    $response = [];
    $code = null;

    $event = Event::where('event_code', $eventCode)->first();

    if ($event) {
      $guests = $event->guests;
      $response = ['guests' => $guests];
      $code = 200;
    } else {
      return response()->json(['error' => 'no guests found'], 400);
      $code = 400;
    }


    return response()->json($response);
  }

  public function addGuests($eventCode)
  {
    $guests = $this->request->input('guests');
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
			$index = count($event->guests) - 1;
			$lastGuest = $event->guests[$index];

      return response()->json(
        [
          'guest' => $lastGuest,
          'flash' => 'Successfully RSVP-d! See you there 🤙',
          'added' => true
        ],
        200
      );
    } catch (Exception $e) {
      return response()->json($e, 400);
    }
  }

  public function getComments($eventCode)
  {
    $response = [];
    $code = null;

    $event = Event::where('event_code', $eventCode)->first();
    $eventId = $event->id;
    $comments = Comment::where('event_id', $eventId)->get();

    foreach ($comments as $comment) {
      $guest = Guest::find($comment->guest_id);
      $comment['guest'] = $guest;
    }

    if ($event) {
      $response = ['success' => true, 'comments' => $comments];
      $code = 200;
    } else {
      $response = ['success' => false, 'error' => 'No event found with that code.'];
      $code = 400;
    }

    return response()->json($response, $code);
  }

  public function addComment($eventCode)
  {
    $response = [];
    $code = null;
    $guestId = $this->request->input('guest_id');
    $body = $this->request->input('body');
    $event = Event::where('event_code', $eventCode)->first();
    $eventId = $event->id;

    $comment = Comment::make(['body' => $body, 'event_id' => $eventId, 'guest_id' => $guestId]);

    if ($event) {
      $comment->save();
      $response = ['success' => true, 'comment' => $comment];
      $code = 200;
    } else {
      $response = ['success' => false, 'error' => 'Could not add comment.'];
      $code = 400;
    }

    return response()->json($response, $code);
  }

  public function update($eventCode)
  {
    $response = [];
    $code = null;

    $event = Event::where('event_code', $eventCode)->first();

    if ($event) {
      $event->fill($this->request->all());
      $event->save();
      $response = ["updated" => true, "event" => $event];
      $code = 200;
    } else {
      $response = ["updated" => false, "error" => "Could not update event"];
      $code = 400;
    }

    return response()->json($response, $code);
  }

  public function delete($id)
  {
    Event::findOrFail($id)->delete();
    return response()->json(['success' => true], 200);
  }
}
