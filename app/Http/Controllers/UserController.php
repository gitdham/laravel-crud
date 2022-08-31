<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {
  //show create form
  public function create() {
    return view('users.create');
  }

  // store user data
  public function store() {
    $formFields = request()->validate([
      'name' => 'required',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|confirmed|min:6',
    ]);

    // hash password
    $formFields['password'] = bcrypt($formFields['password']);

    // create user
    User::create($formFields);
    // $user = User::create($formFields);

    // login
    // auth()->login($user);

    return redirect('/')->with('message', 'User created successfully!');
  }

  // show user login form
  public function login() {
    return view('users.login');
  }

  // user login proccess
  public function authenticate() {
    $credentials = request()->validate([
      'email' => 'required|email',
      'password' => 'required'
    ]);

    if (!auth()->attempt($credentials))
      return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');


    request()->session()->regenerate();
    return redirect('/')->with('message', 'Logged in successfully!');
  }

  // user logout
  public function logout() {
    auth()->logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/')->with('message', 'User logged out!');
  }
}
