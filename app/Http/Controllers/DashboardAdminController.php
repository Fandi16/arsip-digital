<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Archives;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $archiveCount = Archives::count();

        return view('admin.dashboard', compact('userCount', 'archiveCount'));
    }
}
