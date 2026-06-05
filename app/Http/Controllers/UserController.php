<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class UserController extends Controller
{
     // use \App\Traits\UserPhotoFileStorage;
     
    public function index()
    {
        return "index profile";
    }

    public function create()
    {
        return "create profile";
    }

    public function store(Request $request)
    {
        return "store profile";
    }

    public function show(User $user)
    {
        return "show profile";
    }

    public function edit(User $user)
    {
        return "edit profile";
    }

    public function update(Request $request, User $user)
    {
        return "update profile";
    }

    public function destroy(User $user)
    {
        return "destroy profile";
    }
}