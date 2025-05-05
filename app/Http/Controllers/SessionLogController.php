<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Support\Facades\Auth;

class SessionLogController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $sessions = Session::with('user')->orderByDesc('last_activity')->paginate(20);

        return view('admin.session_logs', compact('sessions'));
    }
}
