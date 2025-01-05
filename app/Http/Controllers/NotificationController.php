<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('success', 'အကြောင်းကြားစာကို ဖတ်ရှုပြီးအဖြစ် မှတ်သားပြီးပါပြီ။');
    }

    public function markAllAsRead()
    {
        auth()->user()
            ->notifications()
            ->unread()
            ->update(['read_at' => now()]);

        return back()->with('success', 'အကြောင်းကြားစာအားလုံးကို ဖတ်ရှုပြီးအဖြစ် မှတ်သားပြီးပါပြီ။');
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'အကြောင်းကြားစာကို ဖျက်ပြီးပါပြီ။');
    }
}
