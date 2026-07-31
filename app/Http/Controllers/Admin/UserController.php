<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::withCount(['reviews', 'projects', 'assignments'])
                ->orderBy('name')
                ->paginate(20),
        ]);
    }
}
