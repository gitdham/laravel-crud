<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase {
  use RefreshDatabase;

  public function test_register_page() {
    $response = $this->get('/register');
    $response->assertOk();
    $response->assertViewIs('users.create');
  }

  public function test_store_user() {
    $inputFields = [
      'name' => 'Idham Adzani',
      'email' => 'adzani234@gmail.com',
      'password' => '123123123',
      'password_confirmation' => '123123123',
    ];

    $response = $this->post('/users', $inputFields);
    $response->assertSessionHasNoErrors();
    $response->assertRedirect('/');

    // $this->assertAuthenticated();
  }

  public function test_store_empty_data_user_error() {
    $inputFields = [];

    $response = $this->post('/users', $inputFields);
    $response->assertSessionHasErrors(['name', 'email', 'password']);
  }

  public function test_store_invalid_email_user_error() {
    $inputFields = [
      'name' => 'Idham Adzani',
      'email' => 'adzani234 at gmail dot com',
      'password' => '123123123',
      'password_confirmation' => '123123123',
    ];

    $response = $this->post('/users', $inputFields);
    $response->assertSessionHasErrors(['email']);

    $this->assertGuest();
  }

  public function test_store_user_same_email_error() {
    $inputFields = [
      'name' => 'Idham Adzani',
      'email' => 'adzani234@gmail.com',
      'password' => '123123123',
      'password_confirmation' => '123123123',
    ];

    $this->post('/users', $inputFields);

    $inputFields2 = [
      'name' => 'Idham Adzani',
      'email' => 'adzani234@gmail.com',
      'password' => '123123123',
      'password_confirmation' => '123123123',
    ];

    $response = $this->post('/users', $inputFields2);
    $response->assertSessionHasErrors(['email']);
  }

  public function test_store_user_not_equal_password_error() {
    $inputFields = [
      'name' => 'Idham Adzani',
      'email' => 'adzani234@gmail.com',
      'password' => '123123123',
      'password_confirmation' => 'abcabcabc',
    ];

    $response = $this->post('/users', $inputFields);
    $response->assertSessionHasErrors(['password']);
  }

  public function test_store_user_password_length_error() {
    $inputFields = [
      'name' => 'Idham Adzani',
      'email' => 'adzani234@gmail.com',
      'password' => '123',
      'password_confirmation' => '123',
    ];

    $response = $this->post('/users', $inputFields);
    $response->assertSessionHasErrors(['password']);
  }

  public function test_login_page() {
    $response = $this->get('/login');
    $response->assertOk();
    $response->assertViewIs('users.login');
  }

  public function test_login_empty_data_user_error() {
    $credentials = [];

    $response = $this->post('/login', $credentials);
    $response->assertSessionHasErrors('email', 'password');
  }

  public function test_login_invalid_email_user_error() {
    $credentials = [
      'email' => 'test at mail dot com',
      'password' => '123123123',
    ];

    $response = $this->post('/login', $credentials);
    $response->assertSessionHasErrors('email');
  }

  // public function test_login_not_exist_email_user_error() {
  //   $credentials = [
  //     'email' => 'test@mail.com',
  //     'password' => '123123123',
  //   ];

  //   $response = $this->post('/login', $credentials);
  //   $response->assertSessionHasErrors('email');
  // }

  public function test_login_not_match_password_user_error() {
    $inputFields = [
      'name' => 'Idham Adzani',
      'email' => 'adzani234@gmail.com',
      'password' => '123123123',
      'password_confirmation' => '123123123',
    ];

    $this->post('/users', $inputFields);

    $credentials = [
      'email' => 'adzani234@gmail.com',
      'password' => 'zzzzzzzz',
    ];

    $response = $this->post('/login', $credentials);

    $this->assertGuest();
  }

  public function test_login_success() {
    $inputFields = [
      'name' => 'Idham Adzani',
      'email' => 'adzani234@gmail.com',
      'password' => '123123123',
      'password_confirmation' => '123123123',
    ];

    $this->post('/users', $inputFields);

    $credentials = [
      'email' => 'adzani234@gmail.com',
      'password' => '123123123',
    ];

    $response = $this->post('/login', $credentials);

    $this->assertAuthenticated();
  }

  public function test_user_logout() {
    $this->post('/logout');
    $this->assertGuest();
  }
}
