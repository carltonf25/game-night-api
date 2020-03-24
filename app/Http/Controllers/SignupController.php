<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class SignupController extends BaseController
{
  /**
   * The request instance.
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
  }

  /**
   * Authenticate a user and return the user if the provided credentials are correct.
   * 
   * @param \App\User $user
   * @return JSON array
   */
  public function create(User $user)
  {
    $this->validate($this->request, [
      'email' => 'required|email',
      'password' => 'required'
    ]);

    $email = $this->request->input('email');
    $password = $this->request->input('password');

    // See if an existing user already has this email address
    $user = User::where('email', $email)->first();

    if ($user) {
      return response()->json([
        'error' => 'There is already an account with that email address.'
      ], 203);
    }

    // Validate password
    if (strlen($password) < 6) {
      return response()->json([
        'error' => 'Password must be at least 6 characters long'
      ], 203);
    } else if (strlen($password) > 20) {
      return response()->json([
        'error' => 'Password cannot exceed 20 characters.'
      ], 203);
    }

    // Email and password are valid amd email not in use. Create user. 

    $hashedPassword = Hash::make($password);

		try {
    $user = User::create([
      'email' => $email,
      'password' => $hashedPassword,
      'username' => $email,
      'api_token' => Str::random(60),
    ]);

    return response()->json([
      'user' => $user,
      'created' => true
    ], 200);
		} catch (Exception $e) {
			return response()->json(['error' => 'There was a problem creating the account:' . $e ]);
		}
  }

}
