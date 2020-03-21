<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;

class UserController extends Controller
{
	/**
	 * create private property for the incoming HTTP request
	 */
	private $request;

	public function __construct(Request $request)
	{
		// set incoming request to private $request property 
		$this->request = $request;	
	}

  public function authenticate()
  {
    $this->validate($this->request, [
      'email' => 'required',
      'password' => 'required'
    ]);

    $user = User::where('email', $this->request->input('email'))->first();

    if (Hash::check($this->request->input('password'), $user->password)) {
      $apikey = \base64_encode(str_random(40));

      User::where('email', $this->request->input('email'))->update(['api_key' => "$apikey"]);
    } else {
      return response()->json(['status' => 'fail'], 401);
    }
  }

  public function showAllUsers()
  {
    $users = User::all();
    return response()->json($users, 200);
  }

	public function showOneUser($id)
	{
		$response = [];	
		$code = null;

		try {
			$user = User::find($id);
			$response = ['user' => $user];	
			$code = 200;
		} catch (Exception $e) {
			$response = ['error' => 'User not found ' . $e];	
			$code = 400;
		}

		return response()->json($response, $code);
	}

	public function destroy($id)
	{
		$response = [];
		$code = null;

		try {
			$user = User::findOrFail($id);
			$user->delete();	
			$response = ['success' => true, 'message' => 'user successfully deleted!'];
			$code = 200;
		} catch (Exception $e) {
			$response = ['error' => 'Could not delete user ' . $e];	
			$code = 400;
		}

		return response()->json($response, $code);
	}

	public function updateUser($id)
	{
		$response = [];	
		$code = null;


		try {
			$user = User::findOrFail($id);
			$user->update($this->request->all());	
			$response = ['success' => 'user updated!', 'user' => $user];
			$code = 200;
		} catch (Exception $e) {
			$response = ['error' => 'could not update user:' . $e];	
			$code = 400;
		}
		return response()->json($response, $code);
	}

  public function getEvents($id)
  {
    $user = User::findOrFail($id);
    $events = $user->events;

    if ($events) {
      return response()->json(['events' => $events], 200);
    } else {
      return response()->json(['message' => 'no events found for this user']);
    }
  }
}
