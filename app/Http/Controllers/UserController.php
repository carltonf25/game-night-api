<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
  public function authenticate(Request $request)
  {
    $this->validate($request, [
      'email' => 'required',
      'password' => 'required'
    ]);

    $user = User::where('email', $request->input('email'))->first();

    if (Hash::check($request->input('password'), $user->password)) {
      $apikey = \base64_encode(str_random(40));

      User::where('email', $request->input('email'))->update(['api_key' => "$apikey"]);
    } else {
      return response()->json(['status' => 'fail'], 401);
    }
  }

  public function showAllUsers()
  {
    $users = User::all();
    return response()->json($users, 200);
  }
}
