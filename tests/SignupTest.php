<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class SignupTest extends TestCase
{
  /**
   * Test SignupController 
   *
   * @return void
   */
  use DatabaseTransactions;

  public function test_signup()
  {
    $this->post('/signup', [
      'email' => 'testing@example.com',
      'password' => 'p455w0rd'
    ]);

    $this->seeJsonContains([
      'created' => true
    ]);
  }

  public function test_signup_does_not_allow_duplicate()
  {
    $userCredentials = [
      'email' => 'testing@example.com',
      'password' => 'p455w0rd'
    ];

    $this->post('/signup', $userCredentials);

    $this->post('/signup', $userCredentials);

    $this->seeJsonContains([
      'error' => 'There is already an account with that email address.'
    ]);
  }
}
