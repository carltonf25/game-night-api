<?php

namespace App\Http\Controllers;

use App\Models\Event;
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
			$response = ['event' => $event];
		}
			catch(Exception $e) {
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

		if ($event)	{
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

	public function update($eventCode)
	{
		$event = Event::where('event_code', $eventCode)->first();

		$event->header_image = $this->request->header_image;
		$event->title = $this->request->title;
		$event->description = $this->request->description;
		$event->date = $this->request->date;
		$event->location = $this->request->location;
		$event->event_code = $this->request->event_code;

		$event->save();

		return response()->json(["event" => $event, "updated" => true], 200);
	}

	public function delete($id)
	{
		Event::findOrFail($id)->delete();
		return response()->json(['success' => true], 200);
	}
}
