<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
   * Create a new token.
   * 
   * @param \App\User $user
   * @return string
   */

  /**
   * Authenticate a user and return the user if the provided credentials are correct.
   * 
   * @param \App\User $user
   * @return mixed
   */
  public function create(User $user)
  {
    $this->validate($this->request, [
      'email' => 'required|email',
      'password' => 'required'
    ]);

    $email = $this->request->input('email');
    $password = $this->request->input('password');

    // Find the user by email
    $user = User::where('email', $email)->first();

    if ($user) {
      return response()->json([
        'error' => 'There is already an account with that email address.'
      ], 203);
    }

    // Verify the password
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

    $user = User::create([
      'email' => $email,
      'password' => $hashedPassword,
      'username' => $email
    ]);

    return response()->json([
      'user' => $user,
      'created' => true
    ], 200);
  }
}
