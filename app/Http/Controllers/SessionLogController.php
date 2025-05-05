<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionLogController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $sessions = $user->role === 'admin'
            ? Session::with('user')->orderByDesc('last_activity')->paginate(20)
            : Session::with('user')->where('user_id', $user->id)->orderByDesc('last_activity')->paginate(20);

        return view('admin.session_logs', compact('sessions'));
    }

    public function destroy($id)
    {
        $session = Session::findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $session->delete();

        return back()->with('success', 'Session log deleted.');
    }
}
