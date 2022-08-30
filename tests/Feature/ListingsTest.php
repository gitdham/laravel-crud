<?php

namespace Tests\Feature;

use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListingsTest extends TestCase {
  use RefreshDatabase;

  private function storeListing($formInputs) {
    return $this->post('/listings', $formInputs);
  }

  public function test_index_page() {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertViewIs('listings.index');
  }

  public function test_store_empty_listing_error() {
    $formInputs = [];

    $response = $this->post('/listings', $formInputs);
    $response->assertSessionHasErrors(['title', 'tags', 'company', 'location', 'email', 'website', 'description']);
  }

  public function test_store_listing_email_error() {
    $formInputs = [
      'title' => 'Jobs Title',
      'tags' => 'laravel, vue, fullstact',
      'company' => 'Test Corp',
      'location' => 'Bandung, ID',
      'email' => 'test at compamy dot com',
      'website' => 'http://testweb.com',
      'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
    ];

    $response = $this->post('/listings', $formInputs);
    $response->assertSessionHasErrors(['email']);
  }

  public function test_show_page_not_found() {
    $response = $this->get('/listings/1');
    $response->assertNotFound();
  }

  public function test_store_listing_success() {
    $formInputs = [
      'title' => 'Jobs Title',
      'tags' => 'laravel, vue, fullstact',
      'company' => 'Test Corp',
      'location' => 'Bandung, ID',
      'email' => 'test@company.com',
      'website' => 'http://testweb.com',
      'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
    ];

    $response = $this->post('/listings', $formInputs);
    $response->assertSessionHasNoErrors();
  }

  public function test_store_listing_same_company_error() {
    $formInputs = [
      'title' => 'Jobs Title',
      'tags' => 'laravel, vue, fullstact',
      'company' => 'Test Corp',
      'location' => 'Bandung, ID',
      'email' => 'test@company.com',
      'website' => 'http://testweb.com',
      'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
    ];

    $this->post('/listings', $formInputs);

    $formInputs['id'] = 2;
    $response = $this->post('/listings', $formInputs);
    $response->assertSessionHasErrors(['company']);
  }

  public function test_show_page_found() {
    $formInputs = [
      'title' => 'Jobs Title',
      'tags' => 'laravel, vue, fullstact',
      'company' => 'Test Corp',
      'location' => 'Bandung, ID',
      'email' => 'test@company.com',
      'website' => 'http://testweb.com',
      'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
    ];

    $this->post('/listings', $formInputs);
    $listId = Listing::first()->id;

    $response = $this->get('/listings/' . $listId);
    $response->assertStatus(200);
    $response->assertViewIs('listings.show');
  }

  public function test_delete_list() {
    $formInputs = [
      'title' => 'Jobs Title',
      'tags' => 'laravel, vue, fullstact',
      'company' => 'Test Corp',
      'location' => 'Bandung, ID',
      'email' => 'test@company.com',
      'website' => 'http://testweb.com',
      'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
    ];

    $this->post('/listings', $formInputs);
    $listId = Listing::first()->id;

    $response = $this->delete('/listings/' . $listId);
    $response->assertRedirect('/');
  }
}
