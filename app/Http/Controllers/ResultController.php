<?php

namespace App\Http\Controllers;

use App\Models\LotteryResult;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ResultController extends Controller
{
    public function index()
    {
        $results = LotteryResult::with('creator')
            ->where('status', 'published')
            ->latest()
            ->paginate(15);

        return view('results.index', compact('results'));
    }
}
