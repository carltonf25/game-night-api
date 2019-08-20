<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class SignupTest extends TestCase
{
  /**
   * A basic test example.
   *
   * @return void
   */
  public function test_signup()
  {
    $this->post('/signup', [
      'email' => 'testemail@example.com',
      'password' => 'p455w0rd'
    ]);

    $this->assertEquals(
      $this->app->version(),
      $this->response->getContent()
    );
  }
}
