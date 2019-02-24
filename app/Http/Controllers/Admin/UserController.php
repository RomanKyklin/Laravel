<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\View;


class UserController extends Controller
{

    public function index()
    {
        $users = User::all();

        return View::make('admin/user/index', compact('users'));
    }
}
