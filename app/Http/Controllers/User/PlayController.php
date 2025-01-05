<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Play;
use Illuminate\Http\Request;

class PlayController extends Controller
{
    public function index()
    {
        $plays = Play::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('user.plays.index', compact('plays'));
    }

    public function show(Play $play)
    {
        if ($play->user_id !== auth()->id()) {
            abort(403);
        }

        return view('user.plays.show', compact('play'));
    }
}
