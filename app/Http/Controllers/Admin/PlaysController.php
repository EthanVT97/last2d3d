<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Play;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PlaysController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Play::with('user')->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('created_at', $date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $plays = $query->paginate(15);

        $stats = [
            'total_plays' => Play::count(),
            'total_amount' => Play::sum('amount'),
            'total_2d' => Play::where('type', '2d')->count(),
            'total_3d' => Play::where('type', '3d')->count(),
        ];

        return view('admin.plays.index', compact('plays', 'stats'));
    }
}
