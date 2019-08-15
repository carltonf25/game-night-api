<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use Faker\Provider\Base;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
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
  protected function jwt(User $user)
  {
    $payload = [
      'iss' => "lumen-jwt", // Issuer of token
      'sub' => $user->id, // Subject of the token
      'iat' => time(), // Time when JWT was issued
      'exp' => time() + 60 * 60 // Expiration time
    ];

    return JWT::encode($payload, env('JWT_SECTET'));
  }

  /**
   * Authenticate a user and return the token in the provided credentials are correct.
   * 
   * @param \App\User $user
   * @return mixed
   */
  public function authenticate(User $user)
  {
    $this->validate($this->request, [
      'email' => 'required|email',
      'password' => 'required'
    ]);

    // Find the user by email
    $user = User::where('email', $this->request->input('email'))->first();

    if (!$user) {
      return response()->json([
        'error' => 'Email does not exist.'
      ], 400);
    }

    // Verify the password and generate the token
    if (Hash::check($this->request->input('password'), $user->password)) {
      return response()->json([
        'user' => $user
      ], 200);
    }

    // Bad request response
    return response()->json([
      'error' => 'Email or password is wrong.'
    ], 400);
  }
}
