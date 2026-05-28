<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('customer');

        if ($request->filled('type')) {
            $query->where('user_type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(15)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function toggleBlock(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('alert-danger', 'Self-blocking is restricted.');
        }

        $user->blocked = !$user->blocked;
        $user->save();

        $status = $user->blocked ? 'suspended' : 're-activated';
        return back()->with('alert-success', "User account has been {$status} successfully.");
    }
}